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