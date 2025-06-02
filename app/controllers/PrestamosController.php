<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Caja.php';
require 'app/models/Archivo.php';
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
    private $validar;
    private $clientes;
    private $builder;
    private $prestamos;
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
		try {
			$mt = microtime(true);
			$result = $this->builder->save("prestamos",array(
				'id_cliente' => $_POST['id_cliente'],
				'prestamo_monto' => $_POST['prestamo_monto'],
				'prestamo_interes' => $_POST['prestamo_interes'],
				'prestamo_tipo_pago' => $_POST['prestamo_tipo_pago'],
				'prestamo_num_cuotas' => $_POST['prestamo_num_cuotas'],
				'prestamo_fecha' => $_POST['prestamo_fecha'],
				'prestamo_prox_cobro' => $_POST['prestamo_prox_cobro'],
				'prestamo_monto_interes' => $_POST['prestamo_monto']*$_POST['prestamo_interes']/100,
				'prestamo_saldo_pagar' => 0,
				'prestamo_garantia' => $_POST['prestamo_garantia'],
				'prestamo_garante' => $_POST['prestamo_garante'],
				'prestamo_motivo' => $_POST['prestamo_motivo'],
				'prestamo_comentario' => $_POST['prestamo_comentario'],
				'prestamo_domingo' => $_POST['select_domingos'],
				'prestamo_mt' => $mt,
				'prestamo_estado' => 1,
			));
			
			if($result == 1){
				$id_prestamo = $this->prestamos->listar_x_mt($mt);
				$monto_anterior = $this->clientes->listar_x_id($_POST['id_cliente'])->cliente_credito;
				$monto_actual = $monto_anterior - $_POST['prestamo_monto'];
				$result = $this->builder->update("clientes",array(
					'cliente_credito' => $monto_actual,
				),array(
					'id_cliente' => $_POST['id_cliente'],
				));
			}
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message, "id_p" => $id_prestamo->id_prestamos)));
	}
	public function generar_documento(){
		try{
			$id_prestamo = $_GET['id'];
			$data_prestamo = $this->prestamos->listar_x_id($id_prestamo);
			$data_cliente = $this->clientes->listar_x_id($data_prestamo->id_cliente);
			$fecha_= explode("-",$data_prestamo->prestamo_fecha);
			$diarl = date("N",strtotime($data_prestamo->prestamo_fecha));
			$dia_fecha=$fecha_[0];
			$mes_fecha=$fecha_[1];
			$anho_fecha=$fecha_[2];
			switch ($mes_fecha){
				case '01':
					$mes = 'Enero';
					break;
				case '02':
					$mes = 'Febrero';
					break;
				case '03':
					$mes = 'Marzo';
					break;
				case '04':
					$mes = 'Abril';
					break;
				case '05':
					$mes = 'Mayo';
					break;
				case '06':
					$mes = 'Junio';
					break;
				case '07':
					$mes = 'Julio';
					break;
				case '08':
					$mes = 'Agosto';
					break;
				case '09':
					$mes = 'Setiembre';
					break;
				case '10':
					$mes = 'Octubre';
					break;
				case '11':
					$mes = 'Noviembre';
					break;
				case '12':
					$mes = 'Diciembre';
					break;
			}
			
			$pdf = new Fpdf();
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(180,6,'Reconocimiento de Deuda',0,1,'C',0);
			$pdf->Cell(180,6,'',0,1,'C',0);
			$pdf->SetFont('Arial','',10);
			$pdf->MultiCell(180,6,'Conste con el presente documento el contrato de reconocimiento de deuda, que celebran de una parte de la empresa Inversiones y Multiservicios GUZZ E.I.R.L, identificado con RUC N° 20600864255, domiciliado en la calle. José Olaya #324 - Túpac Amaru - IQUITOS-MAYNAS-LORETO, con su representante legal Karlo Abel Guzmán Arbildo con el DNI, 46119903. A quién en adelante se le llamará  ACREEEDOR.',0,'J',0);
			$pdf->Ln();
			$pdf->MultiCell(180,6,'De otra parte, el (la) Sr(Sra) ' . $data_cliente->cliente_nombre .' '.$data_cliente->cliente_apellido_paterno.' '.$data_cliente->cliente_apellido_materno. ' Identificado con el DNI. N° '.$data_cliente->cliente_dni . ' Domiciliado en '.$data_cliente->cliente_direccion.' que en adelante se le llamara deudor.',0,'J',0);
			$pdf->Ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(180,6,'Cláusulas',0,1,'L',0);
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->MultiCell(180,6,'1. Primero: Por el Presente contrato el deudor reconoce y expresa adeudar al acreedor el monto de '.$saldo_prestamo.' soles por concepto de adquisición de préstamo en la fecha '.$_POST['fecha_prestamo'].' a pagar en '.$_POST['ncuotas'].' cuota(s).',0,'J',0);
			$pdf->Ln();
			$pdf->MultiCell(180,6,'2. Segundo: El (la) SR (SRA) es deudor de la empresa Inversiones y Multiservicios GUZZ E.I.R.L, de la cantidad reconocida como consecuencias de la adquisición de préstamo.',0,'J',0);
			$pdf->Ln();
			$pdf->MultiCell(180,6,'3. Tercero: El presente reconocimiento de deuda se realiza al amparo del artículo 1205 del Código Civil y además normas pertinentes. En fe de lo anteriormente expuesto, dicho documento se ha presentado y legalizado ante un notario.',0,'J',0);
			$pdf->Ln();
			$pdf->MultiCell(180,6,'4. Cuarto: en caso de que el deudor incumpla con la cancelación de la prestación, la empresa acreedora podrá disponer de su derecho de exigir el pago mediante la ley que contempla el código civil.',0,'J',0);
			$pdf->Ln();
			$pdf->Cell(180,6,'Iquitos, '.$dia_fecha.' de '.$mes.' del '.$anho_fecha,0,1,'R',0);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(80,6,'................................',0,0,'L',0);
			$pdf->Cell(80,6,'................................',0,1,'R',0);
			$pdf->Cell(80,6,'       El Acreedor',0,0,'L',0);
			$pdf->Cell(70,6,'  El Deudor',0,1,'R',0);
			$pdf->Ln();
			$pdf->Ln();
			if($data_prestamo->prestamo_garantia!==""){
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(180,6,'CONTRATO PRIVADO DE MUTUO ACUERDO CON GARANTÍA',0,1,'C',0);
				$pdf->Cell(180,6,'',0,1,'C',0);
				$pdf->SetFont('Arial','',10);
				$pdf->MultiCell(180,6,'Conste con el presente documento, el contrato privado mutuo con garantía que celebran por una parte '.$nombre_cliente.' Identificado con el DNI. N° '.$dni_cliente . ' domiciliado legal en '.$direccion_cliente.' a quien en adelante y para estos  efectos se le llamara EL MUTUARIO y por la otra y solo por el presente caso a la EMPRESA INVERSIONES Y MULTISERVICIOS GUZZ E.I.R.L CON EL RUC N°: 20600864255 DOMICILIADO EN LA CALLE: JOSE OLAYA # 324-TUPAC AMARU - IQUITOS. Con el representante legal Karlo Abel Guzmán Arbildo, identificado con el DNI N° 46119903, quien se constituye como garante solidario del MUTUATARIO de acuerdo a las clausulas y términos siguientes:',0,'J',0);
				$pdf->MultiCell(180,6,'1. Primero: EL MUTUATARIO declara acudir al mutuante en forma voluntaria, libre y espontánea, sin coacción, o intimidación alguna para el otorgamiento de un préstamo dinerario de '.$monto_prestamo.' soles',0,'J',0);
				$pdf->MultiCell(180,6,'2. Segundo. EL MUTUATARIO se obliga y compromete a respetar el presente contrato privado con todas sus obligaciones.',0,'J',0);
				$pdf->MultiCell(180,6,'3. Tercero. EL MUTUATARIO como garantía de la reposición dineraria entrega AL MUTUANTE el bien mueble siguiente:',0,'J',0);
				$pdf->MultiCell(180,6,'- '.$data_prestamo->prestamo_garantia,0,'J',0);
				$pdf->Ln();
				$pdf->MultiCell(180,6,'El o las mismos que deberán ser recuperados a los 30 días de firmado el presente documento, vale decir el MUTUATARIO recuperara sus bienes, previo pago del capital más los intereses legales que debe pagar al "mutuante", al contrario, la garantía será retenida en forma definitiva según sea el monto adeudado.',0,'J',0);
				$pdf->MultiCell(180,6,'4. Cuarto: Habiéndose vencido la garantía y superado el plazo legal acordado, será entregado y transferido en propiedad definitiva a favor de la empresa de la Inversiones y Multiservicios GUZZ E.I.R.L, Identificado con el N° RUC: 20600864255, dirección: calle. José Olaya #324 - Túpac Amaru, con el representante legal de nombre Karlo Abel Guzmán Arbirdo y con DNI 46119903, quien a partir de la fecha será, su real y legitimo poseedor y propietario, sin mediar cualquier comunicación antigua.',0,'J',0);
				$pdf->MultiCell(180,6,'5. Quinto: "EL MUTUARIO" y el "MUTUANTE" en común acuerdo valorizan la garantía según factura y tiempo de uso del bien, en la suma de .............. no pudiendo ser el préstamo superior a lo valorizado de la garantía por depreciación.',0,'J',0);
				$pdf->MultiCell(180,6,'6. Sexto: "EL MUTUANTE" para estos efectos hace entrega en el acto "al mutuario" la suma de .......... en dinero en efectivo que "EL MUTUARIO" se compromete a devolver respetando la cláusula tercera del presente contrato.',0,'J',0);
				$pdf->MultiCell(180,6,'7. Séptimo: EL MUTUARIO Y EL MUTUATARIO declaran conocer y aceptar los términos y condiciones de las cláusulas del presente contrato privado, suscribiéndose en la ciudad de Iquitos a los '.$dia_fecha.' días de '.$mes.' del '.$anho_fecha,0,'J',0);
				$pdf->Ln();
				$pdf->Ln();
				$pdf->Ln();
				$pdf->Ln();
				$pdf->Cell(80,6,'................................',0,0,'L',0);
				$pdf->Cell(80,6,'................................',0,1,'R',0);
				$pdf->Cell(80,6,'MUTUARIO',0,0,'L',0);
				$pdf->Cell(80,6,'         GARANTE SOLIDARIO',0,1,'R',0);
				$pdf->Ln();
				$pdf->Ln();
			}

			if($data_prestamo->prestamo_tipo_pago=='Diario'){
				$monto_diario = round($data_prestamo->prestamo_monto / $data_prestamo->prestamo_num_cuotas,2); // 0.74
				$monto_diario_redondeado = ceil($data_prestamo->prestamo_monto / $data_prestamo->prestamo_num_cuotas); // 1
				$total_redondeado = $monto_diario_redondeado * $data_prestamo->prestamo_num_cuotas;
				$date = $data_prestamo->prestamo_fecha;
				$fecha_pago_diario = new DateTime($date);
				$pdf->AddPage();
				$pdf->SetFillColor(232,232,232);
				$pdf->Cell(180,6,'CRONOGRAMA DE PAGOS',0,1,'C',0);
				$pdf->Cell(25,6,'Monto sin redondear: S/. ' . $monto_diario,0,1,'L',0);
				$pdf->Cell(25,6,'Total sin redondear: S/. ' . $data_prestamo->prestamo_monto,0,0,'L',0);
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(232,232,232);
				$pdf->Ln();



				// Guardamos el Y actual antes de comenzar
				$inicioY = $pdf->GetY();

// === COLUMNA IZQUIERDA: CRONOGRAMA ===
				$pdf->SetXY(10, $inicioY); // mitad izquierda
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(25,6,'Fecha',1,0,'C',1);
				$pdf->Cell(25,6,'Monto',1,0,'C',1);
				$pdf->Cell(25,6,'Pago',1,1,'C',1);
				$pdf->SetFont('Arial','',10);

// Recorremos cuotas
				$fechaY = $pdf->GetY();
				if ($diarl == 6) {
					for ($i = 1; $i <= $data_prestamo->prestamo_num_cuotas; $i++) {
						if ($i == 1) $fecha_pago_diario->add(new DateInterval('P2D'));
						else $fecha_pago_diario->add(new DateInterval('P1D'));
						if ($fecha_pago_diario->format('w') == 0) $fecha_pago_diario->add(new DateInterval('P1D'));

						$fecha_pagar = $fecha_pago_diario->format('Y-m-d');
						$this->builder->save("pagos_diarios",[
							'id_prestamo' => $id_prestamo,
							'pago_diario_monto' => $monto_diario,
							'pago_diario_fecha' => $fecha_pagar,
							'pago_diario_estado' => 1
						]);

						$pdf->SetX(10);
						$pdf->Cell(25, 6, date('d-m-Y', strtotime($fecha_pagar)), 1, 0, 'L');
						$pdf->Cell(25, 6, 'S/. ' . $monto_diario_redondeado, 1, 0, 'R');
						$pdf->Cell(25, 6, '', 1, 1, 'R');
					}
				} else {
					for ($i = 1; $i <= $data_prestamo->prestamo_num_cuotas; $i++) {
						$fecha_pago_diario->add(new DateInterval('P1D'));
						if ($fecha_pago_diario->format('w') == 0) $fecha_pago_diario->add(new DateInterval('P1D'));

						$fecha_pagar = $fecha_pago_diario->format('Y-m-d');
						$this->builder->save("pagos_diarios",[
							'id_prestamos' => $id_prestamo,
							'pago_diario_monto' => $monto_diario,
							'pago_diario_fecha' => $fecha_pagar,
							'pago_diario_estado' => 1
						]);

						$pdf->SetX(10);
						$pdf->Cell(25, 6, date('d-m-Y', strtotime($fecha_pagar)), 1, 0, 'L');
						$pdf->Cell(25, 6, 'S/. ' . $monto_diario_redondeado, 1, 0, 'R');
						$pdf->Cell(25, 6, '', 1, 1, 'R');
					}
				}

// TOTAL
				$pdf->SetFont('Arial','B',10);
				$pdf->SetX(10);
				$pdf->Cell(25,6,'Total',1,0,'L');
				$pdf->Cell(25,6,'S/. '.$total_redondeado,1,1,'R');

// === COLUMNA DERECHA: DATOS CLIENTE ===
				$posX = 110;
				$posY = $inicioY;

				$pdf->SetXY($posX, $posY);
				$pdf->SetFont('Arial','B',10);
				$pdf->MultiCell(80,6,'DATOS DEL CLIENTE',0,'J',0);
				$pdf->SetFont('Arial','',10);
				$pdf->SetX($posX); $pdf->MultiCell(80,6,'DNI: '.$data_cliente->cliente_dni,0,'L');
				$pdf->SetX($posX); $pdf->MultiCell(80,6,'Monto Prestado: S/. ' . number_format($data_prestamo->prestamo_monto, 2),0,'L');
				$pdf->SetX($posX); $pdf->MultiCell(80,6,'Interés Aplicado: '.$data_prestamo->prestamo_interes.'%',0,'L');
				$pdf->SetX($posX); $pdf->MultiCell(80,6,'Fecha de Préstamo: '.$data_prestamo->prestamo_fecha,0,'L');
				$pdf->SetX($posX); $pdf->MultiCell(80,6,'Fecha de Vencimiento: __________________________',0,'L');

// CLÁUSULAS
				$pdf->SetX($posX);
				$pdf->MultiCell(80,6,'Cláusulas:',0,'L');
				$pdf->SetX($posX);
				$pdf->MultiCell(80,6,'1. Primero: El cliente tiene un plazo de 30 días para pagar las cuotas establecidas.',0,'J');
				$pdf->SetX($posX);
				$pdf->MultiCell(80,6,'2. Segundo: Si al final del periodo de pago... (etc)',0,'J');
				$pdf->SetX($posX);
				$pdf->MultiCell(80,6,'3. Tercero: Si el cliente quiere cancelar...',0,'J');
				$pdf->SetX($posX);
				$pdf->MultiCell(80,6,'4. Cuarto: Para toda información llamar al 969553545.',0,'J');

// FIRMAS
				$pdf->Ln(); $pdf->Ln();
				$pdf->SetX($posX);
				$pdf->Cell(80,6,'..............................................',0,1,'L');
				$pdf->SetX($posX);
				$pdf->Cell(80,6,$data_cliente->cliente_nombre .' '.$data_cliente->cliente_apellido_paterno.' '.$data_cliente->cliente_apellido_materno,0,1,'L');
				$pdf->SetX($posX);
				$pdf->Cell(80,6,"DNI: " .$data_cliente->cliente_dni,0,1,'L');

// FECHA FINAL
				$pdf->Ln();
				$pdf->Cell(180,6,'Iquitos, '.$dia_fecha.' de '.$mes.' del '.$anho_fecha,0,1,'R');

			}
			$pdf->Output();
		}catch (Exception $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			$message = $e->getMessage();
		}
	}
}