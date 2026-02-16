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
								<th>Datos</th>
								<th>Monto Prestado</th>
								<th>Fecha de cobro</th>
								<th>Días de Mora</th>
								<th>Motivo de Préstamo</th>
								<th><small>Comentarios</small></th>
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
									<td><?= $a  ?></td>
									<td style="width:50px">
                                        <small>DNI: <b><?= $c->cliente_dni ?></b></small> <br>
									    <small>Nombre: </small>
                                        <small><b>
                                            <?= $c->cliente_nombre .' '.
                                            $c->cliente_apellido_paterno.' '.
                                            $c->cliente_apellido_materno ?></b> </small></td>
                                    <td>
                                        <small>Total:   <?= $c->prestamo_monto + $c->prestamo_monto_interes ?></small><br>
                                      <small>Saldo:   <?= $c->prestamo_saldo_pagar ?> </small><br>
                                       <b> <?= $c->prestamo_tipo_pago ?></b>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <small><?= $c->prestamo_prox_cobro ?> </small>
                                    </td>
                                    <td>0</td>
                                    <td><?= $c->prestamo_motivo ?></td>
                                    <td><?= $c->prestamo_comentario ?></td>
                                    <td>
										<?php
										if ($c->prestamo_estado == 1) {
											echo '<small>Activo</small>';
										} else if ($c->prestamo_estado == 2) {
											echo '<small>Cancelado</small>'; // Rojo para cancelado
										} else if ($c->prestamo_estado == 3) {
											echo '<small>Préstamo Antiguo</small>'; // Amarillo (advertencia)
										} else if ($c->prestamo_estado == 4) {
											echo '<small>Préstamo Antiguo Cancelado</small>'; // Gris (secundario)
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


										<?php
										if($c->prestamo_estado != 3){
											?>
                                            <a onclick="preguntar('...','transferir_prestamo','SI','NO','<?= $c->id_prestamos ?>')"
                                               class="btn btn-sm btn-secondary text-white mt-1"
                                               style="cursor:pointer; white-space:nowrap; display:inline-flex; align-items:center; gap:6px;">
                                                <i class="fa fa-refresh"></i> Transferir
                                            </a>
											<?php
										}
										?>

                                        <br>
                                        <a class="text-white btn btn-sm btn-primary m-1" href="<?= _SERVER_ ?>Cobros/pagos/<?= $c->id_prestamos ?>" style="white-space:nowrap">
                                            <i class="fa fa-eye"></i>Previsualización
                                        </a> <br>

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