<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Prestamos.php';
require 'app/models/Cobros.php';
require 'app/models/Caja.php';
require 'app/view/pdf/fpdf/fpdf.php';
class CobrosController
{
    private $usuario;
    private $caja;
    private $rol;
    private $archivo;

    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $clientes;
    private $builder;
    private $prestamos;
    private $cobros;
    public function __construct()
    {
        //Instancias especificas del controlador
        $this->usuario = new Usuario();
        $this->rol = new Rol();
        $this->archivo = new Archivo();
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->clientes = new Clientes();
        $this->builder = new Builder();
        $this->prestamos = new Prestamos();
        $this->cobros = new Cobros();
        $this->caja = new Caja();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function pagar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $id_prestamo = $_GET['id'];
            $prestamos_data = $this->prestamos->listar_x_id($id_prestamo);
            $garante = $this->prestamos->listar_garante_prestamo($id_prestamo);
            $cliente_data = $this->clientes->listar_x_id($prestamos_data->id_cliente);
            $resta_pagar = $this->cobros->listar_total_pagos_x_prestamo($id_prestamo);
            $bancos = $this->cobros->listar_bancos();
            $metodos_pago = $this->cobros->listar_metodos_de_pago();
            $descuentos_prestamos = $this->cobros->listar_decuentos_x_prestamo($id_prestamo);

            // Obtenemos TODAS las cuotas
            $todas_las_cuotas = $this->cobros->listar_cuotas_x_prestamo($id_prestamo);

            $total_pagos_cuenta = $this->prestamos->listar_total_pagos_contados($id_prestamo);

            // --- LÓGICA DE CUOTAS SECUENCIALES ---

            // 1. Filtrar solo las cuotas pendientes (Asumo que estado 1 es pendiente, cambialo si usas 0 u otro valor)
            $cuotas_pendientes = array_filter($todas_las_cuotas, function($cuota) {
                return $cuota->pago_diario_estado == 1;
            });

            // Reindexar el array para que empiece desde 0
            $cuotas_pendientes = array_values($cuotas_pendientes);

            $cuota_a_pagar = null;
            $fecha_proximo_cobro = "Préstamo Finalizado"; // Mensaje por defecto

            if (count($cuotas_pendientes) > 0) {
                // La cuota a pagar es la primera pendiente (la más antigua)
                $cuota_a_pagar = $cuotas_pendientes[0];

                // Si hay una segunda cuota pendiente, esa es el próximo cobro
                if (count($cuotas_pendientes) > 1) {
                    $fecha_proximo_cobro = date('Y-m-d', strtotime($cuotas_pendientes[1]->pago_diario_fecha));
                }
            }
            // -------------------------------------

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/pagar.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function pagos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_prestamo = $_GET['id'];
			$pagos_p = $this->cobros->listar_pagos_x_prestamo($id_prestamo);
			$descuentos_prestamos = $this->cobros->listar_datos_decuentos_x_prestamo($id_prestamo);
			$descuentos_monto = $this->cobros->listar_decuentos_x_prestamo($id_prestamo);
			$listar_cliente_x_prestamo = $this->clientes->listar_x_id_presrtamo($id_prestamo);
            $descuentos_aplicados = $this->cobros->listar_descuentos_x_prestamo($id_prestamo);
            $usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $usuario_nombre = $this->cobros->listar_usuario($usuario);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/pagos.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function guardar_pago()
    {
        $result = 2; // Estado de error por defecto
        $message = 'Error al procesar el pago.';
        $id_pago_generado = 0;

        try {
            $id_prestamo = (int)$_POST['id_prestamo'];
            $id_pago_cuota = (int)$_POST['id_pago'];
            $id_usuario = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $fecha_descuento = date('Y-m-d H:i:s');
            $usuario_nombre = $this->cobros->listar_usuario($id_usuario);

            // RECIBIMOS EL DESCUENTO DESDE EL JS
            $descuento_monto = isset($_POST['descuento']) && is_numeric($_POST['descuento']) ? (float)$_POST['descuento'] : 0;

            // 1. OBTENER DATOS ACTUALES
            $cuota_a_pagar = $this->cobros->listar_cuota_individual($id_pago_cuota);
            $prestamo = $this->prestamos->listar_x_id($id_prestamo);
            $caja_abierta = $this->caja->traer_datos_caja();
            $usuario= $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);

            if ($cuota_a_pagar && $prestamo && $caja_abierta) {

                // GUARDAMOS EL VALOR NOMINAL (ORIGINAL) PARA BAJAR LA DEUDA GLOBAL
                $monto_cuota_original = (float)$cuota_a_pagar->pago_diario_monto;

                // ESTA VARIABLE SERÁ EL DINERO REAL QUE INGRESA (Puede cambiar si hay descuento)
                $monto_cobrado = $monto_cuota_original;

                // ==========================================
                // 2. VERIFICAR Y APLICAR DESCUENTO
                // ==========================================
                if ($descuento_monto > 0) {
                    // Restamos el descuento para saber cuánto cobrar en físico
                    $monto_cobrado = max(0, $monto_cuota_original - $descuento_monto);

                    // Actualizamos la cuota añadiendo los dos nuevos campos solicitados
                    $this->builder->update("pagos_diarios", array(
                        'pago_diario_descuento_estado' => 1,
                        'pago_diario_descuento_monto'  => $descuento_monto,
                        'pago_diario_descuento_fecha'  => $fecha_descuento,
                        'pago_diario_descuento_usuario'=> $usuario_nombre->usuario_nickname
                    ), array(
                        'id_pago_diario' => $id_pago_cuota
                    ));
                }

                // ==========================================
                // 3. ACTUALIZAR ESTADO DE LA CUOTA A "PAGADO"
                // ==========================================
                $this->cobros->cambiar_estado_cuota($id_pago_cuota);

                // ==========================================
                // 4. REGISTRAR EL TICKET DE PAGO (HISTORIAL)
                // ==========================================
                $mt = microtime(true);
                $estado_descuento = ($descuento_monto > 0) ? 1 : 0;

                // Armamos el arreglo de datos con los NUEVOS campos y validamos nulos
                $datos_pago = array(
                    'id_prestamo'              => $id_prestamo,
                    'id_pago_diario'           => $id_pago_cuota, // <-- RELACIÓN DIRECTA CON LA CUOTA
                    'pago_monto'               => $monto_cobrado, // Ticket sale por lo que realmente pagó
                    'pago_metodo'              => $_POST['pago_metodo'] ?? '',
                    'pago_fecha'               => date('Y-m-d H:i:s'),
                    'pago_estado'              => 1,
                    'pago_mt'                  => $mt,
                    'id_cliente'                  => $prestamo->id_cliente,
                    'id_usuario'                  => $usuario,
                    // --- NUEVOS CAMPOS DE DESCUENTO ---
                    'pago_descuento_estado'    => $estado_descuento,
                    'pago_descuento_monto'           => $descuento_monto,

                    // --- NUEVOS CAMPOS DINÁMICOS ---
                    'pago_monto_recibido'      => !empty($_POST['monto_recibido']) ? (float)$_POST['monto_recibido'] : null,
                    'pago_monto_vuelto'        => !empty($_POST['monto_vuelto']) ? (float)$_POST['monto_vuelto'] : null,
                    'pago_operacion'       => !empty($_POST['num_operacion']) ? trim($_POST['num_operacion']) : null,
                    'pago_oper_titular'      => !empty($_POST['nombre_titular']) ? trim($_POST['nombre_titular']) : null,
                    'id_banco'                 => !empty($_POST['banco_entidad']) ? (int)$_POST['banco_entidad'] : null,
                    'pago_fecha_operacion' => !empty($_POST['fecha_transferencia']) ? $_POST['fecha_transferencia'] : null,
                    'pago_observacion'         => !empty($_POST['pago_observacion']) ? trim($_POST['pago_observacion']) : null
                );

                // Guardamos el historial del pago
                $this->builder->save("pagos", $datos_pago);

                $pago_guardado = $this->cobros->listar_pago_guardado_x_mt($mt);
                $id_pago_generado = $pago_guardado ? $pago_guardado->id_pago : 0;

                // ==========================================
                // 5. ACTUALIZAR DINERO EN CAJA
                // ==========================================
                $this->builder->update("caja", array(
                    'monto_caja' => $caja_abierta->monto_caja + $monto_cobrado, // A la caja solo entra el efectivo real
                ), array(
                    'id_caja' => $caja_abierta->id_caja,
                ));

                // ==========================================
                // 6. EVALUAR EL ESTADO GLOBAL DEL PRÉSTAMO
                // ==========================================
                // Descontamos la cuota ORIGINAL de la deuda global
                $nuevo_saldo = max(0, $prestamo->prestamo_saldo_pagar - $monto_cuota_original);

                $todas_las_cuotas = $this->cobros->listar_cuotas_x_prestamo($id_prestamo);
                $cuotas_pendientes = array_filter($todas_las_cuotas, function($c) {
                    return $c->pago_diario_estado == 1;
                });
                $cuotas_pendientes = array_values($cuotas_pendientes);

                $datos_actualizar_prestamo = array(
                    'prestamo_saldo_pagar' => $nuevo_saldo
                );

                if (count($cuotas_pendientes) > 0) {
                    $datos_actualizar_prestamo['prestamo_prox_cobro'] = $cuotas_pendientes[0]->pago_diario_fecha;
                } else {
                    $datos_actualizar_prestamo['prestamo_estado'] = 2; // 2 = Pagado
                    $datos_actualizar_prestamo['prestamo_saldo_pagar'] = 0;
                }

                $this->builder->update("prestamos", $datos_actualizar_prestamo, array(
                    'id_prestamos' => $id_prestamo
                ));

                $result = 1;
                $message = 'OK';
            } else {
                $message = 'No se encontraron los datos del préstamo o la caja está cerrada.';
            }

        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }

        echo json_encode(array(
            "result" => array(
                "code" => $result,
                "message" => $message,
                "id_pago" => $id_pago_generado
            )
        ));
    }

