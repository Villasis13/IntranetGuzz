<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header bg-gradient-primary py-3">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="m-0 font-weight-bold text-white">
							<i class="fas fa-users me-2"></i>CLIENTES REGISTRADOS
						</h5>
						<button onclick="limpiar_clientes()" data-toggle="modal" data-target="#gestionCliente" class="btn btn-success btn-sm shadow-sm">
							<i class="fa fa-plus-circle me-2"></i>Nuevo Cliente
						</button>
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
								?>
								<tr class="text-center">
									<td><?= $a ?></td>
									<td><?= $c->cliente_dni ?></td>
									<td><?= $c->cliente_nombre .' '.
                                        $c->cliente_apellido_paterno.' '.
                                        $c->cliente_apellido_materno ?></td>
                                    <td><?= $c->prestamo_monto ?></td>
                                    <td></td>
                                    <td></td>
                                    <td><?= $c->prestamo_tipo_pago ?></td>
                                    <td></td>
                                    <td><?= $c->prestamo_motivo ?></td>
                                    <td><?= $c->prestamo_comentario ?></td>
                                    <td></td>
                                    <td>
                                        <a href="<?= _SERVER_ ?>prestamos/detalles/<?= $c->id_prestamos ?>" class="btn-sm btn-warning text-white">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <br>
                                        <a href="<?= _SERVER_ ?>cobros/pagar/<?= $c->id_prestamos ?>" style="cursor: pointer" class="btn-sm btn-success text-white">
                                            <i class="fa fa-money"></i>
                                        </a>
                                        <br>
                                        <a onclick="preguntar('<i class=\'fa fa-exclamation-circle text-warning\'></i> ' +
                                                'Cuidado. Está a punto de transferir un préstamo normal a ' +
                                                'préstamo antiguo. ¿Seguro que desea hacerlo? ' +
                                                'Se le recuerda que no hay vuelta atrás ' +
                                                'una vez hecho el cambio.',
                                                'transferir_prestamo','SI','NO', '<?= $c->id_prestamos ?>')"
                                           style="cursor: pointer" class="btn-sm btn-primary text-white">
                                            <i class="fa fa-refresh"></i>
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