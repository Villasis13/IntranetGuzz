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
	public function prestamos_hoy(){
		try{
			$sql = 'select * from prestamos where prestamo_fecha = CURDATE()' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
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

}