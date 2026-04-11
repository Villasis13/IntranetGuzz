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
    public function prestamos_hoy($fecha){
        try{
            // Traemos la cantidad de préstamos y la suma de sus montos en 1 sola consulta
            $sql = 'SELECT 
                    COUNT(id_prestamos) as cantidad, 
                    COALESCE(SUM(prestamo_monto), 0) as total 
                FROM prestamos 
                WHERE DATE(prestamo_fecha) = ?';

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

    public function listar_pagos_desde($fecha_apertura) {
        try {
            // Hacemos INNER JOIN para traer el nombre del cliente
            $sql = "SELECT p.*, c.cliente_nombre, c.cliente_apellido_paterno 
                FROM pagos p
                INNER JOIN prestamos pr ON p.id_prestamo = pr.id_prestamos
                INNER JOIN clientes c ON pr.id_cliente = c.id_cliente
                WHERE p.pago_fecha >= ?";
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
            $sql = "SELECT p.*, c.cliente_nombre, c.cliente_apellido_paterno 
                FROM prestamos p
                INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                WHERE p.prestamo_fecha >= ?";
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
            // Traemos movimientos vinculados a este ID de caja que sean ingresos (tipo 1)
            // Y descartamos los que son pagos de cuotas para no duplicar (asumiendo que los manuales tienen descripción)
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


    public function listar_cuotas_x_id_prestamo($id_prestamo) {
        try {
            // Traemos movimientos vinculados a este ID de caja que sean ingresos (tipo 1)
            // Y descartamos los que son pagos de cuotas para no duplicar (asumiendo que los manuales tienen descripción)
            $sql = "SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_prestamo]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

}