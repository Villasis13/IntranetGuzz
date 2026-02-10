<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header bg-gradient-primary py-3">
					<div class="d-flex justify-content-between align-items-center">
						<h5 style="font-weight: bold" class="m-0 font-weight-bold">
							Lista de Pagos Realizados del cliente: <?= $listar_cliente_x_prestamo->cliente_nombre . ' ' . $listar_cliente_x_prestamo->cliente_apellido_paterno ?>
						</h5>
                        <a href="<?= _SERVER_ ?>prestamos/prestamos" class="btn btn-success">
                            <i class="fa fa-arrow-left me-2"></i>Volver
                        </a>
					</div>
				</div>
                <?php
                $total = 0;
                foreach ($pagos_p as $c){
                    $total += $c->pago_monto;
                }
                ?>
                <h5 style="margin-left: 2%; font-weight: bold" class="text-success">Total Pagado: S/. <?= $total ?></h5>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover table-striped" id="dataTable">
							<thead class="thead-light">
							<tr class="text-center">
								<th>#</th>
								<th>Fecha</th>
								<th>Nro. Recibo</th>
								<th>Monto Pagado</th>
							</tr>
							</thead>

							<tbody>
							<?php
							$a = 1;
							foreach ($pagos_p as $c){
								?>
								<tr class="text-center">
									<td><?= $a ?></td>
									<td><?= $c->pago_fecha ?></td>
									<td><?= $c->id_pago ?></td>
									<td>S/. <?= $c->pago_monto ?></td>
								</tr>
								<?php
								$a++;
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
        <?php
		if(floatval($descuentos_monto[0]->total) > 0){
            ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header bg-gradient-primary py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 style="font-weight: bold" class="m-0 font-weight-bold">
                                Descuentos Aplicados
                            </h5>
                        </div>
                    </div>
					<?php
					$total_d = 0;
                    foreach ($descuentos_prestamos as $c){
                        $total_d += floatval($c->descuento_monto);
                    }
					?>
                    <h5 style="margin-left: 2%; font-weight: bold" class="text-success">Total Descuento: S/. <?= $total_d ?></h5>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" id="dataTable">
                                <thead class="thead-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Monto Pagado</th>
                                </tr>
                                </thead>

                                <tbody>
								<?php
								$a = 1;
								foreach ($descuentos_prestamos as $c){
									?>
                                    <tr class="text-center">
                                        <td><?= $a ?></td>
                                        <td><?= $c->descuento_fecha ?></td>
                                        <td>S/. <?= $c->descuento_monto ?></td>
                                    </tr>
									<?php
									$a++;
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
	</div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>