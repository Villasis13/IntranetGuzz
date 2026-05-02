
<div class="col-12 px-3 mb-3">
    <div class="card shadow">
        <div class="card-body d-flex align-items-center justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fa fa-bar-chart me-1"></i> Resultado del reporte
            </h6>
            <form method="POST" action="<?= _SERVER_ ?>reporte/exportar_pdf_reporte" target="_blank">
                <input type="hidden" name="tipo"             value="<?= htmlspecialchars($_POST['tipo'] ?? '') ?>">
                <input type="hidden" name="Ingresos"         value="<?= htmlspecialchars($_POST['Ingresos'] ?? '') ?>">
                <input type="hidden" name="Egresos"          value="<?= htmlspecialchars($_POST['Egresos'] ?? '') ?>">
                <input type="hidden" name="fecha_diaria"     value="<?= htmlspecialchars($_POST['fecha_diaria'] ?? '') ?>">
                <input type="hidden" name="mes_seleccionado" value="<?= htmlspecialchars($_POST['mes_seleccionado'] ?? '') ?>">
                <input type="hidden" name="fecha_desde"      value="<?= htmlspecialchars($_POST['fecha_desde'] ?? '') ?>">
                <input type="hidden" name="fecha_hasta"      value="<?= htmlspecialchars($_POST['fecha_hasta'] ?? '') ?>">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fa fa-file-pdf-o me-1"></i> Exportar PDF
                </button>
            </form>
        </div>
    </div>
</div>
<?php if(!empty($reporte)): ?>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3">
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Pagos del reporte</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <thead class="text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Fecha Cuota</th>
                            <th>Fecha de Registro</th>
                            <th>Usuario</th>
                            <th>Cliente</th>
                            <th>Método de Pago</th>
                            <th>Monto de Cuota</th>
                            <th>Descuento Aplicado</th>
                            <th>Monto Pagado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $c = 1;
                        $total_ingresos = 0;

                        if (!empty($reporte)):
                            foreach ($reporte as $rp):
                                $total_ingresos += $rp->pago_monto;
                                ?>
                                <tr class="text-center">
                                    <td><?= $c++ ?></td>
                                    <td><small><?= date('d/m/Y', strtotime($rp->pago_diario_fecha)) ?></small></td>
                                    <td><small><?= date('d/m/Y H:i', strtotime($rp->pago_fecha)) ?></small></td>
                                    <td><small><?= htmlspecialchars($rp->usuario_nickname) ?></small></td>
                                    <td><small><?= htmlspecialchars($rp->cliente_nombre . " " . $rp->cliente_apellido_paterno) ?></small></td>
                                    <td><small><?= htmlspecialchars($rp->metodo_pago_nombre) ?></small></td>
                                    <td><small>S/ <?= number_format($rp->pago_diario_monto, 2) ?></small></td>

                                    <?php
                                    // CORRECCIÓN VITAL: Se usa "==" para preguntar, no "=" que asigna el valor
                                    if ($rp->pago_descuento_estado == 1) {
                                        ?>
                                        <td class="text-danger"><small>S/ <?= number_format($rp->pago_descuento_monto ?? 0, 2) ?></small></td>
                                        <td class="text-success fw-bold"><small>S/ <?= number_format($rp->pago_monto, 2) ?></small></td>
                                    <?php } else { ?>
                                        <td><small class="text-muted">No aplica</small></td>
                                        <td class="text-success fw-bold"><small>S/ <?= number_format($rp->pago_monto, 2) ?></small></td>
                                    <?php } ?>
                                </tr>
                            <?php
                            endforeach;
                        endif;
                        ?>
                        </tbody>
                        <tfoot>
                        <tr class="text-end table-success fw-bold">
                            <!-- El colspan debe ser 8 para que el total quede en la 9na columna (Monto Pagado) -->
                            <td colspan="8">Total Ingresos:</td>
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
                        <thead class="text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Fecha de Emisión</th>
                            <th>Fecha de Inicio</th>
                            <th>Periodicidad</th>
                            <th>Usuario</th>
                            <th>Cliente</th>
                            <th>Capital</th>
                            <th>Interés</th>
                            <th>Total Deuda</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $c = 1;
                        $total_egresos = 0;

                        // Añadimos una validación por si en algún momento no hay registros
                        if (!empty($egresos)):
                            foreach ($egresos as $eg):
                                $total_prestamo = $eg->prestamo_monto + $eg->prestamo_monto_interes;
                                $total_egresos += $total_prestamo;
                                ?>
                                <tr class="text-center">
                                    <td><?= $c++ ?></td>
                                    <td><small><?= date('d/m/Y', strtotime($eg->prestamo_fecha)) ?></small></td>
                                    <td><small><?= date('d/m/Y', strtotime($eg->prestamo_fecha_inicio)) ?></small></td>
                                    <td class="text-capitalize"><small><?= htmlspecialchars($eg->prestamo_tipo_pago) ?></small></td>
                                    <td><small><?= htmlspecialchars($eg->usuario_nickname) ?></small></td>
                                    <td><small><?= htmlspecialchars($eg->cliente_nombre. " ". $eg->cliente_apellido_paterno) ?></small></td>
                                    <td><small>S/ <?= number_format($eg->prestamo_monto, 2) ?></small></td>
                                    <td><small>S/ <?= number_format($eg->prestamo_monto_interes, 2) ?></small></td>
                                    <td class="fw-bold text-danger"><small>S/ <?= number_format($total_prestamo, 2) ?></small></td>
                                </tr>
                            <?php
                            endforeach;
                        endif;
                        ?>
                        </tbody>
                        <tfoot>
                        <tr class="text-end table-danger fw-bold">
                            <!-- El colspan ahora es 8 para alinear perfectamente con las 9 columnas -->
                            <td colspan="8">Total Egresos:</td>
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

