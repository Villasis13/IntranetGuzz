<style>
    .color-rojo {
        color: red !important;
    }
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header bg-gradient-primary py-3">
					<div class="d-flex justify-content-between align-items-center">
                        <h4 style="font-weight: bold;">
                            Lista de Todos los Préstamos
                        </h4>
                        <a href="<?= _SERVER_ ?>prestamos/inicio" class="btn btn-success">
                            <i class="fa fa-arrow-left me-2"></i>Volver
                        </a>

                        <!--<button onclick="limpiar_clientes()" data-toggle="modal" data-target="#gestionCliente" class="btn btn-success btn-sm shadow-sm">
							<i class="fa fa-plus-circle me-2"></i>Nuevo Cliente
						</button>-->
					</div>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="dataTable">
							<thead class="thead-light">
							<tr class="text-center">
								<th>#</th>
								<th>DNI</th>
								<th>Nombre</th>
								<th>Monto Prestado</th>
								<th>Saldo a Pagar</th>
								<th>Fecha de cobro</th>
								<th>Tipo de Pago</th>
								<th>Días de Mora</th>
								<th>Motivo de Préstamo</th>
								<th>Comentarios</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
							</thead>

							<tbody>
							<?php
							$a = 1;
							foreach ($prestamos_general as $c){


								$tipo_pago = $c->prestamo_tipo_pago;
								$color_fila = '';

								if ($tipo_pago == 'Diario') {
									$color_fila = 'background-color: #e3f2fd;'; // azul claro
								} elseif ($tipo_pago == 'Semanal') {
									$color_fila = 'background-color: #e8f5e9;'; // verde claro
								} elseif ($tipo_pago == 'Mensual') {
									$color_fila = 'background-color: #fff8e1;'; // amarillo claro
								}


								?>
								<?php
								$clase_texto = ($c->prestamo_estado == 3 || $c->prestamo_estado == 4) ? 'color-rojo' : '';
								?>
                                <tr class="text-center <?= $clase_texto ?>" style="<?= $color_fila ?>">
									<td><?= $a ?></td>
									<td><?= $c->cliente_dni ?></td>
									<td><?= $c->cliente_nombre .' '.
                                        $c->cliente_apellido_paterno.' '.
                                        $c->cliente_apellido_materno ?></td>
                                    <td><?= $c->prestamo_monto ?></td>
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
                                    <td><?= $c->prestamo_tipo_pago ?></td>
                                    <td>0</td>
                                    <td><?= $c->prestamo_motivo ?></td>
                                    <td><?= $c->prestamo_comentario ?></td>
                                    <td>
										<?php
										if ($c->prestamo_estado == 1) {
											echo '<span>Activo</span>';
										} else if ($c->prestamo_estado == 2) {
											echo '<span>Cancelado</span>'; // Rojo para cancelado
										} else if ($c->prestamo_estado == 3) {
											echo '<span>Préstamo Antiguo</span>'; // Amarillo (advertencia)
										} else if ($c->prestamo_estado == 4) {
											echo '<span>Préstamo Antiguo Cancelado</span>'; // Gris (secundario)
										}
										?>
                                    </td>
                                    <td>
                                        <!--<a href="<?php /*= _SERVER_ */?>prestamos/detalles/<?php /*= $c->id_prestamos */?>" class="btn-sm btn-warning text-white">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <br>-->
                                        <?php
                                        if($c->prestamo_estado == 1 || $c->prestamo_estado == 3){
                                            ?>
                                            <a href="<?= _SERVER_ ?>cobros/pagar/<?= $c->id_prestamos ?>"
                                               style="cursor: pointer" class="btn-sm btn-warning text-white">
                                                <i class="fa fa-money"></i> Pagar
                                            </a>
                                        <?php
										}
                                        ?>
                                        
                                        <br>

										<?php
										if($c->prestamo_estado != 3){
											?>
                                            <a onclick="preguntar('<i class=\'fa fa-exclamation-circle text-warning\'></i> ' +
                                                    'Cuidado. Está a punto de transferir un préstamo normal a ' +
                                                    'préstamo antiguo. ¿Seguro que desea hacerlo? ' +
                                                    'Se le recuerda que no hay vuelta atrás ' +
                                                    'una vez hecho el cambio.',
                                                    'transferir_prestamo','SI','NO', '<?= $c->id_prestamos ?>')"
                                               style="cursor: pointer" class="btn btn-sm btn-secondary text-white">
                                                <i class="fa fa-refresh"></i> Transferir
                                            </a>
											<?php
										}
										?>
                                        
                                        <br>
                                        <a class="text-white btn btn-sm btn-primary" href="<?= _SERVER_ ?>Cobros/pagos/<?= $c->id_prestamos ?>">
                                            <i class="fa fa-eye"></i> Vista Previa
                                        </a>
                                        <br>
                                        <a target="_blank" class="text-white btn btn-sm btn-danger" href="<?= _SERVER_ ?>Prestamos/generar_documento/<?= $c->id_prestamos ?>">
                                            <i class="fa fa-file-pdf-o"></i> Imprimir
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