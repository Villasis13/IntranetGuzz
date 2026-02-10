<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header bg-gradient-primary py-3">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="m-0 font-weight-bold">
							Préstamos Antiguos Pendientes
						</h5>
                        <a target="_blank" href="<?= _SERVER_ ?>Prestamos/reporte_prestamos_antiguos" style="cursor: pointer" class="btn-sm btn-danger text-white ">
                            <i class="fa fa-file-pdf-o"> Ver Reporte</i>
                        </a>
					</div>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="dataTable">
							<thead class="thead-light">
							<tr class="text-center">
								<th>#</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th>Monto Prestado</th>
								<th>Monto que adeuda</th>
								<th>Próximo cobro</th>
								<th>Acciones</th>
							</tr>
							</thead>

							<tbody>
							<?php
							$a = 1;
							foreach ($prestamos_antiguos as $c){
								?>
								<tr class="text-center">
									<td><?= $a ?></td>
									<td><?= $c->prestamo_fecha ?></td>
									<td><?= $c->cliente_nombre .' ' . $c->cliente_apellido_paterno.' '.$c->cliente_apellido_materno ?></td>
									<td>S/. <?= $c->prestamo_monto ?></td>
									<td>
                                        <?php
										$resta_pagar = $this->cobros->listar_total_pagos_x_prestamo($c->id_prestamos);
										$descuentos_prestamos = $this->cobros->listar_decuentos_x_prestamo($c->id_prestamos);

										$resta_total = (float) $resta_pagar[0]->total;
										$descuentos_total = (float) $descuentos_prestamos[0]->total;
										$pm = (float)$c->prestamo_monto;

										if ($resta_total > 0) {
											if($descuentos_total > 0){
												$valor_resta_por_pagar =  $pm - $resta_total - $descuentos_total;
												echo "S/. " . ($pm - $resta_total - $descuentos_total);
											}else{
												$valor_resta_por_pagar =  $pm - $resta_total;
												echo "S/. " . ($pm - $resta_total);
											}
										} else {
											$valor_resta_por_pagar = $pm;
											echo "S/. " . $pm;
										}
                                        ?>
                                    </td>
                                    <td>
                                        <?= $c->prestamo_prox_cobro ?>
                                    </td>
                                    <td>
                                        <a href="<?= _SERVER_ ?>cobros/pagar/<?= $c->id_prestamos ?>"
                                           style="cursor: pointer" class="btn-sm btn-warning text-white">
                                            <i class="fa fa-money"></i>
                                        </a>
                                        <br>
                                        <a href="<?= _SERVER_ ?>cobros/pagos/<?= $c->id_prestamos ?>"
                                           style="cursor: pointer" class="btn-sm btn-primary text-white">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <br>
                                        <a target="_blank" class="text-white btn-sm btn-danger" href="<?= _SERVER_ ?>Prestamos/generar_documento/<?= $c->id_prestamos ?>">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    </td>
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
	</div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-primary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold">
                            Préstamos Antiguos Cancelados
                        </h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="dataTable">
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Monto Prestado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>

                            <tbody>
							<?php
							$a = 1;
							foreach ($prestamos_antiguos_cancelados as $c){
								?>
                                <tr class="text-center">
                                    <td><?= $a ?></td>
                                    <td><?= $c->prestamo_fecha ?></td>
                                    
                                    <td><?= $c->cliente_nombre .' ' . $c->cliente_apellido_paterno.' '.$c->cliente_apellido_materno ?></td>
                                    <td>S/. <?= $c->prestamo_monto ?></td>
                                    
                                    <td>
                                        <a href="<?= _SERVER_ ?>cobros/pagos/<?= $c->id_prestamos ?>"
                                           style="cursor: pointer" class="btn-sm btn-primary text-white">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <br>
                                        <a target="_blank" class="text-white btn-sm btn-danger" href="<?= _SERVER_ ?>Prestamos/generar_documento/<?= $c->id_prestamos ?>">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    </td>
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
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>