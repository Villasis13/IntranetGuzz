<?php
require 'app/models/Ventas.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Builder.php';
require 'app/models/Caja.php';
require 'app/models/Clientes.php';
class VentasController
{
    private $usuario;
    private $clientes;
    private $rol;
    private $archivo;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $ventas;
    private $builder;
    private $caja;
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
        $this->caja = new Caja();
        $this->clientes = new Clientes();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$listar_estado_caja = $this->caja->traer_estado_caja()->estado_caja;
			if($listar_estado_caja == 1){
				if($_POST['dni_post']){
					$data_cliente = $this->clientes->listar_x_dni($_POST['dni_post']);
				}
			}
			
			$ventas_pendiente_pago = $this->ventas->ventas_pendiente_pago();
			$ventas_realizadas = $this->ventas->ventas_realizadas();
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
    public function pagar_venta(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_venta = $_GET['id'];
			$monto_total = $this->ventas->listar_ventas_x_id($id_venta);
			$pagos_realizados = $this->ventas->listar_pagos_ventas($id_venta);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/pagar_venta.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function pagos_venta(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_venta = $_GET['id'];
			$pagos_realizados = $this->ventas->listar_pagos_ventas($id_venta)->total;
			$pagos_realizados_venta = $this->ventas->listar_datos_pagos_ventas($id_venta);
			
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'ventas/pagos_venta.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

	public function guardar_realizar_venta(){
		try{
			$ok_data = true;
			if($_POST['venta_pago'] <= $_POST['venta_precio']){
				if($ok_data){
					$fecha_venta = date('Y-m-d H:i:s');
					$mt_venta = microtime(true);
					if($_POST['venta_pago'] < $_POST['venta_precio']){
						//estado 2 porque falta pagar
						$estado_venta = 2;
					}else if($_POST['venta_pago'] == $_POST['venta_precio']){
						//estado 1 porque pago total
						$estado_venta = 1;
					}
					
					$result = $this->builder->save("ventas", array(
						'id_cliente' => $_POST['id_cliente'],
						'venta_producto' => $_POST['venta_producto'],
						'venta_precio' => $_POST['venta_precio'],
						'venta_pago' => $_POST['venta_pago'],
						'venta_fecha' => $fecha_venta,
						'venta_estado' => $estado_venta,
						'venta_mt' => $mt_venta,
					));
					
					if($result == 1){
						
						$data_venta = $this->ventas->listar_ventas_x_mt($mt_venta);
						
						$result = $this->builder->save("ventas_pagos",array(
							'id_venta' => $data_venta->id_venta,
							'venta_pago_monto' => $_POST['venta_pago'],
							'venta_pago_estado' => 1,
							'venta_pago_fecha' => $fecha_venta,
						));
						
						if($result == 1){
							$data_caja = $this->caja->traer_datos_caja();
							$monto_caja = $data_caja->monto_caja + $_POST['venta_pago'];
							$result = $this->builder->update("caja", array(
								'monto_caja' => $monto_caja
							),array(
								'id_caja' => $data_caja->id_caja
							));
						}
						
					}
				}
			}else{
				$result = 3;
			}
			
		}catch(Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
	public function guardar_pago_venta(){
		try{
			$ok_data = true;
			$data_venta = $this->ventas->listar_ventas_x_id($_POST['id_venta']);
			$pagos_realizados = $this->ventas->listar_pagos_ventas($_POST['id_venta'])->total;
//			if($_POST['venta_pago_monto'] <= ($data_venta->venta_precio - $pagos_realizados)){
				if($ok_data){
					if(($pagos_realizados + $_POST['venta_pago_monto']) == $data_venta->venta_precio){
						$result = $this->builder->update("ventas",array(
							'venta_estado' => 1
						),array(
							'id_venta' => $_POST['id_venta']
						));
					}
					
					$result = $this->builder->save("ventas_pagos", array(
						'id_venta' => $_POST['id_venta'],
						'venta_pago_monto' => $_POST['venta_pago_monto'],
						'venta_pago_estado' => 1,
						'venta_pago_fecha' => date('Y-m-d H:i:s'),
					));
					
					if($result == 1){
						$data_caja = $this->caja->traer_datos_caja();
						$monto_caja = $data_caja->monto_caja + $_POST['venta_pago_monto'];
						$result = $this->builder->update("caja", array(
							'monto_caja' => $monto_caja
						),array(
							'id_caja' => $data_caja->id_caja
						));
						
					}
				}
//			}else{
//				$result = 3;
//			}
			
		}catch(Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}

}