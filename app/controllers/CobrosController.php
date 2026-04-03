<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Prestamos.php';
require 'app/models/Cobros.php';
require 'app/models/Caja.php';
require 'app/view/pdf/fpdf/fpdf.php';
class CobrosController
{
    private $usuario;
    private $caja;
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
        $this->caja = new Caja();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }

    public function pagar(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_prestamo = $_GET['id'];
			$prestamos_data = $this->prestamos->listar_x_id($id_prestamo);
			$garante = $this->prestamos->listar_garante_prestamo($id_prestamo);
			$cliente_data = $this->clientes->listar_x_id($prestamos_data->id_cliente);
			$resta_pagar = $this->cobros->listar_total_pagos_x_prestamo($id_prestamo);
			$descuentos_prestamos = $this->cobros->listar_decuentos_x_prestamo($id_prestamo);
            $cuotas=$this->cobros->listar_cuotas_x_prestamo($id_prestamo);
            $total_pagos_cuenta=$this->prestamos->listar_total_pagos_contados($id_prestamo);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/pagar.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function pagos(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_prestamo = $_GET['id'];
			$pagos_p = $this->cobros->listar_pagos_x_prestamo($id_prestamo);
			$descuentos_prestamos = $this->cobros->listar_datos_decuentos_x_prestamo($id_prestamo);
			$descuentos_monto = $this->cobros->listar_decuentos_x_prestamo($id_prestamo);
			$listar_cliente_x_prestamo = $this->clientes->listar_x_id_presrtamo($id_prestamo);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'cobros/pagos.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
	public function guardar_pago()
	{
		$result = 2;
		$message = 'OK';
		try {
            $id_prestamo=$_POST['id_prestamo'];
            $id_pago_cuota=$_POST['id_pago'];
            $cuota_a_pagar=$this->cobros->listar_cuota_individual($id_pago_cuota);
            $monto_caja_abierta = $this->caja->traer_datos_caja();
			$prestamo=$this->prestamos->listar_x_id($id_prestamo);
            $debe_pagar = $this->prestamos->listar_x_id($_POST['id_prestamo'])->prestamo_monto + $prestamo->prestamo_monto_interes; //500
            $total_pagos=$this->prestamos->listar_total_pagos_x_prestamo($id_prestamo);
            $total_pagos_cuenta=$this->prestamos->listar_total_pagos_contados($id_prestamo);
            $cuenta_pago=$total_pagos_cuenta->cuenta ?? 0;
            $ya_pago =$total_pagos->total ?? 0;//150
            $interes=$this->prestamos->listar_x_id($_POST['id_prestamo'])->prestamo_monto_interes ?? 00;
            $prestamo_total_mas_interes=$prestamo->prestamo_monto + $prestamo->prestamo_monto_interes;
            $listar_ultima_caja=$this->caja->listar_ultima_caja();
            $hoy=date("Y-m-d H:i:s");

            //$descuento = $this->cobros->listar_decuentos_x_prestamo($_POST['id_prestamo'])[0]->total ?? 0 ;//80
            if (($total_pagos_cuenta->cuenta + 1) == $prestamo->prestamo_num_cuotas) {
                $prestamo_total_mas_interes = round((float)$prestamo_total_mas_interes, 2);
                $ya_pago = round((float)$ya_pago, 2);
                $cuota = round((float)$cuota_a_pagar->pago_diario_monto, 2);
                $diferencia = round($prestamo_total_mas_interes - ($ya_pago + $cuota), 2);
                $ya_pago = round($ya_pago + $diferencia, 2);


            }

            $total_cancelado=$ya_pago+$cuota;
			if($total_cancelado /*+floatval($descuento)*/ <= floatval($debe_pagar) ){
				if($total_cancelado/* +floatval($descuento)*/ == floatval($debe_pagar)){
					$entrada="Si";
                    $result = $this->builder->update("prestamos",array(
						'prestamo_estado' => 2, //Pagado
					),array(
						'id_prestamos' => $_POST['id_prestamo'],
					));
				}

				$mt = microtime(true);
				$result = $this->builder->save("pagos",array(
					'id_prestamo' => $_POST['id_prestamo'],
					'pago_monto' =>$cuota_a_pagar->pago_diario_monto,
					'pago_recepcion' => $_POST['pago_recepcion'],
					'pago_metodo' => $_POST['pago_metodo'],
					'pago_recepcion_yp' => $_POST['pago_recepcion_yp'],
					'pago_fecha' => date('Y-m-d H:i:s'),
					'pago_estado' => 1,
					'pago_mt' => $mt,
				));
                $result = $this->builder->save("caja_movimientos",array(
                    'id_caja' => $listar_ultima_caja->id_caja,
                    'caja_movimiento_tipo' =>1,
                    'caja_movimiento_monto' => $cuota_a_pagar->pago_diario_monto,
                    'caja_movimiento_fecha' => $hoy,

                ));
                $result=$this->cobros->cambiar_estado_cuota($id_pago_cuota);
                $listar_cuota_proxima=$this->cobros->listar_cuota_proxima($id_prestamo);

                $result = $this->builder->update("prestamos", array(
                    'prestamo_prox_cobro' => $listar_cuota_proxima->pago_diario_fecha,

                ), array(
                    'id_prestamos' => $id_prestamo // <-- el id del registro a actualizar
                ));

                if($result == 1){
                    $prestamo_saldo= $prestamo->prestamo_saldo_pagar - ($cuota_a_pagar->pago_diario_monto+ $diferencia);
					$id_pago = $this->cobros->listar_pago_guardado_x_mt($mt)->id_pago;
					$result = $this->builder->update("prestamos",array(
                        'prestamo_saldo_pagar' =>$prestamo_saldo, //Pagado

                    ),array(
						'id_prestamos' => $_POST['id_prestamo'],
					));
					if($result == 1){
						//Actualizar caja
						$result = $this->builder->update("caja",array(
							'monto_caja' => $monto_caja_abierta->monto_caja + $cuota_a_pagar->pago_diario_monto,
						),array(
							'id_caja' => $monto_caja_abierta->id_caja,
						));
					}
				}
			}else{
				$result = 3;
			}
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message,"id_pago" => $id_pago)));
	}
	public function aplicar_descuento()
	{
		$result = 2;
		$message = 'OK';
		try {
			$result = $this->builder->save("descuentos",array(
				'id_prestamo' => $_POST['id_prestamo'],
				'descuento_monto' => $_POST['descontar_cantidad'],
				'descuento_fecha' => date('Y-m-d H:i:s'),
				'descuento_estado' => 1,
				'descuento_mt' => microtime(true),
			));
		} catch (Exception $e) {
			$this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
			$message = $e->getMessage();
		}
		echo json_encode(array("result" => array("code" => $result, "message" => $message)));
	}
	public function generar_documento(){
		try{
			$id_pago = $_GET['id'];
			$data = $this->cobros->listar_x_id($id_pago);
			$debe_pagar = $this->prestamos->listar_x_id($data->id_prestamos)->prestamo_monto + ($this->prestamos->listar_x_id($data->id_prestamos)->prestamo_monto * $this->prestamos->listar_x_id($data->id_prestamos)->prestamo_interes / 100);
			$ya_pago = $this->prestamos->listar_total_pagos_x_prestamo($data->id_prestamos)->total;
			$descuento = $this->cobros->listar_decuentos_x_prestamo($data->id_prestamos)[0]->total;
			$garante = $this->cobros->listar_garante($data->id_prestamos);
			$saldo_final = $debe_pagar - $ya_pago - $descuento;

			// Ticket de 80mm x 200mm
			$pdf = new FPDF('P','mm',array(80,200));
			$pdf->AddPage();
			$pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png',5,5,15);
			$pdf->Ln(10);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(0,5,'INVERSIONES GUZZ E.I.R.L',0,1,'C');
			$pdf->Ln(2);
			
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(0,5,'RECIBO DE PAGO',0,1,'C');
			$pdf->Ln(10);
			
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(0,5,'Nro Recibo: '.$id_pago,0,1,'L');
			$pdf->Cell(0,5,'Cliente: '.$data->cliente_nombre.' '.$data->cliente_apellido_paterno.' '.$data->cliente_apellido_materno,0,1,'L');
			$pdf->Cell(0,5,'DNI: '.$data->cliente_dni,0,1,'L');
			$pdf->Cell(0,5,'Fecha: '.$data->pago_fecha,0,1,'L');
			$pdf->Cell(0,5,'Tipo: '.$data->prestamo_tipo_pago,0,1,'L');
			$pdf->Cell(0,5,'Resta por Pagar: S/. '.$saldo_final,0,1,'L');
			$pdf->Ln(2);
			
			$pdf->Cell(0,5,'Garante: '.$garante->cliente_nombre . ' ' . $garante->cliente_apellido_paterno,0,1,'L');
			$pdf->Ln(2);
			
			$pdf->Cell(0,5,'Monto Pagado: S/. '.$data->pago_monto,0,1,'L');
			$pdf->Cell(0,5,'Recepcionado por: '.$data->pago_recepcion,0,1,'L');
			
			$pdf->Ln(20);
			$pdf->Cell(0,5,'----------------------------',0,1,'C');
			$pdf->Cell(0,5,'FIRMA',0,1,'C');
			
			$pdf->Output();
			
			/*$pdf = new Fpdf();
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png',5,5,10);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(30);
			$pdf->Cell(30,10,'Inversiones y Multiservicios GUZZ E.I.R.L',0,0,'C');
			$pdf->Image(_SERVER_._MEDIAIMG_.'MG1.png',105,5,10);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(30);
			$pdf->Cell(110,10,'Inversiones y Multiservicios GUZZ E.I.R.L',0,0,'C');
			$pdf->Ln(20);
			$pdf->SetFont('Arial','B',10);
			$pdf->CellFitSpace(50,6,'Recibo de Pago',0,0,'C',0);
			$pdf->CellFitSpace(170,6,'Recibo de Pago',0,1,'C',0);
			$pdf->SetFont('Arial','',10);
			$pdf->CellFitSpace(100,6,'Nro Recibo: ' . $id_pago,0,0,'L',0);
			$pdf->CellFitSpace(120,6,'Nro Recibo: ' . $id_pago,0,1,'L',0);
//			if($producto != ""){
//				$pdf->CellFitSpace(100,6,'Producto: ' . $producto,0,0,'L',0);
//				$pdf->CellFitSpace(180,6,'Producto: ' . $producto,0,1,'L',0);
//			}
			$pdf->CellFitSpace(100,6,'Cliente: ' . $data->cliente_nombre . ' ' . $data->cliente_apellido_paterno. ' ' . $data->cliente_apellido_materno,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Cliente: ' . $data->cliente_nombre . ' ' . $data->cliente_apellido_paterno. ' ' . $data->cliente_apellido_materno,0,1,'L',0);
			$pdf->CellFitSpace(100,6,'DNI: ' . $data->cliente_dni,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'DNI: ' . $data->cliente_dni,0,1,'L',0);
			$pdf->CellFitSpace(100,6,'Fecha: ' . $data->pago_fecha,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Fecha: ' . $data->pago_fecha,0,1,'L',0);
			$pdf->CellFitSpace(100,6,'Tipo: ' . $data->prestamo_tipo_pago,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Tipo: ' . $data->prestamo_tipo_pago,0,1,'L',0);
			$pdf->CellFitSpace(100,6,'Resta por Pagar: S/. ' . $saldo_final,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Resta por Pagar: S/. ' . $saldo_final,0,1,'L',0);
//			$pdf->Ln(2);
//			$pdf->CellFitSpace(100,6,'Garantia:',0,0,'L',0);
//			$pdf->CellFitSpace(180,6,'Garantia:',0,1,'L',0);
//			$pdf->CellFitSpace(100,6,'' . $garantia,0,0,'L',0);
//			$pdf->CellFitSpace(180,6,'' . $garantia,0,1,'L',0);
			$pdf->Ln(2);

			$pdf->Ln(1);
			$pdf->CellFitSpace(100,6,'Garante:',0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Garante:',0,1,'L',0);
			$pdf->CellFitSpace(100,6,'' . $data->prestamo_garante,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'' . $data->prestamo_garante,0,1,'L',0);
			$pdf->Ln(1);
			
			
//			if($saldo_final>0 && $_POST['tipo']=="Mensual"){
//				$pdf->CellFitSpace(100,6,'Si paga el próximo mes: S/. ' . $saldo_final_prox_mes,0,0,'L',0);
//				$pdf->CellFitSpace(180,6,'Si paga el próximo mes: S/. ' . $saldo_final_prox_mes,0,1,'L',0);
//			}
			
			$pdf->Ln();
			$pdf->CellFitSpace(100,6,'Monto Pagado: S/. ' . $data->pago_monto,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Monto Pagado: S/. ' . $data->pago_monto,0,1,'L',0);

			$pdf->Ln();
			$pdf->CellFitSpace(100,6,'Recepcionado por: ' . $data->pago_recepcion,0,0,'L',0);
			$pdf->CellFitSpace(180,6,'Recepcionado por: ' . $data->pago_recepcion,0,1,'L',0);

			$pdf->Ln();
			$pdf->Ln();
			$pdf->CellFitSpace(80,6,'................................',0,0,'L',0);
			$pdf->CellFitSpace(100,6,'................................',0,1,'C',0);
			$pdf->Cell(80,6,'       Firma',0,0,'L',0);
			$pdf->Cell(90,6,'       Firma',0,0,'C',0);
			$pdf->Ln();
			$pdf->Output();*/
		}catch (Exception $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			$message = $e->getMessage();
		}
	}

    public function anular(){
        // Preparamos el array de respuesta para el AJAX
        $res = array('codigo' => 0, 'mensaje' => 'Error desconocido');
        $hoy=date("Y-m-d H:i:s");

        try {
            $id_prestamo = isset($_POST['id_prestamo']) ? $_POST['id_prestamo'] : null;

            if (empty($id_prestamo)) {
                throw new Exception("ID de préstamo no recibido.");
            }

            // 1. Obtener los datos del préstamo antes de anularlo
            $prestamo = $this->cobros->listar_prestamo($id_prestamo);

            if (empty($prestamo)) {
                throw new Exception("El préstamo no existe.");
            }

            // 2. Obtener la última caja abierta
            // (Asumiendo que tienes un modelo/función que trae la caja activa del día)
            $caja_abierta = $this->cobros->obtener_caja_abierta();

            if (empty($caja_abierta)) {
                throw new Exception("No hay ninguna caja abierta. Debe abrir caja para procesar la devolución del dinero.");
            }

            // 3. Preparar la matemática
            $monto_capital = floatval($prestamo->prestamo_monto);
            $monto_interes = floatval($prestamo->prestamo_monto_interes);

            // ADVERTENCIA: Aquí se suman ambos según tu requerimiento.
            // Contablemente se recomienda que sea solo $monto_capital.
            $monto_a_devolver = $monto_capital ;

            $saldo_caja_actual = floatval($caja_abierta->monto_caja);
            $nuevo_saldo_caja = $saldo_caja_actual + $monto_a_devolver;


            // ==========================================
            // INICIO DE LA TRANSACCIÓN SQL
            // ==========================================
            // Es vital usar transacciones: Si falla actualizar la caja, el préstamo no se anula.
            $this->cobros->iniciar_transaccion();

            // PASO 1: Cambiar el estado del préstamo a 2 (Anulado)
            // Necesitas tener esta función en tu modelo Prestamos
            $estado_actualizado = $this->cobros->cambiar_estado($id_prestamo, 2);

            if (!$estado_actualizado) {
                throw new Exception("Error al cambiar el estado del préstamo en la base de datos.");
            }

            // PASO 2: Actualizar el monto total de la caja
            // Necesitas tener esta función en tu modelo Cajas
            $caja_actualizada = $this->cobros->actualizar_monto_caja($caja_abierta->id_caja, $nuevo_saldo_caja);

            if (!$caja_actualizada) {
                throw new Exception("Error al actualizar el saldo de la caja.");
            }

            // PASO 3: Registrar el movimiento de caja (Ingreso)
            $concepto_movimiento = "DEVOLUCIÓN POR ANULACIÓN DE CRÉDITO #" . $id_prestamo;
            $movimiento_registrado = $this->cobros->registrar_movimiento_caja(
                $caja_abierta->id_caja,
                1, // O el ID del tipo de movimiento que uses
                $monto_capital,
                $hoy
            );

            if (!$movimiento_registrado) {
                throw new Exception("Error al registrar el movimiento en el historial de caja.");
            }

            // ==========================================
            // CONFIRMAR TRANSACCIÓN
            // ==========================================
            $this->cobros->confirmar_transaccion(); // COMMIT

            $res['codigo'] = 1;
            $res['mensaje'] = 'Crédito anulado exitosamente y dinero retornado a caja.';

        } catch (Throwable $e) {
            // Si CUALQUIER cosa falla arriba, deshacemos todo
            $this->cobros->revertir_transaccion(); // ROLLBACK

            // Opcional: Registrar el error en tu log
            // $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);

            $res['mensaje'] = $e->getMessage();
        }

        // Devolver la respuesta en JSON para el archivo JS
        echo json_encode($res);
    }

}