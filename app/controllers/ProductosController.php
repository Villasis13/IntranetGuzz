<?php
require 'app/models/Productos.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
class ProductosController
{
    private $usuario;
    private $rol;
    private $archivo;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $productos;
    private $builder;
    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->rol = new Rol();
        $this->archivo = new Archivo();
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->productos = new Productos();
        $this->builder = new Builder();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$productos = $this->productos->todos_productos();
			$medidas = $this->productos->todas_medida();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'productos/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function formato(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$productos = $this->productos->todos_productos();
            $proveedores = $this->productos->llamar_proveedores();
			$traer_medidas = $this->productos->todas_medida();
            $medidas= json_encode($traer_medidas);
            $fecha_actual = date('d/m/y');
            $hora = date('H:i:s');
            $numero_formato = $this->productos->traer_ultimo_formato()->id_formato;

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'productos/formato.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
	public function guardar_editar_productos(){
		$result = 2;
		$message = 'OK';
		try{
			$ok_data = true;
//			$ok_data = $this->validar->validar_parametro('producto_stock', 'POST',true,$ok_data,100,'numero',0);
			if($ok_data){
				$id = $_POST['id_producto'];
				if($id == null){
					$validar_nombre = $this->productos->validar_x_nombre($_POST['producto_nombre']);
					if($validar_nombre){
						$result = 3;
					}else{
						$result = $this->builder->save("productos", array(
							"id_medida" => $_POST['id_medida'],
							"producto_nombre" => $_POST['producto_nombre'],
							"producto_stock" => $_POST['producto_stock'],
							"producto_precio" => $_POST['producto_precio'],
                            "fecha_creacion" => date("Y-m-d H:i:s")
						));
					}
				}else{
					$id_nombre = $this->productos->validar_x_id_nombre($_POST['id_producto'],$_POST['producto_nombre']);
					$validar_nombre = $this->productos->validar_x_nombre($_POST['producto_nombre']);
					if($id_nombre->id_producto != $validar_nombre->id_producto){
						$result = 3;
					}else{
						$id = $_POST['id_producto'];
						$result = $this->builder->update("productos", array(
							"id_medida" => $_POST['id_medida'],
							"producto_nombre" => $_POST['producto_nombre'],
							"producto_stock" => $_POST['producto_stock'],
							"producto_precio" => $_POST['producto_precio']
						), array(
							"id_producto" => $id
						));
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
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
	public function edicion_productos(){
		$ok_data = true;
		$result = 2;
		$message = 'OK';
		try{
			if($ok_data){
				$id = $_POST['guardarid'];
				$result = $this->productos->listar_x_id($id);
			} else {
				$result = 6;
			}
		} catch (Exception $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
	public function eliminar_producto(){
		try{
			$id = $_POST['id'];
			$result=$this->builder->delete("productos", array(
				"id_producto" => $id
			));
		}catch (Exception $e){
			//Registramos el error generado y devolvemos el mensaje enviado por PHP
			$this->log->insertar($e->getMessage(), get_class($this).'|'._FUNCTION_);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
    public function listar_productos_input(){
        try{
            $valor =  $_POST['valor'];
            $result = $this->productos->listar_productos_input($valor);
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
//		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
        echo json_encode($result);

    }
	public function guardar_formato_ingreso(){
		try{
			$ok_data = true;
			$array_productos = json_decode($_POST['array_productos']);
			if($ok_data){
				if(count($array_productos)>0){
					$result = $this->builder->save("formato_ingreso", array(
						'fecha_ingreso' => $_POST['fecha_ingreso'],
						'hora_ingreso' => $_POST['hora_ingreso'],
						'id_proveedor' => $_POST['proveedor'],
					));
				}else{
					$result = 3;
				}
				$id_formato = $this->productos->ultimo_id_fi()->id_formato;
				foreach ($array_productos as $c) {
					// Obtener la medida asociada al producto en la base de datos
					$medida_producto = $this->productos->traer_id_medida($c->id);

					if ($medida_producto && $medida_producto->id_medida != $c->medida) {
						// La medida seleccionada no coincide con la medida del producto en la base de datos
						$medida_errors[] = "La medida para el producto '{$c->nombre}' no es correcta.";
					} else {
						$result = $this->builder->save("detalle_formato_ingreso", array(
							'id_formato' => $id_formato,
							'id_producto' => $c->id,
							'cantidad' => $c->cantidad,
							'id_medida' => $c->medida,
						));
					}
				}
				if (empty($medida_errors)) {
					$result = 1;
				} else {
					$result = 4;
				}
				if($result == 4){
					$id_formato = $this->productos->ultimo_id_fi()->id_formato;
					$this->productos->eliminar_detalle_formato($id_formato);
					$this->productos->eliminar_formato_ingreso($id_formato);
				}
			}
		}catch(Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}

		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
}