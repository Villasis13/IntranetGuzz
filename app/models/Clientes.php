<?php
class Clientes
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    public function validar_x_id($id,$dni){
        try{
            $sql = 'select * from clientes where id_cliente <> ? and cliente_dni = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id,$dni]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function num_clientes(){
        try{
            $sql = 'select id_cliente from clientes order by id_cliente desc limit 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function todos_clientes(){
        try{
            $sql = 'select * from clientes';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_clientes_actualizar(){
        try{
            $sql = 'SELECT * 
					FROM clientes 
					WHERE cliente_fecha <= CURDATE() - INTERVAL 3 MONTH;
					';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_x_id($id){
        try{
            $sql = 'select * from clientes where id_cliente = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_x_id_h($id){
        try{
            $sql = 'select * from clientes_historial_cambios where id_cliente = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_x_id_presrtamo($id){
        try{
            $sql = 'select * from prestamos as p 
					inner join clientes as c on c.id_cliente = p.id_cliente
					where p.id_prestamos = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function motivos_morosos($id){
        try{
            $sql = 'select * from clientes_historial_moroso where id_cliente = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_clientes_garantes($id){
        try{
            $sql = 'select * from clientes_garantes as cg
         			inner join clientes as c on c.id_cliente = cg.id_garante
		 			where cg.id_cliente = ?';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_x_dni($dni){
        try{
            $sql = 'select * from clientes where cliente_dni = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$dni]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_x_id_garante($id_cliente, $id_recomendado){
        try{
            $sql = 'select * from clientes_garantes 
         			where id_cliente = ? and id_garante = ?' ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente, $id_recomendado]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
}