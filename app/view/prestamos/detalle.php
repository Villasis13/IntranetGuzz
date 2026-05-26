<!--Contenido-->
<?php
$estado = intval($data_prestamo->prestamo_estado ?? 0);

$estados_info = [
    1 => ['label' => 'Activo',            'badge' => 'success',   'icono' => 'fa-check-circle'],
    2 => ['label' => 'Cancelado',         'badge' => 'secondary', 'icono' => 'fa-lock'],
    3 => ['label' => 'Antiguo',           'badge' => 'info',      'icono' => 'fa-archive'],
    4 => ['label' => 'Antiguo Cancelado', 'badge' => 'secondary', 'icono' => 'fa-lock'],
    5 => ['label' => 'Anulado',           'badge' => 'danger',    'icono' => 'fa-ban'],
];

$info_estado = $estados_info[$estado] ?? ['label' => 'Desconocido', 'badge' => 'dark', 'icono' => 'fa-question'];
?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar me-2"></i>Detalle del Préstamo
            <span class="badge bg-<?= $info_estado['badge'] ?> ms-2 fs-6 align-middle">
                <i class="fa <?= $info_estado['icono'] ?> me-1"></i><?= $info_estado['label'] ?>
            </span>
        </h1>
        <a href="javascript:history.back()" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <?php if ($estado !== 1): ?>
    <div class="alert alert-<?= $estado === 5 ? 'danger' : 'secondary' ?> border-2 mb-4 shadow text-center py-4" role="alert">
        <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
            <i class="fa <?= $info_estado['icono'] ?> fa-3x"></i>
            <span class="fw-black text-uppercase fs-2 lh-1 label-estado-prestamo">
                <?= $estado === 5 ? 'PRÉSTAMO ANULADO' : htmlspecialchars($info_estado['label']) ?>
            </span>
        </div>
        <p class="mb-0 fs-6">
            <?php if ($estado === 5): ?>
                Este préstamo fue anulado y ya no se encuentra activo. No es posible registrar pagos ni amortizaciones sobre este registro.
            <?php elseif ($estado === 2 || $estado === 4): ?>
                Este préstamo ha sido cancelado y se encuentra cerrado.
            <?php elseif ($estado === 3): ?>
                Este préstamo figura como antiguo. Puede consultarse pero no admite nuevas operaciones.
            <?php endif; ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="card shadow mb-4 position-relative overflow-hidden <?= $estado === 5 ? 'border-danger border-2' : '' ?>">
        <?php if ($estado === 5): ?>
        <div class="ribbon-anulado">ANULADO</div>
        <?php endif; ?>
        <div class="card-header bg-<?= $estado === 5 ? 'danger' : 'primary' ?> text-white py-3">
            <h5 class="m-0 font-weight-bold text-white">
                <i class="fa fa-user me-2"></i>Datos del Cliente
            </h5>
        </div>
        <br>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Nombre:</label>
                        <span><?= $data_cliente->cliente_nombre . ' ' .
							$data_cliente->cliente_apellido_paterno . ' ' . $data_cliente->cliente_apellido_materno ?></span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">DNI:</label>
                        <?= $data_cliente->cliente_dni ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Monto Prestado:</label>
                        S/ <?= $data_prestamo->prestamo_monto ?>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Interés:</label>
						<?= $data_prestamo->prestamo_interes ?> %
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-warning py-3">
            <h5 class="m-0 font-weight-bold text-white">
                <i class="fa fa-info-circle me-2"></i>Condiciones del Préstamo
            </h5>
        </div>
        <br>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="fw-bold">Fecha de Préstamo:</label>
                    <span><?= $data_prestamo->prestamo_fecha ?> </span>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Tipo de Pago:</label>
					<?= $data_prestamo->prestamo_tipo_pago ?>
                </div>
                <div class="col-md-4">
                    <a target="_blank"
                       href="<?= _SERVER_ ?>/Prestamos/generar_documento/<?= $id_prestamos ?>"
                       class="btn btn-outline-<?= $estado === 5 ? 'danger disabled' : 'dark' ?>"
                       <?= $estado === 5 ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                        <i class="fas fa-calendar-alt me-2"></i>Ver Cronograma
                        <?= $estado === 5 ? '<small class="ms-1">(anulado)</small>' : '' ?>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="fw-bold">Motivo:</label>
                    <p><?= $data_prestamo->prestamo_motivo ?> </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white py-3">
            <h5 class="m-0 font-weight-bold text-white">
                <i class="fa fa-history me-2"></i>Historial de Pagos
            </h5>
        </div>
        <br>
        <div class="card-body">
            <div class="mb-5">
                <h4 class="text-primary mb-3">
                    Periodo Actual
					(<?php
					$meses = [
						'01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
						'04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
						'07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
						'10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
					];

					$mes = date('m');
					$anio = date('Y');
					echo $meses[$mes] . ' ' . $anio;
					?>)


                </h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Recibo</th>
                            <th>Monto</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
<!--                            <td>20/07/2024</td>-->
<!--                            <td>CB-001</td>-->
<!--                            <td class="text-success">S/ 150.00</td>-->
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-success mt-3">
                    Total Pagado Este Periodo: <strong>S/ 0.00</strong>
                </div>
            </div>

            <div class="mt-5">
                <h4 class="text-primary mb-3">
                    Periodos Anteriores
                </h4>
                
