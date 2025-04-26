<?php
require 'app/models/Ventas.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Builder.php';
class VentasController
{
    private $usuario;
    private $rol;
    private $archivo;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $ventas;
    private $builder;
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
        $this->ventas = new Ventas();
        $this->builder = new Builder();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$tipo_documento = $this->ventas->tipo_documento();
			$modo_pago = $this->ventas->tipo_pago();
			$clientes = $this->ventas->clientes();
//            $documento=$this->ventas->ultimo_documento();
//            $id_documento=$documento->id_documento;
            $ultimo_id_venta=$this->ventas->ultimo_id_venta();

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function listar_productos_comprar(){
        try{
            $valor =  $_POST['valor'];
            $result = $this->ventas->listar_productos_comprar($valor);
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
//		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
        echo json_encode($result);

    }

    public function ultima_serie(){
        try{
            $tipo_documento =  $_POST['tipo_documento'];
            $result = $this->ventas->ultima_serie($tipo_documento);
        }catch (Exception $e){
            //Registramos el error generado y devolvemos el mensaje enviado por PHP
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
//		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
        echo json_encode(['valor_obtenido' => $result]);

    }

	public function guardar_realizar_venta(){
		try{
			$ok_data = true;
			$array_productos = json_decode($_POST['array_productos']);
			$id_tipo_pago = $_POST['modo_pago'];

			if($ok_data){
				$venta_fecha = date('Y-m-d H:i:s');
				if(count($array_productos)>0){

//                    $documento=$this->ventas->ultimo_documento();
//                    $documento_serie=$documento->documento_serie;
                    /*GUARDAR DOCUMENTO VENTA*/
                    $serie = $_POST['documento'];
                    $result = $this->builder->save("documento", array(
                        'id_tipo_documento' => $_POST['id_tipo_documento'],
                        'documento_serie' => $serie,
                    ));

                    /*JALAR ID DEL DOCUMENTO QUE SE ACABA DE CREAR*/
                    $documento=$this->ventas->ultimo_documento();
                    $id_documento=$documento->id_documento;

                    /*GUARDAR VENTA EN TABLA VENTA ASIGNANDO EL DOCUMENTO CREADO*/
                    $total_venta=0;
                    foreach ($array_productos as $p){$total_venta += $p->subtotal;}
					$result = $this->builder->save("ventas", array(
						'id_cliente' => $_POST['id_cliente'],
						'id_documento' => $id_documento,
						'id_tipo_pago' => $id_tipo_pago,
						'venta_fecha' => $venta_fecha,
						'venta_monto' =>$total_venta,
					));

                    /*JALAR ID DE LA VENTA QUE SE ACABA DE CREAR*/
                    $venta=$this->ventas->ultimo_id_venta();
                    $id_venta=$venta->id_venta;

                    /*GUARDAR DETALLE VENTA*/
                    $array_productos = json_decode($_POST['array_productos']);
                    foreach ($array_productos as $c) {
                        $result = $this->builder->save("detalle_venta", array(
                            'id_venta' => $id_venta,
                            'id_producto' => $c->id,
                            'detalle_venta_cantidad' => $c->vender_cantidad,
                            'detalle_venta_subtotal' => $c->subtotal,
                        ));
                    }

                    /*RESTAR STOCK DE PRODUCTO*/
                    $array_productos = json_decode($_POST['array_productos']);
                    foreach ($array_productos as $c) {

                        $result = $this->builder->update("productos", array(
                            'id_producto' => $c->id,
                            'producto_stock'=>$c->stock-$c->vender_cantidad,
                        ), array(
                            "id_producto" => $c->id,
                        ));
                    }

				}else{
					$result = 3;
				}
/*				foreach ($array_productos as $c) {
					$id_venta = $this->ventas->ultimo_id_venta()->id_venta;
					$result = $this->builder->save("detalle_formato_ingreso", array(
						'$id_venta' => $id_venta,
						'id_producto' => $c->id,
						'detalle_venta_cantidad' => 1,
						'detalle_venta_subtotal' => 1,
					));
				}*/
			}
		}catch(Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}

}