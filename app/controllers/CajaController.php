<?php
require 'app/models/Caja.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Cobros.php';
require 'app/models/Prestamos.php';
require 'app/models/Builder.php';

require 'app/view/pdf/fpdf/fpdf.php';

class CajaController
{
    private $usuario;
    private $rol;
    private $archivo;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $caja;
    private $builder;
    private $cobros;
    private $prestamos;
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
        $this->caja = new Caja();
        $this->cobros = new Cobros();
        $this->builder = new Builder();
        $this->prestamos = new Prestamos();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $ultima_caja = $this->caja->listar_ultima_caja();

            $fecha_caja = null;
            $monto_caja_abierta = null;

            // Nuevas variables para el Arqueo
            $pagos_caja = [];
            $prestamos_caja = [];
            $ingresos_manuales = [];

            // Asume el nombre del usuario logueado (Ajusta la variable de sesión según tu sistema)
            $usuario_actual = $_SESSION['n_usuario'] ?? 'Administrador';

            if($ultima_caja->estado_caja == 1){
                $fecha_caja = $this->caja->traer_fecha()->fecha_caja;
                $monto_caja_abierta = $this->caja->traer_monto_caja()->monto_caja;

                // 1. Traer Pagos desde la fecha de apertura
                $pagos_caja = $this->prestamos->listar_pagos_desde($fecha_caja);

                // 2. Traer Préstamos desde la fecha de apertura
                $prestamos_caja = $this->prestamos->listar_prestamos_desde($fecha_caja);

                // 3. Traer Ingresos Manuales a caja desde la apertura (Tipo 1 = Ingreso manual, si aplica)
                $ingresos_manuales = $this->prestamos->listar_ingresos_manuales_desde($ultima_caja->id_caja);
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'caja/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function historial_pagos(){
        try{
            $this->nav = new Navbar();
            $fecha=date('Y-m-d');
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $pagos_hoy = $this->caja->ingresos_hoy($fecha);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'caja/historial_pagos.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function reporte_de_cajas(){
        try{
            $this->nav = new Navbar();
            $fecha=date('Y-m-d');
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $listar_ultima_caja=$this->caja->listar_ultima_caja();
            $caja_inicio=$listar_ultima_caja->fecha_caja;
            $caja_fin=$listar_ultima_caja->caja_cierre_fecha;
            $reporte_pagos=$this->caja->listar_reportes_de_caja_pagos($caja_inicio,$caja_fin);
            $reporte_prestamos=$this->caja->listar_reportes_de_caja_prestamos($caja_inicio,$caja_fin);
            $movimientos_caja=$this->caja->listar_reportes_de_caja_movimientos($caja_inicio,$caja_fin);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'caja/reporte_de_pagos.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listar_pago(){
        $result = 2;
        $message = '';
        $alumno = [];

        try {

            $id_pago = $_POST['id_pago'];

            $pago = $this->caja->lista_caja_x_id($id_pago);

            if ($pago){
                $result = 1;
            }

        }catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }

        echo json_encode(array("result" => array("code" => $result, "message" => $message,'pago'=>$pago)));
    }

    public function guardar_monto()
    {
        $result  = 2;
        $message = 'OK';
        $contraseña="joseolaya324";
        try {
            $ok_data = true;
            //$ok_data = $this->validar->validar_parametro('profesor_nombre', 'POST',true,$ok_data,200,'texto',0);
            //$ok_data = $this->validar->validar_parametro('profesor_email', 'POST',true,$ok_data,200,'texto',0);
            //$ok_data = $this->validar->validar_parametro('profesor_dni', 'POST',true,$ok_data,11,'texto',0);



            if ($ok_data) {
                $model = new prestamos();
                $model->id_pago              = $_POST['id_pago'];
                $model->pago_monto              =  $_POST['pago_monto'];

            if ($contraseña == $_POST['contrasenha']){
                // OJO: usa el modelo correcto (registros/profesores/etc.)
                $result = $this->caja->guardar_monto($model);
                } else {
                $result = 3;
            }
            }

        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }

        echo json_encode([
            "result" => [
                "code"    => $result,
                "message" => $message
            ]
        ]);
    }




    public function guardar_movimiento_caja()
    {
        $usuario = $this->usuario->listar_usuario($this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_));
        $result  = 2;
        $message = 'OK';
        $fecha = date('Y-m-d H:i');
        $contrasenha="joseolaya324";
        $ultima_caja=$this->caja->listar_ultima_caja();
        $dinero_ultima_caja=$ultima_caja->monto_caja;
        $dinero_agregado_total=floatval($dinero_ultima_caja) + floatval($_POST['monto_caja']);
        try {
            $ok_data = true;
            //$ok_data = $this->validar->validar_parametro('profesor_nombre', 'POST',true,$ok_data,200,'texto',0);
            //$ok_data = $this->validar->validar_parametro('profesor_email', 'POST',true,$ok_data,200,'texto',0);
            //$ok_data = $this->validar->validar_parametro('profesor_dni', 'POST',true,$ok_data,11,'texto',0);



            if ($ok_data) {
                $model = new prestamos();
                $model->monto_caja = $dinero_agregado_total;
                $model->caja_movimiento_tipo= 1;
                $model->caja_movimiento_monto= $_POST['monto_caja'];
                $model->caja_movimiento_fecha= $fecha;
                $model->id_caja= $ultima_caja->id_caja;
                if ($contrasenha == $_POST['contrasenha']){
                    $result = $this->caja->actualizar_monto_caja($model);
                    $result = $this->caja->guardar_movimiento_caja($model);
                } else {
                    $result = 3;
                }
            }

        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }

        echo json_encode([
            "result" => [
                "code"    => $result,
                "message" => $message
            ]
        ]);
    }




