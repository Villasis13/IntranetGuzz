<?php
class Ventas
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }

    public function listar_productos_comprar($valor){
        try {
            $sql = 'SELECT * FROM productos WHERE producto_nombre LIKE ?';
            $stm = $this->pdo->prepare($sql);
            $valor = '%' . $valor . '%'; // Agregar los caracteres '%' antes y después del valor
            $stm->execute([$valor]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function ultimo_id_venta(){
        try {
            $sql = 'SELECT id_venta FROM ventas order by id_venta desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function tipo_documento(){
        try {
            $sql = 'SELECT * FROM tipo_documento';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function tipo_pago(){
        try {
            $sql = 'SELECT * FROM tipo_pago';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function clientes(){
        try {
            $sql = 'SELECT * FROM clientes';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function ultimo_documento(){
        try {
            $sql = 'SELECT * FROM documento ORDER BY id_documento DESC LIMIT 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function ultima_serie($tipo_documento){
        try {
            $sql = "SELECT documento_serie FROM documento WHERE documento_serie LIKE ? ORDER BY id_documento DESC LIMIT 1";
            $stm = $this->pdo->prepare($sql);
            if($tipo_documento==1)
            {
                $tipo_documento='B%';
                $stm->execute([$tipo_documento]);
                return $stm->fetch();
            }
            else if($tipo_documento==2){
                $tipo_documento='F%';
                $stm->execute([$tipo_documento]);
                return $stm->fetch();
            }

        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

}