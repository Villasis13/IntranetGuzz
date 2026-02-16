<?php
class Reporte
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function reporte_hoy($dia){
        try{
            $sql = 'select * from pagos p 
         inner join prestamos pr on p.id_prestamo=pr.id_prestamos
         inner join clientes cl on pr.id_cliente=cl.id_cliente
        where p.pago_fecha=? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$dia]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function reporte_hoy_egresos($dia){
        try{
            $sql = 'select * from prestamos pr 
         inner join clientes cl on pr.id_cliente=cl.id_cliente
         where date(pr.prestamo_fecha)=? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$dia]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function reporte_mes($mes){
        try{
            $sql = 'select * from pagos p 
         inner join prestamos pr on p.id_prestamo=pr.id_prestamos
         inner join clientes cl on pr.id_cliente=cl.id_cliente where month(p.pago_fecha)= ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mes]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function reporte_mes_egresos($mes){
        try{
            $sql = 'select * from prestamos pr 
         inner join clientes cl on pr.id_cliente=cl.id_cliente
         where month(pr.prestamo_fecha)= ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$mes]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function reporte_fechas($inicio,$fin){
        try{
            $sql = 'select * from pagos p 
         inner join prestamos pr on p.id_prestamo=pr.id_prestamos
         inner join clientes cl on pr.id_cliente=cl.id_cliente
         where date(p.pago_fecha) between ? and ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio,$fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function reporte_fechas_egresos($inicio,$fin){
        try{
            $sql = 'select * from prestamos pr 
         inner join clientes cl on pr.id_cliente=cl.id_cliente
         where date(pr.prestamo_fecha) between ? and ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio,$fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

}