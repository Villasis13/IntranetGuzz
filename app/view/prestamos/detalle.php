<!--Contenido-->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-invoice-dollar me-2"></i>Detalle del Préstamo
        </h1>
        <a href="#" class="btn btn-success">
            <i class="fa fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
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
                    <a href="#" class="btn btn-outline-dark">
                        <i class="fas fa-calendar-alt me-2"></i>Ver Cronograma
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
</style>