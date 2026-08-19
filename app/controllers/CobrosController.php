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
            $cuentas_receptoras = $this->cobros->listar_cuentas_receptoras();
            $descuentos_prestamos = $this->cobros->listar_decuentos_x_prestamo($id_prestamo);

            // Historial de pagos del crédito, con el saldo que quedó tras cada pago
            $pagos_anteriores = $this->cobros->historial_pagos_con_saldo($id_prestamo);

            // Sección compartida "Información del crédito"
            $info_credito = $this->cobros->resumen_credito($id_prestamo);

            // Obtenemos TODAS las cuotas
            $todas_las_cuotas = $this->cobros->listar_cuotas_x_prestamo($id_prestamo);

            // Numeración de cuotas (Cuota 1, 2, 3...) sobre el cronograma completo
            $numero_de_cuota = [];
            $n_cuota = 0;
            foreach ($this->cobros->listar_todas_las_cuotas_x_prestamo($id_prestamo) as $cuota_crono) {
                $numero_de_cuota[$cuota_crono->id_pago_diario] = ++$n_cuota;
            }

            $total_pagos_cuenta = $this->prestamos->listar_total_pagos_contados($id_prestamo);

            $fecha_fin_prestamo = $this->cobros->traer_fecha_fin_prestamo($id_prestamo);

            // --- LÓGICA DE CUOTAS SECUENCIALES ---

            // 1. Filtrar solo las cuotas pendientes (Asumo que estado 1 es pendiente, cambialo si usas 0 u otro valor)
            $cuotas_pendientes = array_filter($todas_las_cuotas, function($cuota) {
                return $cuota->pago_diario_estado == 1;
            });

            // Reindexar el array para que empiece desde 0
            $cuotas_pendientes = array_values($cuotas_pendientes);

            // Orden cronológico: la primera pendiente es la cuota actual y el resto
            // se ofrece en ese mismo orden en el selector de cuotas
            usort($cuotas_pendientes, function($a, $b) {
                $cmp = strcmp($a->pago_diario_fecha, $b->pago_diario_fecha);
                return $cmp !== 0 ? $cmp : ($a->id_pago_diario <=> $b->id_pago_diario);
            });

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

            // Deuda actual del cliente
            $saldo_total_pendiente = floatval($prestamos_data->prestamo_saldo_pagar ?? 0);

            // Crédito cancelado (saldo en cero o estado Cancelado/Anulado): no hay nada
            // que cobrar aunque queden cuotas abiertas por datos antiguos
            if ($saldo_total_pendiente <= 0 || in_array(intval($prestamos_data->prestamo_estado), [2, 4, 5])) {
                $cuota_a_pagar       = null;
                $cuotas_pendientes   = [];
                $fecha_proximo_cobro = "Préstamo Finalizado";
            }

            $tasa                  = floatval($prestamos_data->prestamo_interes ?? 0);
            $capital_pendiente     = $tasa > 0
                ? round($saldo_total_pendiente / (1 + $tasa / 100), 2)
                : $saldo_total_pendiente;
            $valor_resta_por_pagar = $saldo_total_pendiente;

            // Condición de amortización: activo + capital pendiente + antes del vencimiento + no cancelado
            $puede_amortizar = intval($prestamos_data->prestamo_estado) === 1
                && $capital_pendiente > 0
                && !empty($cuota_a_pagar)
                && strtotime(date('Y-m-d')) < strtotime($cuota_a_pagar->pago_diario_fecha)
                && !in_array(intval($prestamos_data->prestamo_estado), [2, 4]);

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

            // Cliente principal
            $listar_cliente_x_prestamo = $this->clientes->listar_x_id_presrtamo($id_prestamo);

            // ==========================================
            // NUEVO: BUSCAR DATOS DEL GARANTE
            // ==========================================
            $info_garante = null;
            if (!empty($listar_cliente_x_prestamo->prestamo_garante)) {
                // Nota: Aquí usa la función de tu modelo que lista un solo cliente por su ID.
                // Si tu función se llama diferente (ej: listar_cliente o listar_x_id), cámbiala aquí:
                $info_garante = $this->clientes->listar_cliente_x_id($listar_cliente_x_prestamo->prestamo_garante);
            }

            $descuentos_aplicados = $this->cobros->listar_descuentos_x_prestamo($id_prestamo);
            $usuario=$this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
            $usuario_nombre = $this->cobros->listar_usuario($usuario);

            // Sección compartida "Información del crédito"
            $info_credito = $this->cobros->resumen_credito($id_prestamo);

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
        $result  = 2; // Estado de error por defecto
        $message = 'Error al procesar el pago.';
        $id_pago_generado = 0;
        $transaction_started = false;

        try {
            $id_prestamo     = (int)$_POST['id_prestamo'];
            $id_usuario      = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $fecha_descuento = date('Y-m-d H:i:s');
            $usuario_nombre  = $this->cobros->listar_usuario($id_usuario);

            // RECIBIMOS EL DESCUENTO DESDE EL JS (se aplica sobre la primera cuota de la operación)
            $descuento_monto = isset($_POST['descuento']) && is_numeric($_POST['descuento']) ? (float)$_POST['descuento'] : 0;

            // ==========================================
            // 1. CUOTAS SELECCIONADAS
            // Se acepta el arreglo id_pagos[] (una o varias cuotas).
            // id_pago se mantiene como respaldo del flujo anterior de una sola cuota.
            // ==========================================
            $ids_solicitados = [];
            if (isset($_POST['id_pagos'])) {
                $ids_solicitados = is_array($_POST['id_pagos'])
                    ? $_POST['id_pagos']
                    : explode(',', (string)$_POST['id_pagos']);
            }
            if (empty($ids_solicitados) && !empty($_POST['id_pago'])) {
                $ids_solicitados = [$_POST['id_pago']];
            }
            $ids_solicitados = array_values(array_unique(array_filter(array_map('intval', $ids_solicitados))));

            if (empty($ids_solicitados)) {
                echo json_encode(['result' => ['code' => 6, 'message' => 'Debe seleccionar al menos una cuota para registrar el pago.']]);
                return;
            }

            $prestamo     = $this->prestamos->listar_x_id($id_prestamo);
            $caja_abierta = $this->caja->traer_datos_caja();

            if (!$prestamo || !$caja_abierta) {
                echo json_encode(['result' => ['code' => 2, 'message' => 'No se encontraron los datos del préstamo o la caja está cerrada.']]);
                return;
            }

            // Validación: todas las cuotas deben pertenecer al préstamo y seguir pendientes
            $cuotas_a_pagar = $this->cobros->listar_cuotas_pendientes_x_ids($id_prestamo, $ids_solicitados);

            if (count($cuotas_a_pagar) !== count($ids_solicitados)) {
                echo json_encode(['result' => ['code' => 7, 'message' => 'Una o más cuotas seleccionadas ya fueron pagadas o no pertenecen a este préstamo. Actualice la pantalla.']]);
                return;
            }

            // ==========================================
            // 2. MONTOS DE LA OPERACIÓN
            // ==========================================
            $total_cuotas = 0;
            foreach ($cuotas_a_pagar as $cuota) {
                $total_cuotas += floatval($cuota->pago_diario_monto);
            }
            $total_cuotas = round($total_cuotas, 2);

            // El descuento nunca puede superar el total nominal de las cuotas seleccionadas
            $descuento_monto = max(0, min($descuento_monto, $total_cuotas));
            $total_cobrado   = round(max(0, $total_cuotas - $descuento_monto), 2);

            $monto_recibido_post = !empty($_POST['monto_recibido']) ? (float)$_POST['monto_recibido'] : 0;
            $monto_vuelto_post   = !empty($_POST['monto_vuelto'])   ? (float)$_POST['monto_vuelto']   : 0;

            // Si el cliente entrega más del total sin recibir vuelto, el excedente también amortiza el saldo
            $excedente = ($monto_recibido_post > $total_cobrado && $monto_vuelto_post == 0)
                ? round($monto_recibido_post - $total_cobrado, 2)
                : 0;
            $total_aplicado = round($total_cobrado + $excedente, 2);

            $this->cobros->iniciar_transaccion();
            $transaction_started = true;

            // ==========================================
            // 3. REGISTRO: UNA FILA EN pagos POR CADA CUOTA,
            //    TODAS CON EL MISMO pago_mt (identificador de la operación)
            // ==========================================
            $mt             = microtime(true);
            $ultimo_idx     = count($cuotas_a_pagar) - 1;
            $montos_fila    = [];
            $descuentos_fila = [];

            // El descuento se consume en cascada sobre las cuotas, empezando por la más antigua
            $descuento_restante = $descuento_monto;
            foreach ($cuotas_a_pagar as $idx => $cuota) {
                $monto_cuota    = floatval($cuota->pago_diario_monto);
                $descuento_fila = round(min($descuento_restante, $monto_cuota), 2);
                $descuento_restante = round($descuento_restante - $descuento_fila, 2);

                $monto_fila = $monto_cuota - $descuento_fila;
                // El excedente sin vuelto se suma a la última cuota de la operación
                if ($idx === $ultimo_idx) {
                    $monto_fila += $excedente;
                }

                $descuentos_fila[$idx] = $descuento_fila;
                $montos_fila[$idx]     = round($monto_fila, 2);
            }

            // Reparto del monto recibido entre las filas para que los reportes de caja
            // (que suman recibido - vuelto por fila) no dupliquen el ingreso de la operación.
            $recibido_fila = [];
            if ($monto_recibido_post > 0) {
                $suma_resto = 0;
                foreach ($montos_fila as $idx => $monto_fila) {
                    if ($idx !== 0) $suma_resto += $monto_fila;
                }
                $recibido_fila[0] = round(max(0, $monto_recibido_post - $suma_resto), 2);
                foreach ($montos_fila as $idx => $monto_fila) {
                    if ($idx !== 0) $recibido_fila[$idx] = $monto_fila;
                }
            }

            foreach ($cuotas_a_pagar as $idx => $cuota) {
                $id_pago_cuota    = (int)$cuota->id_pago_diario;
                $descuento_fila   = $descuentos_fila[$idx];
                $estado_descuento = ($descuento_fila > 0) ? 1 : 0;

                // Descuento aplicado sobre la cuota (tabla pagos_diarios)
                if ($descuento_fila > 0) {
                    $this->builder->update("pagos_diarios", array(
                        'pago_diario_descuento_estado'  => 1,
                        'pago_diario_descuento_monto'   => $descuento_fila,
                        'pago_diario_descuento_fecha'   => $fecha_descuento,
                        'pago_diario_descuento_usuario' => $usuario_nombre->usuario_nickname
                    ), array(
                        'id_pago_diario' => $id_pago_cuota
                    ));
                }

                // La cuota queda marcada como pagada
                $this->cobros->cambiar_estado_cuota($id_pago_cuota);

                $this->builder->save("pagos", array(
                    'id_prestamo'           => $id_prestamo,
                    'id_pago_diario'        => $id_pago_cuota,
                    'pago_monto'            => $montos_fila[$idx],
                    'pago_metodo'           => $_POST['pago_metodo'] ?? '',
                    'pago_fecha'            => date('Y-m-d H:i:s'),
                    'pago_estado'           => 1,
                    'pago_mt'               => $mt,
                    'id_cliente'            => $prestamo->id_cliente,
                    'id_usuario'            => $id_usuario,
                    'pago_descuento_estado' => $estado_descuento,
                    'pago_descuento_monto'  => $descuento_fila,
                    'pago_monto_recibido'   => isset($recibido_fila[$idx]) ? $recibido_fila[$idx] : null,
                    'pago_monto_vuelto'     => ($idx === 0 && $monto_vuelto_post > 0) ? $monto_vuelto_post : null,
                    'pago_operacion'        => !empty($_POST['num_operacion'])       ? trim($_POST['num_operacion'])    : null,
                    'pago_oper_titular'     => !empty($_POST['nombre_titular'])      ? trim($_POST['nombre_titular'])   : null,
                    // Dato INTERNO de conciliación: no se imprime en el voucher del cliente
                    'pago_cuenta_receptora' => !empty($_POST['cuenta_receptora'])
                        ? mb_substr(trim($_POST['cuenta_receptora']), 0, 120) : null,
                    'id_banco'              => !empty($_POST['banco_entidad'])       ? (int)$_POST['banco_entidad']     : null,
                    'pago_fecha_operacion'  => !empty($_POST['fecha_transferencia']) ? $_POST['fecha_transferencia']    : null,
                    'pago_observacion'      => !empty($_POST['pago_observacion'])    ? trim($_POST['pago_observacion']) : null
                ));
            }

            // ==========================================
            // 4. ACTUALIZAR DINERO EN CAJA (una sola vez por operación)
            // ==========================================
            $ingreso_caja = ($monto_recibido_post > 0)
                ? ($monto_recibido_post - $monto_vuelto_post)
                : $total_cobrado;
            $this->builder->update("caja", array(
                'monto_caja' => $caja_abierta->monto_caja + $ingreso_caja,
            ), array(
                'id_caja' => $caja_abierta->id_caja,
            ));

            // ==========================================
            // 5. EVALUAR EL ESTADO GLOBAL DEL PRÉSTAMO
            // ==========================================
            $nuevo_saldo = round(max(0, $prestamo->prestamo_saldo_pagar - $total_aplicado), 2);

            // Próximo cobro = siguiente cuota que quedó pendiente
            $cuotas_pendientes = $this->cobros->listar_cuotas_pendientes_ordenadas($id_prestamo);
            $prestamo_cancelado = (count($cuotas_pendientes) === 0 || $nuevo_saldo <= 0);

            $datos_actualizar_prestamo = array(
                'prestamo_saldo_pagar' => $nuevo_saldo
            );

            if (!$prestamo_cancelado) {
                $datos_actualizar_prestamo['prestamo_prox_cobro'] = $cuotas_pendientes[0]->pago_diario_fecha;
            } else {
                // Sin cuotas pendientes o saldo en cero: el préstamo queda CANCELADO.
                // Si el saldo llegó a cero con cuotas todavía abiertas, se cierran todas
                // para que el crédito no vuelva a aparecer en próximos cobros.
                if (count($cuotas_pendientes) > 0) {
                    $this->cobros->cerrar_cuotas_pendientes($id_prestamo);
                }
                $datos_actualizar_prestamo['prestamo_estado']      = 2; // 2 = Cancelado (pagado)
                $datos_actualizar_prestamo['prestamo_saldo_pagar'] = 0;
            }

            $this->builder->update("prestamos", $datos_actualizar_prestamo, array(
                'id_prestamos' => $id_prestamo
            ));

            // Préstamo cancelado por saldo cero: se devuelve el capital prestado a la
            // línea de crédito del cliente y queda registrado en su historial.
            if ($prestamo_cancelado) {
                $this->cobros->restaurar_linea_credito(
                    $prestamo->id_cliente,
                    $prestamo->prestamo_monto,
                    $id_usuario,
                    $id_prestamo,
                    'cancelacion'
                );
            }

            $this->cobros->confirmar_transaccion();
            $transaction_started = false;

            $pago_guardado    = $this->cobros->listar_pago_guardado_x_mt($mt);
            $id_pago_generado = $pago_guardado ? $pago_guardado->id_pago : 0;

            $result  = 1;
            $message = 'OK';

        } catch (Throwable $e) {
            if ($transaction_started) {
                $this->cobros->revertir_transaccion();
            }
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

    public function amortizar()
    {
        try {
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus(
                $this->encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_)
            );

            $id_prestamo    = (int)$_GET['id'];
            $prestamos_data = $this->prestamos->listar_x_id($id_prestamo);
            $cliente_data   = $this->clientes->listar_x_id($prestamos_data->id_cliente);
            $metodos_pago   = $this->cobros->listar_metodos_de_pago();
            $bancos         = $this->cobros->listar_bancos();
            $cuentas_receptoras = $this->cobros->listar_cuentas_receptoras();

            $todas_las_cuotas  = $this->cobros->listar_cuotas_x_prestamo($id_prestamo);
            $cuotas_pendientes = array_values(array_filter(
                $todas_las_cuotas, fn($c) => $c->pago_diario_estado == 1
            ));
            $cuota_a_pagar = $cuotas_pendientes[0] ?? null;

            $saldo_total_pendiente = floatval($prestamos_data->prestamo_saldo_pagar ?? 0);
            $tasa                  = floatval($prestamos_data->prestamo_interes ?? 0);
            $capital_pendiente     = $tasa > 0
                ? round($saldo_total_pendiente / (1 + $tasa / 100), 2)
                : $saldo_total_pendiente;

            $puede_amortizar = intval($prestamos_data->prestamo_estado) === 1
                && $capital_pendiente > 0
                && !empty($cuota_a_pagar)
                && strtotime(date('Y-m-d')) < strtotime($cuota_a_pagar->pago_diario_fecha)
                && !in_array(intval($prestamos_data->prestamo_estado), [2, 4]);

            if (!$puede_amortizar) {
                header('Location: ' . _SERVER_ . 'cobros/pagar/' . $id_prestamo);
                exit;
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/amortizar.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error al cargar amortización\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function guardar_amortizacion()
    {
        $result  = 2;
        $message = 'Error al procesar la amortización.';
        $id_pago_generado    = 0;
        $transaction_started = false;

        try {
            $id_prestamo     = (int)$_POST['id_prestamo'];
            $monto_amortizar = (float)$_POST['monto_amortizar'];
            $id_usuario      = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);
            $usuario_nombre  = $this->cobros->listar_usuario($id_usuario);

            $prestamo     = $this->prestamos->listar_x_id($id_prestamo);
            $caja_abierta = $this->caja->traer_datos_caja();

            if (!$prestamo || !$caja_abierta) {
                $message = 'No se encontraron los datos del préstamo o la caja está cerrada.';
                echo json_encode(['result' => ['code' => $result, 'message' => $message]]);
                return;
            }

            $saldo_total       = floatval($prestamo->prestamo_saldo_pagar);
            $tasa              = floatval($prestamo->prestamo_interes);
            $capital_pendiente = $tasa > 0
                ? round($saldo_total / (1 + $tasa / 100), 2)
                : $saldo_total;

            // Validaciones de negocio
            if ($monto_amortizar <= 0) {
                echo json_encode(['result' => ['code' => 3, 'message' => 'El monto debe ser mayor a S/ 0.00.']]);
                return;
            }
            if ($monto_amortizar > $capital_pendiente) {
                echo json_encode(['result' => ['code' => 4, 'message' => 'El monto excede el capital pendiente (S/ ' . number_format($capital_pendiente, 2) . ').']]);
                return;
            }
            if ($monto_amortizar > $saldo_total) {
                echo json_encode(['result' => ['code' => 5, 'message' => 'El monto excede el saldo total pendiente (S/ ' . number_format($saldo_total, 2) . ').']]);
                return;
            }

            $this->cobros->iniciar_transaccion();
            $transaction_started = true;

            // Registrar en tabla pagos
            $mt = microtime(true);
            $this->builder->save('pagos', [
                'id_prestamo'          => $id_prestamo,
                'id_pago_diario'       => null,
                'pago_monto'           => $monto_amortizar,
                'pago_metodo'          => $_POST['pago_metodo'] ?? '',
                'pago_fecha'           => date('Y-m-d H:i:s'),
                'pago_estado'          => 1,
                'pago_mt'              => $mt,
                'id_cliente'           => $prestamo->id_cliente,
                'id_usuario'           => $id_usuario,
                'pago_monto_recibido'  => !empty($_POST['monto_recibido'])      ? (float)$_POST['monto_recibido']      : null,
                'pago_monto_vuelto'    => !empty($_POST['monto_vuelto'])        ? (float)$_POST['monto_vuelto']        : null,
                'pago_operacion'       => !empty($_POST['num_operacion'])        ? trim($_POST['num_operacion'])         : null,
                'pago_oper_titular'    => !empty($_POST['nombre_titular'])       ? trim($_POST['nombre_titular'])        : null,
                // Dato INTERNO de conciliación: no se imprime en el voucher del cliente
                'pago_cuenta_receptora' => !empty($_POST['cuenta_receptora'])
                    ? mb_substr(trim($_POST['cuenta_receptora']), 0, 120) : null,
                'id_banco'             => !empty($_POST['banco_entidad'])        ? (int)$_POST['banco_entidad']         : null,
                'pago_fecha_operacion' => !empty($_POST['fecha_transferencia'])  ? $_POST['fecha_transferencia']        : null,
                'pago_observacion'     => 'AMORTIZACIÓN' . (!empty($_POST['pago_observacion']) ? ': ' . trim($_POST['pago_observacion']) : ''),
            ]);

            // Actualizar caja
            // Ingreso = Monto recibido - Vuelto (si hay efectivo); si no, usar monto_amortizar
            $monto_recibido_post = !empty($_POST['monto_recibido']) ? (float)$_POST['monto_recibido'] : 0;
            $monto_vuelto_post   = !empty($_POST['monto_vuelto'])   ? (float)$_POST['monto_vuelto']   : 0;
            $ingreso_caja = ($monto_recibido_post > 0)
                ? ($monto_recibido_post - $monto_vuelto_post)
                : $monto_amortizar;
            $this->builder->update('caja', [
                'monto_caja' => $caja_abierta->monto_caja + $ingreso_caja,
            ], ['id_caja' => $caja_abierta->id_caja]);

            // Actualizar saldo del préstamo (amortización aplica sobre capital, el interés proporcional se limpia)
            $nuevo_capital  = max(0, $capital_pendiente - $monto_amortizar);
            $nuevo_saldo    = $tasa > 0 ? round($nuevo_capital * (1 + $tasa / 100), 2) : $nuevo_capital;
            $datos_prestamo = ['prestamo_saldo_pagar' => $nuevo_saldo];
            if ($nuevo_saldo <= 0) {
                $datos_prestamo['prestamo_estado'] = 2;
                // Préstamo cancelado por amortización total: restaurar línea de crédito
                $this->cobros->restaurar_linea_credito(
                    $prestamo->id_cliente,
                    $prestamo->prestamo_monto,
                    $id_usuario,
                    $id_prestamo,
                    'cancelacion'
                );
            }
            $this->builder->update('prestamos', $datos_prestamo, ['id_prestamos' => $id_prestamo]);

            // Redistribuir el nuevo saldo entre las cuotas pendientes y actualizar próximo cobro
            $cuotas = $this->cobros->listar_cuotas_pendientes_ordenadas($id_prestamo);
            $n      = count($cuotas);
            if ($nuevo_saldo > 0 && $n > 0) {
                $cuota_base = round($nuevo_saldo / $n, 2);
                $acumulado  = 0;
                foreach ($cuotas as $i => $cuota) {
                    $monto = ($i === $n - 1)
                        ? round($nuevo_saldo - $acumulado, 2)
                        : $cuota_base;
                    $this->builder->update('pagos_diarios',
                        ['pago_diario_monto' => $monto],
                        ['id_pago_diario'    => $cuota->id_pago_diario]
                    );
                    if ($i < $n - 1) $acumulado += $monto;
                }

                // Reflejar la próxima cuota actualizada en el préstamo
                $this->builder->update('prestamos',
                    ['prestamo_prox_cobro' => $cuotas[0]->pago_diario_fecha],
                    ['id_prestamos'        => $id_prestamo]
                );
            }

            $this->cobros->confirmar_transaccion();
            $transaction_started = false;

            $pago_guardado    = $this->cobros->listar_pago_guardado_x_mt($mt);
            $id_pago_generado = $pago_guardado ? $pago_guardado->id_pago : 0;

            $result  = 1;
            $message = 'Amortización registrada correctamente.';

        } catch (Exception $e) {
            if ($transaction_started) {
                $this->cobros->revertir_transaccion();
            }
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }

        echo json_encode(['result' => ['code' => $result, 'message' => $message, 'id_pago' => $id_pago_generado]]);
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

            // ── OPERACIÓN COMPLETA ────────────────────────────────────────────
            // Un cobro de varias cuotas genera una fila en "pagos" por cada cuota,
            // todas con el mismo pago_mt. Se agrupan para emitir UN solo recibo.
            $grupo = !empty($data->pago_mt) ? $this->cobros->listar_pagos_x_mt($data->pago_mt) : [];
            $grupo = array_values(array_filter($grupo, function ($p) use ($data) {
                return intval($p->id_prestamo) === intval($data->id_prestamos);
            }));
            if (empty($grupo)) $grupo = [$data]; // pagos antiguos sin pago_mt

            // Numeración de cuotas (Cuota 1, 2, 3...) sobre el cronograma completo
            $numero_de_cuota = [];
            $n_cuota = 0;
            foreach ($this->cobros->listar_todas_las_cuotas_x_prestamo($data->id_prestamos) as $cuota_crono) {
                $numero_de_cuota[$cuota_crono->id_pago_diario] = ++$n_cuota;
            }

            // Totales de la operación (suma de todas las cuotas cobradas juntas)
            $op_total_pagado    = 0;
            $op_total_descuento = 0;
            $op_total_recibido  = 0;
            $op_total_vuelto    = 0;
            $ids_recibo         = [];
            foreach ($grupo as $fila) {
                $op_total_pagado    += floatval($fila->pago_monto);
                $op_total_descuento += floatval($fila->pago_descuento_monto ?? 0);
                $op_total_recibido  += floatval($fila->pago_monto_recibido ?? 0);
                $op_total_vuelto    += floatval($fila->pago_monto_vuelto ?? 0);
                $ids_recibo[]        = intval($fila->id_pago);
            }
            $op_total_pagado    = round($op_total_pagado, 2);
            $op_total_descuento = round($op_total_descuento, 2);
            $op_total_recibido  = round($op_total_recibido, 2);
            $op_total_vuelto    = round($op_total_vuelto, 2);
            $nro_recibo         = min($ids_recibo);
            $es_multiple        = count($grupo) > 1;

            // Totales del prestamo
            $debe_pagar      = floatval($data->prestamo_monto) + (floatval($data->prestamo_monto) * floatval($data->prestamo_interes) / 100);
            // Suma de dinero real recibido (pagos.pago_monto), no del nominal de las cuotas
            $ya_pago_result  = $this->cobros->listar_total_pagos_reales_x_prestamo($data->id_prestamos);
            $ya_pago         = floatval($ya_pago_result->total ?? 0);
            // Descuentos manuales globales (tabla descuentos, independiente de los descuentos inline por cuota)
            $descuentos_raw  = $this->cobros->listar_decuentos_x_prestamo($data->id_prestamos);
            $descuento       = (!empty($descuentos_raw) && !empty($descuentos_raw[0]->total)) ? floatval($descuentos_raw[0]->total) : 0;
            $saldo_final     = max(0, $debe_pagar - $ya_pago - $descuento);
            // saldo_anterior: lo que había antes de ESTA operación (todas sus cuotas)
            $saldo_anterior  = $saldo_final + $op_total_pagado;

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
            $pdf->Ln(9);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell($W, 5, 'INVERSIONES GUZZ E.I.R.L', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, 'RUC: 20600864255', 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell($W, 5, 'RECIBO DE PAGO Nro. ' . str_pad($nro_recibo, 6, '0', STR_PAD_LEFT), 0, 1, 'C');
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

            // Detalle: una línea por cada cuota cobrada en esta operación
            $es_amortizacion = empty($data->id_pago_diario);

            if ($es_amortizacion) {
                $pdf->Cell($W, 4, 'Amortización:', 0, 1, 'L');
                $pdf->Cell(5,  4, '', 0, 0);
                $pdf->Cell(35, 4, 'Monto:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($cuota_original, 2), 0, 1, 'R');
            } else {
                $pdf->Cell($W, 4, $es_multiple ? ('Cuotas cobradas (' . count($grupo) . '):') : 'Cuota:', 0, 1, 'L');

                foreach ($grupo as $fila) {
                    $num_cuota   = $numero_de_cuota[$fila->id_pago_diario] ?? null;
                    $fecha_cuota = !empty($fila->fecha_cuota) ? date('d/m/Y', strtotime($fila->fecha_cuota)) : '';
                    $desc_fila   = floatval($fila->pago_descuento_monto ?? 0);
                    $nominal     = isset($fila->cuota_original)
                        ? floatval($fila->cuota_original)
                        : round(floatval($fila->pago_monto) + $desc_fila, 2);

                    $etiqueta = $num_cuota ? ('Cuota ' . $num_cuota) : 'Cuota';
                    if ($fecha_cuota !== '') $etiqueta .= ' - ' . $fecha_cuota;

                    $pdf->Cell(5,  4, '', 0, 0);
                    $pdf->Cell(35, 4, $etiqueta, 0, 0, 'L');
                    $pdf->Cell(30, 4, 'S/ ' . number_format($nominal, 2), 0, 1, 'R');

                    if ($desc_fila > 0) {
                        $pdf->Cell(10, 4, '', 0, 0);
                        $pdf->Cell(30, 4, 'Descuento:', 0, 0, 'L');
                        $pdf->Cell(30, 4, '-S/ ' . number_format($desc_fila, 2), 0, 1, 'R');
                    }
                }

                if ($op_total_descuento > 0) {
                    $pdf->Cell(5,  3, '', 0, 0);
                    $pdf->Cell(65, 3, $SEP_INT, 0, 1, 'C');
                    $pdf->Cell(5,  4, '', 0, 0);
                    $pdf->Cell(35, 4, 'Descuento total:', 0, 0, 'L');
                    $pdf->Cell(30, 4, '-S/ ' . number_format($op_total_descuento, 2), 0, 1, 'R');
                }
            }

            // Pago (totales de la operación completa)
            $pdf->Cell($W, 4, 'Pago:', 0, 1, 'L');
            $pdf->Cell(5,  4, '', 0, 0);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, 'Total pagado:', 0, 0, 'L');
            $pdf->Cell(30, 4, 'S/ ' . number_format($op_total_pagado, 2), 0, 1, 'R');
            $pdf->SetFont('Arial', '', 8);

            if ($op_total_recibido > 0) {
                $diferencia = round($op_total_recibido - $op_total_pagado, 2);

                $pdf->Cell(5,  4, '', 0, 0);
                $pdf->Cell(35, 4, 'Monto Recibido:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($op_total_recibido, 2), 0, 1, 'R');

                if ($op_total_vuelto > 0) {
                    $pdf->Cell(5,  4, '', 0, 0);
                    $pdf->Cell(35, 4, 'Vuelto:', 0, 0, 'L');
                    $pdf->Cell(30, 4, 'S/ ' . number_format($op_total_vuelto, 2), 0, 1, 'R');
                } elseif ($diferencia != 0) {
                    $pdf->Cell(5,  4, '', 0, 0);
                    $pdf->Cell(35, 4, 'Diferencia:', 0, 0, 'L');
                    $pdf->Cell(30, 4, 'S/ ' . number_format($diferencia, 2), 0, 1, 'R');
                }
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
            // Mismos datos que la sección "Información del crédito" de las pantallas
            // cobros/pagar.php y cobros/pagos.php (Cobros::resumen_credito).
            $ic = $this->cobros->resumen_credito($data->id_prestamos);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell($W, 4, 'INFORMACION DEL CREDITO', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 8);

            if ($ic) {
                $fmt_fecha = function ($valor) {
                    return !empty($valor) && strtotime($valor) ? date('d/m/Y', strtotime($valor)) : '-';
                };

                $pdf->Cell(40, 4, 'Capital:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($ic->capital, 2), 0, 1, 'R');
                $pdf->Cell(40, 4, 'Interes (' . floatval($ic->interes_pct) . '%):', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($ic->interes_monto, 2), 0, 1, 'R');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(40, 4, 'Total del credito:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($ic->total_credito, 2), 0, 1, 'R');
                $pdf->SetFont('Arial', '', 8);

                $pdf->Cell($W, 4, 'Fecha de emision: ' . $fmt_fecha($ic->fecha_emision), 0, 1, 'L');
                $pdf->Cell($W, 4, 'Fecha de inicio: '  . $fmt_fecha($ic->fecha_inicio), 0, 1, 'L');
                $pdf->Cell($W, 4, 'Vencimiento: '      . $fmt_fecha($ic->fecha_vencimiento), 0, 1, 'L');

                if ($ic->esta_anulado) {
                    $pdf->Cell($W, 4, 'Proximo pago: CREDITO ANULADO', 0, 1, 'L');
                } elseif ($ic->esta_cancelado) {
                    $pdf->Cell($W, 4, 'Proximo pago: CREDITO CANCELADO', 0, 1, 'L');
                } else {
                    $pdf->Cell($W, 4, 'Proximo pago: ' . $fmt_fecha($ic->proximo_pago), 0, 1, 'L');
                    $pdf->Cell($W, 4, 'Cuotas pendientes: ' . $ic->cuotas_pendientes, 0, 1, 'L');
                }

                $pdf->Cell($W, 4, 'Pagos realizados: ' . $ic->pagos_realizados
                    . ' (' . $ic->cuotas_pagadas . ' de ' . $ic->cuotas_total . ' cuotas)', 0, 1, 'L');
                $pdf->Cell(40, 4, 'Total pagado:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($ic->total_pagado, 2), 0, 1, 'R');
                if ($ic->total_descuento > 0) {
                    $pdf->Cell(40, 4, 'Descuentos aplicados:', 0, 0, 'L');
                    $pdf->Cell(30, 4, '-S/ ' . number_format($ic->total_descuento, 2), 0, 1, 'R');
                }
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(40, 4, 'Saldo pendiente:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($ic->saldo_pendiente, 2), 0, 1, 'R');

                // Estado del credito: destacado cuando esta vencido o cancelado
                $pdf->Cell($W, 4, 'Estado: ' . strtoupper($ic->estado_etiqueta), 0, 1, 'L');
                $pdf->SetFont('Arial', '', 8);

                if ($ic->esta_vencido) {
                    $pdf->MultiCell($W, 4, 'Credito VENCIDO desde el ' . $fmt_fecha($ic->fecha_vencimiento)
                        . '. Saldo pendiente: S/ ' . number_format($ic->saldo_pendiente, 2) . '.', 0, 'L');
                } elseif ($ic->esta_cancelado) {
                    $pdf->MultiCell($W, 4, 'Credito CANCELADO: no quedan cuotas por cobrar.', 0, 'L');
                }
            } else {
                // Respaldo si no se pudo construir el resumen
                $pdf->Cell($W, 4, 'Interes aplicado: ' . floatval($data->prestamo_interes) . '%', 0, 1, 'L');
                if (!empty($fecha_vencimiento)) {
                    $pdf->Cell($W, 4, 'Fecha de vencimiento: ' . date('d/m/Y', strtotime($fecha_vencimiento)), 0, 1, 'L');
                }
                $pdf->Cell(40, 4, 'Saldo Restante:', 0, 0, 'L');
                $pdf->Cell(30, 4, 'S/ ' . number_format($saldo_final, 2), 0, 1, 'R');
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

    /**
     * Constancia completa del crédito en PDF (A4).
     *
     * Reúne datos de la empresa, del cliente, el estado y las condiciones del crédito,
     * el resumen financiero y el detalle de todos los pagos registrados.
     * Los importes salen de Cobros::resumen_credito() y Cobros::historial_pagos_con_saldo(),
     * las mismas fuentes que usan cobros/pagar.php y cobros/pagos.php.
     */
    public function generar_constancia(){
        try{
            date_default_timezone_set('America/Lima');
            $id_prestamo = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            $info    = $this->cobros->resumen_credito($id_prestamo);
            $cliente = $this->clientes->listar_x_id_presrtamo($id_prestamo);
            if (empty($info) || empty($cliente)) throw new Exception("Crédito no encontrado.");

            $pagos = $this->cobros->historial_pagos_con_saldo($id_prestamo);

            // Numeración de cuotas del cronograma
            $numero_de_cuota = [];
            $n_cuota = 0;
            foreach ($this->cobros->listar_todas_las_cuotas_x_prestamo($id_prestamo) as $cuota_crono) {
                $numero_de_cuota[$cuota_crono->id_pago_diario] = ++$n_cuota;
            }

            $fmt = function ($valor, $con_hora = false) {
                if (empty($valor) || !strtotime($valor)) return '-';
                return date($con_hora ? 'd/m/Y H:i' : 'd/m/Y', strtotime($valor));
            };
            // FPDF con fuentes core usa latin1: convertimos los textos con tildes
            // (mb_convert_encoding en lugar de utf8_decode, obsoleto desde PHP 8.2)
            $tx = function ($texto) {
                return mb_convert_encoding((string)$texto, 'ISO-8859-1', 'UTF-8');
            };

            $apellido_m     = !empty($cliente->cliente_apellido_materno) ? ' ' . $cliente->cliente_apellido_materno : '';
            $nombre_cliente = strtoupper($cliente->cliente_nombre . ' ' . $cliente->cliente_apellido_paterno . $apellido_m);

            // ── PDF A4 ────────────────────────────────────────────────────────
            $pdf = new FPDF('P', 'mm', 'A4');
            $pdf->SetMargins(12, 12, 12);
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->AddPage();
            $W = 186; // 210 - 12 - 12

            // ENCABEZADO EMPRESA
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell($W, 6, $tx('INVERSIONES GUZZ E.I.R.L'), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell($W, 4, $tx('RUC: 20600864255'), 0, 1, 'C');
            $pdf->Cell($W, 4, $tx('Constancia emitida el ' . date('d/m/Y H:i')), 0, 1, 'C');
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell($W, 7, $tx('CONSTANCIA DEL CREDITO N° ' . str_pad($info->id_prestamo, 6, '0', STR_PAD_LEFT)), 1, 1, 'C');
            $pdf->Ln(4);

            // Ayudantes de maquetación
            $seccion = function ($titulo) use ($pdf, $W, $tx) {
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(37, 99, 235);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell($W, 6, $tx(' ' . $titulo), 0, 1, 'L', true);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', '', 9);
                $pdf->Ln(1);
            };
            $par = function ($etiqueta1, $valor1, $etiqueta2 = null, $valor2 = null) use ($pdf, $tx) {
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(35, 5, $tx($etiqueta1), 0, 0, 'L');
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(58, 5, $tx($valor1), 0, 0, 'L');
                if ($etiqueta2 !== null) {
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->Cell(35, 5, $tx($etiqueta2), 0, 0, 'L');
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->Cell(58, 5, $tx($valor2), 0, 0, 'L');
                }
                $pdf->Ln(5);
            };

            // DATOS DEL CLIENTE
            $seccion('DATOS DEL CLIENTE');
            $par('Cliente:', $nombre_cliente, 'DNI:', $cliente->cliente_dni);
            $par('Direccion:', $cliente->cliente_direccion ?: '-', 'Telefono:', $cliente->cliente_celular ?: ($cliente->ciente_telefono ?: '-'));
            $pdf->Ln(3);

            // ESTADO DEL CREDITO
            $seccion('ESTADO DEL CREDITO');
            if ($info->esta_vencido)         { $pdf->SetFillColor(254, 226, 226); $pdf->SetTextColor(153, 27, 27); }
            elseif ($info->esta_cancelado)   { $pdf->SetFillColor(220, 252, 231); $pdf->SetTextColor(21, 128, 61); }
            elseif ($info->esta_anulado)     { $pdf->SetFillColor(243, 244, 246); $pdf->SetTextColor(55, 65, 81); }
            else                             { $pdf->SetFillColor(239, 246, 255); $pdf->SetTextColor(29, 78, 216); }
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell($W, 7, $tx('  ' . strtoupper($info->estado_etiqueta)), 0, 1, 'L', true);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 9);

            if ($info->esta_vencido) {
                $pdf->MultiCell($W, 5, $tx('Credito vencido desde el ' . $fmt($info->fecha_vencimiento)
                    . '. Saldo pendiente: S/ ' . number_format($info->saldo_pendiente, 2) . '.'), 0, 'L');
            } elseif ($info->esta_cancelado) {
                $pdf->MultiCell($W, 5, $tx('Credito cancelado: no quedan cuotas por cobrar'
                    . ($info->ultimo_pago ? '. Ultimo pago el ' . $fmt($info->ultimo_pago, true) : '') . '.'), 0, 'L');
            } elseif ($info->esta_anulado) {
                $pdf->MultiCell($W, 5, $tx('Credito anulado. Los importes se conservan solo como historial.'), 0, 'L');
            }
            $pdf->Ln(3);

            // CONDICIONES DEL CREDITO
            $seccion('CONDICIONES DEL CREDITO');
            $par('Capital:',   'S/ ' . number_format($info->capital, 2),
                 'Modalidad:', ucfirst($info->tipo_pago));
            $par('Interes (' . floatval($info->interes_pct) . '%):', 'S/ ' . number_format($info->interes_monto, 2),
                 'Cuotas:',    $info->cuotas_total . ' (' . $info->cuotas_pagadas . ' pagadas / ' . $info->cuotas_pendientes . ' pendientes)');
            $par('Total del credito:', 'S/ ' . number_format($info->total_credito, 2),
                 'Pagos registrados:', (string)$info->pagos_realizados);
            $pdf->Ln(2);
            $par('Fecha de emision:', $fmt($info->fecha_emision), 'Fecha de inicio:', $fmt($info->fecha_inicio));
            $par('Vencimiento:', $fmt($info->fecha_vencimiento),
                 'Proximo pago:', ($info->esta_cancelado || $info->esta_anulado) ? 'Sin cobros pendientes' : $fmt($info->proximo_pago));
            $pdf->Ln(3);

            // RESUMEN FINANCIERO
            $seccion('RESUMEN FINANCIERO');
            $fila_resumen = function ($etiqueta, $valor, $negrita = false) use ($pdf, $tx) {
                $pdf->SetFont('Arial', $negrita ? 'B' : '', 9);
                $pdf->Cell(120, 6, $tx($etiqueta), 'LTB', 0, 'L');
                $pdf->Cell(66, 6, $tx($valor), 'RTB', 1, 'R');
            };
            $fila_resumen('Total del credito (capital + interes)', 'S/ ' . number_format($info->total_credito, 2));
            $fila_resumen('Total pagado', '- S/ ' . number_format($info->total_pagado, 2));
            if ($info->total_descuento > 0) {
                $fila_resumen('Descuentos aplicados', '- S/ ' . number_format($info->total_descuento, 2));
            }
            $fila_resumen('SALDO PENDIENTE', 'S/ ' . number_format($info->saldo_pendiente, 2), true);
            $pdf->Ln(4);

            // DETALLE DE PAGOS
            $seccion('DETALLE DE PAGOS');
            $cols = array(24, 18, 24, 24, 26, 22, 22, 26);
            $enc  = array('Fecha', 'Cuota', 'C. original', 'Monto pagado', 'Metodo', 'Descuento', 'Usuario', 'Saldo restante');

            $cabecera_tabla = function () use ($pdf, $cols, $enc, $tx) {
                $pdf->SetFont('Arial', 'B', 7.5);
                $pdf->SetFillColor(241, 245, 249);
                foreach ($enc as $i => $titulo) {
                    $pdf->Cell($cols[$i], 6, $tx($titulo), 1, 0, 'C', true);
                }
                $pdf->Ln(6);
                $pdf->SetFont('Arial', '', 7.5);
            };
            $cabecera_tabla();

            if (empty($pagos)) {
                $pdf->Cell(array_sum($cols), 6, $tx('Este credito aun no registra pagos.'), 1, 1, 'C');
            } else {
                foreach ($pagos as $pg) {
                    // Repetir cabecera si la tabla salta de pagina
                    if ($pdf->GetY() > 258) {
                        $pdf->AddPage();
                        $cabecera_tabla();
                    }

                    $es_amortizacion = empty($pg->fecha_cuota);
                    $num_cuota       = $es_amortizacion ? 'Amort.' : ($numero_de_cuota[$pg->id_pago_diario] ?? '-');
                    $descuento       = floatval($pg->pago_descuento_monto ?? 0);
                    $cuota_original  = $es_amortizacion
                        ? null
                        : (isset($pg->cuota_original) ? floatval($pg->cuota_original) : null);

                    $pdf->Cell($cols[0], 5.5, $tx($fmt($pg->pago_fecha)), 1, 0, 'C');
                    $pdf->Cell($cols[1], 5.5, $tx($num_cuota), 1, 0, 'C');
                    $pdf->Cell($cols[2], 5.5, $tx($cuota_original !== null ? number_format($cuota_original, 2) : '-'), 1, 0, 'R');
                    $pdf->Cell($cols[3], 5.5, $tx(number_format(floatval($pg->pago_monto), 2)), 1, 0, 'R');
                    $pdf->Cell($cols[4], 5.5, $tx($this->recortar($pg->metodo_nombre ?? '-', 14)), 1, 0, 'C');
                    $pdf->Cell($cols[5], 5.5, $tx($descuento > 0 ? '-' . number_format($descuento, 2) : '-'), 1, 0, 'R');
                    $pdf->Cell($cols[6], 5.5, $tx($this->recortar($pg->pago_usuario ?? '-', 12)), 1, 0, 'C');
                    $pdf->Cell($cols[7], 5.5, $tx(number_format(floatval($pg->saldo_restante ?? 0), 2)), 1, 1, 'R');
                }

                // Totales de la tabla
                $pdf->SetFont('Arial', 'B', 7.5);
                $pdf->SetFillColor(241, 245, 249);
                $pdf->Cell($cols[0] + $cols[1] + $cols[2], 6, $tx('TOTALES'), 1, 0, 'L', true);
                $pdf->Cell($cols[3], 6, $tx(number_format($info->total_pagado, 2)), 1, 0, 'R', true);
                $pdf->Cell($cols[4], 6, '', 1, 0, 'C', true);
                $pdf->Cell($cols[5], 6, $tx($info->total_descuento > 0 ? '-' . number_format($info->total_descuento, 2) : '-'), 1, 0, 'R', true);
                $pdf->Cell($cols[6], 6, '', 1, 0, 'C', true);
                $pdf->Cell($cols[7], 6, $tx(number_format($info->saldo_pendiente, 2)), 1, 1, 'R', true);
            }

            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell($W, 4, $tx('Documento informativo generado por el sistema. No constituye comprobante de pago.'), 0, 1, 'C');

            $pdf->Output('I', 'constancia-credito-' . $info->id_prestamo . '.pdf');

        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"No se pudo generar la constancia del credito.\");</script>";
            echo "<script language=\"javascript\">window.close();</script>";
        }
    }

    /** Recorta un texto para que no desborde la celda del PDF. */
    private function recortar($texto, $largo){
        $texto = trim((string)$texto);
        return (mb_strlen($texto) > $largo) ? (mb_substr($texto, 0, $largo - 1) . '.') : $texto;
    }

    public function anular(){
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
            $caja_abierta = $this->cobros->obtener_caja_abierta();

            if (empty($caja_abierta)) {
                throw new Exception("No hay ninguna caja abierta. Debe abrir caja para procesar la devolución del dinero.");
            }

            // 3. Preparar la matemática
            $monto_capital = floatval($prestamo->prestamo_monto);
            $monto_interes = floatval($prestamo->prestamo_monto_interes);

            // Extraemos el ID del cliente para devolverle su crédito
            $id_cliente = $prestamo->id_cliente;

            $monto_a_devolver = $monto_capital ;
            $saldo_caja_actual = floatval($caja_abierta->monto_caja);
            $nuevo_saldo_caja = $saldo_caja_actual + $monto_a_devolver;

            // ==========================================
            // INICIO DE LA TRANSACCIÓN SQL
            // ==========================================
            $this->cobros->iniciar_transaccion();

            // PASO 1: Cambiar el estado del préstamo a 5 (Anulado)
            $estado_actualizado = $this->cobros->cambiar_estado($id_prestamo, 5);
            if (!$estado_actualizado) {
                throw new Exception("Error al cambiar el estado del préstamo en la base de datos.");
            }

            // PASO 2: Actualizar el monto total de la caja
            $caja_actualizada = $this->cobros->actualizar_monto_caja($caja_abierta->id_caja, $nuevo_saldo_caja);
            if (!$caja_actualizada) {
                throw new Exception("Error al actualizar el saldo de la caja.");
            }

            // PASO 3: Registrar el movimiento de caja (Ingreso)
            $concepto_movimiento = "DEVOLUCIÓN POR ANULACIÓN DE CRÉDITO #" . $id_prestamo;
            $movimiento_registrado = $this->cobros->registrar_movimiento_caja(
                $caja_abierta->id_caja,
                3,
                $monto_capital,
                $hoy
            );
            if (!$movimiento_registrado) {
                throw new Exception("Error al registrar el movimiento en el historial de caja.");
            }

            // ==========================================
            // PASO 4: RESTAURAR LA LÍNEA DE CRÉDITO
            // ==========================================
            $credito_restaurado = $this->cobros->restaurar_linea_credito(
                $id_cliente,
                $monto_capital,
                $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_),
                $id_prestamo,
                'anulacion'
            );
            if (!$credito_restaurado) {
                throw new Exception("Error al restaurar la línea de crédito del cliente.");
            }

            // ==========================================
            // CONFIRMAR TRANSACCIÓN
            // ==========================================
            $this->cobros->confirmar_transaccion(); // COMMIT

            $res['codigo'] = 1;
            $res['mensaje'] = 'Crédito anulado exitosamente. Dinero retornado a caja y línea de crédito restaurada.';

        } catch (Throwable $e) {
            $this->cobros->revertir_transaccion(); // ROLLBACK
            $res['mensaje'] = $e->getMessage();
        }

        echo json_encode($res);
    }
}