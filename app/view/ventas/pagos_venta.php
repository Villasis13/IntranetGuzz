<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-primary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 style="font-weight: bold" class="m-0 font-weight-bold">
                            Lista de Pagos Realizados
                        </h5>
                    </div>
                </div>
                <h5 style="margin-left: 2%; font-weight: bold" class="text-success">Total Pagado: S/. <?= $pagos_realizados ?></h5>

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
							foreach ($pagos_realizados_venta as $c){
								?>
                                <tr class="text-center">
                                    <td><?= $a ?></td>
                                    <td><?= $c->venta_pago_fecha ?></td>
                                    <td><?= $c->id_venta_pago ?></td>
                                    <td>S/. <?= $c->venta_pago_monto ?></td>
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
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>