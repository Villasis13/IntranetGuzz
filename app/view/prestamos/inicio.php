<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Préstamos al Instante</h1>
        <a href="<?= _SERVER_?>Prestamos/prestamos" class="d-none d-sm-inline-block btn btn-secondary shadow-sm text-white">
            <i class="fa fa-list text-white"></i> Ver Todos los Préstamos
        </a>
    </div>

    <?php
    if($listar_estado_caja == 1){
        ?>
        <div class="card shadow mb-4">
            <div class="card-header bg-primary py-3">
                <h6 class="m-0 font-weight-bold text-white">Registro de Nuevo Préstamo</h6>
            </div>
            <div class="card-body">

                <div class="row mb-4 border-bottom pb-3">
                    <form id="formBuscar" method="post" action="inicio" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Buscar por DNI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="dni_post" id="dni_post" value="<?= $data_cliente->cliente_dni ?>" maxlength="8" >
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group">
                                <a href="<?= _SERVER_ ?>Clientes/inicio" class="btn btn-success text-white">
                                    <i class="fa fa-user-plus"></i> Nuevo Cliente
                                </a>
                                <a href="https://serviciosbiometricos.reniec.gob.pe/identifica/main.do" target="_blank" class="btn btn-info">
                                    RENIEC
                                </a>
                                <a href="https://soluciones.equifax.com.pe/efx-portal-web" target="_blank" class="btn btn-light border">
                                    Infocorp
                                </a>
                                <a href="https://www.sunarp.gob.pe/seccion/servicios/detalles/0/c3.html" target="_blank" class="btn btn-dark">
                                    SUNARP
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $data_cliente->id_cliente ?>">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Cliente</label>
                        <input type="text" class="form-control bg-light" value="<?= $data_cliente->cliente_nombre . ' ' .
                        $data_cliente->cliente_apellido_paterno . ' ' . $data_cliente->cliente_apellido_materno ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Línea de Crédito</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input id="linea_actual" name="linea_actual" type="text" class="form-control text-danger fw-bold"
                                   value="<?= $data_cliente->cliente_credito ?? 0 ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php if($data_cliente){ ?>
                            <a href="<?= _SERVER_ ?>Prestamos/aumentarLineaCredito/<?= $data_cliente->id_cliente ?>" class="btn btn-primary w-100" style="margin-top: 29px;">
                                <i class="fas fa-chart-line"></i> Aumentar Crédito
                            </a>
                        <?php } ?>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="monto_prestamo" class="form-label">Monto del Préstamo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input oninput="validar_monto_linea(); calcular_cuota();" onkeyup="validar_numeros_decimales_dos(this.id)"
                                   type="text" class="form-control" name="monto_prestamo" id="monto_prestamo" >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tasa de Interés <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input onkeyup="validar_numeros_decimales_dos(this.id)" oninput="calcular_cuota()"
                                   type="text" class="form-control" name="interes" id="interes" value="15">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div id="div_cuotas" class="col-md-4">
                        <label id="label_cuotas_dias" class="form-label">Días a Pagar <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="prestamo_num_cuotas" id="prestamo_num_cuotas" min="1"
                               max="31" value="1" onkeyup="validar_numeros(this.id)" oninput="calcular_cuota()">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Pago <span class="text-danger">*</span></label>
                        <div class="form-check" style="margin-left: -24px;">
                            <div class="btn-group w-100" role="group">
                                <input onchange="cambiar_proximo_cobro('diario'); ajustar_interfaz_tipo_pago(); calcular_cuota();" type="radio" class="btn-check" name="tipo_pago2" id="diario" value="Diario" checked>
                                <label class="btn btn-outline-primary" for="diario">Diario</label>

                                <input onchange="cambiar_proximo_cobro('semanal'); ajustar_interfaz_tipo_pago();" type="radio" class="btn-check" name="tipo_pago2" id="semanal" value="Semanal">
                                <label class="btn btn-outline-primary" for="semanal">Semanal</label>

                                <input onchange="cambiar_proximo_cobro('mensual'); ajustar_interfaz_tipo_pago();" type="radio" class="btn-check" name="tipo_pago2" id="mensual" value="Mensual">
                                <label class="btn btn-outline-primary" for="mensual">Mensual</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" style="position: relative; margin-bottom: 20px;">
                        <label class="form-label"><b>Fecha de Emisión</b></label>
                        <input type="date" class="form-control" id="fecha_prestamo2" name="fecha_prestamo2"
                               value="<?= date('Y-m-d') ?>"
                               onchange="ajustar_interfaz_tipo_pago(); actualizar_mensaje_inicio();">

                        <div style="position: absolute; width: 100%; margin-top: 2px;">
                            <small style="font-size: 0.75rem; color: #566a7f;">
                                El préstamo inicia el: <span id="fecha_inicio_prestamo" class="fw-bold" style="color: #566a7f;"><?= date('d-m-Y', strtotime('+1 day')) ?></span>
                            </small>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de Primer Cobro</label>
                        <input  type="date" class="form-control" id="fecha_prox_cobro2" name="fecha_prox_cobro2"  value="<?= date('Y-m-d', strtotime('+1 day')) ?>" >
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div id="div_diario_domingos" class="col-md-3">
                        <label for="select_domingos" class="form-label">¿Incluir domingos?</label>
                        <select onchange="ajustar_interfaz_tipo_pago();" class="form-control" id="select_domingos" name="select_domingos">
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <div id="div_cuota_diaria" class="col-md-3">
                        <label class="form-label text-success fw-bold">Cuota Estimada</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white">S/</span>
                            <input type="text" class="form-control bg-light text-success fw-bold" id="cuota_diaria_visual" readonly value="0.00">
                            <input type="hidden" name="cuota_calculada_hidden" id="cuota_calculada_hidden" value="0">
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">(Capital + Interés) / Cuotas</small>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-6">
                        <label for="prestamo_garantia" class="form-label">Garantía</label>
                        <input type="text" class="form-control" name="prestamo_garantia" id="prestamo_garantia" >
                    </div>

                    <div class="col-md-6">
                        <label for="prestamo_garante" class="form-label">Garante</label>
                        <select class="form-control" id="prestamo_garante" name="prestamo_garante">
                            <option value="">Seleccionar</option>
                            <?php foreach ($clientes_g as $c) { ?>
                                <option value="<?= $c->id_cliente ?>"><?= $c->cliente_dni . ' ' . $c->cliente_nombre ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="prestamo_motivo" class="form-label">Motivo del Préstamo</label>
                        <textarea class="form-control" name="prestamo_motivo" id="prestamo_motivo" rows="2"></textarea>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="prestamo_comentario" class="form-label">Comentarios Adicionales</label>
                        <textarea id="prestamo_comentario" class="form-control" name="prestamo_comentario" rows="3"></textarea>
                    </div>
                </div>
                <input type="hidden" id="forzar_garante" name="forzar_garante" value="0">


                <div class="text-center mt-5">
                    <a onclick="guardar_prestamo()" type="submit" class="btn text-white btn-primary px-5">
                        <i class="fa fa-save me-2"></i> Registrar Préstamo
                    </a>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger text-center" role="alert">
            <h4 class="alert-heading">¡Caja Cerrada!</h4>
            <p>Para registrar un nuevo préstamo, primero debes abrir la caja.</p>
            <hr>
        </div>
    <?php } ?>
</div>

<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        background-color: #fff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 0.75rem !important;
        color: #495057 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 8px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        outline: 0 !important;
    }
    /* Animación para el pulso de actualización */
    @keyframes efectoActualizacion {
        0% {
            background-color: #d1e7dd !important; /* Verde claro tipo Bootstrap */
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.5) !important;
            transform: scale(1.02);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0) !important;
            transform: scale(1);
        }
    }

    .animacion-cambio {
        animation: efectoActualizacion 0.5s ease-out !important;
    }

</style>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>
