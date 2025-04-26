<?php
class Productos
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
	public function todos_productos(){
		try{
			$sql = 'select * from productos p inner join medida m on p.id_medida = m.id_medida';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function ultimo_id_fi(){
		try{
			$sql = 'select id_formato from formato_ingreso order by id_formato desc limit 1';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function eliminar_detalle_formato($id){
		try{
			$sql = 'delete from detalle_formato_ingreso where id_formato = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function eliminar_formato_ingreso($id){
		try{
			$sql = 'delete from formato_ingreso where id_formato = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function todas_medida(){
		try{
			$sql = 'select * from medida';
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function validar_x_id_nombre($id, $nombre){
		try{
			$sql = 'select id_producto from productos where id_producto = ? and producto_nombre = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id,$nombre]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function validar_x_nombre($nombre){
		try{
			$sql = 'select * from productos where producto_nombre = ?';
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$nombre]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function listar_x_id($id){
		try{
			$sql = 'select * from productos where id_producto = ?' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute([$id]);
			return $stm->fetch();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
	public function llamar_proveedores(){
		try{
			$sql = 'select * from proveedor' ;
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			return $stm->fetchAll();
		} catch (Throwable $e){
			$this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
			return [];
		}
	}
    public function listar_productos_input($valor){
        try {
            $sql = 'SELECT * FROM productos p inner join medida m on p.id_medida = m.id_medida WHERE p.producto_nombre LIKE ?';
            $stm = $this->pdo->prepare($sql);
            $valor = '%' . $valor . '%'; // Agregar los caracteres '%' antes y después del valor
            $stm->execute([$valor]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'._FUNCTION_);
            return [];
        }
    }
    public function traer_ultimo_formato(){
        try {
            $sql = 'SELECT id_formato FROM formato_ingreso order by id_formato desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function traer_id_medida($id){
        try {
            $sql = 'SELECT id_medida FROM productos where id_producto = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
}