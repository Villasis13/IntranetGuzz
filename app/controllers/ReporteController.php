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
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            $tipo_de_reporte = $_POST['tipo'];
            $con_ingresos = !empty($_POST['Ingresos']);
            $con_egresos  = !empty($_POST['Egresos']);

            $reporte  = [];
            $egresos  = [];

            if ($tipo_de_reporte == 'diario'){
                // Toma la fecha seleccionada o la de hoy si por algo llega vacío
                $fecha_diaria = !empty($_POST['fecha_diaria']) ? $_POST['fecha_diaria'] : date("Y-m-d");

                if ($con_ingresos) $reporte = $this->reporte->reporte_hoy($fecha_diaria);
                if ($con_egresos)  $egresos = $this->reporte->reporte_hoy_egresos($fecha_diaria);

            } elseif ($tipo_de_reporte == 'mensual'){
                // Toma el mes seleccionado del dropdown
                $mes = !empty($_POST['mes_seleccionado']) ? $_POST['mes_seleccionado'] : date("n");

                if ($con_ingresos) $reporte = $this->reporte->reporte_mes($mes);
                if ($con_egresos)  $egresos = $this->reporte->reporte_mes_egresos($mes);

            } elseif ($tipo_de_reporte == 'fechas'){
                // Toma el rango de fechas
                $fecha_inicio = $_POST['fecha_desde'];
                $fecha_fin    = $_POST['fecha_hasta'];

                if ($con_ingresos) $reporte = $this->reporte->reporte_fechas($fecha_inicio, $fecha_fin);
                if ($con_egresos)  $egresos = $this->reporte->reporte_fechas_egresos($fecha_inicio, $fecha_fin);
            }

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'admin/ver_reportes.php';
            require _VIEW_PATH_ . 'footer.php';

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script>alert(\"Error al procesar el reporte.\"); window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
}