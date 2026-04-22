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
			$prestamos_antiguos = $this->prestamos->listar_prestamos_antiguos();
			$prestamos_antiguos_cancelados = $this->prestamos->listar_prestamos_antiguos_cancelados();
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
    public function detalles(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_prestamos = $_GET['id'];
			$data_prestamo = $this->prestamos->listar_x_id($id_prestamos);
			$data_cliente = $this->clientes->listar_x_id($data_prestamo->id_cliente);
			
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
			if($_POST['clave_validacion'] == 'joseolaya324'){
				if($_POST['incremento'] > 0){
					$result = $this->builder->save("clientes_linea_credito",array(
						'id_cliente' => $_POST['id_cliente'],
						'cliente_linea_monto' => $_POST['incremento'],
						'cliente_linea_motivo' => $_POST['motivo_aumento'],
						'cliente_linea_estado' => 1
					));
					
					if($result == 1){
						$monto_anterior = $this->clientes->listar_x_id($_POST['id_cliente'])->cliente_credito;
						$monto_actual = $monto_anterior + $_POST['incremento'];
						$result = $this->builder->update("clientes",array(
							'cliente_credito' => $monto_actual,
						),array(
							'id_cliente' => $_POST['id_cliente'],
						));
					}
				}else{
					$result = 4;
				}
			}else{
				$result = 3;
			}
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
    public function guardar_prestamo()
    {
        $result = 2;
        $message = 'OK';
        $id_generado = 0;

        try {
            // Aseguramos que ambos valores sean enteros para una comparación exacta
            $id_cliente = !empty($_POST['id_cliente']) ? (int)$_POST['id_cliente'] : 0;
            $garante = !empty($_POST['prestamo_garante']) ? (int)$_POST['prestamo_garante'] : 0;

            // NUEVA VALIDACIÓN: Si el garante seleccionado es el mismo cliente
            if ($garante > 0 && $garante === $id_cliente) {
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
                        $result_prestamo = $this->builder->save("prestamos",array(
                            'id_cliente' => $_POST['id_cliente'],
                            'prestamo_monto' => $_POST['prestamo_monto'],
                            'prestamo_interes' => $_POST['prestamo_interes'],
                            'prestamo_tipo_pago' => $_POST['prestamo_tipo_pago'],
                            'prestamo_num_cuotas' => $_POST['prestamo_num_cuotas'],
                            'prestamo_fecha_inicio' => $_POST['prestamo_fecha_inicio'],
                            'prestamo_fecha' => $fecha,
                            'prestamo_prox_cobro' => $_POST['prestamo_prox_cobro'],
                            'prestamo_monto_interes' =>ceil( $_POST['prestamo_monto']*$_POST['prestamo_interes']/100),
                            'prestamo_saldo_pagar' => $_POST['prestamo_monto'] + ($_POST['prestamo_monto']*$_POST['prestamo_interes']/100),
                            'prestamo_garantia' => $_POST['prestamo_garantia'],
                            'prestamo_garante' => $_POST['prestamo_garante'],
                            'prestamo_motivo' => $_POST['prestamo_motivo'],
                            'prestamo_comentario' => $_POST['prestamo_comentario'],
                            'prestamo_domingo' => $_POST['select_domingos'],
                            'prestamo_mt' => $mt,
                            'prestamo_estado' => 1,
                        ));

                        if($result_prestamo == 1){
                            $id_prestamo_obj = $this->prestamos->listar_x_mt($mt);

                            if($id_prestamo_obj){
                                $id_generado = $id_prestamo_obj->id_prestamos;

                                // ==========================================
                                // 2. GUARDAR LAS CUOTAS
                                // ==========================================
                                $num_cuotas = (int)$_POST['prestamo_num_cuotas'];
                                $tipo_pago = strtolower($_POST['prestamo_tipo_pago']);
                                $incluir_domingos = strtolower($_POST['select_domingos']);

                                $monto_total_deuda = $_POST['prestamo_monto'] + ceil($_POST['prestamo_monto'] * $_POST['prestamo_interes'] / 100);
                                $monto_cuota = round($monto_total_deuda / $num_cuotas, 1);

                                $fecha_iterador = new DateTime($_POST['prestamo_fecha']);
                                $fecha_iterador->modify('+1 day');

                                $suma_cuotas_acumuladas = 0;

                                for ($i = 1; $i <= $num_cuotas; $i++) {
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

                                    if ($i == $num_cuotas) {
                                        $monto_esta_cuota = $monto_total_deuda - $suma_cuotas_acumuladas;
                                    } else {
                                        $monto_esta_cuota = $monto_cuota;
                                        $suma_cuotas_acumuladas += $monto_esta_cuota;
                                    }

                                    $this->builder->save("pagos_diarios", array(
                                        'id_prestamos' => $id_generado,
                                        'pago_diario_monto' => round($monto_esta_cuota, 1),
                                        'pago_diario_fecha' => $fecha_iterador->format('Y-m-d'),
                                        'pago_diario_estado' => 1
                                    ));
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
            $fecha_emision = date('d/m/Y', strtotime($data_prestamo->prestamo_fecha));

            // B) Fecha de Inicio (Leída directamente de tu nuevo campo en la BD)
            // Asegúrate de que el campo en tu BD se llame exactamente 'prestamo_fecha_inicio'
            $fecha_inicio = date('d/m/Y', strtotime($data_prestamo->prestamo_fecha_inicio));

            // C) Primer Pago (Sacado de las cuotas generadas)
            $fecha_primer_pago = $primera_cuota ? date('d/m/Y', strtotime($primera_cuota->pago_diario_fecha)) : 'No registrada';

            // D) Último Pago (Sacado de las cuotas generadas)
            $fecha_ultimo_pago = $ultima_cuota ? date('d/m/Y', strtotime($ultima_cuota->pago_diario_fecha)) : 'No registrada';


            // Textos para los párrafos legales
            $timestamp_prestamo = strtotime($data_prestamo->prestamo_fecha);
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
            if($data_prestamo->prestamo_garantia !== ""){
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(180,6,'CONTRATO PRIVADO DE MUTUO ACUERDO CON GARANTÍA',0,1,'C',0);
                $pdf->Cell(180,6,'',0,1,'C',0);
                $pdf->SetFont('Arial','',10);
                $pdf->MultiCell(180,6,'Conste con el presente documento, el contrato privado mutuo con garantía que celebran por una parte '.$nombre_completo.' Identificado con el DNI. N° '.$data_cliente->cliente_dni . ' domiciliado legal en '.$data_cliente->cliente_direccion.' a quien en adelante y para estos efectos se le llamara EL MUTUARIO y por la otra y solo por el presente caso a la EMPRESA INVERSIONES Y MULTISERVICIOS GUZZ E.I.R.L CON EL RUC N°: 20600864255 DOMICILIADO EN LA CALLE: JOSE OLAYA # 324-TUPAC AMARU - IQUITOS. Con el representante legal Karlo Abel Guzmán Arbildo, identificado con el DNI N° 46119903, quien se constituye como garante solidario del MUTUATARIO de acuerdo a las clausulas y términos siguientes:',0,'J',0);
                $pdf->MultiCell(180,6,'1. Primero: EL MUTUATARIO declara acudir al mutuante en forma voluntaria, libre y espontánea, sin coacción, o intimidación alguna para el otorgamiento de un préstamo dinerario de '.$data_prestamo->prestamo_monto.' soles',0,'J',0);
                $pdf->MultiCell(180,6,'2. Segundo. EL MUTUATARIO se obliga y compromete a respetar el presente contrato privado con todas sus obligaciones.',0,'J',0);
                $pdf->MultiCell(180,6,'3. Tercero. EL MUTUATARIO como garantía de la reposición dineraria entrega AL MUTUANTE el bien mueble siguiente:',0,'J',0);
                $pdf->MultiCell(180,6,'- '.$data_prestamo->prestamo_garantia,0,'J',0);
                $pdf->Ln();
                $pdf->MultiCell(180,6,'El o los mismos que deberán ser recuperados una vez cancelada la totalidad del préstamo; vale decir, el MUTUATARIO recuperará sus bienes previo pago del capital más los intereses acordados que debe pagar al "mutuante". En caso de incumplimiento del pago total al finalizar el cronograma acordado, la garantía será retenida en forma definitiva para cubrir el monto adeudado.',0,'J',0);                $pdf->MultiCell(180,6,'4. Cuarto: Habiéndose vencido la garantía y superado el plazo legal acordado, será entregado y transferido en propiedad definitiva a favor de la empresa de la Inversiones y Multiservicios GUZZ E.I.R.L, Identificado con el N° RUC: 20600864255, dirección: calle. José Olaya #324 - Túpac Amaru, con el representante legal de nombre Karlo Abel Guzmán Arbirdo y con DNI 46119903, quien a partir de la fecha será, su real y legitimo poseedor y propietario, sin mediar cualquier comunicación antigua.',0,'J',0);
                $pdf->MultiCell(180,6,'5. Quinto: "EL MUTUARIO" y el "MUTUANTE" en común acuerdo valorizan la garantía según factura y tiempo de uso del bien, en la suma de .............. no pudiendo ser el préstamo superior a lo valorizado de la garantía por depreciación.',0,'J',0);
                $pdf->MultiCell(180,6,'6. Sexto: "EL MUTUANTE" para estos efectos hace entrega en el acto "al mutuario" la suma de .......... en dinero en efectivo que "EL MUTUARIO" se compromete a devolver respetando la cláusula tercera del presente contrato.',0,'J',0);
                $pdf->MultiCell(180,6,'7. Séptimo: EL MUTUARIO Y EL MUTUATARIO declaran conocer y aceptar los términos y condiciones de las cláusulas del presente contrato privado, suscribiéndose en la ciudad de Iquitos a los '.$dia_fecha.' días de '.$mes.' del '.$anho_fecha,0,'J',0);
                $pdf->Ln(); $pdf->Ln(); $pdf->Ln(); $pdf->Ln();
                $pdf->Cell(80,6,'................................',0,0,'L',0);
                $pdf->Cell(80,6,'................................',0,1,'R',0);
                $pdf->Cell(80,6,'MUTUARIO',0,0,'L',0);
                $pdf->Cell(80,6,'         GARANTE SOLIDARIO',0,1,'R',0);
            }

            // --- PÁGINA 3: CRONOGRAMA ---
            $pdf->AddPage();
            $pdf->SetFillColor(232,232,232);
            $pdf->Cell(180,6,'CRONOGRAMA DE PAGOS - ' . strtoupper($data_prestamo->prestamo_tipo_pago),0,1,'C',0);
            $pdf->Cell(25,6,'Monto sin redondear: S/. ' . number_format($monto_total, 2),0,1,'L',0);

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
            $pdf->Cell(25,6,'S/. '. number_format($monto_total, 2),1,1,'R',1);

            // COLUMNA DERECHA: DATOS DEL CLIENTE
            $posX = 110;
            $pdf->SetXY($posX, $inicioY);
            $pdf->SetFont('Arial','B',10);
            $pdf->MultiCell(80,6,'DATOS DEL CLIENTE',0,'J',0);
            $pdf->SetFont('Arial','',10);

            $pdf->SetX($posX); $pdf->MultiCell(80,5,'DNI: '.$data_cliente->cliente_dni,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Monto Prestado: S/. ' . number_format($data_prestamo->prestamo_monto, 2),0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Interés Aplicado: '.$data_prestamo->prestamo_interes.'%',0,'L');

            // IMPRESIÓN DE LAS 4 FECHAS (Modificado según lo que pediste)
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Fecha de Emisión: ' . $fecha_emision,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Inicio de Préstamo: ' . $fecha_inicio,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Primer Pago: ' . $fecha_primer_pago,0,'L');
            $pdf->SetX($posX); $pdf->MultiCell(80,5,'Último Pago: ' . $fecha_ultimo_pago,0,'L');

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