<!--                <div class="card mb-4">-->
<!--                    <div class="card-header bg-light">-->
<!--                        <h5>Junio 2024</h5>-->
<!--                    </div>-->
<!--                    <div class="card-body">-->
<!--                        <div class="table-responsive">-->
<!--                            <table class="table table-sm">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th>Fecha</th>-->
<!--                                    <th>Recibo</th>-->
<!--                                    <th>Monto</th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                <tr>-->
<!--                                    <td>15/06/2024</td>-->
<!--                                    <td>CB-045</td>-->
<!--                                    <td>S/ 200.00</td>-->
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                        <div class="bg-light p-3 rounded mt-2">-->
<!--                            <p class="mb-1">Capital Inicial: S/ 5,000.00</p>-->
<!--                            <p class="mb-1">Total Pagado: S/ 1,200.00</p>-->
<!--                            <p class="mb-0">Saldo Final: S/ 3,800.00</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>

<!--    <div class="card shadow mb-4">-->
<!--        <div class="card-header bg-danger text-white py-3">-->
<!--            <h5 class="m-0 font-weight-bold">-->
<!--                <i class="fas fa-times-circle me-2"></i>Cancelación de Préstamo-->
<!--            </h5>-->
<!--        </div>-->
<!--        <div class="card-body">-->
<!--            <form>-->
<!--                <div class="row">-->
<!--                    <div class="col-md-8">-->
<!--                        <div class="form-floating mb-3">-->
<!--                            <input type="text" class="form-control" value="Cambio de domicilio" readonly>-->
<!--                            <label>Motivo de Cancelación</label>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <button class="btn btn-danger h-100 w-100">-->
<!--                            <i class="fas fa-ban me-2"></i>Cancelar Préstamo-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->

    <?php if (!empty($amortizaciones_detalle)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background-color: #f6a821;">
            <h5 class="m-0 font-weight-bold text-white">
                <i class="fa fa-hand-holding-usd me-2"></i>Amortizaciones Realizadas
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-muted" style="width:40px;">#</th>
                        <th class="text-muted">FECHA</th>
                        <th class="text-muted">USUARIO</th>
                        <th class="text-muted">CAPITAL ANTES</th>
                        <th style="color:#f6a821;">AMORTIZACIÓN</th>
                        <th class="text-muted">CAPITAL DESPUÉS</th>
                        <th class="text-muted">INTERÉS</th>
                        <th class="text-muted">SALDO TOTAL ANTES</th>
                        <th class="text-muted">SALDO TOTAL DESPUÉS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $n = 1; foreach ($amortizaciones_detalle as $am): ?>
                    <tr>
                        <td class="align-middle"><?= $n++ ?></td>
                        <td class="align-middle"><?= date('d/m/Y H:i', strtotime($am->pago_fecha)) ?></td>
                        <td class="align-middle">
                            <span class="badge badge-light border text-dark">
                                <i class="fa fa-user me-1 text-secondary"></i>
                                <?= htmlspecialchars($am->pago_usuario) ?>
                            </span>
                        </td>
                        <td class="align-middle text-muted">S/ <?= number_format($am->capital_antes, 2) ?></td>
                        <td class="align-middle font-weight-bold" style="color:#f6a821;">
                            - S/ <?= number_format($am->monto_amortizacion, 2) ?>
                        </td>
                        <td class="align-middle text-success font-weight-bold">S/ <?= number_format($am->capital_despues, 2) ?></td>
                        <td class="align-middle text-muted"><?= number_format($am->interes, 1) ?> %</td>
                        <td class="align-middle text-muted">S/ <?= number_format($am->saldo_total_antes, 2) ?></td>
                        <td class="align-middle text-success font-weight-bold">S/ <?= number_format($am->saldo_total_despues, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light">
                    <tr>
                        <td colspan="3" class="text-right font-weight-bold align-middle text-uppercase">
                            Total Amortizado:
                        </td>
                        <td colspan="6" class="font-weight-bold align-middle" style="color:#f6a821;">
                            - S/ <?= number_format(array_sum(array_column($amortizaciones_detalle, 'monto_amortizacion')), 2) ?>
                            <small class="text-muted ms-2">(<?= count($amortizaciones_detalle) ?> amortización<?= count($amortizaciones_detalle) > 1 ? 'es' : '' ?>)</small>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<style>
    .info-group {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .info-group label {
        min-width: 160px;
        color: #4a5568;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Ribbon diagonal "ANULADO" */
    .ribbon-anulado {
        position: absolute;
        top: 28px;
        right: -40px;
        width: 170px;
        padding: 7px 0;
        background: #dc3545;
        color: #fff;
        text-align: center;
        font-weight: 900;
        font-size: 13px;
        letter-spacing: 3px;
        transform: rotate(45deg);
        z-index: 10;
        box-shadow: 0 3px 10px rgba(220, 53, 69, 0.55);
        pointer-events: none;
        user-select: none;
    }

    /* Texto grande del banner de estado */
    .label-estado-prestamo {
        letter-spacing: 4px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
</style>