<style>
    :root {
        --primary-color: #007bff;
        --secondary-bg: #f8f9fa;
        --border-color: #e3e6f0;
        --text-muted: #566a7f;
    }

    .panel {
        background-color: #ffffff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .panel-heading {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: #ffffff;
        padding: 20px;
        border: none;
    }

    .panel-title {
        font-size: 1.25rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
        text-align: center;
    }

    .panel-body {
        padding: 30px;
    }

    fieldset {
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        background-color: var(--secondary-bg);
    }

    legend {
        float: none;
        width: auto;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary-color);
        padding: 0 15px;
        margin-bottom: 0;
        text-transform: uppercase;
    }

    /* Estilo para los Radio Buttons como botones de grupo */
    .report-type-group .btn-check:checked + .btn {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }

    .report-type-group .btn {
        border: 1px solid var(--border-color);
        background-color: #fff;
        color: var(--text-muted);
        padding: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .alert-info {
        background-color: #e7f3ff;
        border: none;
        border-left: 4px solid var(--primary-color);
        color: #0056b3;
        font-size: 0.85rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 10px 15px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
    }

    /* Checkboxes personalizados */
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-generate {
        padding: 12px;
        font-weight: bold;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>

<div class="container-fluid">
    <div class="contenido">
        <div class="row mt-5 justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-chart-line me-2"></i> Reportes de Caja</h3>
                    </div>

                    <div class="panel-body">
                        <form method="post" action="<?= _SERVER_ ?>Reporte/ver_reportes" target="_blank">

                            <fieldset>
                                <legend>Configuración de Filtro</legend>

                                <div class="btn-group w-100 report-type-group mb-4" role="group">
                                    <input type="radio" class="btn-check" name="tipo" id="diario" value="diario" checked>
                                    <label class="btn btn-outline-primary" for="diario">
                                        <i class="fa fa-calendar-day d-block mb-1"></i> Diario
                                    </label>

                                    <input type="radio" class="btn-check" name="tipo" id="mensual" value="mensual">
                                    <label class="btn btn-outline-primary" for="mensual">
                                        <i class="fa fa-calendar-alt d-block mb-1"></i> Mensual
                                    </label>

                                    <input type="radio" class="btn-check" name="tipo" id="fechas" value="fechas">
                                    <label class="btn btn-outline-primary" for="fechas">
                                        <i class="fa fa-calendar-week d-block mb-1"></i> Intervalo
                                    </label>
                                </div>

                                <div class="alert alert-info py-3 mb-4" id="descripcion_reporte">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-info-circle me-3" style="font-size: 1.2rem;"></i>
                                        <span id="texto_descripcion">Generar el reporte basado en las operaciones realizadas el día de hoy.</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-2" id="div_diario">
                                        <label class="form-label fw-bold small text-muted">Seleccionar Fecha</label>
                                        <input type="date" class="form-control" name="fecha_diaria" id="fecha_diaria" value="<?= date('Y-m-d') ?>">
                                    </div>

                                    <div class="col-12 mb-2" id="div_mensual" style="display:none;">
                                        <label class="form-label fw-bold small text-muted">Seleccionar Mes (Año <?= date('Y') ?>)</label>
                                        <select class="form-select" name="mes_seleccionado" id="mes_seleccionado">
                                            <?php
                                            $mes_actual = date('n');
                                            $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"];
                                            foreach ($meses as $index => $nombre):
                                                $valor = $index + 1; ?>
                                                <option value="<?= $valor ?>" <?= ($valor == $mes_actual) ? 'selected' : '' ?>><?= $nombre ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-2 div_intervalo" style="display:none;">
                                        <label class="form-label fw-bold small text-muted">Desde</label>
                                        <input type="date" class="form-control" name="fecha_desde" id="fecha_desde">
                                    </div>
                                    <div class="col-md-6 mb-2 div_intervalo" style="display:none;">
                                        <label class="form-label fw-bold small text-muted">Hasta</label>
                                        <input type="date" class="form-control" name="fecha_hasta" id="fecha_hasta">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset>
                                <legend>Contenido del Reporte</legend>
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-2 mb-md-0">
                                            <input class="form-check-input" type="checkbox" checked id="Ingresos" name="Ingresos" value="Ingresos">
                                            <label class="form-check-label fw-600" for="Ingresos">Ingresos</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3 mb-md-0">
                                            <input class="form-check-input" type="checkbox" id="Egresos" name="Egresos" value="Egresos">
                                            <label class="form-check-label fw-600" for="Egresos">Egresos</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-generate w-100">
                                            <i class="fa fa-file-pdf me-2"></i> Generar
                                        </button>
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
        const descripciones = {
            diario:  'Generar el reporte basado en las operaciones realizadas el día de hoy o la fecha seleccionada.',
            mensual: 'Consolidado de todas las transacciones correspondientes al mes elegido del presente año.',
            fechas:  'Reporte detallado de los movimientos registrados entre el rango de fechas establecido.'
        };

        $('input[name="tipo"]').change(function () {
            let valor = $(this).val();

            // Animación suave de cambio de texto
            $('#texto_descripcion').fadeOut(200, function() {
                $(this).text(descripciones[valor]).fadeIn(200);
            });

            // Control de visibilidad con efectos de slide
            if (valor === 'diario') {
                $('#div_diario').slideDown();
                $('#div_mensual, .div_intervalo').slideUp();
            } else if (valor === 'mensual') {
                $('#div_mensual').slideDown();
                $('#div_diario, .div_intervalo').slideUp();
            } else if (valor === 'fechas') {
                $('.div_intervalo').css('display', 'flex').hide().slideDown();
                $('#div_diario, #div_mensual').slideUp();
            }
        });

        $('#fecha_desde').on('change', function () {
            $('#fecha_hasta').attr('min', $(this).val());
        });
    });
</script>