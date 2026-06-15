
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


<?php if(!empty($reporte_cuotas)): ?>
    <div class="col-12 px-3">
        <div class="card shadow mb-3 my-3">
            <div class="card-body mt-3">
                <div class="card-header py-3 bg-primary">
                    <h5 class="m-0 font-weight-bold text-white">Pago de Cuotas</h5>
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
                        $total_nominal    = 0;
                        $total_descuentos = 0;
                        $total_cobrado    = 0;
                        foreach ($reporte_cuotas as $rp):
                            $descuento_fila    = ($rp->pago_descuento_estado == 1) ? floatval($rp->pago_descuento_monto ?? 0) : 0;
                            $total_nominal    += floatval($rp->pago_diario_monto);
                            $total_descuentos += $descuento_fila;
                            $total_cobrado    += floatval($rp->pago_monto);
                            ?>
                            <tr class="text-center">
                                <td><?= $c++ ?></td>
                                <td><small><?= date('d/m/Y', strtotime($rp->pago_diario_fecha)) ?></small></td>
                                <td><small><?= date('d/m/Y H:i', strtotime($rp->pago_fecha)) ?></small></td>
                                <td><small><?= htmlspecialchars($rp->usuario_nickname) ?></small></td>
                                <td><small><?= htmlspecialchars($rp->cliente_nombre . " " . $rp->cliente_apellido_paterno) ?></small></td>
                                <td><small><?= htmlspecialchars($rp->metodo_pago_nombre) ?></small></td>
                                <td><small>S/ <?= number_format($rp->pago_diario_monto, 2) ?></small></td>
                                <?php if ($rp->pago_descuento_estado == 1): ?>
                                    <td class="text-danger"><small>- S/ <?= number_format($descuento_fila, 2) ?></small></td>
                                    <td class="text-success fw-bold"><small>S/ <?= number_format($rp->pago_monto, 2) ?></small></td>
                                <?php else: ?>
                                    <td><small class="text-muted">—</small></td>
                                    <td class="text-success fw-bold"><small>S/ <?= number_format($rp->pago_monto, 2) ?></small></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr class="text-end table-light">
                            <td colspan="6"></td>
                            <td class="fw-bold text-muted"><small>Total nominal:</small></td>
                            <td class="text-danger fw-bold"><small>- S/ <?= number_format($total_descuentos, 2) ?></small></td>
                            <td class="text-muted fw-bold text-center"><small>S/ <?= number_format($total_nominal, 2) ?></small></td>
                        </tr>
                        <tr class="text-end table-success fw-bold">
                            <td colspan="8">Total Cobrado:</td>
                            <td class="text-center">S/ <?= number_format($total_cobrado, 2) ?></td>
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
                    <h5 class="m-0 font-weight-bold text-white">Pago de Cuotas</h5>
                </div>
                <div class="text-center py-4">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay cuotas registradas en este período</h5>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php /* SECCIÓN AMORTIZACIONES oculta temporalmente */ ?>

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
                        $total_capital = 0;
                        $total_interes = 0;
                        $total_deuda   = 0;

                        if (!empty($egresos)):
                            foreach ($egresos as $eg):
                                $monto_capital  = $eg->prestamo_monto;
                                $monto_interes  = $eg->prestamo_monto_interes;
                                $total_prestamo = $monto_capital + $monto_interes;
                                $es_anulado     = (intval($eg->prestamo_estado) === 5);
                                $cuotas         = $eg->prestamo_cuotas ?? '-';

                                // Los anulados se muestran pero NO cuentan en los totales
                                if (!$es_anulado) {
                                    $total_capital += $monto_capital;
                                    $total_interes += $monto_interes;
                                    $total_deuda   += $total_prestamo;
                                }
                                ?>
                                <tr class="text-center <?= $es_anulado ? 'table-danger' : '' ?>">
                                    <td>
                                        <small><?= $c++ ?></small>
                                        <?php if ($es_anulado): ?>
                                            <br><span class="badge bg-danger" style="font-size:0.6em;">ANULADO</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?= date('d/m/Y', strtotime($eg->prestamo_fecha)) ?></small></td>
                                    <td><small><?= date('d/m/Y', strtotime($eg->prestamo_fecha_inicio)) ?></small></td>
                                    <td class="text-capitalize">
                                        <small><?= htmlspecialchars($eg->prestamo_tipo_pago) ?></small>
                                        <br><small class="text-muted"><?= $cuotas ?> cuotas</small>
                                    </td>
                                    <td><small><?= htmlspecialchars($eg->usuario_nickname) ?></small></td>
                                    <td><small><?= htmlspecialchars($eg->cliente_nombre . ' ' . $eg->cliente_apellido_paterno) ?></small></td>
                                    <td class="<?= $es_anulado ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <small>S/ <?= number_format($monto_capital, 2) ?></small>
                                        <?php if ($es_anulado): ?>
                                            <br><small class="text-danger fw-bold" style="text-decoration:none;">Monto anulado</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="<?= $es_anulado ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <small>S/ <?= number_format($monto_interes, 2) ?></small>
                                    </td>
                                    <td class="fw-bold <?= $es_anulado ? 'text-muted text-decoration-line-through' : 'text-danger' ?>">
                                        <small>S/ <?= number_format($total_prestamo, 2) ?></small>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        endif;
                        ?>
                        </tbody>
                        <tfoot>
                        <tr class="text-end table-danger fw-bold">
                            <td colspan="6" class="text-end">Total Capital:</td>
                            <td class="text-center">S/ <?= number_format($total_capital, 2) ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="text-end table-warning fw-bold">
                            <td colspan="8" class="text-end">Total Capital + Interés:</td>
                            <td class="text-center" style="font-size: 1.1em;">S/ <?= number_format($total_deuda, 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="9" class="text-end text-muted fst-italic" style="font-size:0.78em;">
                                * Los registros anulados se muestran como referencia y no se incluyen en los totales.
                            </td>
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

