<?php
class Cobros
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
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
			$sql = 'SELECT SUM(pago_monto) AS total
					FROM pagos
					WHERE DATE(pago_fecha) = ?
					';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$fecha]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
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
			$sql = 'SELECT * from pagos_diarios pd
         			inner join prestamos p on pd.id_prestamos = p.id_prestamos
                    inner join clientes c on p.id_cliente = c.id_cliente
         			where pd.pago_diario_fecha <= date_add(CURDATE(),interval 1 day ) and pd.pago_diario_estado=1 and p.prestamo_estado=1
         			order by pd.pago_diario_fecha desc';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}

}