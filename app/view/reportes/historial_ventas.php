<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h3>HISTORIAL DE VENTAS</h3>
<!--					<div class="row">-->
<!--						<div class="col-lg-5">-->
<!--							<label for="desde">Desde</label>-->
<!--							<input name="desde" id="desde" type="date" class="form-control mt-2">-->
<!--						</div>-->
<!--						<div class="col-lg-5">-->
<!--							<label for="hasta">Hasta</label>-->
<!--							<input type="date" name="hasta" id="hasta" class="form-control mt-2">-->
<!--						</div>-->
<!--						<div class="col-lg-2">-->
<!--							<button type="button" name="buscar" class="form-control btn-success" style="margin-top: 30px">Buscar</button>-->
<!--						</div>-->
<!--					</div>-->
					<div class="table mt-5">
						<table class="table table-striped" id="dataTable">
							<thead class="text-capitalize">
							<tr>
								<th>Detalle</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Monto</th>
								<th>Modo de pago</th>
							</tr>
							</thead>
							<tbody>
							<?php
							foreach ($ventas as $v){
								?>
								<tr>
									<td>Se realizó una venta el </td>
									<td><?= $v->venta_fecha ?></td>
									<td>al cliente <?= $v->cliente_nombre ?></td>
									<td>monto de <?= $v->venta_monto ?></td>
									<td>pagó con <?= $v->tipo_pago_nombre ?></td>
								</tr>
								<?php
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
<script src="<?php echo _SERVER_ . _JS_;?>reportes.js"></script>