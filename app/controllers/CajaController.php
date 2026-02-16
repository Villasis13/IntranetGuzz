<?php
require 'app/models/Caja.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Cobros.php';
require 'app/models/Prestamos.php';
require 'app/models/Builder.php';
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
            $fecha_caja = $this->caja->traer_fecha()->fecha_caja;
            $monto_caja_abierta = $this->caja->traer_monto_caja()->monto_caja;
			$datos_caja_general = $this->caja->traer_datos_caja_general();
            $ultima_caja=$this->caja->listar_ultima_caja();
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
}