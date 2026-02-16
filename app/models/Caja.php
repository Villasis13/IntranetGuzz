<?php
class Caja
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function traer_fecha(){
        try{
            $sql = 'select fecha_caja from caja order by id_caja desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function traer_monto_caja(){
        try{
            $sql = 'select monto_caja from caja order by id_caja desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function traer_datos_ultimo(){
        try{
            $sql = 'select * from caja order by id_caja desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function traer_datos_caja(){
        try{
            $sql = 'select * from caja order by id_caja desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function traer_datos_caja_general(){
        try{
            $sql = 'select * from caja';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function ingresos_hoy($fecha){
        try{
            $sql = 'select * from pagos where date(pago_fecha)=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$fecha]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }


    public function lista_caja_x_id($pago){
        try{
            $sql = 'select * from pagos where id_pago=?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$pago]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_ultima_caja(){
        try{
            $sql = 'select * from caja order by id_caja desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_reportes_de_caja_pagos($inicio,$fin){
        try{
            $sql = 'select * from pagos where pago_fecha between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio,$fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_reportes_de_caja_prestamos($inicio,$fin){
        try{
            $sql = 'select * from prestamos where prestamo_fecha between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio,$fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_reportes_de_caja_movimientos($inicio,$fin){
        try{
            $sql = 'select * from caja_movimientos where caja_movimiento_fecha between ? and ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$inicio,$fin]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function cerrar_caja($fecha,$caja)
    {
        try {
            $sql_pago = 'UPDATE caja SET
                        estado_caja = 0,
                        caja_cierre_fecha=?
                    WHERE id_caja = ?';
            $stm_pago = $this->pdo->prepare($sql_pago);
            $stm_pago->execute([$fecha,$caja]);
            return 1; // Retorna 1 si la actualización fue exitosa
        } catch (Throwable $e) {
            // Registrar el error
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2; // Error al procesar
        }
    }


    public function guardar_monto($model)
    {
        try {
                $sql_pago = 'UPDATE pagos SET
                        pago_monto = ?
                    WHERE id_pago = ?';
                $stm_pago = $this->pdo->prepare($sql_pago);
                $stm_pago->execute([
                    $model->pago_monto,
                    $model->id_pago
                ]);

                return 1; // Retorna 1 si la actualización fue exitosa

        } catch (Throwable $e) {
            // Registrar el error
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2; // Error al procesar
        }
    }

    public function guardar_movimiento_caja($model)
    {
        try {
            $sql_pago = 'INSERT INTO caja_movimientos (id_caja,caja_movimiento_tipo,caja_movimiento_monto,caja_movimiento_fecha)
                             VALUES (?,?,?,?)';
            $stm_pago = $this->pdo->prepare($sql_pago);
            $stm_pago->execute([
                $model->id_caja,
                $model->caja_movimiento_tipo,
                $model->caja_movimiento_monto,
                $model->caja_movimiento_fecha
            ]);

            return 1; // Retorna 1 si la actualización fue exitosa

        } catch (Throwable $e) {
            // Registrar el error
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2; // Error al procesar
        }
    }

    public function actualizar_monto_caja($model)
    {
        try {
            $sql_alumno = 'UPDATE caja SET
                        monto_caja = ?
                    WHERE id_caja = ?';
            $stm_alumno = $this->pdo->prepare($sql_alumno);
            $stm_alumno->execute([
                $model->monto_caja,
                $model->id_caja
            ]);
            return 1; // Retorna 1 si la actualización fue exitosa
        } catch (Throwable $e) {
            // Registrar el error
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            return 2; // Error al procesar
        }
    }


    public function traer_estado_caja(){
        try{
            $sql = 'SELECT estado_caja
					FROM caja
					WHERE DATE(fecha_caja) = CURDATE();
					';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 0;
        }
    }
}