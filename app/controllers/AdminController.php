<?php
require 'app/models/Caja.php';
class AdminController{
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $caja;
    public function __construct()
    {
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
		$this->validar = new Validar();
        $this->sesion = new Sesion();
        $this->caja = new Caja();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$num_clientes = $this->nav->num_clientes();

			$estado_caja = $this->caja->traer_estado_caja();

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

    public function finalizar_sesion(){
        $this->sesion->finalizar_sesion();
    }
}

