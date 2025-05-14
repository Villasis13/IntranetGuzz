<!-- Modal Agregar-->
<div class="modal fade" id="gestionProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Agregar/Editar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
										<input type="hidden" id="id_producto" name="id_producto">
										<label for="producto_nombre" class="col-form-label">Nombre del producto</label>
										<input class="form-control" type="text" id="producto_nombre" name="producto_nombre" maxlength="100">
									</div>

                                    <div class="col-lg-6">
                                        <label for="producto_precio" class="col-form-label">Precio</label>
                                        <input class="form-control" type="text" id="producto_precio" name="producto_precio" maxlength="100">
                                    </div>
								</div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="id_medida" class="col-form-label">Unidad</label>
                                        <select class="form-control" name="id_medida" id="id_medida">
                                            <option value="">Escoger</option>
                                            <?php foreach ($medidas as $m){?>
                                                <option value="<?= $m->id_medida ?>"><?= $m->nombre_medida?></option>
                                            <?php }?>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="producto_stock" class="col-form-label">Stock</label>
                                        <input class="form-control" type="text" id="producto_stock" name="producto_stock" maxlength="100">
                                    </div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btn-agregar-producto" onclick="guardar_editar_productos()"><i class="fa fa-save fa-sm text-white-50"></i> Guardar</button>
			</div>
		</div>
	</div>
</div>
<!--Contenido-->
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<button onclick="limpiar_productos()" style="float: right; position: relative;" data-toggle="modal" id="botonmodal"  data-target="#gestionProducto" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i class="fa fa-plus fa-sm text-white-50"></i> Agregar Nuevo</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
							<thead class="text-capitalize">
							<tr>
								<th>#</th>
								<th>Nombre</th>
								<th>Stock</th>
								<th>Precio</th>
								<th>Opciones</th>
							</tr>
							</thead>
							<tbody>
							<?php
                            $contador = 1;
							foreach ($productos as $p){
								?>
								<tr>
                                    <td><?=$contador;?></td>

									<td>
										<?=$p->producto_nombre;?>
                                    </td>
                                    <td>
                                        <?=$p->producto_stock;?>
                                        <?=$p->nombre_medida;?>
                                    </td>
									<td>
                                        S/. <?=$p->producto_precio;?>
                                    </td>
									<td>
										<a type="button" data-toggle="modal" data-target="#gestionProducto"  class="btn btn-sm btn-warning text-white" onclick="editar_productos(<?= $p->id_producto?>)"><i class="fa fa-pencil"></i> Editar</a>
									</td>
								</tr>
								<?php
                                $contador++;
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>productos.js"></script>