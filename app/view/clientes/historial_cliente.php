<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 m-0 font-weight-bold text-white">
                        <i class="fa fa-user me-2"></i>Historial del Cliente
                    </h2>
                </div>
                <a href="<?= _SERVER_ ?>Clientes/inicio" class="btn btn-light">
                    <i class="fa fa-arrow-left me-2"></i>Volver a Clientes
                </a>
            </div>
        </div>
        <br>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-70px symbol-circle me-4">
                            <span class="symbol-label bg-light-primary text-primary fs-2 fw-bold">
                                <?= $data_cliente->cliente_nombre[0].$data_cliente->cliente_apellido_paterno[0] ?>
                            </span>
                        </div>
                        <div>
                            <h3 class="mb-0">
                                <?= $data_cliente->cliente_nombre .' '.$data_cliente->cliente_apellido_paterno . ' ' . 
                                $data_cliente->cliente_apellido_materno ?>
                            </h3>
                            <div class="text-muted">DNI: <?= $data_cliente->cliente_dni ?></div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">Línea de Crédito</div>
                                <div class="fw-bold fs-5 text-success">S/ <?= $data_cliente->cliente_credito ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">Préstamos Activos</div>
                                <?php
								$prestamos_activos = 0;
                                foreach ($prestamos_cliente as $pc){
                                    if($pc->prestamo_estado == 1){
                                        $prestamos_activos++;
                                    }
                                }
                                ?>
                                <div class="fw-bold fs-5 text-primary"><?= $prestamos_activos ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fa fa-file-invoice-dollar me-2"></i>Préstamos Realizados
                    </h5>
                </div>
                <hr>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0">
                            <thead class="bg-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Garante</th>
                                <th>Tipo</th>
                                <th>Cuotas</th>
                                <th>Interés</th>
                                <th>Garantía</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>

							<?php
							foreach ($prestamos_cliente as $pc){
								?>
                                <tr>
                                    <td><?= $pc->prestamo_fecha ?></td>
                                    <td class="fw-bold">S/ <?= $pc->prestamo_monto ?></td>
                                    <td> <?= $pc->cliente_nombre . ' ' . $pc->cliente_apellido_paterno ?> </td>
                                    <td><?= $pc->prestamo_tipo_pago ?></td>
                                    <td><?= $pc->prestamo_num_cuotas ?></td>
                                    <td><?= $pc->prestamo_interes ?>%</td>
                                    <td><?= $pc->prestamo_garantia ?></td>
                                    <td>
										<?php
										if($pc->prestamo_estado == 1){
											$estado = 'Activo';
										}else if($pc->prestamo_estado == 2){
											$estado = 'Cancelado';
										}else if($pc->prestamo_estado == 3){
                                            $estado = 'Préstamo Antiguo';
										}else if($pc->prestamo_estado == 4){
                                            $estado = 'Cancelado';
										}
										if($pc->prestamo_estado == 1){
											echo '<span class="badge bg-success">'.$estado.'</span>';
										}else if($pc->prestamo_estado == 2 || $pc->prestamo_estado == 4 || $pc->prestamo_estado == 3){
											echo '<span class="badge bg-danger">'.$estado.'</span>';
										}
										?>
                                    </td>
                                    <td>
                                        <!--<a href="<?php /*= _SERVER_ */?>Prestamos/detalles/<?php /*= $pc->id_prestamos */?>" class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye me-1"></i>Detalles
                                </a>-->
										<?php
										if($pc->prestamo_estado == 1 || $pc->prestamo_estado == 4 || $pc->prestamo_estado == 3){
											?>
                                            <a href="<?= _SERVER_ ?>Cobros/pagar/<?= $pc->id_prestamos ?>" class="btn btn-sm btn-primary">
                                                <i class="fa fa-money me-1"></i>Pagar
                                            </a>
											<?php
										}
										?>

                                    </td>
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
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fa fa-file-invoice-dollar me-2"></i>Cambios Realizados a los Datos
                    </h5>
                </div>
                <hr>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="align-middle">#</th>
                                <th class="align-middle">Nombre Completo</th>
                                <th class="align-middle">DNI</th>
                                <th class="align-middle">Cónyuge</th>
                                <th class="align-middle">Contacto</th>
                                <th class="align-middle">Dirección</th>
                                <th class="align-middle">Línea Crédito</th>
                            </tr>
                            </thead>
                            <tbody>

							<?php
							$contador_cliente = 1;
							foreach ($data_cliente_h as $c){
								?>
                                <tr class="text-center">
                                    <td class="align-middle"><?=$contador_cliente?></td>
                                    <td class="align-middle text-left">
                                        <div class="fw-bold"><?=$c->cliente_nombre?></div>
                                        <small class="text-muted">
											<?=$c->cliente_apellido_paterno . ' ' . $c->cliente_apellido_materno?>
                                        </small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary"><?=$c->cliente_dni?></span>
                                    </td>
                                    <td class="align-middle"><?=$c->cliente_pareja ?: '--'?></td>
                                    <td class="align-middle">
                                        <div><?=$c->cliente_celular?></div>
                                        <small class="text-info"><?=$c->cliente_correo ?: ''?></small>
                                    </td>
                                    <td class="align-middle">
                                        <div><?=$c->cliente_direccion?></div>
                                        <small class="text-muted"><?=$c->cliente_referencia?></small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-success rounded-pill">
                                            S/ <?=number_format($c->cliente_credito, 2)?>
                                        </span>
                                    </td>
                                </tr>
								<?php
								$contador_cliente++;
							}
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold">
                        <i class="fa fa-file-invoice-dollar me-2"></i>Motivos de cambio de moroso
                    </h5>
                </div>
                <hr>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0">
                            <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Motivo</th>
                            </tr>
                            </thead>
                            <tbody>

							<?php
							$contador_cliente = 1;
							foreach ($morosos_motivos as $c){
								?>
                                <tr>
                                    <td><?=$contador_cliente?></td>
                                    <td ><?=$c->cliente_historial_moroso_comentario ?: '--'?></td>
                                </tr>
								<?php
								$contador_cliente++;
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