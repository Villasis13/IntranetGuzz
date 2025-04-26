<?php
class Builder
{
    private $log;

    public function __construct()
    {
        $this->log = new Log();
        $this->pdo = Database::getConnection();
    }

//    function save($table, $datos) {
//        try {
//            // Conexión a la base de datos
//            $conn = mysqli_connect('localhost', 'root', '', _DB_);
//
//            // Preparación de la consulta
//            $query = "INSERT INTO " . $table . " (";
//            $params = '';
//            foreach ($datos as $key => $value) {
//                $query .= "$key,";
//                $params .= '?,';
//
//            }
//            $query = rtrim($query, ',');
//            $params = rtrim($params, ',');
//            $query .= ") VALUES ($params)";
//            $stmt = mysqli_prepare($conn, $query);
//
//            // Vinculación de los parámetros
//            $types = str_repeat('s', count($datos));
//            $values = array_values($datos);
//            array_unshift($values, $types);
//            call_user_func_array(array($stmt, 'bind_param'), $values);
//
//            // Ejecución de la consulta
//            $stmt->execute();
//
//
//            // Cierre de la conexión
//            $stmt->close();
//            mysqli_close($conn);
//            return 1;
//        }catch (Throwable $e){
//            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
//            return 2;
//        }
//    }
    public function save($table , $datos){
        try{
            $query = "INSERT INTO " . $table . " (";
            $params = '';
            $values =[];
            foreach ($datos as $key => $value){
                $query .= "$key,";
                $params .= '?,';
                array_push($values , $value);
            }
            $query = rtrim($query, ',');
            $params = rtrim($params, ',');

            $query .= ") VALUES (".$params.")";

            $sql = $query ;
            $stm = $this->pdo->prepare($sql);
            $stm->execute($values);

            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function list($table,$datos){
        try{
            $data ='';
            foreach ($datos as $key ){
                $params.=" $key,";
            }

            $params = rtrim($params, ',');
            $query = 'SELECT '.$params.' FROM '.$table;

            $sql = $query;
            $stm = $this->pdo->prepare($sql);
            $stm->execute([]);
            $data = $stm->fetchAll();
            return $data;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return [];
        }
    }

    public function update($table, $camps , $condiciones){
        try{
            $campos = '';
            $cond ='';
            $valores = [];
            foreach ($camps as $key => $value){
                $campos .= "$key = ? ,";
                array_push($valores, $value);
            }
            foreach ($condiciones as $keys => $values){
                $cond .= " $keys = ? and";
                array_push($valores, $values);
            }
            $campos = rtrim($campos, ',');
            $cond = rtrim($cond, 'and');

            $sql = 'update  '.$table.' set '.$campos.'  where '. $cond;
            $stm = $this->pdo->prepare($sql);
            $stm->execute($valores);

            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function delete($table , $condiciones){
        try{
            $campos = '';
            $cond ='';
            $valores = [];

            foreach ($condiciones as $keys => $values){
                $cond .= " $keys = ? and";
                array_push($valores, $values);
            }
            $campos = rtrim($campos, ',');
            $cond = rtrim($cond, 'and');

            $sql = 'delete  from '.$table.'  where '. $cond;
            $stm = $this->pdo->prepare($sql);
            $stm->execute($valores);

            return 1;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            return 2;
        }
    }
    public function join ($table1, $table2, $joincolumn, $select=[], $where=[]){
        try {
            /* Los selects que iran al principio de la consulta */
            $selects = empty($select) ? '*': implode(',', $select);
            $query ="SELECT {$select} FROM {$table1} INNER JOIN {$table2} ON {$table1}.{$joincolumn} = {$table2}.{$joincolumn} ";
            if (!empty($where)){
                $query .= 'WHERE';
                $i =0;
                foreach ($where as $key => $value){
                    if ($i>0){
                        $query.=' AND ';
                    }
                    $query .= "{$key} = ?";
                    $params[] = $value;
                    $i++;
                }
            }
            /* ------------------- Buscar --------------------------- */
            $stm = $this->pdo->prepare($query);
            $stm->execute($params);
            $result = $stm->fetch();
        }  catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $result = [];
        }
        return $result;


    }
    /*  Revisar funcion   */
    function joinTables($table1, $table2, $joinColumn, $select = [], $where = [], $pdo) {
        /*$results = joinTables('users', 'orders', 'user_id', ['users.name', 'orders.order_id', 'orders.order_total'], ['users.status' => 'active'], $pdo);*/
        $selectColumns = empty($select) ? '*' : implode(',', $select);

        $query = "SELECT {$selectColumns} FROM {$table1} INNER JOIN {$table2} ON {$table1}.{$joinColumn} = {$table2}.{$joinColumn}";

        if (!empty($where)) {
            $query .= ' WHERE ';
            $i = 0;

            foreach ($where as $key => $value) {
                if ($i > 0) {
                    $query .= ' AND ';
                }

                $query .= "{$key} = ?";
                $params[] = $value;

                $i++;
            }
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute(isset($params) ? $params : []);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }





}