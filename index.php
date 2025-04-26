<?php
require 'core/globals.php';
session_start();
require 'app/models/Log.php';
$log = new Log();
$_SESSION['acceso'] = 1;
$correr = true;
if(
    isset($_GET['c']) &&
    isset($_GET['a']) &&
    isset($_GET['mod'])
    && $_GET['c'] == "Login"
    && $_GET['a'] == "inicio"
    && $_GET['mod'] == "superadmin"){
    $_SESSION['superadmin'] = true;
}
if(_MANTENIMIENTO_WEB == 1){
    $correr = $_SESSION['superadmin'] ?? false;
}
$function_action = "Desconocido";
$archivo = "";
$controlador = "";
$accion = "";
$validacion = array("estado" => 'error',"accion" => 'inicio',"mensaje" => 'Error General');
if($correr){
    try{
        require 'core/Database.php';
        require 'app/models/Validar.php';
        require 'app/models/Encriptar.php';
        require 'app/models/Menui.php';
        require 'app/models/Navbar.php';
        require 'app/models/Sesion.php';
        $menui = new Menui();
        $encriptar = new Encriptar();
        $sesion = new Sesion();
        header("Content-Type: text/html;charset=utf-8");
        set_error_handler("exception_error_handler");
        require 'core/session.php';
        if(isset($_GET['c'])){
            $controlador = $_GET['c'];
        } else {
            if(isset($_SESSION['ru'])){
                $controlador = "Admin";
            } else {
                $controlador = "Login";
            }
        }
        $controlador = trim(ucfirst($controlador));
        $_SESSION['web'] = true;
        $accion = $_GET['a'] ?? "inicio";
        $accion = trim(strtolower($accion));
        $function_action = $controlador . "|" . $accion;
        $archivo = 'app/controllers/' . $controlador . 'Controller.php';
        if(file_exists($archivo)){
            $autorizado = false;
            if(isset($_SESSION['ru'])){
                $autorizado = $menui->verificar_permiso_usuario($encriptar->desencriptar($_SESSION['ru'], _FULL_KEY_), $controlador, $accion);
                $permiso = $encriptar->desencriptar($_SESSION['s_'],_FULL_KEY_);
            } else {
                $autorizado = $menui->verificar_permiso_usuario(1, $controlador, $accion);
                $permiso = 1;
            }
            if($autorizado && $permiso == 1){
                $validacion['estado'] = 'ok';
                $validacion['accion'] = '';
                $validacion['mensaje'] = '';
            } else {
                if($permiso == 0){
                    $validacion['estado'] = 'error';
                    $validacion['accion'] = 'inicio';
                } else {
                    if(isset($_SESSION['ru'])){
                        $validacion['estado'] = 'error';
                        $validacion['accion'] = 'inicio';
                    } else {
                        $validacion['estado'] = 'login';
                        $validacion['accion'] = '';
                    }
                }
                $validacion['mensaje'] = 'SIN PERMISOS SUFICIENTES DE ACCESO';
            }
        } else {
            $validacion['estado'] = 'error';
            $validacion['accion'] = 'inicio';
            $validacion['mensaje'] = 'El archivo consultado ' . $archivo . ' no existe';
        }
    } catch (Exception $e){
        $validacion['estado'] = 'error';
        $validacion['accion'] = 'inicio';
        $validacion['mensaje'] = $e->getMessage();
    }
} else {
    session_destroy();
    $validacion['estado'] = 'error';
    $validacion['accion'] = 'mantenimiento';
    $validacion['mensaje'] = 'Sistema en Mantenimiento';
}
switch ($validacion['estado']){
    case 'ok':
        try{
            $_SESSION['accion'] = $accion;
            $_SESSION['controlador'] = $controlador;
            require $archivo;
            $clase = sprintf('%sController', $controlador);
            $controller = new $clase;
            $controller->$accion();
            unset($_SESSION['accion']);
            unset($_SESSION['controlador']);
            unset($_SESSION['icono']);
        } catch (Throwable $e){
            require 'app/controllers/ErrorController.php';
            $clase = sprintf('%sController', 'Error');
            $accion = 'inicio';
            $controller = new $clase;
            $controller->$accion();
            $log->insertar($e->getMessage(), $function_action);
        }
        break;
    case 'login':
        $sesion->cerrar_sesion();
        require 'app/controllers/LoginController.php';
        $clase = 'LoginController';
        $accion = 'inicio';
        $controller = new $clase;
        $controller->$accion();
        break;
    default:
        require 'app/controllers/ErrorController.php';
        $clase = 'ErrorController';
        $accion = $validacion['accion'];
        $controller = new $clase;
        $controller->$accion();
        echo "<script language=\"javascript\">console.log(\"". $validacion['mensaje']."\");</script>";
        if($validacion['accion'] != "mantenimiento"){
            $log->insertar($validacion['mensaje'], $function_action);
        }
        break;
}