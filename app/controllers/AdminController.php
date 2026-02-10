<?php
require 'app/models/Caja.php';
require 'app/models/Prestamos.php';
require 'app/models/Cobros.php';
require 'app/models/Clientes.php';
class AdminController{
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $caja;
    private $prestamos;
    private $cobros;
    private $clientes;
    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
		$this->validar = new Validar();
        $this->sesion = new Sesion();
        $this->caja = new Caja();
        $this->caja = new Caja();
        $this->prestamos = new Prestamos();
        $this->cobros = new Cobros();
        $this->clientes = new Clientes();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$num_clientes = $this->nav->num_clientes();
			$estado_caja = $this->caja->traer_estado_caja();
			$prestamos_hoy = $this->prestamos->prestamos_hoy();
			$ingresos_hoy = $this->cobros->prestamos_hoy()->total;
			$egresos_hoy = $this->prestamos->egresos_hoy()->total;
			$actualizar_clientes = $this->clientes->listar_clientes_actualizar();
			$proximos_cobros = $this->cobros->listar_proximos_cobros();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function documentos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/documentos.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function reportes(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/reportes.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function finalizar_sesion(){
        $this->sesion->finalizar_sesion();
    }
}