    public function abrir_caja(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
//			$ok_data = $this->validar->validar_parametro('monto_caja', 'POST',true,$ok_data,100,'numero',0);
            if($ok_data){
                $id_caja = $_POST['id_caja'];
                $fecha_caja = date('Y-m-d H:i:s');
                $monto_caja = $_POST['caja_monto'];
                if($id_caja == null){
                    $result = $this->builder->save("caja", array(
                        "fecha_caja" => $fecha_caja,
                        "monto_caja" => $monto_caja,
                        "monto_apertura_caja" => $monto_caja,
                        "estado_caja" => 1
                    ));
                }
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    public function cerrar_caja(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
//			$ok_data = $this->validar->validar_parametro('monto_caja', 'POST',true,$ok_data,100,'numero',0);
            if($ok_data){
                $id_caja = $_POST['id_caja'];
                $fecha= (date("Y-m-d H:i:s"));
                $result=$this->caja->cerrar_caja($fecha,$id_caja);

            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }



    public function editar_caja(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
//			$ok_data = $this->validar->validar_parametro('monto_caja', 'POST',true,$ok_data,100,'numero',0);
            if($ok_data){
                $id_caja = $this->caja->traer_datos_ultimo()->id_caja;
                $monto_caja = $_POST['input_editar_monto'];
                if($id_caja != null){
                    $result = $this->builder->update("caja", array(
                        "monto_caja" => $monto_caja,
                        "monto_apertura_caja" => $monto_caja,
                    ),array(
						'id_caja' => $id_caja
					));
                }
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function abrir_caja_ultimo_monto(){
        $result = 2;
        $message = 'OK';
        try{
            $ok_data = true;
//			$ok_data = $this->validar->validar_parametro('monto_caja', 'POST',true,$ok_data,100,'numero',0);
            if($ok_data){
                $id_caja = $_POST['id_caja'];
                $fecha_caja = date('Y-m-d H:i:s');
                $monto_caja = $_POST['caja_monto'];
                if($id_caja == null){
					$ultimo_monto = $this->caja->traer_monto_caja()->monto_caja;
					if($ultimo_monto){
//						$result = $this->builder->save("caja", array(
//							"fecha_caja" => $fecha_caja,
//							"monto_caja" => $ultimo_monto,
//							"estado_caja" => 1
//						));
						$result = 1;
						$dato_monto = $ultimo_monto;
					}else{
						$result = 3;
					}
                }
            }else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        //Retornamos el json
        echo json_encode(array("result" => array("code" => $result,"dato_m" => $dato_monto, "message" => $message)));
    }


    public function exportar_pdf_arqueo(){
        try {
            date_default_timezone_set('America/Lima');

            // 🚨 IMPORTANTE: Asegúrate de que esta ruta apunte correctamente a tu archivo FPDF
            // Si tu framework ya lo carga automáticamente (autoload), puedes comentar esta línea.
            // require_once _CORE_ . 'fpdf/fpdf.php';

            $ultima_caja = $this->caja->listar_ultima_caja();
            $usuario_actual = $_SESSION['n_usuario'] ?? 'Administrador';

            $fecha_caja        = null;
            $pagos_caja        = [];
            $prestamos_caja    = [];
            $ingresos_manuales = [];

            if($ultima_caja->estado_caja == 1){
                $fecha_caja        = $this->caja->traer_fecha()->fecha_caja;
                $pagos_caja        = $this->prestamos->listar_pagos_desde($fecha_caja);
                $prestamos_caja    = $this->prestamos->listar_prestamos_desde($fecha_caja);
                $ingresos_manuales = $this->prestamos->listar_ingresos_manuales_desde($ultima_caja->id_caja);
            }

            // Cálculos (idénticos a la vista)
            $suma_pagos = 0;
            foreach ((array)$pagos_caja as $p) $suma_pagos += $p->pago_monto;

            $suma_prestamos = 0;
            foreach ((array)$prestamos_caja as $pr) $suma_prestamos += $pr->prestamo_monto;

            $suma_ingresos_manuales = 0;
            foreach ((array)$ingresos_manuales as $m) $suma_ingresos_manuales += $m->caja_movimiento_monto;

            $total_ingresos = $suma_pagos + $suma_ingresos_manuales;
            $total_egresos  = $suma_prestamos;
            $saldo_final    = $ultima_caja->monto_apertura_caja + $total_ingresos - $total_egresos;

            $texto_emision = "Iquitos, " . date('d/m/Y') . " a las " . date('H:i:s');

            // ── FPDF ─────────────────────────────────────────────────────────────
            $pdf = new Fpdf();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetMargins(10, 10, 10);

            // Encabezado
            $pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png', 5, 5, 18);
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell(180, 7, 'ARQUEO DE CAJA', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(180, 5, 'Inversiones y Multiservicios GUZZ E.I.R.L  -  RUC N 20600864255', 0, 1, 'C');
            $pdf->Cell(180, 5, 'Calle Jose Olaya #324 - Tupac Amaru - Iquitos', 0, 1, 'C');
            $pdf->Ln(3);

            // Info general de la caja
            $pdf->SetFillColor(232, 232, 232);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(90, 6, 'Usuario: ' . $usuario_actual, 1, 0, 'L', true);
            $pdf->Cell(90, 6, 'Fecha Apertura: ' . date('d/m/Y H:i:s', strtotime($fecha_caja)), 1, 1, 'L', true);
            $pdf->Cell(90, 6, 'Monto Apertura: S/ ' . number_format($ultima_caja->monto_apertura_caja, 2), 1, 0, 'L', true);
            $pdf->Cell(90, 6, 'ID Caja: #' . $ultima_caja->id_caja, 1, 1, 'L', true);
            $pdf->Ln(4);

            // ── SECCIÓN 1: Apertura ───────────────────────────────────────────────
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(180, 6, 'Apertura de Caja', 1, 1, 'L', true);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 5, 'Fecha',     1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Hora',      1, 0, 'C', true);
            $pdf->Cell(80, 5, 'Usuario',   1, 0, 'C', true);
            $pdf->Cell(48, 5, 'Concepto',  1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(30, 5, date('d/m/Y', strtotime($fecha_caja)), 1, 0, 'C');
            $pdf->Cell(22, 5, date('H:i:s', strtotime($fecha_caja)), 1, 0, 'C');
            $pdf->Cell(80, 5, $usuario_actual, 1, 0, 'L');
            $pdf->Cell(48, 5, 'Saldo anterior / Monto inicial', 1, 1, 'L');
            $pdf->Ln(3);

            // ── SECCIÓN 2: Pago de Cuotas ─────────────────────────────────────────
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(180, 6, 'Pago de Cuotas', 1, 1, 'L', true);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 5, 'Fecha',   1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Hora',    1, 0, 'C', true);
            $pdf->Cell(80, 5, 'Cliente', 1, 0, 'C', true);
            $pdf->Cell(28, 5, 'Metodo',  1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Ingreso', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 8);
            if (!empty($pagos_caja)) {
                foreach ($pagos_caja as $pago) {
                    $pdf->Cell(30, 5, date('d/m/Y', strtotime($pago->pago_fecha)), 1, 0, 'C');
                    $pdf->Cell(22, 5, date('H:i:s', strtotime($pago->pago_fecha)), 1, 0, 'C');
                    $pdf->Cell(80, 5, $pago->cliente_nombre . ' ' . $pago->cliente_apellido_paterno, 1, 0, 'L');
                    $pdf->Cell(28, 5, ucfirst($pago->pago_metodo), 1, 0, 'C');
                    $pdf->Cell(20, 5, 'S/ ' . number_format($pago->pago_monto, 2), 1, 1, 'R');
                }
            } else {
                $pdf->Cell(180, 5, 'No se han registrado pagos en este turno.', 1, 1, 'C');
            }
            $pdf->Ln(3);

            // ── SECCIÓN 3: Préstamos ──────────────────────────────────────────────
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(180, 6, 'Prestamos Otorgados', 1, 1, 'L', true);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 5, 'Fecha',   1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Hora',    1, 0, 'C', true);
            $pdf->Cell(80, 5, 'Cliente', 1, 0, 'C', true);
            $pdf->Cell(28, 5, 'Tipo',    1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Egreso',  1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 8);
            if (!empty($prestamos_caja)) {
                foreach ($prestamos_caja as $prestamo) {
                    $pdf->Cell(30, 5, date('d/m/Y', strtotime($prestamo->prestamo_fecha)), 1, 0, 'C');
                    $pdf->Cell(22, 5, date('H:i:s', strtotime($prestamo->prestamo_fecha)), 1, 0, 'C');
                    $pdf->Cell(80, 5, $prestamo->cliente_nombre . ' ' . $prestamo->cliente_apellido_paterno, 1, 0, 'L');
                    $pdf->Cell(28, 5, 'Prestamo ' . ucfirst($prestamo->prestamo_tipo_pago), 1, 0, 'C');
                    $pdf->Cell(20, 5, 'S/ ' . number_format($prestamo->prestamo_monto, 2), 1, 1, 'R');
                }
            } else {
                $pdf->Cell(180, 5, 'No se han otorgado prestamos en este turno.', 1, 1, 'C');
            }
            $pdf->Ln(3);

            // ── SECCIÓN 4: Ingresos Manuales ──────────────────────────────────────
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(180, 6, 'Ingreso de Monto Manual', 1, 1, 'L', true);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 5, 'Fecha',       1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Hora',        1, 0, 'C', true);
            $pdf->Cell(60, 5, 'Usuario',     1, 0, 'C', true);
            $pdf->Cell(48, 5, 'Descripcion', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Ingreso',     1, 1, 'C', true); // Columna restaurada

            $pdf->SetFont('Arial', '', 8);
            if (!empty($ingresos_manuales)) {
                foreach ($ingresos_manuales as $mov) {
                    $pdf->Cell(30, 5, date('d/m/Y', strtotime($mov->caja_movimiento_fecha)), 1, 0, 'C');
                    $pdf->Cell(22, 5, date('H:i:s', strtotime($mov->caja_movimiento_fecha)), 1, 0, 'C');
                    $pdf->Cell(60, 5, $usuario_actual, 1, 0, 'L');
                    $pdf->Cell(48, 5, 'Anadido Manualmente', 1, 0, 'L');
                    $pdf->Cell(20, 5, 'S/ ' . number_format($mov->caja_movimiento_monto, 2), 1, 1, 'R'); // Monto impreso
                }
            } else {
                $pdf->Cell(180, 5, 'No hay ingresos manuales registrados.', 1, 1, 'C');
            }
            $pdf->Ln(5);

            // ── RESUMEN FINAL ─────────────────────────────────────────────────────
            $pdf->SetFillColor(232, 232, 232);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(130, 6, 'Total Ingresos del Turno:', 1, 0, 'R', true);
            $pdf->Cell(50,  6, 'S/ ' . number_format($total_ingresos, 2), 1, 1, 'R', true);

            $pdf->Cell(130, 6, 'Total Egresos del Turno:', 1, 0, 'R', true);
            $pdf->Cell(50,  6, 'S/ ' . number_format($total_egresos, 2), 1, 1, 'R', true);

            $pdf->SetFillColor(197, 224, 180);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(130, 8, 'SALDO ACTUAL EN CAJA:', 1, 0, 'R', true);
            $pdf->Cell(50,  8, 'S/ ' . number_format($saldo_final, 2), 1, 1, 'R', true);

            $pdf->Ln(4);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(180, 5, $texto_emision, 0, 1, 'R');

            $pdf->Output('I', 'Arqueo_Caja_' . date('Ymd_His') . '.pdf');

        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
        }
    }

}