<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Prestamos.php';
require 'app/models/Cobros.php';
require 'app/models/Reporte.php';

require 'app/view/pdf/fpdf/fpdf.php';

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

    public function exportar_pdf_reporte() {
        try {
            date_default_timezone_set('America/Lima');

            $tipo_de_reporte = $_POST['tipo'];
            $con_ingresos    = !empty($_POST['Ingresos']);
            $con_egresos     = !empty($_POST['Egresos']);

            $reporte = [];
            $egresos = [];

            if ($tipo_de_reporte == 'diario') {
                $fecha_diaria  = !empty($_POST['fecha_diaria']) ? $_POST['fecha_diaria'] : date("Y-m-d");
                $label_periodo = 'Diario: ' . date('d/m/Y', strtotime($fecha_diaria));

                if ($con_ingresos) $reporte = $this->reporte->reporte_hoy($fecha_diaria);
                if ($con_egresos)  $egresos = $this->reporte->reporte_hoy_egresos($fecha_diaria);

            } elseif ($tipo_de_reporte == 'mensual') {
                $mes = !empty($_POST['mes_seleccionado']) ? $_POST['mes_seleccionado'] : date("n");
                $meses_es = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio',
                    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
                $label_periodo = 'Mensual: ' . $meses_es[(int)$mes] . ' ' . date('Y');

                if ($con_ingresos) $reporte = $this->reporte->reporte_mes($mes);
                if ($con_egresos)  $egresos = $this->reporte->reporte_mes_egresos($mes);

            } elseif ($tipo_de_reporte == 'fechas') {
                $fecha_inicio  = $_POST['fecha_desde'];
                $fecha_fin     = $_POST['fecha_hasta'];
                $label_periodo = 'Rango: ' . date('d/m/Y', strtotime($fecha_inicio))
                    . ' al '   . date('d/m/Y', strtotime($fecha_fin));

                if ($con_ingresos) $reporte = $this->reporte->reporte_fechas($fecha_inicio, $fecha_fin);
                if ($con_egresos)  $egresos = $this->reporte->reporte_fechas_egresos($fecha_inicio, $fecha_fin);
            }

            // Cálculos
            $total_ingresos = 0;
            foreach ((array)$reporte as $rp) $total_ingresos += $rp->pago_monto;

            $total_egresos = 0;
            foreach ((array)$egresos as $eg) $total_egresos += ($eg->prestamo_monto + $eg->prestamo_monto_interes);

            $balance        = $total_ingresos - $total_egresos;
            $usuario_actual = $_SESSION['n_usuario'] ?? 'Administrador';
            $texto_emision  = "Iquitos, " . date('d/m/Y') . " a las " . date('H:i:s');

            // ── FPDF ─────────────────────────────────────────────────────────────
            $pdf = new Fpdf();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetMargins(10, 10, 10);

            // Encabezado
            $pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png', 5, 5, 18);
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->Cell(190, 7, 'REPORTE DE INGRESOS Y EGRESOS', 0, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(190, 5, 'Inversiones y Multiservicios GUZZ E.I.R.L  -  RUC N 20600864255', 0, 1, 'C');
            $pdf->Cell(190, 5, 'Calle Jose Olaya #324 - Tupac Amaru - Iquitos', 0, 1, 'C');
            $pdf->Ln(3);

            // Info general
            $pdf->SetFillColor(232, 232, 232);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(95, 6, 'Usuario: ' . $usuario_actual, 1, 0, 'L', true);
            $pdf->Cell(95, 6, 'Periodo: ' . $label_periodo,  1, 1, 'L', true);
            $pdf->Cell(95, 6, 'Tipo de reporte: ' . ucfirst($tipo_de_reporte), 1, 0, 'L', true);
            $pdf->Cell(95, 6, 'Generado: ' . date('d/m/Y H:i:s'), 1, 1, 'L', true);
            $pdf->Ln(4);

            // ── SECCIÓN 1: Ingresos ───────────────────────────────────────────────
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, 'Ingresos - Pagos de Cuotas', 1, 1, 'L', true);

            // Encabezados de tabla de ingresos (Suman 190)
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(8,  5, '#', 1, 0, 'C', true);
            $pdf->Cell(17, 5, 'F. Cuota', 1, 0, 'C', true);
            $pdf->Cell(25, 5, 'F. Registro', 1, 0, 'C', true);
            $pdf->Cell(18, 5, 'Usuario', 1, 0, 'C', true);
            $pdf->Cell(42, 5, 'Cliente', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Método', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Cuota', 1, 0, 'C', true);
            $pdf->Cell(18, 5, 'Dscto.', 1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Monto Pagado', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 7);
            if (!empty($reporte)) {
                $c = 1;
                foreach ($reporte as $rp) {
                    // Si tienes problemas con cortes de tildes aquí, cambia 'substr' por 'mb_substr'
                    $nombre_cliente = substr($rp->cliente_nombre . ' ' . $rp->cliente_apellido_paterno, 0, 25);
                    $usuario = substr($rp->usuario_nickname, 0, 12);
                    $metodo = substr($rp->metodo_pago_nombre, 0, 12);

                    $pdf->Cell(8,  5, $c++, 1, 0, 'C');
                    $pdf->Cell(17, 5, date('d/m/Y', strtotime($rp->pago_diario_fecha)), 1, 0, 'C');
                    $pdf->Cell(25, 5, date('d/m/Y H:i', strtotime($rp->pago_fecha)), 1, 0, 'C');
                    $pdf->Cell(18, 5, $usuario, 1, 0, 'C');
                    $pdf->Cell(42, 5, $nombre_cliente, 1, 0, 'L');
                    $pdf->Cell(20, 5, $metodo, 1, 0, 'C');
                    $pdf->Cell(20, 5, 'S/ ' . number_format($rp->pago_diario_monto, 2), 1, 0, 'R');

                    if ($rp->pago_descuento_estado == 1) {
                        $pdf->Cell(18, 5, 'S/ ' . number_format($rp->pago_descuento_monto ?? 0, 2), 1, 0, 'R');
                    } else {
                        $pdf->Cell(18, 5, 'No aplica', 1, 0, 'C');
                    }

                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(22, 5, 'S/ ' . number_format($rp->pago_monto, 2), 1, 1, 'R');
                    $pdf->SetFont('Arial', '', 7);
                }
                // Fila de Total
                $pdf->SetFillColor(213, 232, 212);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(168, 5, 'Total Ingresos', 1, 0, 'R', true);
                $pdf->Cell(22,  5, 'S/ ' . number_format($total_ingresos, 2), 1, 1, 'R', true);
            } else {
                $pdf->Cell(190, 5, 'Ingresos no registrados en este periodo.', 1, 1, 'C');
            }
            $pdf->Ln(4);

            // ── SECCIÓN 2: Egresos ────────────────────────────────────────────────
            $pdf->SetFillColor(200, 200, 200);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, 'Egresos - Préstamos Otorgados', 1, 1, 'L', true);

            // Encabezados de tabla de egresos (Suman 190)
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(8,  5, '#', 1, 0, 'C', true);
            $pdf->Cell(18, 5, 'F. Emision', 1, 0, 'C', true);
            $pdf->Cell(18, 5, 'F. Inicio', 1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Periodicidad', 1, 0, 'C', true);
            $pdf->Cell(18, 5, 'Usuario', 1, 0, 'C', true);
            $pdf->Cell(44, 5, 'Cliente', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Capital', 1, 0, 'C', true);
            $pdf->Cell(20, 5, 'Interés', 1, 0, 'C', true);
            $pdf->Cell(22, 5, 'Total Deuda', 1, 1, 'C', true);

            $pdf->SetFont('Arial', '', 7);
            if (!empty($egresos)) {
                $c = 1;
                foreach ($egresos as $eg) {
                    $monto_total = $eg->prestamo_monto + $eg->prestamo_monto_interes;
                    $nombre_cliente = substr($eg->cliente_nombre . ' ' . $eg->cliente_apellido_paterno, 0, 25);
                    $usuario = substr($eg->usuario_nickname, 0, 12);
                    $tipo_pago = substr(ucfirst($eg->prestamo_tipo_pago), 0, 15);

                    $pdf->Cell(8,  5, $c++, 1, 0, 'C');
                    $pdf->Cell(18, 5, date('d/m/Y', strtotime($eg->prestamo_fecha)), 1, 0, 'C');
                    $pdf->Cell(18, 5, date('d/m/Y', strtotime($eg->prestamo_fecha_inicio)), 1, 0, 'C');
                    $pdf->Cell(22, 5, $tipo_pago, 1, 0, 'C');
                    $pdf->Cell(18, 5, $usuario, 1, 0, 'C');
                    $pdf->Cell(44, 5, $nombre_cliente, 1, 0, 'L');
                    $pdf->Cell(20, 5, 'S/ ' . number_format($eg->prestamo_monto, 2), 1, 0, 'R');
                    $pdf->Cell(20, 5, 'S/ ' . number_format($eg->prestamo_monto_interes, 2), 1, 0, 'R');

                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(22, 5, 'S/ ' . number_format($monto_total, 2), 1, 1, 'R');
                    $pdf->SetFont('Arial', '', 7);
                }
                // Fila de Total
                $pdf->SetFillColor(255, 199, 206);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(168, 5, 'Total Egresos', 1, 0, 'R', true);
                $pdf->Cell(22,  5, 'S/ ' . number_format($total_egresos, 2), 1, 1, 'R', true);
            } else {
                $pdf->Cell(190, 5, 'Egresos no registrados en este periodo.', 1, 1, 'C');
            }
            $pdf->Ln(5);

            // ── RESUMEN FINAL ─────────────────────────────────────────────────────
            $pdf->SetFillColor(232, 232, 232);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(150, 6, 'Total Ingresos del Periodo:', 1, 0, 'R', true);
            $pdf->Cell(40,  6, 'S/ ' . number_format($total_ingresos, 2), 1, 1, 'R', true);
            $pdf->Cell(150, 6, 'Total Egresos del Periodo:',  1, 0, 'R', true);
            $pdf->Cell(40,  6, 'S/ ' . number_format($total_egresos, 2),  1, 1, 'R', true);

            if ($balance >= 0) {
                $pdf->SetFillColor(197, 224, 180);
            } else {
                $pdf->SetFillColor(255, 199, 206);
            }
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(150, 8, 'BALANCE DEL PERIODO:', 1, 0, 'R', true);
            $pdf->Cell(40,  8, 'S/ ' . number_format($balance, 2), 1, 1, 'R', true);

            $pdf->Ln(4);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(190, 5, $texto_emision, 0, 1, 'R');

            $pdf->Output('I', 'Reporte_' . ucfirst($tipo_de_reporte) . '_' . date('Ymd_His') . '.pdf');

        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
        }
    }
}