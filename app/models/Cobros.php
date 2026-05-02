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
			$sql = 'SELECT * from pagos where id_prestamo = ?';
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
            $sql = 'SELECT * FROM prestamos p
               INNER JOIN clientes c ON p.id_cliente = c.id_cliente
               WHERE p.prestamo_prox_cobro <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
               AND p.prestamo_estado = 1
               ORDER BY p.prestamo_prox_cobro ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
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

}