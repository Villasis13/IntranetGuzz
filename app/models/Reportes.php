<?php
class Reportes
{
    private $pdo;
    private $log;
    public function __construct(){
        $this->pdo = Database::getConnection();
        $this->log = new Log();
    }
    public function Ventas(){
        try{
            $sql = 'select * from ventas v inner join clientes c on v.id_cliente = c.id_cliente
					inner join tipo_pago tp on v.id_tipo_pago = tp.id_tipo_pago
         			order by c.id_cliente desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function Compras(){
        try{
            $sql = 'select * from formato_ingreso fi inner join proveedor p on fi.id_proveedor = p.id_proveedor 
         			order by fi.fecha_ingreso desc';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

}
