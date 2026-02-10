<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Prestamos.php';
class ClientesController
{
    private $usuario;
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
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $clientes = $this->clientes->todos_clientes();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function garante(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_cliente = $_GET['id'];
			$data_cliente = $this->clientes->listar_x_id($id_cliente);
			$garantes = $this->clientes->listar_clientes_garantes($id_cliente);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/garante.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_cliente(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_cliente = $_GET['id'];
			$data_cliente = $this->clientes->listar_x_id($id_cliente);
			$data_cliente_h = $this->clientes->listar_x_id_h($id_cliente);
			$prestamos_cliente = $this->prestamos->listar_x_id_cliente($id_cliente);
			$morosos_motivos = $this->clientes->motivos_morosos($id_cliente);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/historial_cliente.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function guardar_editar_clientes()
    {
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            $id = $_POST['id_cliente'];
            $cliente_dni = $_POST['cliente_dni'];
            if ($ok_data) {
                if ($id == null) {
                    $validar_dni = $this->clientes->validar_x_id($id, $cliente_dni);
                    if ($validar_dni) {
                        $result = 3;
                    } else {
                        $result = $this->builder->save("clientes", array(
                            "cliente_dni" => $cliente_dni,
                            "cliente_nombre" => $_POST['cliente_nombre'],
                            "cliente_apellido_paterno" => $_POST['cliente_apellido_paterno'],
                            "cliente_apellido_materno" => $_POST['cliente_apellido_materno'],
                            "cliente_fecha_nacimiento" => $_POST['cliente_fecha_nacimiento'] ?? null,
                            "cliente_direccion" => $_POST['cliente_direccion'],
                            "cliente_referencia" => $_POST['cliente_referencia'] ?? null,
                            "cliente_celular" => $_POST['cliente_celular'],
                            "cliente_correo" => $_POST['cliente_correo'] ?? null,
                            "cliente_nro_tarjeta" => $_POST['cliente_nro_tarjeta'] ?? null,
                            "cliente_clave" => $_POST['cliente_clave'] ?? null,
                            "cliente_lugar_trabajo" => $_POST['cliente_lugar_trabajo'] ?? null,
                            "cliente_otro" => $_POST['cliente_otro'] ?? null,
                            "cliente_estado" => 1,
                            "cliente_fecha" => date("Y-m-d H:i:s")
                        ));
                    }
                } else {
					$validar_dni = $this->clientes->validar_x_id($id,$cliente_dni);
                    if ($validar_dni) {
                        $result = 3;
                    } else {
						$datos_x_id = $this->clientes->listar_x_id($id);
						$this->builder->save("clientes_historial_cambios", array(
							"id_cliente" => $datos_x_id->id_cliente,
							"cliente_dni" => $datos_x_id->cliente_dni,
							"cliente_apellido_paterno" => $datos_x_id->cliente_apellido_paterno,
							"cliente_apellido_materno" => $datos_x_id->cliente_apellido_materno,
							"cliente_nombre" => $datos_x_id->cliente_nombre,
							"cliente_fecha_nacimiento" => $datos_x_id->cliente_fecha_nacimiento,
							"cliente_direccion" => $datos_x_id->cliente_direccion,
							"cliente_referencia" => $datos_x_id->cliente_referencia,
							"cliente_telefono" => $datos_x_id->cliente_telefono,
							"cliente_celular" => $datos_x_id->cliente_celular,
							"cliente_correo" => $datos_x_id->cliente_correo,
							"cliente_nro_tarjeta" => $datos_x_id->cliente_nro_tarjeta,
							"cliente_motivo_ad" => $datos_x_id->cliente_motivo_ad,
							"cliente_credito" => $datos_x_id->cliente_credito,
							"cliente_estado" => $datos_x_id->cliente_estado,
							"cliente_fecha" => date("Y-m-d")
						));
						
						
						
                        $result = $this->builder->update("clientes", array(
							"cliente_dni" => $cliente_dni,
							"cliente_nombre" => $_POST['cliente_nombre'],
							"cliente_apellido_paterno" => $_POST['cliente_apellido_paterno'],
							"cliente_apellido_materno" => $_POST['cliente_apellido_materno'],
							"cliente_fecha_nacimiento" => $_POST['cliente_fecha_nacimiento'] ?? null,
							"cliente_direccion" => $_POST['cliente_direccion'],
							"cliente_referencia" => $_POST['cliente_referencia'] ?? null,
							"cliente_celular" => $_POST['cliente_celular'],
							"cliente_correo" => $_POST['cliente_correo'] ?? null,
							"cliente_nro_tarjeta" => $_POST['cliente_nro_tarjeta'] ?? null,
							"cliente_clave" => $_POST['cliente_clave'] ?? null,
							"cliente_lugar_trabajo" => $_POST['cliente_lugar_trabajo'] ?? null,
							"cliente_otro" => $_POST['cliente_otro'] ?? null, 	
							"cliente_fecha" => date('Y-m-d'), 	
                        ), array("id_cliente" => $id));
                    }
                }
            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function actualizar_cliente_a_moroso()
    {
        $result = 2;
        $message = 'OK';
        try {
			$id_ = $_POST['id_cliente_moroso'];
			$comentario = $_POST['cliente_historial_moroso_comentario'];
			$guardarid = $_POST['guardarid'];
			$tipo = $_POST['tipo'];
			$estado = $this->clientes->listar_x_id($id_)->cliente_estado;
			if($id_){
				$e = 0;
				$result = $this->builder->update("clientes", array(
					"cliente_estado" => $e,
				), array(
					"id_cliente" =>  $id_
				));
			}else{
				$e = 1;
				$result = $this->builder->update("clientes", array(
					"cliente_estado" => $e,
				), array(
					"id_cliente" =>  $guardarid
				));
			}
			
			if($comentario){
				$this->builder->save('clientes_historial_moroso',array(
					'id_cliente' =>$id_,
					'cliente_historial_moroso_comentario' => $comentario,
					'cliente_historial_moroso_estado' => 1,
					'cliente_historial_moroso_fecha' => date('Y-m-d'),
					'cliente_historial_moroso_mt' => microtime(true),
				));
			}
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function guardar_cliente_garante()
    {
        $result = 2;
        $message = 'OK';
        try {
			if($_POST['id_cliente']!=$_POST['id_cliente_recomendado']){
				$buscar_existente = $this->clientes->listar_x_id_garante($_POST['id_cliente'],$_POST['id_cliente_recomendado']);
				if(!$buscar_existente){
					$result = $this->builder->save("clientes_garantes", array(
						"id_cliente" => $_POST['id_cliente'],
						"id_garante" => $_POST['id_cliente_recomendado'],
						"cliente_garante_estado" => 1,
						"cliente_garante_mt" => microtime(true),
					));
				}else{
					$result = 3;
				}
			}else{
				$result = 4;
			}
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function edicion_clientes(){
        $ok_data = true;
        $result = 2;
        $message = 'OK';
        try{
            if($ok_data){
                $id = $_POST['guardarid'];
                $result = $this->clientes->listar_x_id($id);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function buscar_cliente_garante(){
        $ok_data = true;
        $result = 2;
        $message = 'OK';
        try{
            $result = $this->clientes->listar_x_dni($_POST['btn_dni_garante_nuevo']);
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function eliminar_cliente(){
        try{
            $id = $_POST['id'];
			$result = $this->builder->update("clientes",array(
				'cliente_estado' => 0
			),array(
			'id_cliente' => $id
			));
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'._FUNCTION_);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
}