<!--Contenido-->
<div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Préstamos al Instante</h1>
            <a class="d-none d-sm-inline-block btn btn-danger shadow-sm text-white">
                <i class="fa fa-list text-white"></i> Ver Todos los Préstamos
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-primary py-3">
                <h6 class="m-0 font-weight-bold text-white">Registro de Nuevo Préstamo</h6>
            </div>
            <div class="card-body">

                <div class="row mb-4 border-bottom pb-3">
                    <form method="post" action="Prestamos.php" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Buscar por DNI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="dni_post" id="dni" value="<?= $dni_cliente ?>" maxlength="8" required>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group">
                                <a class="btn btn-success text-white">
                                    <i class="fa fa-user-plus"></i> Nuevo Cliente
                                </a>
                                <a href="https://serviciosbiometricos.reniec.gob.pe/identifica/main.do" target="_blank" class="btn btn-info">
                                    RENIEC
                                </a>
                                <a href="https://soluciones.equifax.com.pe/efx-portal-web"
                                   target="_blank" class="btn btn-light border">
                                    Infocorp
                                </a>
                                <a href="https://www.sunarp.gob.pe/seccion/servicios/detalles/0/c3.html"
                                   target="_blank" class="btn btn-dark">
                                    SUNARP
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Formulario de Préstamo -->
                <form method="post" action="guardar_prestamo.php" id="formulario" target="_blank">
                    <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Cliente</label>
                            <input type="text" class="form-control bg-light" value="<?= $nombre ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Línea de Crédito</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="text" class="form-control text-danger fw-bold" value="<?= $lineaCredito ?? '0.00' ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <a href="AumentarLineaCredito.php?id=<?= $id_cliente ?>" class="btn btn-primary w-100" style="margin-top: 29px;">
                                <i class="fas fa-chart-line"></i> Aumentar Crédito
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Monto del Préstamo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" class="form-control" name="monto2" id="monto" step="0.01" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tasa de Interés <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="interes" id="interes" value="15" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Número de Cuotas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="ncuotas2" id="ncuotas" min="1" max="36" value="1" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Pago <span class="text-danger">*</span></label>
                            <div class="form-check" style="margin-left: -24px;">
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="tipo_pago2" id="diario" value="Diario" checked>
                                    <label class="btn btn-outline-primary" for="diario">Diario</label>

                                    <input type="radio" class="btn-check" name="tipo_pago2" id="semanal" value="Semanal">
                                    <label class="btn btn-outline-primary" for="semanal">Semanal</label>

                                    <input type="radio" class="btn-check" name="tipo_pago2" id="mensual" value="Mensual">
                                    <label class="btn btn-outline-primary" for="mensual">Mensual</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fecha de Préstamo</label>
                            <input type="date" class="form-control" name="fecha_prestamo2" value="<?= $fecha ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Próximo Cobro</label>
                            <input type="date" class="form-control" name="fecha_prox_cobro2" required>
                        </div>


                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Garantía</label>
                            <input type="text" class="form-control" name="garantia2" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Garante</label>
                            <input type="text" class="form-control" name="garante" value="<?= $garante ?>" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Motivo del Préstamo</label>
                            <textarea class="form-control" name="motivo" rows="2"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Comentarios Adicionales</label>
                            <textarea class="form-control" name="comentarios" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-lg btn-primary px-5">
                            <i class="fa fa-save me-2"></i> Registrar Préstamo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>