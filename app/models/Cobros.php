<?php
class Cobros
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    // Inicia la transacción (Pone la base de datos en modo "espera")
    public function iniciar_transaccion() {
        $this->pdo->beginTransaction();
    }

    // Confirma y guarda todos los cambios definitivamente
    public function confirmar_transaccion() {
        $this->pdo->commit();
    }

    // Cancela y revierte todo si hubo algún error
    public function revertir_transaccion() {
        $this->pdo->rollBack();
    }

	public function listar_total_pagos_x_prestamo($id){
		try{
			$sql = 'SELECT SUM(pago_diario_monto) AS total FROM pagos_diarios WHERE id_prestamos = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}

    public function listar_total_pagos_reales_x_prestamo($id_prestamo){
        try{
            $sql = 'SELECT SUM(pago_monto) AS total FROM pagos WHERE id_prestamo = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }
	public function listar_decuentos_x_prestamo($id){
		try{
			$sql = 'SELECT SUM(descuento_monto) AS total FROM descuentos WHERE id_prestamo = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}

    public function listar_cuotas_x_prestamo($id){
        try{
            $sql = 'SELECT * FROM pagos_diarios WHERE id_prestamos =? and pago_diario_estado=1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function listar_cuotas_pendientes_ordenadas($id_prestamo){
        try{
            $sql = 'SELECT * FROM pagos_diarios
                WHERE id_prestamos = ? AND pago_diario_estado = 1
                ORDER BY pago_diario_fecha ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_cuota_individual($id){
        try{
            $sql = 'SELECT * FROM pagos_diarios WHERE id_pago_diario = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function cambiar_estado_cuota($id)
    {
        try {
                $sql_horario = 'UPDATE pagos_diarios SET
                            pago_diario_estado = 0
                        WHERE id_pago_diario = ?';
                $stm_horario = $this->pdo->prepare($sql_horario);
                $stm_horario->execute([$id]);
                return 1; // Actualización exitosa
        } catch (Throwable $e) {
            // Registrar el error
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2; // Error al procesar
        }
    }

    public function cambiar_estado_antiguo($id)
    {
        try {
            $sql_horario = 'UPDATE prestamos SET
                            prestamo_estado = 3 
                        WHERE id_prestamos = ?';
            $stm_horario = $this->pdo->prepare($sql_horario);
            $stm_horario->execute([$id]);
            return 1; // Actualización exitosa
        } catch (Throwable $e) {
            // Registrar el error
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2; // Error al procesar
        }
    }


	public function listar_garante($id){
		try{
			$sql = 'SELECT * from prestamos as p 
					inner join clientes as c on p.prestamo_garante = c.id_cliente
					where p.id_prestamos = ?
';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}
	public function listar_datos_decuentos_x_prestamo($id){
		try{
			$sql = 'SELECT * FROM descuentos WHERE id_prestamo = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}
	public function listar_pago_guardado_x_mt($id){
		try{
			$sql = 'SELECT * from pagos where pago_mt = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}
	public function listar_x_id($id){
		try{
			$sql = 'SELECT * from pagos as pa
         			inner join prestamos as pr on pa.id_prestamo = pr.id_prestamos
         			inner join clientes as cl on pr.id_cliente = cl.id_cliente
         			where pa.id_pago = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}
	public function listar_pagos_x_prestamo($id){
		try{
			$sql = 'SELECT p.*,
                        pd.pago_diario_fecha  AS fecha_cuota,
                        pd.pago_diario_monto  AS cuota_original,
                        u.usuario_nickname    AS pago_usuario,
                        mp.metodo_pago_nombre AS metodo_nombre
                    FROM pagos p
                    LEFT JOIN pagos_diarios  pd ON p.id_pago_diario = pd.id_pago_diario
                    LEFT JOIN usuarios       u  ON p.id_usuario     = u.id_usuario
                    LEFT JOIN metodos_pago   mp ON p.pago_metodo    = mp.id_metodo_pago
                    WHERE p.id_prestamo = ?
                    ORDER BY p.pago_fecha ASC';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}
    public function prestamos_hoy($fecha){
        try{
            // Usamos COALESCE para garantizar que si no hay pagos, devuelva 0 y no NULL
            $sql = 'SELECT COALESCE(SUM(pago_monto), 0) AS total
               FROM pagos
               WHERE DATE(pago_fecha) = ?';

            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);

            return $stm->fetch();

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            // Devolvemos un objeto con 'total' en 0 para que el controlador no se rompa
            return (object)['total' => 0];
        }
    }

    public function amortizaciones_hoy($fecha){
        try{
            $sql = 'SELECT COALESCE(SUM(pago_monto), 0) AS total
               FROM pagos
               WHERE DATE(pago_fecha) = ? AND id_pago_diario IS NULL';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return (object)['total' => 0];
        }
    }
	public function prestamos_hoy_fecha($fecha){
		try{
			$fecha = date('Y-m-d', strtotime($fecha));
			$sql = 'SELECT SUM(pago_monto) AS total
					FROM pagos
					WHERE DATE(pago_fecha) = ?;
					';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$fecha]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}
    public function listar_proximos_cobros(){
        try{
            // Solo créditos activos CON saldo pendiente: un préstamo cancelado
            // (saldo en cero) no debe seguir apareciendo en próximos cobros.
            //
            // Se traen además, en la misma consulta, los datos del cronograma que la
            // pantalla necesita para decidir el estado (vencido / mora / al día):
            //   fecha_fin_prestamo  = última cuota programada (fecha final real)
            //   proxima_cuota_*     = cuota pendiente más antigua
            //   cuotas_pendientes   = cuántas quedan por cobrar
            $sql = 'SELECT p.*, c.*,
                    (SELECT MAX(pd.pago_diario_fecha) FROM pagos_diarios pd
                        WHERE pd.id_prestamos = p.id_prestamos) AS fecha_fin_prestamo,
                    (SELECT MIN(pd.pago_diario_fecha) FROM pagos_diarios pd
                        WHERE pd.id_prestamos = p.id_prestamos AND pd.pago_diario_estado = 1) AS proxima_cuota_fecha,
                    (SELECT pd.pago_diario_monto FROM pagos_diarios pd
                        WHERE pd.id_prestamos = p.id_prestamos AND pd.pago_diario_estado = 1
                        ORDER BY pd.pago_diario_fecha ASC, pd.id_pago_diario ASC LIMIT 1) AS proxima_cuota_monto,
                    (SELECT COUNT(*) FROM pagos_diarios pd
                        WHERE pd.id_prestamos = p.id_prestamos AND pd.pago_diario_estado = 1) AS cuotas_pendientes
               FROM prestamos p
               INNER JOIN clientes c ON p.id_cliente = c.id_cliente
              where  p.prestamo_estado = 1
                AND p.prestamo_saldo_pagar > 0
                AND EXISTS (
                    SELECT 1 FROM pagos_diarios pd
                    WHERE pd.id_prestamos = p.id_prestamos AND pd.pago_diario_estado = 1
                )
               ORDER BY p.prestamo_prox_cobro ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_prestamo($id){
        try{
            $sql = 'SELECT * from prestamos 
					where id_prestamos = ?
';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function obtener_caja_abierta(){
        try{
            // Buscamos cajas con estado 1, las ordenamos de la más nueva a la más vieja,
            // y con LIMIT 1 nos traemos solo la última que se abrió.
            $sql = 'SELECT * FROM caja 
                WHERE estado_caja = 1 
                ORDER BY id_caja DESC 
                LIMIT 1';

            $stm = $this->pdo->prepare($sql);
            // Ya no necesitamos pasarle el [$id] al execute() porque no hay parámetros dinámicos
            $stm->execute();

            return $stm->fetch(); // Retorna el objeto/array con los datos de la caja

        } catch (Throwable $e){
            // Si hay error, lo guarda en tu log
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function cambiar_estado($id_prestamo, $nuevo_estado){
        try{
            // Preparamos la consulta UPDATE
            $sql = 'UPDATE prestamos 
                SET prestamo_estado = ? 
                WHERE id_prestamos = ?';

            $stm = $this->pdo->prepare($sql);

            // Ejecutamos pasando los parámetros en el orden exacto de los "?"
            if ($stm->execute([$nuevo_estado, $id_prestamo])) {
                return 1; // <-- Cambio solicitado: retorna 1 si tuvo éxito
            } else {
                return 2; // Retorna false si la ejecución falló silenciosamente
            }

        } catch (Throwable $e){
            // Si hay un error de base de datos, lo registramos en el log
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    public function actualizar_monto_caja($id_caja, $nuevo_saldo){
        try{
            // Preparamos la consulta UPDATE
            $sql = 'UPDATE caja
                SET monto_caja = ? 
                WHERE id_caja = ?';

            $stm = $this->pdo->prepare($sql);

            // Ejecutamos pasando los parámetros en el orden exacto de los "?"
            // Primero el nuevo saldo, luego el ID de la caja
            if ($stm->execute([$nuevo_saldo, $id_caja])) {
                return 1; // <-- Retorna 1 si la actualización tuvo éxito
            } else {
                return false; // Retorna false si la ejecución falló silenciosamente
            }

        } catch (Throwable $e){
            // Si hay un error de base de datos, lo registramos en el log
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return false;
        }
    }


    public function registrar_movimiento_caja($id_caja, $tipo, $monto, $fecha){
        try{
            // Preparamos la consulta INSERT
            $sql = 'INSERT INTO caja_movimientos (id_caja, caja_movimiento_tipo,caja_movimiento_monto,caja_movimiento_fecha) 
                VALUES (?, ?,?,?)';

            $stm = $this->pdo->prepare($sql);

            // Ejecutamos pasando los parámetros en el orden exacto de los "?"
            // Primero el ID de la caja, luego el saldo
            if ($stm->execute([$id_caja, $tipo, $monto, $fecha])) {
                return 1; // <-- Retorna 1 si la inserción tuvo éxito
            } else {
                return false; // Retorna false si la ejecución falló silenciosamente
            }

        } catch (Throwable $e){
            // Si hay un error de base de datos, lo registramos en el log
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return false;
        }
    }

    public function listar_proximo_pago_diario($id_prestamo){
        try{
            $sql = 'SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ? 
                AND pago_diario_estado = 1 
                ORDER BY pago_diario_fecha ASC
                LIMIT 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }

    public function listar_cuota_proxima($id){
        try{
            $sql = 'SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ? 
                AND pago_diario_estado = 1
                ORDER BY pago_diario_fecha ASC
                LIMIT 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }

    public function listar_descuentos_x_prestamo($id_prestamo) {
        try {
            $sql = "SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ? AND pago_diario_descuento_estado = 1 
                ORDER BY pago_diario_fecha ASC";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_usuario($id_prestamo) {
        try {
            $sql = "SELECT * FROM usuarios 
                WHERE id_usuario = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_bancos(){
        try{
            $sql = 'SELECT * FROM bancos';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([]);
            return $stm->fetchall();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function listar_metodos_de_pago(){
        try{
            $sql = 'SELECT * FROM metodos_pago';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([]);
            return $stm->fetchall();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function listar_metodo_pago($id) {
        try {
            $sql = 'SELECT * FROM metodos_pago WHERE id_metodo_pago = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }

    /**
     * Devuelve al cliente el capital prestado sumándolo a su línea de crédito y deja
     * constancia del movimiento en el historial (tabla clientes_linea_credito).
     *
     * @param int         $id_cliente
     * @param float       $monto_capital Capital del préstamo que se devuelve a la línea
     * @param string|null $usuario       Usuario que originó el movimiento (null = SISTEMA)
     * @param int|null    $id_prestamo   Préstamo que motiva la restauración (trazabilidad)
     * @param string      $motivo_tipo   'cancelacion' (saldo en cero) o 'anulacion'
     */
    public function restaurar_linea_credito($id_cliente, $monto_capital, $usuario = null, $id_prestamo = null, $motivo_tipo = 'cancelacion') {
        try {
            $monto_capital = round(floatval($monto_capital), 2);
            if ($monto_capital <= 0) return false;

            $referencia = $id_prestamo ? (' del préstamo #' . intval($id_prestamo)) : '';
            $motivo = ($motivo_tipo === 'anulacion')
                ? ('Restauración automática de línea de crédito por anulación' . $referencia)
                : ('Restauración automática de línea de crédito por cancelación' . $referencia);

            // Si ese préstamo ya devolvió su capital, no se vuelve a sumar
            if ($id_prestamo) {
                $stm_dup = $this->pdo->prepare('SELECT COUNT(*) FROM clientes_linea_credito
                        WHERE id_cliente = ? AND cliente_linea_tipo = ? AND cliente_linea_motivo = ?');
                $stm_dup->execute([$id_cliente, 'restauracion', $motivo]);
                if (intval($stm_dup->fetchColumn()) > 0) {
                    return true;
                }
            }

            $stm_actual = $this->pdo->prepare('SELECT cliente_credito FROM clientes WHERE id_cliente = ?');
            $stm_actual->execute([$id_cliente]);
            $credito_anterior = floatval($stm_actual->fetchColumn());
            $credito_nuevo    = round($credito_anterior + $monto_capital, 2);

            // Sumamos el capital al saldo actual de su línea
            $stm = $this->pdo->prepare('UPDATE clientes SET cliente_credito = ? WHERE id_cliente = ?');
            if (!$stm->execute([$credito_nuevo, $id_cliente])) return false;

            // Trazabilidad: mismo historial que usan los ajustes manuales
            $stm_hist = $this->pdo->prepare('INSERT INTO clientes_linea_credito
                    (id_cliente, cliente_linea_monto, cliente_linea_motivo, cliente_linea_estado,
                     cliente_linea_tipo, cliente_linea_credito_anterior, cliente_linea_credito_nuevo,
                     cliente_linea_usuario, cliente_linea_fecha)
                    VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?)');
            $stm_hist->execute([
                $id_cliente,
                $monto_capital,
                $motivo,
                'restauracion',
                $credito_anterior,
                $credito_nuevo,
                ($usuario !== null && $usuario !== '') ? $usuario : 'SISTEMA',
                date('Y-m-d H:i:s')
            ]);

            return true;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return false;
        }
    }

    public function contar_cuotas_pendientes($id_prestamo){
        try{
            // Ajusta los nombres de tu tabla de cuotas si es necesario
            $sql = "SELECT COUNT(*) as pendientes FROM pagos_diarios 
                WHERE id_prestamos = ? AND pago_diario_estado = 1"; // Asumo que estado 0 = no pagado
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            $resultado = $stm->fetch();
            return $resultado->pendientes;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    public function traer_fecha_fin_prestamo($id_prestamo){
        try {
            // Usamos MAX() para obtener la fecha de la última cuota programada
            $sql = "SELECT MAX(pago_diario_fecha) as fecha_vencimiento 
                FROM pagos_diarios 
                WHERE id_prestamos = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            $resultado = $stm->fetch();

            return $resultado ? $resultado->fecha_vencimiento : null;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }

    /**
     * Cuentas o titulares receptores usados antes (dato interno de conciliación).
     * Alimenta el datalist de sugerencias para que el cajero no escriba variantes
     * del mismo nombre ("Yape Ana" / "yape ana") y los reportes agrupen bien.
     */
    public function listar_cuentas_receptoras(){
        try{
            $sql = 'SELECT pago_cuenta_receptora AS cuenta, COUNT(*) AS usos
                    FROM pagos
                    WHERE pago_cuenta_receptora IS NOT NULL
                      AND TRIM(pago_cuenta_receptora) <> ""
                    GROUP BY pago_cuenta_receptora
                    ORDER BY usos DESC, cuenta ASC
                    LIMIT 50';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    /**
     * Historial de pagos del préstamo con el saldo que quedó después de cada uno.
     *
     * La BD no guarda un saldo por pago, así que se reconstruye hacia atrás desde el
     * saldo actual del préstamo: el último registro siempre coincide con el saldo real.
     * Una cuota descuenta su monto; una amortización descuenta capital + interés proporcional.
     *
     * Es la única fuente de esta columna: la usan cobros/pagar.php y la constancia PDF.
     */
    public function historial_pagos_con_saldo($id_prestamo){
        try{
            $pagos = $this->listar_pagos_x_prestamo($id_prestamo);
            if (!is_array($pagos)) return [];

            $stm = $this->pdo->prepare('SELECT prestamo_saldo_pagar, prestamo_interes
                                        FROM prestamos WHERE id_prestamos = ?');
            $stm->execute([$id_prestamo]);
            $p = $stm->fetch();
            if (empty($p)) return [];

            $tasa       = floatval($p->prestamo_interes);
            $saldo_iter = floatval($p->prestamo_saldo_pagar);

            for ($i = count($pagos) - 1; $i >= 0; $i--) {
                $pagos[$i]->saldo_restante = max(0, round($saldo_iter, 2));

                $monto           = floatval($pagos[$i]->pago_monto);
                $es_amortizacion = empty($pagos[$i]->fecha_cuota);
                $saldo_iter     += $es_amortizacion ? $monto * (1 + $tasa / 100) : $monto;
            }

            return $pagos;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    /**
     * Datos consolidados del crédito para la sección "Información del crédito".
     * Es la ÚNICA fuente de estos números: la usan cobros/pagar.php, cobros/pagos.php
     * y el voucher PDF, para que las tres pantallas muestren siempre lo mismo.
     */
    public function resumen_credito($id_prestamo){
        try{
            $sql = 'SELECT * FROM prestamos WHERE id_prestamos = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            $p = $stm->fetch();
            if (empty($p)) return null;

            // Movimientos de pago registrados
            $stm_pag = $this->pdo->prepare('SELECT
                        COUNT(*)                              AS pagos_realizados,
                        COUNT(DISTINCT pago_mt)               AS operaciones,
                        COALESCE(SUM(pago_monto), 0)          AS total_pagado,
                        COALESCE(SUM(pago_descuento_monto),0) AS total_descuento,
                        MAX(pago_fecha)                       AS ultimo_pago
                    FROM pagos WHERE id_prestamo = ?');
            $stm_pag->execute([$id_prestamo]);
            $pag = $stm_pag->fetch();

            // Cronograma de cuotas
            $stm_cuo = $this->pdo->prepare('SELECT
                        COUNT(*) AS cuotas_total,
                        SUM(CASE WHEN pago_diario_estado = 1 THEN 1 ELSE 0 END) AS cuotas_pendientes,
                        MIN(pago_diario_fecha) AS primera_cuota,
                        MAX(pago_diario_fecha) AS ultima_cuota,
                        MIN(CASE WHEN pago_diario_estado = 1 THEN pago_diario_fecha END) AS proxima_cuota
                    FROM pagos_diarios WHERE id_prestamos = ?');
            $stm_cuo->execute([$id_prestamo]);
            $cuo = $stm_cuo->fetch();

            $capital       = floatval($p->prestamo_monto);
            $interes_pct   = floatval($p->prestamo_interes);
            $interes_monto = floatval($p->prestamo_monto_interes);
            if ($interes_monto <= 0 && $interes_pct > 0) {
                $interes_monto = round($capital * $interes_pct / 100, 2);
            }

            $saldo          = floatval($p->prestamo_saldo_pagar);
            $estado         = intval($p->prestamo_estado);
            $cuotas_total   = intval($cuo->cuotas_total ?? 0);
            $cuotas_pend    = intval($cuo->cuotas_pendientes ?? 0);
            $vencimiento    = $cuo->ultima_cuota ?? null;

            // Cancelado: saldo en cero, o estado Cancelado / Antiguo cancelado
            $cancelado = ($saldo <= 0) || in_array($estado, [2, 4]);
            $anulado   = ($estado === 5);
            // Vencido: pasó la última fecha del cronograma y todavía debe
            $vencido   = !$cancelado && !$anulado && !empty($vencimiento)
                         && strtotime(date('Y-m-d')) > strtotime($vencimiento);

            if ($anulado)        $etiqueta = 'Anulado';
            elseif ($cancelado)  $etiqueta = 'Cancelado';
            elseif ($vencido)    $etiqueta = 'Vencido';
            else                 $etiqueta = 'Activo';

            return (object) array(
                'id_prestamo'       => intval($p->id_prestamos),
                'capital'           => $capital,
                'interes_pct'       => $interes_pct,
                'interes_monto'     => $interes_monto,
                'total_credito'     => round($capital + $interes_monto, 2),
                'tipo_pago'         => $p->prestamo_tipo_pago,
                'fecha_emision'     => $p->prestamo_fecha_emision ?: null,
                'fecha_inicio'      => $p->prestamo_fecha_inicio ?: ($cuo->primera_cuota ?? null),
                'primer_cobro'      => $cuo->primera_cuota ?? null,
                'fecha_vencimiento' => $vencimiento,
                'proximo_pago'      => $cancelado ? null : ($cuo->proxima_cuota ?? null),
                'pagos_realizados'  => intval($pag->pagos_realizados ?? 0),
                'operaciones'       => intval($pag->operaciones ?? 0),
                'ultimo_pago'       => $pag->ultimo_pago ?? null,
                'cuotas_total'      => $cuotas_total,
                'cuotas_pagadas'    => max(0, $cuotas_total - $cuotas_pend),
                'cuotas_pendientes' => $cuotas_pend,
                'total_pagado'      => round(floatval($pag->total_pagado ?? 0), 2),
                'total_descuento'   => round(floatval($pag->total_descuento ?? 0), 2),
                'saldo_pendiente'   => max(0, round($saldo, 2)),
                'estado'            => $estado,
                'estado_etiqueta'   => $etiqueta,
                'esta_cancelado'    => $cancelado,
                'esta_vencido'      => $vencido,
                'esta_anulado'      => $anulado
            );
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }

    /**
     * Cierra de golpe todas las cuotas que sigan pendientes en un préstamo.
     * Se usa cuando el saldo llega a cero: el crédito queda cancelado y no debe
     * arrastrar cuotas abiertas que lo devuelvan a la lista de próximos cobros.
     */
    public function cerrar_cuotas_pendientes($id_prestamo){
        try{
            $sql = 'UPDATE pagos_diarios SET pago_diario_estado = 0
                    WHERE id_prestamos = ? AND pago_diario_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->rowCount();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }

    /**
     * Todas las cuotas del préstamo (pagadas y pendientes) en orden cronológico.
     * Sirve para numerarlas (Cuota 1, 2, 3...) tanto en pantalla como en el recibo.
     */
    public function listar_todas_las_cuotas_x_prestamo($id_prestamo){
        try{
            $sql = 'SELECT * FROM pagos_diarios
                    WHERE id_prestamos = ?
                    ORDER BY pago_diario_fecha ASC, id_pago_diario ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    /**
     * Cuotas PENDIENTES del préstamo cuyo id esté dentro de $ids.
     * Se usa para validar que cada cuota enviada desde la pantalla de pago
     * pertenezca realmente al préstamo y siga sin pagarse.
     */
    public function listar_cuotas_pendientes_x_ids($id_prestamo, array $ids){
        try{
            $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
            if (empty($ids)) return [];

            $marcas = implode(',', array_fill(0, count($ids), '?'));
            $sql = 'SELECT * FROM pagos_diarios
                    WHERE id_prestamos = ?
                      AND pago_diario_estado = 1
                      AND id_pago_diario IN (' . $marcas . ')
                    ORDER BY pago_diario_fecha ASC, id_pago_diario ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array_merge([$id_prestamo], $ids));
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    /**
     * Todos los pagos que comparten el mismo identificador de operación (pago_mt).
     * Un cobro de varias cuotas genera una fila por cuota, todas con el mismo pago_mt:
     * esto permite emitir un solo recibo por operación.
     */
    public function listar_pagos_x_mt($mt){
        try{
            $sql = 'SELECT p.*,
                        pd.pago_diario_fecha AS fecha_cuota,
                        pd.pago_diario_monto AS cuota_original
                    FROM pagos p
                    LEFT JOIN pagos_diarios pd ON p.id_pago_diario = pd.id_pago_diario
                    WHERE p.pago_mt = ?
                    ORDER BY pd.pago_diario_fecha ASC, p.id_pago ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mt]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

}