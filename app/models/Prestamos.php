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
	public function listar_x_id($id){
		try{
			$sql = 'select * from prestamos where id_prestamos = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function listar_prestamos(){
		try{
			$sql = 'select * from prestamos as p 
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
			$sql = 'select sum(pago_monto) as total from pagos where id_prestamo = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id_p]);
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}

}