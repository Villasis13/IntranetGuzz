<style>
    .panel {
        background-color: #ffffff;
        border: 1px solid #cce5ff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
        overflow: hidden;
    }

    .panel-heading {
        background-color: #007bff;
        color: #ffffff;
        padding: 15px;
        border-bottom: 1px solid #cce5ff;
    }

    .panel-title {
        font-size: 20px;
        font-weight: bold;
        margin: 0;
        text-align: center;
    }

    .panel-body {
        background-color: #ffffff;
        padding: 25px;
    }

    fieldset {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    legend {
        font-size: 16px;
        font-weight: bold;
        padding: 0 10px;
    }
</style>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


<div class="container-fluid">
    <div class="contenido">
        <div class="row mt-4 justify-content-center">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">REPORTES</h3>
                    </div>

                    <div class="panel-body">
                        <form method="post" action="Reporte.php" target="_blank">
                            <fieldset>
                                <legend>Tipo de Reporte</legend>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-center">
                                        <label for="diario">Diario</label><br>
                                        <input type="radio" checked id="diario" name="tipo" value="diario">
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label for="mensual">Mensual</label><br>
                                        <input type="radio" id="mensual" name="tipo" value="mensual">
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label for="fechas">Intervalo de Fechas</label><br>
                                        <input type="radio" id="fechas" name="tipo" value="fechas">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Desde</label>
                                        <input type="date" disabled class="form-control" name="fecha_prestamo" id="fecha_prestamo">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Hasta</label>
                                        <input type="date" disabled class="form-control" name="fecha_prox_cobro" id="fecha_prox_cobro">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>A considerar</legend>
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <label for="Ingresos">Ingresos</label><br>
                                        <input type="checkbox" checked id="Ingresos" name="Ingresos" value="Ingresos">
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <label for="Egresos">Egresos</label><br>
                                        <input type="checkbox" id="Egresos" name="Egresos" value="Egresos">
                                    </div>
                                    <div class="col-md-4 text-center d-flex align-items-end justify-content-center">
                                        <input type="submit" class="btn btn-primary" value="Generar Reporte">
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                        <hr>

                        <form method="post" action="Reporte_posterior.php" target="_blank">
                            <fieldset>
                                <legend>Reporte día posterior</legend>
                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="fecha_desde">Desde</label>
                                        <input type="date" id="fecha_desde" name="fecha_desde" class="form-control">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="fecha_hasta">Hasta</label>
                                        <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end justify-content-center">
                                        <input type="submit" class="btn btn-success w-100" value="Generar">
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // Deshabilita ambos al inicio
        $('#fecha_prestamo, #fecha_prox_cobro').prop('disabled', true);

        // Habilita cuando se selecciona el tipo "fechas"
        $('input[name=tipo]').change(function () {
            if ($('#fechas').is(':checked')) {
                $('#fecha_prestamo, #fecha_prox_cobro').prop('disabled', false);
            } else {
                $('#fecha_prestamo, #fecha_prox_cobro').prop('disabled', true);
                $('#fecha_prestamo, #fecha_prox_cobro').val('');
            }
        });

        // Establece la fecha mínima en "Hasta" según "Desde"
        $('#fecha_prestamo').on('change', function () {
            let desde = $(this).val();
            $('#fecha_prox_cobro').attr('min', desde);
        });
    });
</script>
