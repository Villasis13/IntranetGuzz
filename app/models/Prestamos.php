<?php
class Prestamos
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

	public function listar_x_mt($mt){
		try{
			$sql = 'select * from prestamos where prestamo_mt = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$mt]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
    public function listar_x_id($id_prestamo) {
        try {
            // Hacemos el LEFT JOIN apuntando a la tabla clientes como "g" (garante)
            $sql = "SELECT p.*, 
                       g.cliente_nombre AS garante_nombre, 
                       g.cliente_apellido_paterno AS garante_paterno, 
                       g.cliente_apellido_materno AS garante_materno
                FROM prestamos p
                LEFT JOIN clientes g ON p.prestamo_garante = g.id_cliente
                WHERE p.id_prestamos = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }
	public function listar_garante_prestamo($id){
		try{
			$sql = 'select * from prestamos as p 
         			inner join clientes as c on p.prestamo_garante = c.id_cliente
         			where id_prestamos = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function listar_prestamos_antiguos(){
		try{
			$sql = 'select * from prestamos as p
         			inner join clientes as c on p.id_cliente = c.id_cliente
         			where p.prestamo_estado = 3' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}

    public function duplicidad_garante($id){
        try{
            $sql = 'select * from prestamos where prestamo_garante = ? and prestamo_estado=1' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

	public function listar_prestamos_antiguos_pagos(){
		try{
			$sql = 'select * from pagos as pa
    				inner join prestamos as p on pa.id_prestamo = p.id_prestamos
         			inner join clientes as c on p.id_cliente = c.id_cliente
         			where p.prestamo_estado = 3' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
    public function prestamos_hoy($fecha){
        try{
            // Traemos la cantidad de préstamos y la suma de sus montos en 1 sola consulta
            $sql = 'SELECT
                    COUNT(id_prestamos) as cantidad,
                    COALESCE(SUM(prestamo_monto), 0) as total
                FROM prestamos
                WHERE DATE(prestamo_fecha_sistema) = ? and prestamo_estado= 1 ';

            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);

            // Usamos fetch() porque la consulta agrupa todo en una sola fila
            return $stm->fetch();

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            // Retornamos un objeto por defecto para que la vista no se rompa si hay error
            return (object)['cantidad' => 0, 'total' => 0];
        }
    }
	public function egresos_hoy($fecha){
		try{
			$sql = 'select sum(prestamo_monto) as total from prestamos where prestamo_fecha = ? ' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$fecha]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function egresos_hoy_fecha($fecha){
		try{
			$fecha = date('Y-m-d', strtotime($fecha));
			$sql = 'select sum(prestamo_monto) as total from prestamos where prestamo_fecha = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$fecha]);
//			return $stm->fetch();
			return $stm->fetch() ?: ['total' => 0];
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function listar_prestamos_antiguos_cancelados(){
		try{
			$sql = 'select * from prestamos as p
         			inner join clientes as c on p.id_cliente = c.id_cliente
         			where p.prestamo_estado = 4' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function listar_x_id_cliente($id){
		try{
			$sql = 'select * from prestamos as p
         			inner join clientes as c on p.prestamo_garante = c.id_cliente
         			where p.id_cliente = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}


    public function listar_prestamos_cliente($id){
        try{
            $sql = 'select p.*,c.*,cl.cliente_nombre as nombre_garante,cl.cliente_apellido_paterno as apellido_garante from prestamos as p
         			inner join clientes as c on p.id_cliente = c.id_cliente
                    left join clientes as cl on p.prestamo_garante = cl.id_cliente
         			where p.id_cliente = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


	public function listar_prestamos(){
		try{
			// Se agregan los datos del cronograma que la tabla necesita para el bloque
			// de fechas: fin real del préstamo y próxima cuota realmente pendiente.
			$sql = 'select p.*, c.*,
						(select max(pd.pago_diario_fecha) from pagos_diarios pd
							where pd.id_prestamos = p.id_prestamos) as fecha_fin_prestamo,
						(select min(pd.pago_diario_fecha) from pagos_diarios pd
							where pd.id_prestamos = p.id_prestamos and pd.pago_diario_estado = 1) as proxima_cuota_fecha,
						(select count(*) from pagos_diarios pd
							where pd.id_prestamos = p.id_prestamos and pd.pago_diario_estado = 1) as cuotas_pendientes,
						(select count(*) from pagos_diarios pd
							where pd.id_prestamos = p.id_prestamos and pd.pago_diario_estado <> 2) as cuotas_total
					from prestamos as p
					inner join clientes as c on p.id_cliente = c.id_cliente';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function listar_total_pagos_x_prestamo($id_p){
		try{
			$sql = 'select sum(pago_diario_monto) as total from pagos_diarios where id_prestamos = ? and pago_diario_estado=0 ';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id_p]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}

    public function listar_total_pagos_contados($id_p){
        try{
            $sql = 'select count(pago_diario_monto) as cuenta from pagos_diarios where id_prestamos = ? and pago_diario_estado=0 ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_p]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_pagos_desde($fecha_apertura) {
        try {
            $sql = "SELECT p.*, c.cliente_nombre, c.cliente_apellido_paterno, mp.metodo_pago_nombre,
                CASE
                    WHEN p.pago_monto_recibido IS NOT NULL AND p.pago_monto_recibido > 0
                    THEN p.pago_monto_recibido - COALESCE(p.pago_monto_vuelto, 0)
                    ELSE p.pago_monto
                END AS ingreso_display
                FROM pagos p
                INNER JOIN prestamos pr ON p.id_prestamo = pr.id_prestamos
                INNER JOIN clientes c ON pr.id_cliente = c.id_cliente
                LEFT JOIN metodos_pago mp ON mp.id_metodo_pago = p.pago_metodo
                WHERE p.pago_fecha >= ? AND p.id_pago_diario IS NOT NULL";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_apertura]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_amortizaciones_desde($fecha_apertura) {
        try {
            $sql = "SELECT p.*, c.cliente_nombre, c.cliente_apellido_paterno, mp.metodo_pago_nombre,
                pr.prestamo_estado,
                CASE
                    WHEN p.pago_monto_recibido IS NOT NULL AND p.pago_monto_recibido > 0
                    THEN p.pago_monto_recibido - COALESCE(p.pago_monto_vuelto, 0)
                    ELSE p.pago_monto
                END AS ingreso_display
                FROM pagos p
                INNER JOIN prestamos pr ON p.id_prestamo = pr.id_prestamos
                INNER JOIN clientes c ON pr.id_cliente = c.id_cliente
                LEFT JOIN metodos_pago mp ON mp.id_metodo_pago = p.pago_metodo
                WHERE p.pago_fecha >= ? AND p.id_pago_diario IS NULL";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_apertura]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_prestamos_desde($fecha_apertura) {
        try {
            // CORRECCIÓN: Ahora filtramos estrictamente por prestamo_fecha_sistema
            $sql = "SELECT p.*, c.cliente_nombre, c.cliente_apellido_paterno 
            FROM prestamos p
            INNER JOIN clientes c ON p.id_cliente = c.id_cliente
            WHERE p.prestamo_fecha_sistema >= ?";

            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha_apertura]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_ingresos_manuales_desde($id_caja) {
        try {
            $sql = "SELECT * FROM caja_movimientos
                WHERE id_caja = ? AND caja_movimiento_tipo = 1";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_anulaciones_prestamos_desde($id_caja) {
        try {
            $sql = "SELECT * FROM caja_movimientos
                WHERE id_caja = ? AND caja_movimiento_tipo = 3";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_caja]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


    public function listar_fecha_vencimiento_prestamo($id_prestamo) {
        try {
            $sql = "SELECT MAX(pago_diario_fecha) AS fecha_vencimiento FROM pagos_diarios WHERE id_prestamos = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
        }
    }

    public function listar_cuotas_x_id_prestamo($id_prestamo) {
        try {
            // Traemos movimientos vinculados a este ID de caja que sean ingresos (tipo 1)
            // Y descartamos los que son pagos de cuotas para no duplicar (asumiendo que los manuales tienen descripción)
            $sql = "SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ? AND id_prestamo_renovacion IS NULL";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    // =====================================================================
    // PRÉSTAMOS EN RECUPERACIÓN (estado 3) / RECUPERACIÓN CANCELADA (estado 4)
    // =====================================================================

    // Frecuencias de abono permitidas en un acuerdo => intervalo para la próxima fecha
    const FRECUENCIAS_ACUERDO = array(
        'Diaria' => 'P1D', 'Semanal' => 'P7D', 'Quincenal' => 'P15D', 'Mensual' => 'P1M', 'Libre' => null,
    );

    // Listado con totales: deuda original, pagado, saldo y acuerdo vigente
    public function listar_prestamos_recuperacion($estado){
        try{
            $sql = 'SELECT p.*, c.cliente_dni, c.cliente_nombre, c.cliente_apellido_paterno,
                           c.cliente_apellido_materno, c.cliente_celular,
                           (SELECT COALESCE(SUM(pg.pago_monto), 0) FROM pagos pg WHERE pg.id_prestamo = p.id_prestamos) AS total_pagado,
                           (SELECT MAX(pg.pago_fecha) FROM pagos pg WHERE pg.id_prestamo = p.id_prestamos) AS ultimo_pago,
                           a.prestamo_acuerdo_monto, a.prestamo_acuerdo_abono_sugerido, a.prestamo_acuerdo_frecuencia,
                           a.prestamo_acuerdo_proxima_fecha, a.prestamo_acuerdo_fecha
                    FROM prestamos p
                    INNER JOIN clientes c ON c.id_cliente = p.id_cliente
                    LEFT JOIN prestamos_acuerdos a ON a.id_prestamos = p.id_prestamos AND a.prestamo_acuerdo_estado = 1
                    WHERE p.prestamo_estado = ?
                    ORDER BY a.prestamo_acuerdo_proxima_fecha IS NULL, a.prestamo_acuerdo_proxima_fecha, p.id_prestamos DESC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$estado]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_acuerdos_x_prestamo($id_prestamo){
        try{
            $sql = 'SELECT a.*, u.usuario_nickname
                    FROM prestamos_acuerdos a
                    LEFT JOIN usuarios u ON u.id_usuario = a.id_usuario
                    WHERE a.id_prestamos = ?
                    ORDER BY a.prestamo_acuerdo_fecha, a.id_prestamo_acuerdo';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    // Todos los pagos del préstamo (cuotas del cronograma original y abonos), en orden
    public function listar_pagos_recuperacion($id_prestamo){
        try{
            $sql = 'SELECT pg.*, mp.metodo_pago_nombre, u.usuario_nickname
                    FROM pagos pg
                    LEFT JOIN metodos_pago mp ON mp.id_metodo_pago = pg.pago_metodo
                    LEFT JOIN usuarios u ON u.id_usuario = pg.id_usuario
                    WHERE pg.id_prestamo = ?
                    ORDER BY pg.pago_fecha, pg.id_pago';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    // Historial de un préstamo en recuperación: acuerdos y pagos en orden, con el saldo que quedó
    // después de cada uno. El saldo se reconstruye hacia atrás desde el saldo guardado del préstamo,
    // así el último movimiento siempre coincide con lo que falta pagar. Lo usan la ficha de
    // recuperación y el recibo de pago, para que ambos muestren las mismas cifras.
    public function linea_tiempo_recuperacion($prestamo){
        $acuerdos = $this->listar_acuerdos_x_prestamo($prestamo->id_prestamos);
        $pagos = $this->listar_pagos_recuperacion($prestamo->id_prestamos);

        // Desde cuándo está en recuperación (préstamos convertidos antes de guardar esta fecha: primer acuerdo)
        $inicio = $prestamo->prestamo_fecha_recuperacion
            ?: (!empty($acuerdos) ? $acuerdos[0]->prestamo_acuerdo_fecha : null);

        $eventos = array();
        foreach ($acuerdos as $a) {
            $eventos[] = array('tipo' => 'acuerdo', 'fecha' => $a->prestamo_acuerdo_fecha, 'orden' => 0, 'dato' => $a);
        }
        $total_pagado = 0;
        $total_abonos = 0;
        foreach ($pagos as $pg) {
            $es_abono = $pg->id_pago_diario === null && $inicio && $pg->pago_fecha >= $inicio;
            $total_pagado += floatval($pg->pago_monto);
            if ($es_abono) $total_abonos += floatval($pg->pago_monto);
            $eventos[] = array('tipo' => $es_abono ? 'abono' : ($pg->id_pago_diario === null ? 'amortizacion' : 'cuota'),
                               'fecha' => $pg->pago_fecha, 'orden' => 1, 'dato' => $pg);
        }
        usort($eventos, function ($x, $y) {
            return strcmp($x['fecha'], $y['fecha']) ?: ($x['orden'] <=> $y['orden']);
        });
        $saldo_iter = round(floatval($prestamo->prestamo_saldo_pagar), 2);
        for ($k = count($eventos) - 1; $k >= 0; $k--) {
            $eventos[$k]['saldo'] = $saldo_iter;
            $saldo_iter = $eventos[$k]['tipo'] === 'acuerdo'
                ? round(floatval($eventos[$k]['dato']->prestamo_acuerdo_deuda_anterior), 2)
                : round($saldo_iter + floatval($eventos[$k]['dato']->pago_monto), 2);
        }

        $acuerdo_vigente = null;
        foreach ($acuerdos as $a) {
            if (intval($a->prestamo_acuerdo_estado) === 1) $acuerdo_vigente = $a;
        }

        return array(
            'acuerdos'        => $acuerdos,
            'acuerdo_vigente' => $acuerdo_vigente,
            'eventos'         => $eventos,
            'inicio'          => $inicio,
            'total_pagado'    => round($total_pagado, 2),
            'total_abonos'    => round($total_abonos, 2),
        );
    }

    // Pasa un préstamo activo con saldo a recuperación. Devuelve 1 si cambió, 4 si no corresponde.
    public function pasar_a_recuperacion($id_prestamo){
        try{
            $stm = $this->pdo->prepare('UPDATE prestamos
                                        SET prestamo_estado = 3, prestamo_fecha_recuperacion = ?
                                        WHERE id_prestamos = ? AND prestamo_estado = 1 AND prestamo_saldo_pagar > 0');
            $stm->execute([date('Y-m-d H:i:s'), $id_prestamo]);
            return $stm->rowCount() > 0 ? 1 : 4;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    // Registra un nuevo acuerdo; el anterior queda como historial y el saldo pasa a ser el monto acordado.
    // Devuelve 1 = ok, 2 = error, 4 = el préstamo no está en recuperación.
    public function guardar_acuerdo_recuperacion($id_prestamo, $datos, $id_usuario){
        try{
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare('SELECT * FROM prestamos WHERE id_prestamos = ? FOR UPDATE');
            $stm->execute([$id_prestamo]);
            $prestamo = $stm->fetch();
            if (!$prestamo || intval($prestamo->prestamo_estado) !== 3) {
                $this->pdo->rollBack();
                return 4;
            }

            $this->pdo->prepare('UPDATE prestamos_acuerdos SET prestamo_acuerdo_estado = 0
                                 WHERE id_prestamos = ? AND prestamo_acuerdo_estado = 1')
                ->execute([$id_prestamo]);

            $this->pdo->prepare('INSERT INTO prestamos_acuerdos (id_prestamos, prestamo_acuerdo_deuda_anterior,
                                        prestamo_acuerdo_monto, prestamo_acuerdo_abono_sugerido, prestamo_acuerdo_frecuencia,
                                        prestamo_acuerdo_proxima_fecha, prestamo_acuerdo_observacion, prestamo_acuerdo_fecha,
                                        id_usuario, prestamo_acuerdo_estado)
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)')
                ->execute([
                    $id_prestamo,
                    round(floatval($prestamo->prestamo_saldo_pagar), 2),
                    $datos['monto'],
                    $datos['abono_sugerido'],
                    $datos['frecuencia'],
                    $datos['proxima_fecha'],
                    $datos['observacion'],
                    date('Y-m-d H:i:s'),
                    $id_usuario,
                ]);

            // Nuevo saldo = monto acordado. Un descuento se aplica primero al interés y luego al capital;
            // un recargo se suma como interés. Queda en el historial de movimientos.
            $cobros = new Cobros();
            $s = $cobros->saldos_prestamo($id_prestamo);
            $diferencia = round($datos['monto'] - ($s->capital + $s->interes), 2);
            if ($diferencia < 0) {
                $d_interes = -min($s->interes, -$diferencia);
                $d_capital = round($diferencia - $d_interes, 2);
            } else {
                $d_interes = $diferencia;
                $d_capital = 0;
            }
            $cobros->registrar_movimiento($id_prestamo, 'acuerdo', $d_capital, $d_interes,
                'Acuerdo: debía S/ ' . number_format($s->capital + $s->interes, 2) . ', acordado S/ ' . number_format($datos['monto'], 2),
                null, $id_usuario);

            if ($datos['proxima_fecha']) {
                $this->pdo->prepare('UPDATE prestamos SET prestamo_prox_cobro = ? WHERE id_prestamos = ?')
                    ->execute([$datos['proxima_fecha'], $id_prestamo]);
            }

            $this->pdo->commit();
            return 1;
        } catch (Throwable $e){
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }

    // Registra un abono parcial: entra a la caja abierta, baja el saldo y, si llega a cero,
    // el préstamo pasa a "Recuperación cancelada" (estado 4).
    // Devuelve array(code, id_pago): 1 = ok, 2 = error, 3 = caja cerrada,
    // 4 = el préstamo no está en recuperación, 5 = el abono supera el saldo.
    public function guardar_abono_recuperacion($id_prestamo, $pago, $id_usuario){
        try{
            $this->pdo->beginTransaction();
            $stm = $this->pdo->prepare('SELECT * FROM prestamos WHERE id_prestamos = ? FOR UPDATE');
            $stm->execute([$id_prestamo]);
            $prestamo = $stm->fetch();
            if (!$prestamo || intval($prestamo->prestamo_estado) !== 3) {
                $this->pdo->rollBack();
                return array(4, 0);
            }
            $saldo = round(floatval($prestamo->prestamo_saldo_pagar), 2);
            if ($pago['monto'] > $saldo) {
                $this->pdo->rollBack();
                return array(5, 0);
            }
            $caja = $this->pdo->query('SELECT id_caja, estado_caja FROM caja ORDER BY id_caja DESC LIMIT 1 FOR UPDATE')->fetch();
            if (!$caja || intval($caja->estado_caja) !== 1) {
                $this->pdo->rollBack();
                return array(3, 0);
            }

            $ahora = date('Y-m-d H:i:s');
            $this->pdo->prepare('INSERT INTO pagos (id_prestamo, id_pago_diario, id_cliente, id_usuario, pago_monto,
                                        pago_metodo, pago_fecha, pago_estado, pago_mt, pago_descuento_estado,
                                        pago_descuento_monto, pago_operacion, pago_cuenta_receptora, id_banco, pago_observacion)
                                 VALUES (?, NULL, ?, ?, ?, ?, ?, 1, ?, 0, 0, ?, ?, ?, ?)')
                ->execute([
                    $id_prestamo, $prestamo->id_cliente, $id_usuario, $pago['monto'],
                    $pago['metodo'], $ahora, microtime(true),
                    $pago['operacion'], $pago['cuenta_receptora'], $pago['banco'], $pago['observacion'],
                ]);
            $id_pago = (int)$this->pdo->lastInsertId();

            $this->pdo->prepare('UPDATE caja SET monto_caja = monto_caja + ? WHERE id_caja = ?')
                ->execute([$pago['monto'], $caja->id_caja]);

            $saldos = (new Cobros())->aplicar_pago($id_prestamo, $id_pago, $pago['monto'], 'abono_recuperacion',
                'Abono de recuperación', $id_usuario);
            $nuevo_saldo = $saldos->saldo;
            if ($nuevo_saldo <= 0) {
                $this->pdo->prepare('UPDATE prestamos SET prestamo_estado = 4 WHERE id_prestamos = ?')
                    ->execute([$id_prestamo]);
                $this->pdo->prepare('UPDATE prestamos_acuerdos SET prestamo_acuerdo_proxima_fecha = NULL
                                     WHERE id_prestamos = ? AND prestamo_acuerdo_estado = 1')
                    ->execute([$id_prestamo]);
            } else {
                // La próxima fecha del acuerdo avanza según su frecuencia, contada desde hoy
                $stm = $this->pdo->prepare('SELECT * FROM prestamos_acuerdos WHERE id_prestamos = ? AND prestamo_acuerdo_estado = 1');
                $stm->execute([$id_prestamo]);
                $acuerdo = $stm->fetch();
                $intervalo = $acuerdo ? (self::FRECUENCIAS_ACUERDO[$acuerdo->prestamo_acuerdo_frecuencia] ?? null) : null;
                if ($intervalo) {
                    $proxima = (new DateTime(date('Y-m-d')))->add(new DateInterval($intervalo))->format('Y-m-d');
                    $this->pdo->prepare('UPDATE prestamos_acuerdos SET prestamo_acuerdo_proxima_fecha = ? WHERE id_prestamo_acuerdo = ?')
                        ->execute([$proxima, $acuerdo->id_prestamo_acuerdo]);
                    $this->pdo->prepare('UPDATE prestamos SET prestamo_prox_cobro = ? WHERE id_prestamos = ?')
                        ->execute([$proxima, $id_prestamo]);
                }
            }

            $this->pdo->commit();
            return array(1, $id_pago);
        } catch (Throwable $e){
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return array(2, 0);
        }
    }

}
