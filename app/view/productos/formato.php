<!--Contenido-->
<form method="post" enctype="multipart/form-data" id="formulario_formato">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="text-primary fs-5" style="color: #00003C !important; margin-left: 2px; margin-top: -11px;">BUSCAR PRODUCTO</h3>
                                </div>
                                <div class="col-lg-12">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <input type="text" class="form-control" id="agg_producto" name="agg_producto" placeholder="Ingrese información..."
                                               style="margin-bottom: 18px; margin-left: -2px; width: 50%; margin-top: 1px;">
                                    </div>
                                    <div id="lista_productos">

                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead class="text-capitalize">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Medida</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody id="llenado_tabla">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4" style="max-width: 550px;">
                    <h3 class="text-primary fs-5" style="color: #00003C !important; margin-left: 60px; margin-top: 12px;">FORMATO DE INGRESO</h3>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label w-50 fs-6">N° Ingreso</label>
                                        <input class="form-control w-50 m-2" id="numero_ingreso" name="numero_ingreso" value="<?= $numero_formato +1 ?>" readonly>
                                        <!--                            <h3 style="color: #00003C; margin-top: 10px; margin-top: -45px">Número de Ingreso #--><?php //= $numero_formato +1 ?><!--</h3>-->
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <input type="hidden" class="form-control" id="id_formato" name="id_formato">
                                        <label for="fecha_ingreso" class="form-label w-50 fs-6">Fecha</label>
                                        <input class="form-control w-50 m-2" id="fecha_ingreso" name="fecha_ingreso" value="<?= $fecha_actual?>" readonly>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label for="hora_ingreso" class="form-label w-50 fs-6">Hora</label>
                                        <input class="form-control w-50 m-2" id="hora_ingreso" name="hora_ingreso" value="<?= $hora?>" readonly>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label for="proveedor" class="form-label w-50 fs-6">Proveedor</label>
                                        <select class="form-control w-50 m-2" name="proveedor" id="proveedor">
                                            <option value="">Escoger</option>
											<?php foreach ($proveedores as $p){?>
                                                <option value="<?= $p->id_proveedor ?>"><?= $p->nombre_proveedor?></option>
											<?php }?>
                                        </select>
                                    </div>
                                    <button style="margin-left: 55px; margin-top: 16px;" type="submit" class="btn btn-success" id="btn-agregar-formato_ingreso" name="btn-agregar-formato_ingreso"><i class="fa fa-save"></i> Realizar Compra</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    let medida = '<?= $medidas ?>';
    let datos_medida = JSON.parse(medida)

    let agg_producto = document.getElementById('agg_producto');

    if(agg_producto && agg_producto.addEventListener){
        agg_producto.addEventListener('keyup',function (){
            buscar_productos()
        });
    }
</script>
<script>
    function actualizarHora() {
        const inputHora = document.getElementById('hora_ingreso');
        const horaActual = new Date();
        const horas = String(horaActual.getHours()).padStart(2, '0');
        const minutos = String(horaActual.getMinutes()).padStart(2, '0');
        const segundos = String(horaActual.getSeconds()).padStart(2, '0');

        const horaFormateada = `${horas}:${minutos}:${segundos}`;
        inputHora.value = horaFormateada;
    }

    // Actualizar cada segundo (1000 ms)
    setInterval(actualizarHora, 1000);

    // Ejecutar una vez al cargar la página para mostrar la hora de inmediato
    actualizarHora();
</script>


<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>productos.js"></script>