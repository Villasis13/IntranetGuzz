<?php
require 'app/models/Reportes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
class ReportesController
{
    private $usuario;
    private $rol;
    private $archivo;
    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $reportes;
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
        $this->reportes = new Reportes();
        $this->builder = new Builder();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$ventas = $this->reportes->Ventas();
			$compras = $this->reportes->Compras();
			require _VIEW_PATH_ . 'header.php';
			require _VIEW_PATH_ . 'navbar.php';
			require _VIEW_PATH_ . 'reportes/inicio.php';
			require _VIEW_PATH_ . 'footer.php';

        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_compras(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$compras = $this->reportes->Compras();
			require _VIEW_PATH_ . 'header.php';
			require _VIEW_PATH_ . 'navbar.php';
			require _VIEW_PATH_ . 'reportes/historial_compras.php';
			require _VIEW_PATH_ . 'footer.php';

        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_ventas(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$ventas = $this->reportes->Ventas();
			require _VIEW_PATH_ . 'header.php';
			require _VIEW_PATH_ . 'navbar.php';
			require _VIEW_PATH_ . 'reportes/historial_ventas.php';
			require _VIEW_PATH_ . 'footer.php';

        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }



}