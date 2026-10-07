<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Caja.php';
require 'app/models/Archivo.php';
require 'app/models/Cobros.php';
require 'app/models/Prestamos.php';

//use Codedge\Fpdf\Fpdf\Fpdf;
require 'app/view/pdf/fpdf/fpdf.php';
class PrestamosController
{
    private $usuario;
    private $rol;
    private $archivo;

    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $caja;
    private $log;
    private $pdo;
    private $validar;
    private $clientes;
    private $builder;
    private $prestamos;
    private $cobros;
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
        $this->caja = new Caja();
        $this->validar = new Validar();
        $this->clientes = new Clientes();
        $this->builder = new Builder();
        $this->prestamos = new Prestamos();
        $this->cobros = new Cobros();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$listar_estado_caja = $this->caja->listar_ultima_caja()->estado_caja;
			if($listar_estado_caja == 1){
				if($_POST['dni_post']){
					$data_cliente = $this->clientes->listar_x_dni($_POST['dni_post']);
				}
			}
			$clientes_g = $this->clientes->todos_clientes();
			
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'prestamos/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function prestamos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            // Aplica el interés de los préstamos cuyo plazo venció con saldo (una vez por periodo)
            $this->cobros->aplicar_intereses_vencidos();
			$prestamos_general = $this->prestamos->listar_prestamos();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'prestamos/prestamos.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function prestamos_antiguos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			// En recuperación (estado 3) y recuperación cancelada (estado 4), con totales y acuerdo vigente
			$prestamos_antiguos = $this->prestamos->listar_prestamos_recuperacion(3);
			$prestamos_antiguos_cancelados = $this->prestamos->listar_prestamos_recuperacion(4);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'prestamos/prestamos_antiguos.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    // Ficha de un préstamo en recuperación: deuda, acuerdos, abonos y saldo
    public function recuperacion(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            $id_prestamo = (int)($_GET['id'] ?? 0);
            $prestamo = $this->prestamos->listar_x_id($id_prestamo);
            if (!$prestamo || !in_array(intval($prestamo->prestamo_estado), [3, 4])) {
                echo "<script language=\"javascript\">alert(\"Este préstamo no está en recuperación.\");</script>";
                echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."Prestamos/prestamos_antiguos\";</script>";
                return;
            }
            $cliente = $this->clientes->listar_x_id($prestamo->id_cliente);
            $metodos_pago = $this->cobros->listar_metodos_de_pago();
            $bancos = $this->cobros->listar_bancos();
            $cuentas_receptoras = $this->cobros->listar_cuentas_receptoras();
            $frecuencias = array_keys(Prestamos::FRECUENCIAS_ACUERDO);
            $ultima_caja = $this->caja->listar_ultima_caja();
            $caja_abierta = $ultima_caja && intval($ultima_caja->estado_caja) === 1;

            $linea = $this->prestamos->linea_tiempo_recuperacion($prestamo);
            $acuerdos            = $linea['acuerdos'];
            $acuerdo_vigente     = $linea['acuerdo_vigente'];
            $eventos             = $linea['eventos'];
            $inicio_recuperacion = $linea['inicio'];
            $total_pagado        = $linea['total_pagado'];
            $total_abonos        = $linea['total_abonos'];

            $deuda_original = round(floatval($prestamo->prestamo_monto) + floatval($prestamo->prestamo_monto_interes), 2);
            $ajuste_acuerdos = 0;
            foreach ($acuerdos as $a) {
                $ajuste_acuerdos += floatval($a->prestamo_acuerdo_monto) - floatval($a->prestamo_acuerdo_deuda_anterior);
            }
            $saldo_actual = round(floatval($prestamo->prestamo_saldo_pagar), 2);
            $movimientos_prestamo = $this->cobros->listar_movimientos_x_prestamo($id_prestamo);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'prestamos/recuperacion.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    // Códigos: 1 ok, 2 error, 4 el préstamo no es activo o no tiene saldo
    public function pasar_a_recuperacion(){
        $result = 2;
        $message = 'OK';
        try{
            $result = $this->prestamos->pasar_a_recuperacion((int)($_POST['id_prestamo'] ?? 0));
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    // Códigos: 1 ok, 2 error, 4 no está en recuperación, 6 datos inválidos
    public function guardar_acuerdo_recuperacion(){
        $result = 2;
        $message = 'OK';
        try{
            $monto      = $_POST['acuerdo_monto'] ?? '';
            $abono      = $_POST['acuerdo_abono_sugerido'] ?? '';
            $frecuencia = $_POST['acuerdo_frecuencia'] ?? '';
            $fecha      = $_POST['acuerdo_proxima_fecha'] ?? '';
            $fecha_ok   = DateTime::createFromFormat('Y-m-d', $fecha);
            $fecha_ok   = ($fecha_ok && $fecha_ok->format('Y-m-d') === $fecha) ? $fecha : null;
            $observacion = trim($_POST['acuerdo_observacion'] ?? '');

            $ok_data = is_numeric($monto) && round((float)$monto, 2) > 0
                && ($abono === '' || (is_numeric($abono) && (float)$abono > 0 && (float)$abono <= (float)$monto))
                && array_key_exists($frecuencia, Prestamos::FRECUENCIAS_ACUERDO)
                && ($fecha === '' || $fecha_ok)
                && ($frecuencia === 'Libre' || $fecha_ok);

            if ($ok_data) {
                $result = $this->prestamos->guardar_acuerdo_recuperacion((int)($_POST['id_prestamo'] ?? 0), array(
                    'monto'          => round((float)$monto, 2),
                    'abono_sugerido' => $abono === '' ? null : round((float)$abono, 2),
                    'frecuencia'     => $frecuencia,
                    'proxima_fecha'  => $fecha_ok,
                    'observacion'    => $observacion !== '' ? mb_substr($observacion, 0, 500) : null,
                ), $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_));
            } else {
                $result = 6;
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    // Códigos: 1 ok, 2 error, 3 caja cerrada, 4 no está en recuperación, 5 supera el saldo, 6 datos inválidos
    public function guardar_abono_recuperacion(){
        $result = 2;
        $message = 'OK';
        $id_pago = 0;
        try{
            $monto  = $_POST['abono_monto'] ?? '';
            $metodo = (int)($_POST['abono_metodo'] ?? 0);
            $metodo_valido = false;
            foreach ($this->cobros->listar_metodos_de_pago() as $m) {
                if (intval($m->id_metodo_pago) === $metodo) $metodo_valido = true;
            }
            if (is_numeric($monto) && round((float)$monto, 2) > 0 && $metodo_valido) {
                $texto = function ($campo, $largo) {
                    $v = trim($_POST[$campo] ?? '');
                    return $v !== '' ? mb_substr($v, 0, $largo) : null;
                };
                list($result, $id_pago) = $this->prestamos->guardar_abono_recuperacion((int)($_POST['id_prestamo'] ?? 0), array(
                    'monto'            => round((float)$monto, 2),
                    'metodo'           => $metodo,
                    'operacion'        => $texto('abono_operacion', 500),
                    'cuenta_receptora' => $texto('abono_cuenta_receptora', 120),
                    'banco'            => !empty($_POST['abono_banco']) ? (int)$_POST['abono_banco'] : null,
                    'observacion'      => $texto('abono_observacion', 500),
                ), $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_));
            } else {
                $result = 6;
            }
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "id_pago" => $id_pago)));
    }

    public function detalles(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_prestamos = $_GET['id'];
			$data_prestamo = $this->prestamos->listar_x_id($id_prestamos);
			$data_cliente  = $this->clientes->listar_x_id($data_prestamo->id_cliente);

			// Datos del cronograma para la sección "Fechas del crédito"
			$info_credito = $this->cobros->resumen_credito($id_prestamos);

            // Reconstruct amortization history retroactively (no schema change required)
            $tasa        = floatval($data_prestamo->prestamo_interes ?? 0);
            $todos_pagos = (array)$this->cobros->listar_pagos_x_prestamo($id_prestamos);

            $running_saldo        = floatval($data_prestamo->prestamo_saldo_pagar ?? 0);
            $amortizaciones_detalle = [];

            foreach (array_reverse($todos_pagos) as $pago) {
                if (empty($pago->id_pago_diario)) {
                    $saldo_total_despues = $running_saldo;
                    $capital_despues     = $tasa > 0 ? round($saldo_total_despues / (1 + $tasa / 100), 2) : $saldo_total_despues;
                    $capital_antes       = round($capital_despues + floatval($pago->pago_monto), 2);
                    $saldo_total_antes   = $tasa > 0 ? round($capital_antes * (1 + $tasa / 100), 2) : $capital_antes;

                    $amortizaciones_detalle[] = (object)[
                        'pago_fecha'          => $pago->pago_fecha,
                        'pago_usuario'        => $pago->pago_usuario ?? ($pago->pago_recepcion ?? '—'),
                        'capital_antes'       => $capital_antes,
                        'monto_amortizacion'  => floatval($pago->pago_monto),
                        'capital_despues'     => $capital_despues,
                        'interes'             => $tasa,
                        'saldo_total_antes'   => $saldo_total_antes,
                        'saldo_total_despues' => $saldo_total_despues,
                        'id_pago'             => $pago->id_pago,
                    ];

                    $running_saldo = $saldo_total_antes;
                } else {
                    $cuota_original = floatval($pago->cuota_original ?? $pago->pago_monto);
                    $running_saldo += $cuota_original;
                }
            }

            $amortizaciones_detalle = array_reverse($amortizaciones_detalle);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'prestamos/detalle.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function aumentarLineaCredito(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

			$id_cliente = $_GET['id'];
			$data_cliente = $this->clientes->listar_x_id($id_cliente);

			// Trazabilidad: ajustes manuales y restauraciones automáticas de la línea
			$historial_linea_credito = $this->clientes->listar_historial_linea_credito($id_cliente);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'prestamos/aumentar_linea_credito.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
	public function guardar_nueva_linea_credito()
	{
		$result = 2;
		$message = 'OK';
		try {
			if ($_POST['clave_validacion'] == 'joseolaya324') {
				$monto = floatval($_POST['incremento']);
				$tipo  = $_POST['tipo_ajuste'] ?? 'incremento';

				if ($monto <= 0) {
					$result = 4;
				} else {
					$monto_anterior = floatval($this->clientes->listar_x_id($_POST['id_cliente'])->cliente_credito);

					if ($tipo === 'incremento') {
						$monto_nuevo = $monto_anterior + $monto;
					} elseif ($tipo === 'disminucion') {
						$monto_nuevo = $monto_anterior - $monto;
						if ($monto_nuevo < 0) {
							$result = 5;
							echo json_encode(array("result" => array("code" => $result, "message" => $message)));
							return;
						}
					} else {
						$monto_nuevo = $monto;
					}

					$usuario = $this->encriptar->desencriptar($_SESSION['c_u'], _FULL_KEY_);

					$result = $this->builder->save("clientes_linea_credito", array(
						'id_cliente'                     => $_POST['id_cliente'],
						'cliente_linea_monto'            => $monto,
						'cliente_linea_motivo'           => $_POST['motivo_aumento'],
						'cliente_linea_tipo'             => $tipo,
						'cliente_linea_credito_anterior' => $monto_anterior,
						'cliente_linea_credito_nuevo'    => $monto_nuevo,
						'cliente_linea_usuario'          => $usuario,
						'cliente_linea_fecha'            => date('Y-m-d H:i:s'),
						'cliente_linea_estado'           => 1
					));

					if ($result == 1) {
						$result = $this->builder->update("clientes", array(
							'cliente_credito' => $monto_nuevo,
						), array(
							'id_cliente' => $_POST['id_cliente'],
						));
					}
				}
			} else {
				$result = 3;
			}
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
    /**
     * Número de cuotas permitido según la modalidad de pago.
     *
     * Semanal = 4 y Mensual = 1 son fijos: la pantalla los bloquea y aquí se vuelven
     * a forzar, para que un POST manipulado no pueda crear un préstamo con otro valor.
     * Diario acepta los días que indique el usuario.
     *
     * Debe coincidir con CUOTAS_POR_MODALIDAD en js/prestamos.js.
     */
    private function cuotas_segun_modalidad($tipo_pago, $cuotas_solicitadas)
    {
        $fijas = array(
            'semanal' => 4,
            'mensual' => 1
        );

        $tipo = strtolower(trim((string)$tipo_pago));
        if (isset($fijas[$tipo])) {
            return $fijas[$tipo];
        }

        $cuotas = (int)$cuotas_solicitadas;
        return $cuotas > 0 ? $cuotas : 1;
    }

    // Arma y valida los datos de la garantía enviados desde Prestamos/inicio.
    // Devuelve null si falta un dato obligatorio o los valores no son coherentes.
    // prestamo_garantia guarda un resumen legible para las vistas que solo leen ese campo.
    private function datos_garantia()
    {
        $tipo        = trim($_POST['garantia_tipo'] ?? '');
        $nombre      = trim($_POST['garantia_nombre'] ?? '');
        $descripcion = trim($_POST['garantia_descripcion'] ?? '');
        $estado      = trim($_POST['garantia_estado'] ?? '');
        $valor_real  = $_POST['garantia_valor_real'] ?? '';
        $valor_asig  = $_POST['garantia_valor_asignado'] ?? '';

        if ($tipo === '' || $nombre === '' || $estado === '' || !is_numeric($valor_real) || !is_numeric($valor_asig)) {
            return null;
        }
        $valor_real = round((float)$valor_real, 2);
        $valor_asig = round((float)$valor_asig, 2);
        if ($valor_real <= 0 || $valor_asig <= 0 || $valor_asig > $valor_real) {
            return null;
        }

        $placa = $chasis = $anho = $color = null;
        $resumen = $tipo . ': ' . $nombre;
        if ($tipo === 'Vehículo') {
            $placa  = strtoupper(trim($_POST['garantia_placa'] ?? ''));
            $chasis = strtoupper(trim($_POST['garantia_chasis'] ?? ''));
            $anho   = (int)($_POST['garantia_anho'] ?? 0);
            $color  = trim($_POST['garantia_color'] ?? '');
            if ($placa === '' || $chasis === '' || $color === '' || $anho < 1950 || $anho > (int)date('Y') + 1) {
                return null;
            }
            $resumen .= ' | Placa: ' . $placa . ' | Chasis: ' . $chasis . ' | Año: ' . $anho . ' | Color: ' . $color;
        }
        $resumen .= ' | Estado: ' . $estado;
        if ($descripcion !== '') {
            $resumen .= ' | ' . $descripcion;
        }

        return array(
            'prestamo_garantia'                => mb_substr($resumen, 0, 1000),
            'prestamo_garantia_tipo'           => $tipo,
            'prestamo_garantia_nombre'         => $nombre,
            'prestamo_garantia_descripcion'    => $descripcion !== '' ? $descripcion : null,
            'prestamo_garantia_estado'         => $estado,
            'prestamo_garantia_valor_real'     => $valor_real,
            'prestamo_garantia_valor_asignado' => $valor_asig,
            'prestamo_garantia_placa'          => $placa,
            'prestamo_garantia_chasis'         => $chasis,
            'prestamo_garantia_anho'           => $anho,
            'prestamo_garantia_color'          => $color,
        );
    }

    // Detalle de la garantía como pares etiqueta => valor, para imprimirla en los documentos.
    // Préstamos anteriores al registro estructurado solo tienen el texto libre de prestamo_garantia.
    private function detalle_garantia($prestamo)
    {
        if (empty($prestamo->prestamo_garantia_nombre)) {
            return trim((string)$prestamo->prestamo_garantia) !== ''
                ? array('Garantía' => $prestamo->prestamo_garantia)
                : array();
        }

        $detalle = array(
            'Tipo'  => $prestamo->prestamo_garantia_tipo,
            'Bien'  => $prestamo->prestamo_garantia_nombre,
        );
        if ($prestamo->prestamo_garantia_tipo === 'Vehículo') {
            $detalle['Placa']  = $prestamo->prestamo_garantia_placa;
            $detalle['Chasis'] = $prestamo->prestamo_garantia_chasis;
            $detalle['Año']    = $prestamo->prestamo_garantia_anho;
            $detalle['Color']  = $prestamo->prestamo_garantia_color;
        }
        $detalle['Estado'] = $prestamo->prestamo_garantia_estado;
        if (!empty($prestamo->prestamo_garantia_descripcion)) {
            $detalle['Descripción'] = $prestamo->prestamo_garantia_descripcion;
        }
        $detalle['Valor Real']     = 'S/. ' . number_format($prestamo->prestamo_garantia_valor_real, 2);
        $detalle['Valor Asignado'] = 'S/. ' . number_format($prestamo->prestamo_garantia_valor_asignado, 2);

        return $detalle;
    }

    public function guardar_prestamo()
    {
        $result = 2;
        $message = 'OK';
        $id_generado = 0;
        $usuario= $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);
        try {
            // ==========================================
            // CUOTAS BLOQUEADAS SEGÚN MODALIDAD
            // Semanal y Mensual tienen un número de cuotas fijo, sin importar lo que
            // llegue del formulario. Diario sigue siendo flexible. Se normaliza aquí
            // para que tanto el préstamo como su cronograma usen el mismo valor.
            // ==========================================
            $_POST['prestamo_num_cuotas'] = $this->cuotas_segun_modalidad(
                $_POST['prestamo_tipo_pago'] ?? '',
                $_POST['prestamo_num_cuotas'] ?? 1
            );

            // Aseguramos que ambos valores sean enteros para una comparación exacta
            $id_cliente = !empty($_POST['id_cliente']) ? (int)$_POST['id_cliente'] : 0;
            $garante = !empty($_POST['prestamo_garante']) ? (int)$_POST['prestamo_garante'] : 0;

            $garantia = $this->datos_garantia();

            if ($garantia === null) {
                $result = 6;
            // NUEVA VALIDACIÓN: Si el garante seleccionado es el mismo cliente
            } elseif ($garante > 0 && $garante === $id_cliente) {
                $result = 5;
            } else {
                // Si pasa la validación, continuamos con el flujo normal
                $validar_duplicidad=$this->prestamos->duplicidad_garante($garante);
                $ultima_caja = $this->caja->listar_ultima_caja();
                $fecha= $_POST['prestamo_fecha']. ' ' . date('H:i:s');
                $forzar = isset($_POST['forzar_garante']) && $_POST['forzar_garante'] == '1';

                if ($validar_duplicidad && !$forzar){
                    $result=4;
                } else {
                    $monto_caja_abierta = $this->caja->traer_datos_caja();

                    if(($monto_caja_abierta->monto_caja - $_POST['prestamo_monto'])>=0){
                        $mt = microtime(true);

                        // ==========================================
                        // 1. GUARDAR EL PRÉSTAMO
                        // ==========================================
                        $result_prestamo = $this->builder->save("prestamos", array_merge(array(
                            'id_cliente'             => $_POST['id_cliente'],
                            'id_usuario'             => $usuario,
                            'prestamo_monto'         => $_POST['prestamo_monto'],
                            'prestamo_interes'       => $_POST['prestamo_interes'],
                            'prestamo_tipo_pago'     => $_POST['prestamo_tipo_pago'],
                            'prestamo_num_cuotas'    => $_POST['prestamo_num_cuotas'],
                            'prestamo_fecha_inicio'  => $_POST['prestamo_fecha_inicio'],

                            // ==========================================
                            // NUEVAS FECHAS
                            // ==========================================
                            'prestamo_fecha_emision' => $fecha, // <-- Cambiado: Fecha "legal" o elegida en el formulario
                            'prestamo_fecha_sistema' => date('Y-m-d H:i:s'), // <-- Se mantiene: Fecha intocable para la caja

                            'prestamo_prox_cobro'    => $_POST['prestamo_prox_cobro'],
                            'prestamo_monto_interes' => ceil($_POST['prestamo_monto'] * $_POST['prestamo_interes'] / 100),
                            'prestamo_saldo_pagar'   => $_POST['prestamo_monto'] + ($_POST['prestamo_monto'] * $_POST['prestamo_interes'] / 100),
                            // Saldos por concepto: lo pendiente de capital y de interés
                            'prestamo_capital_pendiente' => round((float)$_POST['prestamo_monto'], 2),
                            'prestamo_interes_pendiente' => round((float)$_POST['prestamo_monto'] * (float)$_POST['prestamo_interes'] / 100, 2),
                            'prestamo_garante'       => $_POST['prestamo_garante'],
                            'prestamo_motivo'        => $_POST['prestamo_motivo'],
                            'prestamo_comentario'    => $_POST['prestamo_comentario'],
                            'prestamo_domingo'       => $_POST['select_domingos'],
                            'prestamo_mt'            => $mt,
                            'prestamo_estado'        => 1
                        ), $garantia));

                        if($result_prestamo == 1){
                            $id_prestamo_obj = $this->prestamos->listar_x_mt($mt);

                            if($id_prestamo_obj){
                                $id_generado = $id_prestamo_obj->id_prestamos;

                                // Primer movimiento del historial: el préstamo otorgado (capital + interés pactado)
                                $this->cobros->registrar_movimiento($id_generado, 'desembolso', 0, 0,
                                    'Préstamo otorgado: capital S/ ' . number_format((float)$_POST['prestamo_monto'], 2)
                                    . ' + interés ' . floatval($_POST['prestamo_interes']) . '%', null, $usuario);

                                // ==========================================
                                // 2. GUARDAR LAS CUOTAS
                                // ==========================================
                                $num_cuotas = (int)$_POST['prestamo_num_cuotas'];
                                $tipo_pago = strtolower($_POST['prestamo_tipo_pago']);
                                $incluir_domingos = strtolower($_POST['select_domingos']);

                                // Total = capital + interés (sin ceil sobre el interés para coincidir con el JS)
                                $monto_total_deuda = (float)$_POST['prestamo_monto'] + ((float)$_POST['prestamo_monto'] * (float)$_POST['prestamo_interes'] / 100);
                                // Cuota base: redondeo hacia arriba al primer decimal (igual que Math.ceil(x*10)/10 en JS)
                                $monto_cuota = ceil($monto_total_deuda / $num_cuotas * 10) / 10;

                                // Calcular primer cobro desde la fecha de emisión para garantizar consistencia
                                $fecha_emision_base = new DateTime($_POST['prestamo_fecha']);
                                if ($tipo_pago == 'semanal') {
                                    $fecha_iterador = clone $fecha_emision_base;
                                    $fecha_iterador->add(new DateInterval('P7D'));
                                    if ($fecha_iterador->format('w') == 0) {
                                        $fecha_iterador->add(new DateInterval('P1D'));
                                    }
                                } elseif ($tipo_pago == 'mensual') {
                                    $fecha_iterador = clone $fecha_emision_base;
                                    $fecha_iterador->add(new DateInterval('P1M'));
                                    if ($fecha_iterador->format('w') == 0) {
                                        $fecha_iterador->add(new DateInterval('P1D'));
                                    }
                                } else {
                                    $fecha_iterador = new DateTime($_POST['prestamo_prox_cobro']);
                                }

                                $suma_cuotas_acumuladas = 0;

                                for ($i = 1; $i <= $num_cuotas; $i++) {
                                    if ($i == $num_cuotas) {
                                        // Última cuota absorbe la diferencia; round a 2 decimales para evitar residuo flotante
                                        $monto_esta_cuota = round($monto_total_deuda - $suma_cuotas_acumuladas, 2);
                                    } else {
                                        $monto_esta_cuota = $monto_cuota;
                                        $suma_cuotas_acumuladas += $monto_esta_cuota;
                                    }

                                    $this->builder->save("pagos_diarios", array(
                                        'id_prestamos' => $id_generado,
                                        'pago_diario_monto' => $monto_esta_cuota,
                                        'pago_diario_fecha' => $fecha_iterador->format('Y-m-d'),
                                        'pago_diario_estado' => 1
                                    ));

                                    if ($tipo_pago == 'diario') {
                                        $fecha_iterador->add(new DateInterval('P1D'));
                                        if ($incluir_domingos == 'no' && $fecha_iterador->format('w') == 0) {
                                            $fecha_iterador->add(new DateInterval('P1D'));
                                        }
                                    } else if ($tipo_pago == 'semanal') {
                                        $fecha_iterador->add(new DateInterval('P7D'));
                                        if ($fecha_iterador->format('w') == 0) {
                                            $fecha_iterador->add(new DateInterval('P1D'));
                                        }
                                    } else if ($tipo_pago == 'mensual') {
                                        $fecha_iterador->add(new DateInterval('P1M'));
                                        if ($fecha_iterador->format('w') == 0) {
                                            $fecha_iterador->add(new DateInterval('P1D'));
                                        }
                                    }
                                }

                                // ==========================================
                                // 3. ACTUALIZAR SALDOS
                                // ==========================================
                                // Se eliminó la inserción duplicada en caja_movimientos.

                                $monto_anterior = $this->clientes->listar_x_id($_POST['id_cliente'])->cliente_credito;
                                $monto_actual = $monto_anterior - $_POST['prestamo_monto'];

                                $this->builder->update("clientes",array('cliente_credito' => $monto_actual,),array('id_cliente' => $_POST['id_cliente'],));

                                $this->builder->update("caja",array(
                                    'monto_caja' => $monto_caja_abierta->monto_caja - $_POST['prestamo_monto'],
                                ),array(
                                    'id_caja' => $monto_caja_abierta->id_caja
                                ));

                                $result = 1; // ÉXITO TOTAL

                            }
                        }
                    } else {
                        $result = 3;
                    }
                }
            } // Cierre del else de la nueva validación
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }

        echo json_encode(array("result" => array("code" => $result, "message" => $message, "id_p" => $id_generado)));
    }


    public function transferir_prestamo()
	{
		$result = 2;
		$message = 'OK';
		try {
			$result = $this->builder->update("prestamos",array(
				'prestamo_estado' => 3,
			),array(
				'id_prestamos' => $_POST['id_prestamos'],
			));
			
			if($result == 1){
				//Guardar para vender garantias_ventas
				$data_prestamo = $this->prestamos->listar_x_id($_POST['id_prestamos']);
				
				/*$result = $this->builder->save("garantias_ventas",array(
					'id_prestamos' => $_POST['id_prestamos'],
					'garantia_venta_descripcion' => $data_prestamo->prestamo_garantia,
					'garantia_venta_precio' => 0,
					'garantia_venta_fecha' => date('Y-m-d H:i:s'),
					'garantia_venta_estado' => 1,
					'garantia_venta_mt' => microtime(true),
				));*/
				$result = $this->builder->save("ventas",array(
					'id_cliente' => $data_prestamo->id_cliente,
					'venta_producto' => $data_prestamo->prestamo_garantia,
					'venta_precio' => 0,
					'venta_pago' => 0,
					'venta_fecha' => date('Y-m-d H:i:s'),
					'venta_estado' => 2,
					'venta_mt' => microtime(true),
				));
			}
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
    public function generar_documento(){
        try{
            date_default_timezone_set('America/Lima'); // Asegura la hora correcta de Iquitos

            $id_prestamo = $_GET['id'];
            $data_prestamo = $this->prestamos->listar_x_id($id_prestamo);
            $data_cliente = $this->clientes->listar_x_id($data_prestamo->id_cliente);
            $cuotas_reales = $this->prestamos->listar_cuotas_x_id_prestamo($id_prestamo);
            $monto_total = round($data_prestamo->prestamo_monto + $data_prestamo->prestamo_monto_interes, 1);

            $primera_cuota = $this->clientes->listar_primera_cuota($id_prestamo);
            $ultima_cuota = $this->clientes->listar_ultima_cuota($id_prestamo);

            // 1. EXTRAER Y FORMATEAR LAS 4 FECHAS CLAVE (Formato DD/MM/YYYY)

            // A) Fecha de Emisión (La original del préstamo)
            $fecha_emision = date('d/m/Y', strtotime($data_prestamo->prestamo_fecha_emision));

            // B) Fecha de Inicio (Leída directamente de tu nuevo campo en la BD)
            // Asegúrate de que el campo en tu BD se llame exactamente 'prestamo_fecha_inicio'
            $fecha_inicio = date('d/m/Y', strtotime($data_prestamo->prestamo_fecha_inicio));

            // C) Primer Pago (Sacado de las cuotas generadas)
            $fecha_primer_pago = $primera_cuota ? date('d/m/Y', strtotime($primera_cuota->pago_diario_fecha)) : 'No registrada';

            // D) Último Pago (Sacado de las cuotas generadas)
            $fecha_ultimo_pago = $ultima_cuota ? date('d/m/Y', strtotime($ultima_cuota->pago_diario_fecha)) : 'No registrada';


            // Textos para los párrafos legales
            $timestamp_prestamo = strtotime($data_prestamo->prestamo_fecha_emision);
            $dia_fecha = date('d', $timestamp_prestamo);
            $mes_fecha = date('m', $timestamp_prestamo);
            $anho_fecha = date('Y', $timestamp_prestamo);

            // Obtenemos la hora de emisión de la base de datos
            $hora_emision = date('H:i:s', $timestamp_prestamo);

            $meses = [
                '01'=>'Enero', '02'=>'Febrero', '03'=>'Marzo', '04'=>'Abril',
                '05'=>'Mayo', '06'=>'Junio', '07'=>'Julio', '08'=>'Agosto',
                '09'=>'Setiembre', '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre'
            ];
            $mes = $meses[$mes_fecha];

            // CORRECCIÓN AQUÍ: Usamos las variables de la base de datos ($dia_fecha, $mes, $anho_fecha, $hora_emision)
            // en lugar de usar date() que traía la fecha actual.
            $texto_emision = "Iquitos, " . $dia_fecha . " de " . $mes . " de " . $anho_fecha . " a las " . $hora_emision;

            $pdf = new Fpdf();
            $pdf->AliasNbPages();
            $pdf->AddPage();

            // --- PÁGINA 1: RECONOCIMIENTO DE DEUDA ---
            $pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png',5,5,18);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(180,6,'Reconocimiento de Deuda',0,1,'C',0);
            $pdf->Cell(180,6,'',0,1,'C',0);
            $pdf->SetFont('Arial','',10);

            $pdf->MultiCell(180,6,'Conste con el presente documento el contrato de reconocimiento de deuda, que celebran de una parte de la empresa Inversiones y Multiservicios GUZZ E.I.R.L, identificado con RUC N° 20600864255, domiciliado en la calle. José Olaya #324 - Túpac Amaru - IQUITOS-MAYNAS-LORETO, con su representante legal Karlo Abel Guzmán Arbildo con el DNI, 46119903. A quién en adelante se le llamará ACREEEDOR.',0,'J',0);
            $pdf->Ln();

            $nombre_completo = $data_cliente->cliente_nombre .' '.$data_cliente->cliente_apellido_paterno.' '.$data_cliente->cliente_apellido_materno;
            $pdf->MultiCell(180,6,'De otra parte, el (la) Sr(Sra) ' . $nombre_completo .' Identificado con el DNI. N° '.$data_cliente->cliente_dni . ' Domiciliado en '.$data_cliente->cliente_direccion.' que en adelante se le llamara deudor.',0,'J',0);
            $pdf->Ln();

            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(180,6,'Cláusulas',0,1,'L',0);
            $pdf->Ln();
            $pdf->SetFont('Arial','',10);

            $pdf->MultiCell(180,6,'1. Primero: Por el Presente contrato el deudor reconoce y expresa adeudar al acreedor el monto de '.$data_prestamo->prestamo_monto.' soles por concepto de adquisición de préstamo en la fecha '.$data_prestamo->prestamo_fecha.' a pagar en '.$data_prestamo->prestamo_num_cuotas.' cuota(s).',0,'J',0);
            $pdf->Ln();
            $pdf->MultiCell(180,6,'2. Segundo: El (la) SR (SRA) es deudor de la empresa Inversiones y Multiservicios GUZZ E.I.R.L, de la cantidad reconocida como consecuencias de la adquisición de préstamo.',0,'J',0);
            $pdf->Ln();
            $pdf->MultiCell(180,6,'3. Tercero: El presente reconocimiento de deuda se realiza al amparo del artículo 1205 del Código Civil y además normas pertinentes. En fe de lo anteriormente expuesto, dicho documento se ha presentado y legalizado ante un notario.',0,'J',0);
            $pdf->Ln();
            $pdf->MultiCell(180,6,'4. Cuarto: en caso de que el deudor incumpla con la cancelación de la prestación, la empresa acreedora podrá disponer de su derecho de exigir el pago mediante la ley que contempla el código civil.',0,'J',0);
            $pdf->Ln();

            $pdf->Cell(180,6,$texto_emision,0,1,'R',0);
            $pdf->Ln(); $pdf->Ln(); $pdf->Ln();

            $pdf->Cell(80,6,'................................',0,0,'L',0);
            $pdf->Cell(80,6,'................................',0,1,'R',0);
            $pdf->Cell(80,6,'       El Acreedor',0,0,'L',0);
            $pdf->Cell(70,6,'  El Deudor',0,1,'R',0);
            $pdf->Ln(); $pdf->Ln();

            // --- PÁGINA 2: GARANTÍA ---
            $detalle_garantia = $this->detalle_garantia($data_prestamo);
            if(!empty($detalle_garantia)){
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(180,6,'CONTRATO PRIVADO DE MUTUO ACUERDO CON GARANTÍA',0,1,'C',0);
                $pdf->Cell(180,6,'',0,1,'C',0);
                $pdf->SetFont('Arial','',10);
                $pdf->MultiCell(180,6,'Conste con el presente documento, el contrato privado mutuo con garantía que celebran por una parte '.$nombre_completo.' Identificado con el DNI. N° '.$data_cliente->cliente_dni . ' domiciliado legal en '.$data_cliente->cliente_direccion.' a quien en adelante y para estos efectos se le llamara EL MUTUARIO y por la otra y solo por el presente caso a la EMPRESA INVERSIONES Y MULTISERVICIOS GUZZ E.I.R.L CON EL RUC N°: 20600864255 DOMICILIADO EN LA CALLE: JOSE OLAYA # 324-TUPAC AMARU - IQUITOS. Con el representante legal Karlo Abel Guzmán Arbildo, identificado con el DNI N° 46119903, quien se constituye como garante solidario del MUTUATARIO de acuerdo a las clausulas y términos siguientes:',0,'J',0);
                $pdf->MultiCell(180,6,'1. Primero: EL MUTUATARIO declara acudir al mutuante en forma voluntaria, libre y espontánea, sin coacción, o intimidación alguna para el otorgamiento de un préstamo dinerario de '.$data_prestamo->prestamo_monto.' soles',0,'J',0);
                $pdf->MultiCell(180,6,'2. Segundo. EL MUTUATARIO se obliga y compromete a respetar el presente contrato privado con todas sus obligaciones.',0,'J',0);
                $pdf->MultiCell(180,6,'3. Tercero. EL MUTUATARIO como garantía de la reposición dineraria entrega AL MUTUANTE el bien mueble siguiente:',0,'J',0);
                // Datos cortos en dos columnas para que el contrato no pase a otra hoja;
                // la descripción (y el texto libre de préstamos antiguos) va a ancho completo.
                $datos_cortos = array_diff_key($detalle_garantia, array('Descripción' => 1, 'Garantía' => 1, 'Valor Real' => 1, 'Valor Asignado' => 1));
                $columna = 0;
                foreach ($datos_cortos as $etiqueta => $valor) {
                    $pdf->Cell(90,5,'- '.$etiqueta.': '.$valor,0,$columna,'L',0);
                    $columna = 1 - $columna;
                }
                if ($columna == 1) {
                    $pdf->Ln();
                }
                if (isset($detalle_garantia['Valor Real'])) {
                    $pdf->Cell(180,5,'- Valor Real: '.$detalle_garantia['Valor Real'].'        Valor Asignado como Garantía: '.$detalle_garantia['Valor Asignado'],0,1,'L',0);
                }
                foreach (array('Descripción', 'Garantía') as $etiqueta) {
                    if (isset($detalle_garantia[$etiqueta])) {
                        $pdf->MultiCell(180,5,'- '.$etiqueta.': '.$detalle_garantia[$etiqueta],0,'J',0);
                    }
                }
                $pdf->Ln(2);
                $pdf->MultiCell(180,6,'El o los mismos que deberán ser recuperados una vez cancelada la totalidad del préstamo; vale decir, el MUTUATARIO recuperará sus bienes previo pago del capital más los intereses acordados que debe pagar al "mutuante". En caso de incumplimiento del pago total al finalizar el cronograma acordado, la garantía será retenida en forma definitiva para cubrir el monto adeudado.',0,'J',0);                $pdf->MultiCell(180,6,'4. Cuarto: Habiéndose vencido la garantía y superado el plazo legal acordado, será entregado y transferido en propiedad definitiva a favor de la empresa de la Inversiones y Multiservicios GUZZ E.I.R.L, Identificado con el N° RUC: 20600864255, dirección: calle. José Olaya #324 - Túpac Amaru, con el representante legal de nombre Karlo Abel Guzmán Arbirdo y con DNI 46119903, quien a partir de la fecha será, su real y legitimo poseedor y propietario, sin mediar cualquier comunicación antigua.',0,'J',0);
                $pdf->MultiCell(180,6,'5. Quinto: "EL MUTUARIO" y el "MUTUANTE" en común acuerdo valorizan la garantía según factura y tiempo de uso del bien, en la suma de '.(!empty($data_prestamo->prestamo_garantia_valor_asignado) ? 'S/ '.number_format($data_prestamo->prestamo_garantia_valor_asignado, 2) : '..............').' no pudiendo ser el préstamo superior a lo valorizado de la garantía por depreciación.',0,'J',0);
                $pdf->MultiCell(180,6,'6. Sexto: "EL MUTUANTE" para estos efectos hace entrega en el acto "al mutuario" la suma de S/ '.number_format($data_prestamo->prestamo_monto, 2).' en dinero en efectivo que "EL MUTUARIO" se compromete a devolver respetando la cláusula tercera del presente contrato.',0,'J',0);
                $pdf->MultiCell(180,6,'7. Séptimo: EL MUTUARIO Y EL MUTUATARIO declaran conocer y aceptar los términos y condiciones de las cláusulas del presente contrato privado, suscribiéndose en la ciudad de Iquitos a los '.$dia_fecha.' días de '.$mes.' del '.$anho_fecha,0,'J',0);
                $pdf->Ln(); $pdf->Ln(); $pdf->Ln();
                $pdf->Cell(80,6,'................................',0,0,'L',0);
                $pdf->Cell(80,6,'................................',0,1,'R',0);
                $pdf->Cell(80,6,'MUTUARIO',0,0,'L',0);
                $pdf->Cell(80,6,'         GARANTE SOLIDARIO',0,1,'R',0);
            }

            // --- PÁGINA 3: CRONOGRAMA ---
            // Total exacto = suma de las cuotas almacenadas en DB
            $monto_total_cronograma = array_sum(array_column((array)$cuotas_reales, 'pago_diario_monto'));

            $pdf->AddPage();
            $pdf->SetFillColor(232,232,232);
            $pdf->Cell(180,6,'CRONOGRAMA DE PAGOS - ' . strtoupper($data_prestamo->prestamo_tipo_pago),0,1,'C',0);

            $pdf->SetFont('Arial','',10);
            $pdf->Ln();

            $inicioY = $pdf->GetY();

            // COLUMNA IZQUIERDA: TABLA
            $pdf->SetXY(10, $inicioY);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(15,6,'N',1,0,'C',1);
            $pdf->Cell(30,6,'Fecha',1,0,'C',1);
            $pdf->Cell(25,6,'Monto',1,0,'C',1);
            $pdf->Cell(20,6,'Estado',1,1,'C',1);
            $pdf->SetFont('Arial','',10);

            $item = 1;

            foreach ($cuotas_reales as $cuota) {
                $pdf->SetX(10);
                $pdf->Cell(15, 6, $item, 1, 0, 'C');
                $fecha_pago_db = date('d/m/Y', strtotime($cuota->pago_diario_fecha));
                $pdf->Cell(30, 6, $fecha_pago_db, 1, 0, 'C');
                $pdf->Cell(25, 6, 'S/. ' . number_format($cuota->pago_diario_monto, 2), 1, 0, 'R');
                $pdf->Cell(20, 6, '', 1, 1, 'C');
                $item++;
            }

            $pdf->SetFont('Arial','B',10);
            $pdf->SetX(10);
            $pdf->Cell(45,6,'Total General',1,0,'L',1);
            $pdf->Cell(25,6,'S/. '. number_format($monto_total_cronograma, 2),1,1,'R',1);

            // COLUMNA DERECHA: DATOS DEL CLIENTE
            $posX = 110;
            $pdf->SetXY($posX, $inicioY);
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell(80,6,'DATOS DEL CLIENTE',0,'J',0);
            $pdf->SetFont('Arial','',10);

            // Validamos si hay garante, si no, ponemos el guion
            $texto_garante = '-';
            if (!empty($data_prestamo->prestamo_garante) && !empty($data_prestamo->garante_nombre)) {
                $texto_garante = $data_prestamo->garante_nombre . ' ' . $data_prestamo->garante_paterno . ' ' . $data_prestamo->garante_materno;
            }


            $pdf->SetX($posX); $pdf->MultiCell(80,5,'DNI: '.$data_cliente->cliente_dni,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Garante: ' . $texto_garante,0,'L'); // <-- NUEVO DATO AÑADIDO
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Monto Prestado: S/. ' . number_format($data_prestamo->prestamo_monto, 2),0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Interés Aplicado: '.$data_prestamo->prestamo_interes.'%',0,'L');

            // IMPRESIÓN DE LAS 4 FECHAS (Modificado según lo que pediste)
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Fecha de Emisión: ' . $fecha_emision,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Inicio de Préstamo: ' . $fecha_inicio,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Primer Pago: ' . $fecha_primer_pago,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Fecha de Vencimiento del Préstamo: ' . $fecha_ultimo_pago,0,'L');

            // DATOS DE LA GARANTÍA
            if (!empty($detalle_garantia)) {
                $pdf->Ln(3);
                $pdf->SetX($posX);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(80,6,'DATOS DE LA GARANTÍA',0,'J',0);
                $pdf->SetFont('Arial','',10);
                foreach ($detalle_garantia as $etiqueta => $valor) {
                    $pdf->SetX($posX); $pdf->MultiCell(80,5,$etiqueta.': '.$valor,0,'L');
                }
            }

            // CLÁUSULAS DERECHAS
            $pdf->Ln();
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Cláusulas:',0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,4,'1. El cliente se compromete a pagar las cuotas segun el cronograma y plazo establecido.',0,'J');
            $pdf->SetX($posX); $pdf->MultiCell(80,4,'2. Si al final del periodo de pago...',0,'J');
            $pdf->SetX($posX); $pdf->MultiCell(80,4,'3. Si el cliente quiere cancelar...',0,'J');
            $pdf->SetX($posX); $pdf->MultiCell(80,4,'4. Info llamar al 969553545.',0,'J');

            // FIRMAS DERECHAS
            $pdf->Ln(); $pdf->Ln();
            $pdf->SetX($posX); $pdf->Cell(80,6,'..............................................',0,1,'L');
            $pdf->SetX($posX); $pdf->Cell(80,6,$nombre_completo,0,1,'L');
            $pdf->SetX($posX); $pdf->Cell(80,6,"DNI: " .$data_cliente->cliente_dni,0,1,'L');

            $pdf->Ln();
            // Imprime la misma fecha y hora del sistema en la tercera página
            $pdf->Cell(180,6,$texto_emision,0,1,'R');

            $pdf->Output();

        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
    }


    public function reporte_prestamos_antiguos(){
		try{
			$pdf = new Fpdf();
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png', 5, 5, 18);
			$pdf->SetFont('Arial', 'B', 12);
			$pdf->Cell(180, 6, 'Inversiones y Multiservicios GUZZ E.I.R.L', 0, 1, 'C');
			$pdf->SetFont('Arial', '', 12);
			$pdf->Ln(8);
			$pdf->Cell(180, 6, 'Préstamos Antiguos', 0, 1, 'C');
			$pdf->Ln(4);
			$pdf->Cell(0, 6, 'Momento del Reporte: ' . date('Y-m-d H:i:s'), 0, 1, 'L');

			//INGRESOS----------------------------------------------------------------
			$pdf->Ln(10);
			$pdf->SetFont('Arial', 'U', 12);
			$pdf->Cell(180, 6, 'INGRESOS', 0, 1, 'L');
			$prestamos_antiguos_pagos = $this->prestamos->listar_prestamos_antiguos_pagos();
			$monto_total_ingresos = 0;
			foreach ($prestamos_antiguos_pagos as $p) {
				$monto_total_ingresos += $p->pago_monto;
			}

			$pdf->SetFont('Arial', '', 12);
			$pdf->Cell(180, 6, 'Monto Total Ingresos: S/ ' .number_format($monto_total_ingresos,2), 0, 1, 'L');
			
			
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(25, 6, 'DNI', 1, 0, 'C', 0);
			$pdf->Cell(50, 6, 'Nombre', 1, 0, 'C', 0);
			$pdf->Cell(25, 6, 'Fecha', 1, 0, 'C', 0);
			$pdf->Cell(25, 6, 'Nro Recibo', 1, 0, 'C', 0);
			$pdf->Cell(40, 6, 'Monto', 1, 1, 'C', 0);

			foreach ($prestamos_antiguos_pagos as $p) {
				$pdf->Cell(25, 6, $p->cliente_dni, 1, 0, 'C', 0);
				$pdf->Cell(50, 6, $p->cliente_nombre . ' ' . $p->cliente_apellido_paterno . ' ' . $p->cliente_apellido_materno, 1, 0, 'L', 0);
				$pdf->Cell(25, 6, date('d-m-Y', strtotime($p->prestamo_fecha)), 1, 0, 'L', 0);
				$pdf->Cell(25, 6, $p->id_pago, 1, 0, 'R', 0);
				$pdf->Cell(40, 6, 'S/ ' . number_format($p->pago_monto, 2), 1, 1, 'R', 0);
			}
			
			
			//EGRESOS------------------------------------------------------------------
			$pdf->Ln(10);
			$pdf->SetFont('Arial', 'U', 12);
			$pdf->Cell(180, 6, 'Egresos', 0, 1, 'L');
			$prestamos_antiguos = $this->prestamos->listar_prestamos_antiguos();
			$monto_total_egresos = 0;
			$monto_total_adeudan = 0;
			foreach ($prestamos_antiguos as $p) {
				$resta_pagar = $this->cobros->listar_total_pagos_x_prestamo($p->id_prestamos);
				$descuentos_prestamos = $this->cobros->listar_decuentos_x_prestamo($p->id_prestamos);
				$resta_total = (float) $resta_pagar[0]->total;
				$descuentos_total = (float) $descuentos_prestamos[0]->total;
				$pm = (float)$p->prestamo_monto;
				if ($resta_total > 0) {
					if($descuentos_total > 0){
						$valor_resta_por_pagar =  $pm - $resta_total - $descuentos_total;
					}else{
						$valor_resta_por_pagar =  $pm - $resta_total;
					}
				} else {
					$valor_resta_por_pagar = $pm;
				}
				
				$monto_total_egresos += $p->prestamo_monto;
				$monto_total_adeudan += $valor_resta_por_pagar;
			}

			$pdf->SetFont('Arial', '', 12);
			$pdf->Cell(180, 6, 'Monto Total Egresos: S/ ' .number_format($monto_total_egresos,2), 0, 1, 'L');
			$pdf->Cell(180, 6, 'Monto Total que adeudan: S/ ' . number_format($monto_total_adeudan,2), 0, 1, 'L');
			$pdf->SetFont('Arial', '', 10);
			$pdf->Cell(25, 6, 'DNI', 1, 0, 'C', 0);
			$pdf->Cell(50, 6, 'Nombre', 1, 0, 'C', 0);
			$pdf->Cell(25, 6, 'Fecha', 1, 0, 'C', 0);
			$pdf->Cell(40, 6, 'Monto prestado ', 1, 0, 'C', 0);
			$pdf->Cell(40, 6, 'Monto que adeuda', 1, 1, 'C', 0);
			
			foreach ($prestamos_antiguos as $p) {
				$resta_pagar = $this->cobros->listar_total_pagos_x_prestamo($p->id_prestamos);
				$descuentos_prestamos = $this->cobros->listar_decuentos_x_prestamo($p->id_prestamos);
				$resta_total = (float) $resta_pagar[0]->total;
				$descuentos_total = (float) $descuentos_prestamos[0]->total;
				$pm = (float)$p->prestamo_monto;
				if ($resta_total > 0) {
					if($descuentos_total > 0){
						$valor_resta_por_pagar =  $pm - $resta_total - $descuentos_total;
					}else{
						$valor_resta_por_pagar =  $pm - $resta_total;
					}
				} else {
					$valor_resta_por_pagar = $pm;
				}
				
				$pdf->Cell(25, 6, $p->cliente_dni, 1, 0, 'C', 0);
				$pdf->Cell(50, 6, $p->cliente_nombre . ' ' . $p->cliente_apellido_paterno . ' ' . $p->cliente_apellido_materno, 1, 0, 'L', 0);
				$pdf->Cell(25, 6, date('d-m-Y', strtotime($p->prestamo_fecha)), 1, 0, 'L', 0);
				$pdf->Cell(40, 6, 'S/ ' . number_format($p->prestamo_monto, 2), 1, 0, 'R', 0);
				$pdf->Cell(40, 6, 'S/ ' . number_format($valor_resta_por_pagar, 2), 1, 1, 'R', 0);
			}
			$pdf->Output();
		}catch (Exception $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			$message = $e->getMessage();
		}
	}


}