<!--Contenido-->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-pie me-2"></i>Reportes
        </h1>
        <a href="Gastos.php" class="btn btn-danger shadow-sm">
            <i class="fas fa-coins me-2"></i>Gestión de Gastos
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="m-0 font-weight-bold">
                <i class="fas fa-file-alt me-2"></i>Generador de Reportes
            </h5>
        </div>
        <br>

        <div class="card-body">
            <form class="needs-validation" novalidate>
                <div class="row mb-5 g-4">
                    <div class="col-md-12">
                        <h4 class="text-primary mb-3">
                            <i class="fa fa-filter me-2"></i>Tipo de Reporte
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-body text-center">
                                <label class="form-check-label" for="diario">
                                    <i class="fas fa-sun fa-3x text-warning mb-3"></i>
                                    <h5>Diario</h5>
                                </label>
                                <input type="radio" class="form-check-input" id="diario" name="tipo" checked>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <label class="form-check-label" for="mensual">
                                    <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                                    <h5>Mensual</h5>
                                </label>
                                <input type="radio" class="form-check-input" id="mensual" name="tipo">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <label class="form-check-label" for="fechas">
                                    <i class="fas fa-calendar-day fa-3x text-success mb-3"></i>
                                    <h5>Intervalo</h5>
                                </label>
                                <input type="radio" class="form-check-input" id="fechas" name="tipo">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5 g-4">
                    <div class="col-md-12">
                        <h4 class="text-primary mb-3">
                            <i class="fa fa-calendar me-2"></i>Selección de Fechas
                        </h4>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="desde" placeholder=" " disabled>
                            <label>Fecha Inicio</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="hasta" placeholder=" " disabled>
                            <label>Fecha Fin</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-5 g-4">
                    <div class="col-md-12">
                        <h4 class="text-primary mb-3">
                            <i class="fa fa-sliders me-2"></i>Filtros Adicionales
                        </h4>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ingresos" checked>
                            <label class="form-check-label" for="ingresos">
                                <i class="fa fa-arrow-circle-down text-success me-2"></i>Ingresos
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="egresos">
                            <label class="form-check-label" for="egresos">
                                <i class="fa fa-arrow-circle-up text-danger me-2"></i>Egresos
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fa fa-file-pdf-o me-2"></i>Generar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mt-5">
        <div class="card-header bg-secondary text-white py-3">
            <h5 class="m-0 font-weight-bold">
                <i class="fas fa-chart-line me-2"></i>Reporte Histórico
            </h5>
        </div>
        <br>
        <div class="card-body">
            <form class="row g-4 align-items-end">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="fechaDesde">
                        <label>Desde</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="fechaHasta">
                        <label>Hasta</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fa fa-bar-chart me-2"></i>Generar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 15px;
    }
    .form-check-input:checked {
        background-color: #3487C9;
        border-color: #3487C9;
    }
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>