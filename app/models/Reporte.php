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
            $sql = 'SELECT p.*, pr.*, cl.*, u.usuario_nickname,
                        COALESCE(pg.pago_diario_fecha, DATE(p.pago_fecha)) AS pago_diario_fecha,
                        COALESCE(p.pago_monto + COALESCE(p.pago_descuento_monto, 0), pg.pago_diario_monto, p.pago_monto) AS pago_diario_monto,
                        mp.metodo_pago_nombre,
                        CASE WHEN p.id_pago_diario IS NULL THEN "Amortización" ELSE "Cuota" END AS tipo_pago
                    FROM pagos p
                    INNER JOIN prestamos    pr ON p.id_prestamo  = pr.id_prestamos
                    INNER JOIN clientes     cl ON pr.id_cliente  = cl.id_cliente
                    INNER JOIN usuarios     u  ON p.id_usuario   = u.id_usuario
                    LEFT  JOIN pagos_diarios pg ON p.id_pago_diario = pg.id_pago_diario
                    LEFT  JOIN metodos_pago  mp ON p.pago_metodo    = mp.id_metodo_pago
                    WHERE DATE(p.pago_fecha) = ?';
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
            $sql = 'select pr.*, pr.prestamo_fecha_emision as prestamo_fecha, cl.cliente_nombre, cl.cliente_apellido_paterno, u.usuario_nickname 
                from prestamos pr 
                inner join clientes cl on pr.id_cliente=cl.id_cliente
                inner join usuarios u  on u.id_usuario=pr.id_usuario
                where date(pr.prestamo_fecha_emision) = ? ';
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
            $sql = 'SELECT p.*, pr.*, cl.*, u.usuario_nickname,
                        COALESCE(pg.pago_diario_fecha, DATE(p.pago_fecha)) AS pago_diario_fecha,
                        COALESCE(p.pago_monto + COALESCE(p.pago_descuento_monto, 0), pg.pago_diario_monto, p.pago_monto) AS pago_diario_monto,
                        mp.metodo_pago_nombre,
                        CASE WHEN p.id_pago_diario IS NULL THEN "Amortización" ELSE "Cuota" END AS tipo_pago
                    FROM pagos p
                    INNER JOIN prestamos     pr ON p.id_prestamo   = pr.id_prestamos
                    INNER JOIN clientes      cl ON pr.id_cliente   = cl.id_cliente
                    INNER JOIN usuarios      u  ON p.id_usuario    = u.id_usuario
                    LEFT  JOIN pagos_diarios pg ON p.id_pago_diario = pg.id_pago_diario
                    LEFT  JOIN metodos_pago  mp ON p.pago_metodo    = mp.id_metodo_pago
                    WHERE MONTH(p.pago_fecha) = ?';
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
            $sql = 'select pr.*, pr.prestamo_fecha_emision as prestamo_fecha, cl.cliente_nombre, cl.cliente_apellido_paterno, u.usuario_nickname 
                from prestamos pr 
                inner join clientes cl on pr.id_cliente=cl.id_cliente
                inner join usuarios u on pr.id_usuario=u.id_usuario
                where month(pr.prestamo_fecha_emision) = ? ';
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
            $sql = 'SELECT p.*, pr.*, cl.*, u.usuario_nickname,
                        COALESCE(pg.pago_diario_fecha, DATE(p.pago_fecha)) AS pago_diario_fecha,
                        COALESCE(p.pago_monto + COALESCE(p.pago_descuento_monto, 0), pg.pago_diario_monto, p.pago_monto) AS pago_diario_monto,
                        mp.metodo_pago_nombre,
                        CASE WHEN p.id_pago_diario IS NULL THEN "Amortización" ELSE "Cuota" END AS tipo_pago
                    FROM pagos p
                    INNER JOIN prestamos     pr ON p.id_prestamo    = pr.id_prestamos
                    INNER JOIN clientes      cl ON pr.id_cliente    = cl.id_cliente
                    INNER JOIN usuarios      u  ON p.id_usuario     = u.id_usuario
                    LEFT  JOIN pagos_diarios pg ON p.id_pago_diario = pg.id_pago_diario
                    LEFT  JOIN metodos_pago  mp ON p.pago_metodo    = mp.id_metodo_pago
                    WHERE DATE(p.pago_fecha) BETWEEN ? AND ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio,$fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function reporte_fechas_egresos($inicio, $fin){
        try{
            // Añadimos el alias as prestamo_fecha para no romper las vistas
            $sql = 'select pr.*, pr.prestamo_fecha_emision as prestamo_fecha, cl.cliente_nombre, cl.cliente_apellido_paterno, u.usuario_nickname 
                from prestamos pr 
                inner join clientes cl on pr.id_cliente=cl.id_cliente
                inner join usuarios u on pr.id_usuario=u.id_usuario
                where date(pr.prestamo_fecha_emision) between ? and ? ';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio, $fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

}