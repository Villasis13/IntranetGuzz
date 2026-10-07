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
    // Clientes con atrasos de pago, indexados por id_cliente. Es solo una alerta: un atraso
    // NO convierte al cliente en moroso; ese estado se asigna a mano tras revisar su historial.
    //   atrasados          = préstamos activos con alguna cuota vencida sin pagar
    //   cuota_mas_antigua  = fecha de la cuota vencida más antigua (para los días de atraso)
    //   en_recuperacion    = préstamos en recuperación (estado 3)
    public function clientes_con_atraso(){
        try{
            $sql = 'SELECT p.id_cliente,
                           COUNT(DISTINCT CASE WHEN p.prestamo_estado = 1 THEN p.id_prestamos END) AS atrasados,
                           MIN(CASE WHEN p.prestamo_estado = 1 THEN pd.pago_diario_fecha END) AS cuota_mas_antigua,
                           COUNT(DISTINCT CASE WHEN p.prestamo_estado = 3 THEN p.id_prestamos END) AS en_recuperacion
                    FROM prestamos p
                    LEFT JOIN pagos_diarios pd ON pd.id_prestamos = p.id_prestamos
                                              AND pd.pago_diario_estado = 1
                                              AND pd.pago_diario_fecha < CURDATE()
                    WHERE (p.prestamo_estado = 1 AND p.prestamo_saldo_pagar > 0 AND pd.id_pago_diario IS NOT NULL)
                       OR p.prestamo_estado = 3
                    GROUP BY p.id_cliente';
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $atrasos = array();
            foreach ($stm->fetchAll() as $m) {
                $atrasos[$m->id_cliente] = $m;
            }
            return $atrasos;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_documentos_x_id($id_cliente){
        try{
            $sql = 'SELECT id_cliente_documento, cliente_documento_tipo, cliente_documento_descripcion,
                           cliente_documento_nombre, cliente_documento_mime, cliente_documento_tamanho,
                           cliente_documento_fecha
                    FROM clientes_documentos
                    WHERE id_cliente = ? AND cliente_documento_estado = 1
                    ORDER BY cliente_documento_fecha DESC, id_cliente_documento DESC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }
    public function listar_documento($id_documento){
        try{
            $sql = 'SELECT * FROM clientes_documentos WHERE id_cliente_documento = ? AND cliente_documento_estado = 1';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_documento]);
            return $stm->fetch();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return null;
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

    public function listar_cliente_x_id($id){
        try{
            $sql = 'select * from clientes 
					where id_cliente = ?';
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
            $sql = 'select * from prestamos as p
         			inner join clientes as c on c.id_cliente = p.prestamo_garante
		 			where p.id_cliente = ?';
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

    public function listar_direcciones_x_id($id_cliente) {
        try {
            $sql = 'SELECT * FROM clientes_direcciones WHERE id_cliente = ? ORDER BY cldir_orden ASC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetchAll();
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function guardar_direcciones($id_cliente, $direcciones) {
        try {
            $this->pdo->prepare('DELETE FROM clientes_direcciones WHERE id_cliente = ?')->execute([$id_cliente]);
            $stmt = $this->pdo->prepare('INSERT INTO clientes_direcciones (id_cliente, cldir_direccion, cldir_referencia, cldir_orden) VALUES (?, ?, ?, ?)');
            foreach ($direcciones as $i => $d) {
                $dir = trim($d['direccion'] ?? '');
                if ($dir === '') continue;
                $stmt->execute([$id_cliente, $dir, ($d['referencia'] ?: null), $i + 1]);
            }
            return true;
        } catch (Throwable $e) {
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return false;
        }
    }

    public function listar_historial_linea_credito($id_cliente){
        try{
            $sql = 'SELECT * FROM clientes_linea_credito
                    WHERE id_cliente = ?
                    ORDER BY id_cliente_linea_credito DESC';
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetchAll();
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function listar_primera_cuota($id){
        try{
            // Añadimos ORDER BY para asegurar que sea el primero y LIMIT 1
            $sql = 'SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ? AND id_prestamo_renovacion IS NULL 
                ORDER BY pago_diario_fecha ASC 
                LIMIT 1';

            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            return $stm->fetch(); // fetch() ya devuelve una sola fila o 'false' si no hay nada

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return false; // Es mejor retornar false cuando fetch() falla o está vacío
        }
    }

    public function listar_ultima_cuota($id){
        try{
            // Añadimos ORDER BY DESC para traer la fecha más lejana y LIMIT 1
            $sql = 'SELECT * FROM pagos_diarios 
                WHERE id_prestamos = ? AND id_prestamo_renovacion IS NULL 
                ORDER BY pago_diario_fecha DESC 
                LIMIT 1';

            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id]);

            return $stm->fetch();

        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return false;
        }
    }

}