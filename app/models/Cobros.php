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
			$sql = 'SELECT SUM(pago_monto) AS total FROM pagos WHERE id_prestamo = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetchAll();
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
	public function prestamos_hoy(){
		try{
			$sql = 'SELECT SUM(pago_monto) AS total
					FROM pagos
					WHERE DATE(pago_fecha) = CURDATE();
					';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
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
			$sql = 'SELECT * from pagos_diarios as pd 
         			inner join prestamos as pr on pd.id_prestamos = pr.id_prestamos
         			inner join clientes as c on pr.id_cliente = c.id_cliente
         			where pd.pago_diario_fecha > curdate()';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return 0;
		}
	}

}