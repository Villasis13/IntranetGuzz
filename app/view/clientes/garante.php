
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- Encabezado Principal -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold text-white">
                            <i class="fa fa-user-circle-o me-2"></i>Gestión de Garantes
                        </h4>
                        <a href="inicio" class="btn btn-light">
                            <i class="fa fa-arrow-left me-2"></i>Volver a Clientes
                        </a>
                    </div>
                </div>
                <br>

                <!-- Formulario de Búsqueda -->
                <div class="card-body bg-white">
                    <form class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">DNI del Garante</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Ingrese DNI" value="70123456">
                                <button class="btn btn-warning">
                                    <i class="fa fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Recomendado por</label>
                            <input type="text" class="form-control bg-light"
                                   value="Juan Pérez Martínez - 70123456" readonly>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-success w-100">
                                <i class="fa fa-save me-2"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info py-3">
                    <h5 class="m-0 font-weight-bold text-white">
                        <i class="fa fa-address-book me-2"></i>Garantes Registrados
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover m-0">
                            <thead class="bg-light">
                            <tr class="text-center">
                                <th>DNI</th>
                                <th>Nombre Completo</th>
                                <th>Dirección</th>
                                <th>Contacto</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="text-center">
                                <td>70123456</td>
                                <td>Juan Pérez Martínez</td>
                                <td>Av. Lima 123</td>
                                <td>987654321</td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban me-1"></i>Desactivar
                                    </button>
                                </td>
                            </tr>

                            <tr class="text-center">
                                <td>76543210</td>
                                <td>María Gómez Sánchez</td>
                                <td>Jr. Ayacucho 456</td>
                                <td>912345678</td>
                                <td><span class="badge bg-danger">Inactivo</span></td>
                                <td>
                                    <button class="btn btn-success btn-sm">
                                        <i class="fa fa-check me-1"></i>Activar
                                    </button>
                                </td>
                            </tr>

                            <tr class="text-center">
                                <td>71234567</td>
                                <td>Carlos Rojas Díaz</td>
                                <td>Av. Brasil 789</td>
                                <td>934567890</td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban me-1"></i>Desactivar
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>