<!--Contenido-->
<form method="post" enctype="multipart/form-data" id="formulario_realizar_venta">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <label for="productos_comprar">Productos</label>
                        <input class="form-control mt-2" id="productos_comprar" placeholder="Ingrese el código de barras o el nombre del producto">
                        <div id="lista_productos_comprar">

                        </div>
                        <label style="margin-top: 12px;" for="id_cliente">Cliente</label>
                        <select class="form-control mt-2" id="id_cliente" name="id_cliente">
                            <option value="">Escoger</option>
							<?php foreach ($clientes as $c){?>
                                <option value="<?= $c->id_cliente ?>"><?= $c->cliente_nombre?></option>
							<?php }?>
                        </select>
                        <div class="d-flex">
                            <h3 class="mt-3" id="total_venta">Total Venta: S/. 0.00</h3>
                            <button class="btn btn-success" id="btn_realizar_venta" style="height: 40px; margin-left: 110px; margin-top: 12px">Realizar Venta</button>
                            <a class="btn btn-danger text-white" style="height: 40px; margin-top: 12px; margin-left: 2px" onclick="vaciar_listado()">Vaciar Listado</a>
                        </div>
                        <div class="card-body">
                            <div class="table">
                                <table class="table table-striped">
                                    <thead class="text-capitalize">
                                    <tr>
                                        <!--                                    <th>ID</th>-->
                                        <th>Nombre</th>
                                        <th>Stock Disponible</th>
                                        <th>Cantidad</th>
                                        <!--                                    <th>Medida</th>-->
                                        <th>P. Unitario</th>
                                        <th>Total</th>
                                        <th>Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tabla_vender">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h3 id="total_venta_pago">Total Venta: S/. 00.00 <?php  ?> </h3>
                        <input type="hidden" id="id_venta" name="id_venta">
                        <label for="id_tipo_documento">Documento *</label>
                        <select class="form-control mt-2" id="id_tipo_documento" name="id_tipo_documento" onchange="ultima_serie()" onselect="ultima_serie()">
                            <option value="">Escoger</option>
							<?php foreach ($tipo_documento as $t){?>
                                <option value="<?= $t->id_tipo_documento ?>"><?= $t->tipo_documento_nombre?></option>
							<?php }?>
                        </select>
                        <label for="id_tipo_pago" class="mt-3">Tipo Pago *</label>
                        <select class="form-control mt-2" id="modo_pago" name="modo_pago" >
                            <option value="">Escoger</option>
							<?php foreach ($modo_pago as $g){?>
                                <option value="<?= $g->id_tipo_pago ?>"><?= $g->tipo_pago_nombre?></option>
							<?php }?>
                        </select>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="documento" class="mt-3">Serie</label>
                                <input id="documento" name="documento" class="form-control" readonly value="">
                            </div>
                            <div class="col-lg-6">
                                <label for="numero_venta" class="mt-3">N° Venta</label>
                                <input id="numero_venta" class="form-control" readonly value=" <?php $numero_venta = $ultimo_id_venta->id_venta+1; echo htmlspecialchars($numero_venta); ?> ">
                            </div>
                        </div>
                        <label for="documento_comprar" class="mt-3">Efectivo recibido *</label>
                        <input class="form-control mt-2" id="efectivo_recibido" oninput="calcular_vuelto()">
                        <input type="checkbox" class="mt-3" id="pago_exacto?" onchange="calcular_vuelto()">
                        <label>Pago exacto</label>
                        <h3 class="mt-3 text-danger" id="vuelto">Vuelto: S/. 0.00</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>

<script>
    let agg_producto = document.getElementById('productos_comprar');

    if(agg_producto && agg_producto.addEventListener){
        agg_producto.addEventListener('keyup',function (){
            buscar_productos_comprar()
        });
    }
</script>