    public function aplicar_descuento()
	{
		$result = 2;
		$message = 'OK';
		try {
			$result = $this->builder->save("descuentos",array(
				'id_prestamo' => $_POST['id_prestamo'],
				'descuento_monto' => $_POST['descontar_cantidad'],
				'descuento_fecha' => date('Y-m-d H:i:s'),
				'descuento_estado' => 1,
				'descuento_mt' => microtime(true),
			));
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
    public function generar_documento(){
        try{
            date_default_timezone_set('America/Lima');
            $id_pago = $_GET['id'];

            // ── DATOS ─────────────────────────────────────────────────────────
            $data = $this->cobros->listar_x_id($id_pago);
            if (empty($data)) throw new Exception("Pago no encontrado.");

            // Totales del prestamo
            $debe_pagar      = floatval($data->prestamo_monto) + (floatval($data->prestamo_monto) * floatval($data->prestamo_interes) / 100);
            $ya_pago_result  = $this->prestamos->listar_total_pagos_x_prestamo($data->id_prestamos);
            $ya_pago         = floatval($ya_pago_result->total ?? 0);
            $descuentos_raw  = $this->cobros->listar_decuentos_x_prestamo($data->id_prestamos);
            $descuento       = (!empty($descuentos_raw) && !empty($descuentos_raw[0]->total)) ? floatval($descuentos_raw[0]->total) : 0;
            $saldo_final     = max(0, $debe_pagar - $ya_pago - $descuento);
            $saldo_anterior  = $saldo_final + floatval($data->pago_monto);

            // Cuota original desde pagos_diarios
            $cuota          = !empty($data->id_pago_diario) ? $this->cobros->listar_cuota_individual($data->id_pago_diario) : null;
            $cuota_original = $cuota ? floatval($cuota->pago_diario_monto) : floatval($data->pago_monto);

            // Metodo de pago
            $metodo         = $this->cobros->listar_metodo_pago($data->pago_metodo);
            $metodo_nombre  = $metodo ? ucfirst($metodo->metodo_pago_nombre) : 'N/A';

            // Usuario que registro
            $usuario_reg    = !empty($data->id_usuario) ? $this->cobros->listar_usuario($data->id_usuario) : null;
            $nombre_usuario = $usuario_reg ? ($usuario_reg->usuario_nickname ?? 'Admin') : 'Admin';

            // Fecha de vencimiento del prestamo
            $vencimiento       = $this->prestamos->listar_fecha_vencimiento_prestamo($data->id_prestamos);
            $fecha_vencimiento = (!empty($vencimiento) && !empty($vencimiento->fecha_vencimiento)) ? $vencimiento->fecha_vencimiento : null;

            // Nombre completo del cliente
            $apellido_m      = !empty($data->cliente_apellido_materno) ? ' ' . $data->cliente_apellido_materno : '';
            $nombre_cliente  = strtoupper($data->cliente_nombre . ' ' . $data->cliente_apellido_paterno . $apellido_m);

            // ── FPDF ──────────────────────────────────────────────────────────
            $W = 70; // Ancho util: 80mm - 5mm margen izq - 5mm margen der
            $SEP = str_repeat('-', 38);
            $SEP_INT = str_repeat('-', 28); // Separador interior (indentado)

            $pdf = new FPDF('P', 'mm', array(80, 210));
            $pdf->SetMargins(5, 5, 5);
            $pdf->SetAutoPageBreak(true, 5);
            $pdf->AddPage();

            // ── ENCABEZADO ────────────────────────────────────────────────────
            $pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png', 5, 5, 12);
            $pdf->Ln(9);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell($W, 5, 'INVERSIONES GUZZ E.I.R.L', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'RUC: 20600864255', 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell($W, 5, 'RECIBO DE PAGO Nro. ' . str_pad($id_pago, 6, '0', STR_PAD_LEFT), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'Fecha: ' . date('d/m/Y H:i', strtotime($data->pago_fecha)), 0, 1, 'C');
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // ── DATOS DEL CLIENTE ─────────────────────────────────────────────
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell($W, 4, 'DATOS DEL CLIENTE', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'Cliente: ' . $nombre_cliente, 0, 1, 'L');
            $pdf->Cell($W, 4, 'DNI: ' . $data->cliente_dni, 0, 1, 'L');
            $pdf->Cell($W, 4, 'Tipo de credito: ' . ucfirst($data->prestamo_tipo_pago), 0, 1, 'L');
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // ── DETALLE DEL PAGO ──────────────────────────────────────────────
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell($W, 4, 'DETALLE DEL PAGO', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);

            // Saldo Anterior
            $pdf->Cell(40, 4, 'Saldo Anterior:', 0, 0, 'L');
            $pdf->Cell(30, 4, 'S/ ' . number_format($saldo_anterior, 2), 0, 1, 'R');

            // Cuota
            $pdf->Cell($W, 4, 'Cuota:', 0, 1, 'L');
            $pdf->Cell(5,  4, '', 0, 0);
            $pdf->Cell(35, 4, 'Original:', 0, 0, 'L');
            $pdf->Cell(30, 4, 'S/ ' . number_format($cuota_original, 2), 0, 1, 'R');

            if ($data->pago_descuento_estado == 1 && floatval($data->pago_descuento_monto) > 0) {
                $pdf->Cell(5,  4, '', 0, 0);
                $pdf->Cell(35, 4, 'Descuento:', 0, 0, 'L');
                $pdf->Cell(30, 4, '-S/ ' . number_format(floatval($data->pago_descuento_monto), 2), 0, 1, 'R');
                $pdf->Cell(5,  3, '', 0, 0);
                $pdf->Cell(65, 3, $SEP_INT, 0, 1, 'C');
                $pdf->Cell(5,  4, '', 0, 0);
                $pdf->Cell(35, 4, 'Cuota Final:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format(floatval($data->pago_monto), 2), 0, 1, 'R');
            }

            // Pago
            $pdf->Cell($W, 4, 'Pago:', 0, 1, 'L');
            $pdf->Cell(5,  4, '', 0, 0);
            $pdf->Cell(35, 4, 'Monto Pagado:', 0, 0, 'L');
            $pdf->Cell(30, 4, 'S/ ' . number_format(floatval($data->pago_monto), 2), 0, 1, 'R');
            if (!empty($data->pago_monto_recibido)) {
                $pdf->Cell(5,  4, '', 0, 0);
                $pdf->Cell(35, 4, 'Monto Recibido:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format(floatval($data->pago_monto_recibido), 2), 0, 1, 'R');
                $pdf->Cell(5,  4, '', 0, 0);
                $pdf->Cell(35, 4, 'Vuelto:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format(floatval($data->pago_monto_vuelto ?? 0), 2), 0, 1, 'R');
            }
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // Saldo Restante
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(40, 4, 'Saldo Restante:', 0, 0, 'L');
            $pdf->Cell(30, 4, 'S/ ' . number_format($saldo_final, 2), 0, 1, 'R');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // ── METODO DE PAGO ────────────────────────────────────────────────
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell($W, 4, 'METODO DE PAGO', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'Metodo: ' . $metodo_nombre, 0, 1, 'L');
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // ── INFORMACION DEL CREDITO ───────────────────────────────────────
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell($W, 4, 'INFORMACION DEL CREDITO', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'Interes aplicado: ' . floatval($data->prestamo_interes) . '%', 0, 1, 'L');
            if (!empty($fecha_vencimiento)) {
                $pdf->Cell($W, 4, 'Fecha de vencimiento: ' . date('d/m/Y', strtotime($fecha_vencimiento)), 0, 1, 'L');
            }
            if (!empty($data->prestamo_prox_cobro)) {
                $pdf->Cell($W, 4, 'Proximo pago: ' . date('d/m/Y', strtotime($data->prestamo_prox_cobro)), 0, 1, 'L');
            }
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // ── CONTROL INTERNO ───────────────────────────────────────────────
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell($W, 4, 'CONTROL INTERNO', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'Registrado por: ' . $nombre_usuario, 0, 1, 'L');
            if (!empty($data->pago_observacion)) {
                $pdf->Cell($W, 4, 'Observacion: ' . $data->pago_observacion, 0, 1, 'L');
            } elseif ($data->pago_descuento_estado == 1) {
                $pdf->Cell($W, 4, 'Observacion: Descuento aplicado', 0, 1, 'L');
            }
            $pdf->Cell($W, 3, $SEP, 0, 1, 'C');

            // ── FIRMA ─────────────────────────────────────────────────────────
            $pdf->Ln(10);
            $pdf->Cell($W, 4, str_repeat('-', 25), 0, 1, 'C');
            $pdf->Cell($W, 4, 'FIRMA CLIENTE', 0, 1, 'C');

            $pdf->Output();

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
    }

    public function anular(){
        // Preparamos el array de respuesta para el AJAX
        $res = array('codigo' => 0, 'mensaje' => 'Error desconocido');
        $hoy=date("Y-m-d H:i:s");

        try {
            $id_prestamo = isset($_POST['id_prestamo']) ? $_POST['id_prestamo'] : null;

            if (empty($id_prestamo)) {
                throw new Exception("ID de préstamo no recibido.");
            }

            // 1. Obtener los datos del préstamo antes de anularlo
            $prestamo = $this->cobros->listar_prestamo($id_prestamo);

            if (empty($prestamo)) {
                throw new Exception("El préstamo no existe.");
            }

            // 2. Obtener la última caja abierta
            // (Asumiendo que tienes un modelo/función que trae la caja activa del día)
            $caja_abierta = $this->cobros->obtener_caja_abierta();

            if (empty($caja_abierta)) {
                throw new Exception("No hay ninguna caja abierta. Debe abrir caja para procesar la devolución del dinero.");
            }

            // 3. Preparar la matemática
            $monto_capital = floatval($prestamo->prestamo_monto);
            $monto_interes = floatval($prestamo->prestamo_monto_interes);

            // ADVERTENCIA: Aquí se suman ambos según tu requerimiento.
            // Contablemente se recomienda que sea solo $monto_capital.
            $monto_a_devolver = $monto_capital ;

            $saldo_caja_actual = floatval($caja_abierta->monto_caja);
            $nuevo_saldo_caja = $saldo_caja_actual + $monto_a_devolver;


            // ==========================================
            // INICIO DE LA TRANSACCIÓN SQL
            // ==========================================
            // Es vital usar transacciones: Si falla actualizar la caja, el préstamo no se anula.
            $this->cobros->iniciar_transaccion();

            // PASO 1: Cambiar el estado del préstamo a 5 (Anulado)
            // Necesitas tener esta función en tu modelo Prestamos
            $estado_actualizado = $this->cobros->cambiar_estado($id_prestamo, 5);

            if (!$estado_actualizado) {
                throw new Exception("Error al cambiar el estado del préstamo en la base de datos.");
            }

            // PASO 2: Actualizar el monto total de la caja
            // Necesitas tener esta función en tu modelo Cajas
            $caja_actualizada = $this->cobros->actualizar_monto_caja($caja_abierta->id_caja, $nuevo_saldo_caja);

            if (!$caja_actualizada) {
                throw new Exception("Error al actualizar el saldo de la caja.");
            }

            // PASO 3: Registrar el movimiento de caja (Ingreso)
            $concepto_movimiento = "DEVOLUCIÓN POR ANULACIÓN DE CRÉDITO #" . $id_prestamo;
            $movimiento_registrado = $this->cobros->registrar_movimiento_caja(
                $caja_abierta->id_caja,
                3, // Tipo 3 = Devolución por anulación de préstamo
                $monto_capital,
                $hoy
            );

            if (!$movimiento_registrado) {
                throw new Exception("Error al registrar el movimiento en el historial de caja.");
            }

            // ==========================================
            // CONFIRMAR TRANSACCIÓN
            // ==========================================
            $this->cobros->confirmar_transaccion(); // COMMIT

            $res['codigo'] = 1;
            $res['mensaje'] = 'Crédito anulado exitosamente y dinero retornado a caja.';

        } catch (Throwable $e) {
            // Si CUALQUIER cosa falla arriba, deshacemos todo
            $this->cobros->revertir_transaccion(); // ROLLBACK

            // Opcional: Registrar el error en tu log
            // $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);

            $res['mensaje'] = $e->getMessage();
        }

        // Devolver la respuesta en JSON para el archivo JS
        echo json_encode($res);
    }

}