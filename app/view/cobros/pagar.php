<!--Contenido-->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-hand-holding-usd me-2"></i>Pago de Deuda
        </h1>
        <a href="#" class="btn btn-success">
            <i class="fa fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Nombre:</label>
                        Bryan Diaz Vargas
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">DNI:</label>
                        71423949
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Monto Inicial:</label>
                        <span class="text-success">S/ 5,000.00</span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Resta por Pagar:</label>
                        <span class="badge bg-danger">S/ 26.00</span>
                    </div>
                </div>

                <!-- Detalles Préstamo -->
                <div class="col-md-6">
                    <div class="info-group mb-3">
                        <label class="fw-bold">Interés Aplicado:</label>
                        15%
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Tipo de Pago:</label>
                        <span class="badge bg-info">Semanal</span>
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Fecha del Préstamo:</label>
                        15/05/2025
                    </div>
                    <div class="info-group mb-3">
                        <label class="fw-bold">Fecha Vencimiento:</label>
                        <span class="badge bg-danger">20/05/2025</span>
                    </div>
                </div>
            </div>

            <form>
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" placeholder=" ">
                            <label>Monto a Pagar (S/)</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control">
                            <label>Recepción</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <select class="form-select">
                                <option value="">Seleccione</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="efectivo">Efectivo</option>
                            </select>
                            <label style="margin-left: -3px;">Método de Pago</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <a href="../Prestamos/detalles" class="btn btn-secondary shadow-sm d-flex align-items-center justify-content-center" style="display: block; width: 100%; height: 100%;">
                            <i class="fa fa-list me-2"></i>Ver Detalles de los Pagos
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-header bg-gray" style="color: white;padding: 13px;">
                                Garantías
                            </div>
                            <br>
                            <div class="card-body" style="padding: 8px;">
                                <p class="mb-1">
                                    <strong>Garantía:</strong>
                                    Vehículo Toyota Yaris 2020
                                </p>
                                <div class="mt-2">
                                    <label class="fw-bold">Garantes:</label>
                                    <ul class="list-unstyled">
                                        <li class="text-muted">70123456 - María Gómez Sánchez</li>
                                        <li class="text-muted">71234567 - Pedro Castillo Torres</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating mb-3" style="height: 50%;">
                            <input type="date" class="form-control w-100" style="height: 86%;" readonly>
                            <label>Fecha Esperada</label>
                        </div>
                        <div class="form-floating mb-3" style="height: 50%;">
                            <input type="date" class="form-control w-100" style="height: 86%;">
                            <label>Próximo Cobro</label>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-primary px-5">
                        <i class="fa fa-check-circle me-2"></i>Confirmar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .info-group label {
        width: 150px;
        color: #4a5568;
    }
    .card-header {
        font-weight: 500;
    }
</style>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>