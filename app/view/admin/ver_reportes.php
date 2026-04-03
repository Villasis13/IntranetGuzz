<?php if(!empty($reporte)): ?>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3">
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Pagos del reporte</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha/Hora</th>
                            <th>Monto</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $c = 1;
                        $total_ingresos = 0;
                        foreach ($reporte as $rp):
                            $total_ingresos += $rp->pago_monto;
                            ?>
                            <tr class="text-center">
                                <td><?= $c++ ?></td>
                                <td><?= $rp->cliente_nombre . ' ' . $rp->cliente_apellido_paterno ?></td>
                                <td><?= $rp->pago_fecha ?></td>
                                <td>S/ <?= number_format($rp->pago_monto, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr class="text-end table-success fw-bold">
                            <td colspan="3">Total Ingresos</td>
                            <td class="text-center">S/ <?= number_format($total_ingresos, 2) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3">
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Pagos del reporte</h5>
                </div>
                <div class="text-center py-4">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Ingresos no registrados en este período</h5>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($egresos)): ?>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3">
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Préstamos del reporte</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <thead class="text-center">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha/Hora</th>
                            <th>Próximo cobro</th>
                            <th>Monto (Con Intereses)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $c = 1;
                        $total_egresos = 0;
                        foreach ($egresos as $eg):
                            $total_egresos += $eg->prestamo_monto + $eg->prestamo_monto_interes;
                            ?>
                            <tr class="text-center">
                                <td><?= $c++ ?></td>
                                <td><?= $eg->cliente_nombre . ' ' . $eg->cliente_apellido_paterno ?></td>
                                <td><?= $eg->prestamo_fecha ?></td>
                                <td><?= $eg->prestamo_prox_cobro ?></td>
                                <td>S/ <?= number_format($eg->prestamo_monto + $eg->prestamo_monto_interes, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr class="text-end table-danger fw-bold">
                            <td colspan="4">Total Egresos</td>
                            <td class="text-center">S/ <?= number_format($total_egresos, 2) ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3">
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Préstamos del reporte</h5>
                </div>
                <div class="text-center py-4">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Egresos no registrados en este período</h5>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>