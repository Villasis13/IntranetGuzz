<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold text-white">
                            Pago de Deuda
                        </h4>
                        <a class="btn btn-light">
                            <i class="fa fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
                <br>
                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="m-0 font-weight-bold">
                                        <i class="fa fa-user me-2"></i>Información del Cliente
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <div class="text-muted small">DNI</div>
                                                <div class="fw-bold">70123456</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <div class="text-muted small">NOMBRE COMPLETO</div>
                                                <div class="fw-bold">Juan Pérez Martínez</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-header py-3">
                                    <h5 class="m-0 font-weight-bold">
                                        <i class="fa fa-file me-2"></i>Detalles del Préstamo
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="text-muted small">MONTO PRESTADO</div>
                                                <div class="fw-bold">S/ 5,000.00</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="text-muted small">TASA DE INTERÉS</div>
                                                <div class="fw-bold">15%</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="text-muted small">FECHA DE PRÉSTAMO</div>
                                                <div class="fw-bold">15-05-2023</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="text-muted small">TIPO DE PAGO</div>
                                                <div class="fw-bold">Mensual</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="text-muted small">GARANTÍA</div>
                                                <div class="fw-bold">Documento de propiedad</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="info-box info-box-danger">
                                                <div class="text-muted small">SALDO PENDIENTE</div>
                                                <div class="fw-bold fs-4 text-danger">S/ 3,250.00</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card mb-4">
                                <div class="card-header py-3 bg-info">
                                    <h5 class="m-0 font-weight-bold text-white">
                                        <i class="fa fa-user-secret me-2"></i>Garantes
                                    </h5>
                                </div>
                                <br>
                                <div class="card-body">
                                    <ul class="guarantor-list">
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>María Gómez Sánchez</strong>
                                                    <div class="text-muted small">DNI: 76543210</div>
                                                </div>
                                                <span class="badge bg-success badge-status">Activo</span>
                                            </div>
                                        </li>
                                        <br>
                                        <li>
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>Carlos Rojas Díaz</strong>
                                                    <div class="text-muted small">DNI: 71234567</div>
                                                </div>
                                                <span class="badge bg-success badge-status">Activo</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <a href="#" class="btn btn-outline-primary me-2">
                                    <i class="fa fa-list me-2"></i>Ver Detalles de Pagos
                                </a>
                                <a href="#" class="btn btn-outline-success">
                                    <i class="fa fa-percent me-2"></i>Aplicar Descuento
                                </a>
                            </div>
                        </div>
                        <hr>

                        <div class="col-lg-12">
                            <div class="payment-form mb-4">
                                <h4 class="mb-4 border-bottom pb-3">
                                    <i class="fa fa-credit-card me-2 text-primary"></i>Realizar Pago
                                </h4>
                                <form>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Monto a pagar</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">S/</span>
                                                <input type="text" class="form-control amount-input" value="1,250.00">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Método de pago</label>
                                            <select class="form-select">
                                                <option value="efectivo">Efectivo</option>
                                                <option value="transferencia">Transferencia Bancaria</option>
                                                <option value="tarjeta">Tarjeta de Crédito</option>
                                                <option value="otros">Otros</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Fecha Esperada de Pago</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="15-08-2023" readonly>
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Próximo cobro</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" value="15-09-2023">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold">Recepción</label>
                                            <input type="text" class="form-control" placeholder="Detalles de recepción">
                                        </div>
                                    </div>

                                    <div class="row mb-4 d-flex justify-content-center">
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                                <i class="fa fa-check-circle me-2"></i>Realizar Pago
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
