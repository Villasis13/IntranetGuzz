<?php
require 'app/models/Clientes.php';
require 'app/models/Builder.php';
require 'app/models/Usuario.php';
require 'app/models/Rol.php';
require 'app/models/Archivo.php';
require 'app/models/Prestamos.php';
require 'app/models/Cobros.php';
class ClientesController
{
    private $usuario;
    private $rol;
    private $archivo;

    //Variables fijas para cada llamada al controlador
    private $sesion;
    private $encriptar;
    private $log;
    private $validar;
    private $clientes;
    private $builder;
    private $prestamos;
    private $cobros;
    public function __construct()
    {
        //Instancias especificas del controlador
        $this->usuario = new Usuario();
        $this->rol = new Rol();
        $this->archivo = new Archivo();
        //Instancias fijas para cada llamada al controlador
        $this->encriptar = new Encriptar();
        $this->log = new Log();
        $this->sesion = new Sesion();
        $this->validar = new Validar();
        $this->clientes = new Clientes();
        $this->builder = new Builder();
        $this->prestamos = new Prestamos();
        $this->cobros = new Cobros();
    }
    public function inicio(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
            // Aplica el interés de los préstamos cuyo plazo venció con saldo (una vez por periodo)
            $this->cobros->aplicar_intereses_vencidos();
            $clientes = $this->clientes->todos_clientes();
            $clientes_atraso = $this->clientes->clientes_con_atraso();
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/inicio.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function garante(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_cliente = $_GET['id'];
			$data_cliente = $this->clientes->listar_x_id($id_cliente);
			$garantes = $this->clientes->listar_clientes_garantes($id_cliente);
            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/garante.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    public function historial_cliente(){
        try{
            $this->nav = new Navbar();
            $navs = $this->nav->listar_menus($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
			$id_cliente = $_GET['id'];
			$data_cliente = $this->clientes->listar_x_id($id_cliente);
			$data_cliente_h = $this->clientes->listar_x_id_h($id_cliente);
			$prestamos_cliente = $this->prestamos->listar_prestamos_cliente($id_cliente);
			$morosos_motivos = $this->clientes->motivos_morosos($id_cliente);
			$historial_linea_credito = $this->clientes->listar_historial_linea_credito($id_cliente);

            require _VIEW_PATH_ . 'header.php';
            require _VIEW_PATH_ . 'navbar.php';
            require _VIEW_PATH_ . 'clientes/historial_cliente.php';
            require _VIEW_PATH_ . 'footer.php';
        }
        catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            echo "<script language=\"javascript\">alert(\"Error Al Mostrar Contenido. Redireccionando Al Inicio\");</script>";
            echo "<script language=\"javascript\">window.location.href=\"". _SERVER_ ."\";</script>";
        }
    }
    // Documentos adjuntos del cliente: tipos de archivo aceptados (extensión => MIME real) y tamaño máximo
    const DOC_FORMATOS = array(
        'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
        'webp' => 'image/webp', 'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    );
    const DOC_TIPOS = array('DNI', 'Recibo de luz', 'Foto personal', 'Otro');
    const DOC_MAX_BYTES = 10485760; // 10 MB

    // Guarda los archivos enviados en documentos[] (con documentos_tipo[] y documentos_descripcion[]).
    // Devuelve los nombres de los archivos rechazados para avisar al usuario.
    private function guardar_documentos_cliente($id_cliente, $id_usuario)
    {
        $rechazados = array();
        if (empty($_FILES['documentos']['name']) || !is_array($_FILES['documentos']['name'])) {
            return $rechazados;
        }

        $carpeta = 'uploads/clientes/' . (int)$id_cliente;
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        foreach ($_FILES['documentos']['name'] as $i => $nombre_original) {
            if ($nombre_original === '' || $_FILES['documentos']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $tmp  = $_FILES['documentos']['tmp_name'][$i];
            $peso = (int)$_FILES['documentos']['size'][$i];
            $ext  = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
            $tipo = $_POST['documentos_tipo'][$i] ?? '';
            $descripcion = trim($_POST['documentos_descripcion'][$i] ?? '');

            // La extensión y el contenido real del archivo deben coincidir con un formato permitido
            $valido = $_FILES['documentos']['error'][$i] === UPLOAD_ERR_OK
                && is_uploaded_file($tmp)
                && $peso > 0 && $peso <= self::DOC_MAX_BYTES
                && isset(self::DOC_FORMATOS[$ext])
                && in_array($tipo, self::DOC_TIPOS, true);
            $mime = $valido ? $finfo->file($tmp) : '';
            if (!$valido || $mime !== self::DOC_FORMATOS[$ext]) {
                $rechazados[] = $nombre_original;
                continue;
            }

            $ruta = $carpeta . '/' . date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            // Fotos JPG/PNG se reducen a 1600 px como máximo (legible para DNI y recibos); el resto se guarda tal cual
            $guardado = in_array($mime, array('image/jpeg', 'image/png'), true)
                ? $this->archivo->subir_imagen_comprimida($tmp, $ruta, false, 1600, 1600, 85)
                : move_uploaded_file($tmp, $ruta);
            if (!$guardado) {
                $rechazados[] = $nombre_original;
                continue;
            }

            $this->builder->save('clientes_documentos', array(
                'id_cliente'                    => $id_cliente,
                'cliente_documento_tipo'        => $tipo,
                'cliente_documento_descripcion' => $descripcion !== '' ? mb_substr($descripcion, 0, 255) : null,
                'cliente_documento_nombre'      => mb_substr(basename($nombre_original), 0, 255),
                'cliente_documento_ruta'        => $ruta,
                'cliente_documento_mime'        => $mime,
                'cliente_documento_tamanho'     => filesize($ruta),
                'id_usuario'                    => $id_usuario,
                'cliente_documento_fecha'       => date('Y-m-d H:i:s'),
                'cliente_documento_estado'      => 1,
            ));
        }
        return $rechazados;
    }

    public function guardar_editar_clientes()
    {
        $documentos_rechazados = array();
        $result = 2;
        $message = 'OK';
        try {
            $ok_data = true;
            $id = $_POST['id_cliente'];
            $cliente_dni = $_POST['cliente_dni'];
            $id_usuario_edicion = $this->encriptar->desencriptar($_SESSION['c_u'],_FULL_KEY_);

            if ($ok_data) {
                $dirs_json = $_POST['cliente_direcciones'] ?? '[]';
                $dirs = json_decode($dirs_json, true) ?: [];
                $primera_dir = trim($dirs[0]['direccion'] ?? '');
                $primera_ref = $dirs[0]['referencia'] ?? null;

                if ($id == null) {
                    $validar_dni = $this->clientes->validar_x_id($id, $cliente_dni);
                    if ($validar_dni) {
                        $result = 3;
                    } else {
                        $result = $this->builder->save("clientes", array(
                            "cliente_dni" => $cliente_dni,
                            "cliente_nombre" => $_POST['cliente_nombre'],
                            "cliente_apellido_paterno" => $_POST['cliente_apellido_paterno'],
                            "cliente_apellido_materno" => $_POST['cliente_apellido_materno'],
                            "cliente_fecha_nacimiento" => $_POST['cliente_fecha_nacimiento'] ?? null,
                            "cliente_direccion" => $primera_dir,
                            "cliente_referencia" => $primera_ref ?: null,
                            "cliente_celular" => $_POST['cliente_celular'],
                            "cliente_celular2" => $_POST['cliente_celular2'] ?: null,
                            "cliente_correo" => $_POST['cliente_correo'] ?? null,
                            "cliente_nro_tarjeta" => $_POST['cliente_nro_tarjeta'] ?? null,
                            "cliente_lugar_trabajo" => $_POST['cliente_lugar_trabajo'] ?? null,
                            "cliente_otro" => $_POST['cliente_otro'] ?? null,
                            "cliente_estado" => 1,
                            "cliente_credito" => 0,
                            "cliente_fecha" => date("Y-m-d H:i:s")
                        ));
                        if ($result == 1) {
                            $nuevo_id = $this->builder->lastInsertId();
                            $this->clientes->guardar_direcciones($nuevo_id, $dirs);
                            $documentos_rechazados = $this->guardar_documentos_cliente($nuevo_id, $id_usuario_edicion);
                        }
                    }
                } else {
					$validar_dni = $this->clientes->validar_x_id($id,$cliente_dni);
                    if ($validar_dni) {
                        $result = 3;
                    } else {
						$datos_x_id = $this->clientes->listar_x_id($id);
						$this->builder->save("clientes_historial_cambios", array(
							"id_cliente" => $datos_x_id->id_cliente,
							"cliente_dni" => $datos_x_id->cliente_dni,
							"cliente_apellido_paterno" => $datos_x_id->cliente_apellido_paterno,
							"cliente_apellido_materno" => $datos_x_id->cliente_apellido_materno,
							"cliente_nombre" => $datos_x_id->cliente_nombre,
							"cliente_fecha_nacimiento" => $datos_x_id->cliente_fecha_nacimiento,
							"cliente_direccion" => $datos_x_id->cliente_direccion,
							"cliente_referencia" => $datos_x_id->cliente_referencia,
							"cliente_telefono" => $datos_x_id->cliente_telefono,
							"cliente_celular" => $datos_x_id->cliente_celular,
							"cliente_celular2" => $datos_x_id->cliente_celular2 ?? null,
							"cliente_correo" => $datos_x_id->cliente_correo,
							"cliente_nro_tarjeta" => $datos_x_id->cliente_nro_tarjeta,
							"cliente_motivo_ad" => $datos_x_id->cliente_motivo_ad,
							"cliente_credito" => $datos_x_id->cliente_credito,
							"cliente_estado" => $datos_x_id->cliente_estado,
							"cliente_fecha" => date("Y-m-d"),
							"usuario_edicion" => $id_usuario_edicion ?? null
						));

                        $result = $this->builder->update("clientes", array(
							"cliente_dni" => $cliente_dni,
							"cliente_nombre" => $_POST['cliente_nombre'],
							"cliente_apellido_paterno" => $_POST['cliente_apellido_paterno'],
							"cliente_apellido_materno" => $_POST['cliente_apellido_materno'],
							"cliente_fecha_nacimiento" => $_POST['cliente_fecha_nacimiento'] ?? null,
							"cliente_direccion" => $primera_dir,
							"cliente_referencia" => $primera_ref ?: null,
							"cliente_celular" => $_POST['cliente_celular'],
							"cliente_celular2" => $_POST['cliente_celular2'] ?: null,
							"cliente_correo" => $_POST['cliente_correo'] ?? null,
							"cliente_nro_tarjeta" => $_POST['cliente_nro_tarjeta'] ?? null,
							"cliente_lugar_trabajo" => $_POST['cliente_lugar_trabajo'] ?? null,
							"cliente_otro" => $_POST['cliente_otro'] ?? null,
							"cliente_fecha" => date('Y-m-d'),
                        ), array("id_cliente" => $id));
                        if ($result == 1) {
                            $this->clientes->guardar_direcciones($id, $dirs);
                            $documentos_rechazados = $this->guardar_documentos_cliente($id, $id_usuario_edicion);
                        }
                    }
                }
            } else {
                $result = 6;
                $message = "Integridad de datos fallida. Algún parametro se está enviando mal";
            }
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "documentos_rechazados" => $documentos_rechazados)));
    }
    public function actualizar_cliente_a_moroso()
    {
        $result = 2;
        $message = 'OK';
        try {
			$id_ = $_POST['id_cliente_moroso'];
			$comentario = $_POST['cliente_historial_moroso_comentario'];
			$guardarid = $_POST['guardarid'];
			$tipo = $_POST['tipo'];
			$fecha = date("Y-m-d H:i:s");
			$estado = $this->clientes->listar_x_id($id_)->cliente_estado;
			if($estado==1){
				$e = 0;
				$result = $this->builder->update("clientes", array(
					"cliente_estado" => $e,
				), array(
					"id_cliente" =>  $id_
				));
			}else{
				$e = 1;
				$result = $this->builder->update("clientes", array(
					"cliente_estado" => $e,
				), array(
					"id_cliente" =>  $guardarid
				));
			}
			
			if($comentario){
				$this->builder->save('clientes_historial_moroso',array(
					'id_cliente' =>$id_,
					'cliente_historial_moroso_comentario' => $comentario,
					'cliente_historial_moroso_estado' => 1,
					'cliente_historial_moroso_fecha' => date('Y-m-d'),
					'cliente_historial_moroso_mt' => microtime(true),
				));
			}
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function guardar_cliente_garante()
    {
        $result = 2;
        $message = 'OK';
        try {
			if($_POST['id_cliente']!=$_POST['id_cliente_recomendado']){
				$buscar_existente = $this->clientes->listar_x_id_garante($_POST['id_cliente'],$_POST['id_cliente_recomendado']);
				if(!$buscar_existente){
					$result = $this->builder->save("clientes_garantes", array(
						"id_cliente" => $_POST['id_cliente'],
						"id_garante" => $_POST['id_cliente_recomendado'],
						"cliente_garante_estado" => 1,
						"cliente_garante_mt" => microtime(true),
					));
				}else{
					$result = 3;
				}
			}else{
				$result = 4;
			}
        } catch (Exception $e) {
            $this->log->insertar($e->getMessage(), get_class($this) . '|' . __FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function edicion_clientes(){
        $ok_data = true;
        $result = 2;
        $message = 'OK';
        try{
            if($ok_data){
                $id = $_POST['guardarid'];
                $result = $this->clientes->listar_x_id($id);
                $result->direcciones = $this->clientes->listar_direcciones_x_id($id);
                $result->documentos = $this->clientes->listar_documentos_x_id($id);
            } else {
                $result = 6;
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    // Historial de comportamiento de pago, para decidir si corresponde marcar al cliente como moroso
    public function resumen_comportamiento_cliente(){
        $result = 2;
        $message = 'OK';
        $resumen = null;
        try{
            $resumen = $this->cobros->resumen_comportamiento_cliente((int)($_POST['id_cliente'] ?? 0));
            $result = $resumen ? 1 : 2;
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message, "resumen" => $resumen)));
    }

    // Baja lógica de un documento adjunto: deja de listarse, el archivo se conserva en disco
    public function eliminar_documento_cliente(){
        $result = 2;
        $message = 'OK';
        try{
            $documento = $this->clientes->listar_documento($_POST['id_cliente_documento'] ?? 0);
            if ($documento) {
                $result = $this->builder->update('clientes_documentos',
                    array('cliente_documento_estado' => 0),
                    array('id_cliente_documento' => $documento->id_cliente_documento));
            }
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }

    // Muestra/descarga un documento del cliente. La carpeta uploads/clientes no es accesible
    // por URL directa, así que los archivos solo se entregan por aquí (con sesión y permiso).
    public function ver_documento(){
        try{
            $documento = $this->clientes->listar_documento($_GET['id'] ?? 0);
            $ruta = $documento ? realpath($documento->cliente_documento_ruta) : false;
            $base = realpath('uploads/clientes');
            if (!$ruta || !$base || strpos($ruta, $base . DIRECTORY_SEPARATOR) !== 0) {
                http_response_code(404);
                echo 'Documento no encontrado';
                return;
            }
            // Imágenes y PDF se abren en el navegador; Word se descarga
            $en_linea = strpos($documento->cliente_documento_mime, 'image/') === 0
                || $documento->cliente_documento_mime === 'application/pdf';
            $nombre = str_replace(array('"', "\r", "\n"), '', $documento->cliente_documento_nombre);
            header('Content-Type: ' . $documento->cliente_documento_mime);
            header('Content-Length: ' . filesize($ruta));
            header('Content-Disposition: ' . ($en_linea ? 'inline' : 'attachment') . '; filename="' . $nombre . '"');
            header('X-Content-Type-Options: nosniff');
            header('Cache-Control: private, max-age=0');
            readfile($ruta);
        } catch (Throwable $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            http_response_code(500);
            echo 'No se pudo abrir el documento';
        }
    }

    public function buscar_cliente_garante(){
        $ok_data = true;
        $result = 2;
        $message = 'OK';
        try{
            $result = $this->clientes->listar_x_dni($_POST['btn_dni_garante_nuevo']);
        } catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'.__FUNCTION__);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
    public function eliminar_cliente(){
        try{
            $id = $_POST['id'];
			$result = $this->builder->update("clientes",array(
				'cliente_estado' => 0
			),array(
			'id_cliente' => $id
			));
        }catch (Exception $e){
            $this->log->insertar($e->getMessage(), get_class($this).'|'._FUNCTION_);
            $message = $e->getMessage();
        }
        echo json_encode(array("result" => array("code" => $result, "message" => $message)));
    }
}