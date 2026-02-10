<?php
class Ventas
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
	
    public function listar_ventas_x_mt($mt){
        try {
            $sql = 'SELECT * FROM ventas where venta_mt = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mt]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_ventas_x_id($id){
        try {
            $sql = 'SELECT * FROM ventas where id_venta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_pagos_ventas($id_v){
        try {
            $sql = 'SELECT sum(venta_pago_monto) as total FROM ventas_pagos where id_venta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_v]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_datos_pagos_ventas($id_v){
        try {
            $sql = 'SELECT * FROM ventas_pagos where id_venta = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_v]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function ventas_pendiente_pago(){
        try {
            $sql = 'SELECT * FROM ventas as v 
         			inner join clientes as c on v.id_cliente = c.id_cliente
         			where v.venta_estado = 2';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function ventas_realizadas(){
        try {
            $sql = 'SELECT * FROM ventas as v 
         			inner join clientes as c on v.id_cliente = c.id_cliente
         			where v.venta_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
}