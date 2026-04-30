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
         inner join usuarios u on p.id_usuario=u.id_usuario
         inner join pagos_diarios pg on p.id_pago_diario=pg.id_pago_diario
        inner join metodos_pago mp on mp.id_metodo_pago=p.pago_metodo
        where date(p.pago_fecha)=? ';
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
         inner join usuarios u  on u.id_usuario=pr.id_usuario
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
                    inner join clientes cl on pr.id_cliente=cl.id_cliente 
                    inner join usuarios u on p.id_usuario=u.id_usuario
                    inner join pagos_diarios pg on p.id_pago_diario=pg.id_pago_diario
                    inner join metodos_pago mp on mp.id_metodo_pago=p.pago_metodo
                    where month(p.pago_fecha)= ?';
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
         inner join usuarios u on pr.id_usuario=u.id_usuario
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
          inner join usuarios u on p.id_usuario=u.id_usuario
         inner join pagos_diarios pg on p.id_pago_diario=pg.id_pago_diario
         inner join metodos_pago mp on mp.id_metodo_pago=p.pago_metodo
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
         inner join usuarios u on pr.id_usuario=u.id_usuario
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