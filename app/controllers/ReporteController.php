<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Prestamos.php';
require 'app/models/Cobros.php';
require 'app/models/Reporte.php';
class ReporteController
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
    private $cobros;
    private $reporte;
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
        $this->cobros = new Cobros();
        $this->reporte = new Reporte();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'reporte/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function ver_reportes(){
        try{
            $this->nav = new Navbar();
            $tipo_de_reporte=$_POST['tipo'];
            if ($tipo_de_reporte=='diario'){
                $hoy= date("Y-m-d");
                $reporte=$this->reporte->reporte_hoy($hoy);
                if (!empty($_POST['Egresos'])){
                    $egresos=$this->reporte->reporte_hoy_egresos($hoy);
                }
            } elseif ($tipo_de_reporte=='mensual'){
                $mes= date("n");
                $reporte=$this->reporte->reporte_mes($mes);
                if (!empty($_POST['Egresos'])){
                    $egresos=$this->reporte->reporte_mes_egresos($mes);
                }

            } elseif ($tipo_de_reporte=='fechas'){
                $fecha_inicio=$_POST['fecha_prestamo'];
                $fecha_fin=$_POST['fecha_prox_cobro'];
                $reporte=$this->reporte->reporte_fechas($fecha_inicio,$fecha_fin);
                if (!empty($_POST['Egresos'])){
                    $egresos=$this->reporte->reporte_fechas_egresos($fecha_inicio,$fecha_fin);
                }
            }
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/ver_reportes.php';
            require _VIEW_PATH_ . 'footer.php';
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
}