<!-- ACTUALIZAR DATOS DEL CLIENTE -->
<div class="modal fade" id="gestionCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2 text-white"></i>AGREGAR/EDITAR CLIENTE
                </h5>
                <button type="button" class="btn-close btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    <input type="hidden" id="id_cliente" name="id_cliente">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">DNI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_dni" name="cliente_dni" maxlength="8" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Apellido Paterno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cliente_apellido_paterno" name="cliente_apellido_paterno" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Apellido Materno <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cliente_apellido_materno" name="cliente_apellido_materno" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="cliente_fecha_nacimiento" name="cliente_fecha_nacimiento">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lugar de Trabajo</label>
                                <input type="text" class="form-control" id="cliente_lugar_trabajo" name="cliente_lugar_trabajo">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dirección <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cliente_direccion" name="cliente_direccion" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="cliente_referencia" name="cliente_referencia">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Celular <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cliente_celular" name="cliente_celular" required>
                                </div>

                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">N° Tarjeta</label>
                                    <input type="text" class="form-control" id="cliente_nro_tarjeta" name="cliente_nro_tarjeta">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Clave</label>
                                    <input type="password" class="form-control" id="cliente_clave" name="cliente_clave">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Información Adicional</label>
                                <input type="text" class="form-control" id="cliente_otro" name="cliente_otro">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="cliente_correo"
                                       name="cliente_correo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-success" onclick="guardar_editar_clientes()">
                    <i class="fa fa-save me-2"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Datos Financieros</h1>
                <div class="d-flex align-items-center">
                    <a href="<?= _SERVER_ ?>Caja/inicio" class="btn btn-danger shadow-sm">
                        <i class="fas fa-cash-register fa-sm text-white-50"></i> Caja Chica
                    </a>
                </div>
            </div>

            <div class="row mb-1">
                <div class="col-xl col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Ingresos Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/
                                        <?= number_format($ingresos_hoy, 1) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-money fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Egresos Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/
                                        <?= number_format($egresos_hoy, 1) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-hand-o-down fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6 mb-4" style="border-left: 4px solid #f6a821;">
                    <div class="card shadow h-100 py-2" style="border-left: 4px solid #f6a821 !important;">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1" style="color:#f6a821;">
                                        Amortizaciones Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">S/
                                        <?= number_format($amortizaciones_hoy, 1) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-compress fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Clientes Activos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $num_clientes ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Préstamos Hoy</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $cantidad_prestamos_hoy?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-file fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-primary">
                            <h6 class="m-0 font-weight-bold text-white">Próximos Cobros</h6>
                        </div>
                        <br>
                        <div class="card-body" style="margin-bottom: 21px;">
                            <div class="table-responsive ">
                                <table class="table table-bordered" id="dataTable1" width="100%">
                                    <thead class="text-center table-light">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                            <th>Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $style = "";
                                        $hoy = date('Y-m-d');
                                        $hoy_time = strtotime($hoy); // Convertimos hoy a tiempo numérico para operar

                                        foreach ($proximos_cobros as $pc){
                                            $fecha_cobro = date('Y-m-d', strtotime($pc->prestamo_prox_cobro));
                                            $cobro_time = strtotime($fecha_cobro); // Convertimos el cobro a tiempo numérico

                                            // Calculamos la diferencia exacta en días (86400 segundos tiene un día)
                                            $diferencia_dias = ($cobro_time - $hoy_time) / 86400;

                                            // Contamos cuántas cuotas le faltan a este préstamo
                                            $cuotas_restantes = $this->cobros->contar_cuotas_pendientes($pc->id_prestamos);

                                            // ── 1. DEFINIR COLOR DEL TEXTO DE LA FILA ──
                                            if ($diferencia_dias < 0) {
                                                $style = 'red'; // Ya pasó la fecha
                                            } elseif ($diferencia_dias == 0) {
                                                $style = '#C9A227'; // Vence hoy (Dorado)
                                            } else {
                                                $style = 'green'; // Aún hay tiempo
                                            }
                                            ?>
                                            <tr class="text-center">
                                                <td style="color: <?= $style ?>">
                                                    <?= htmlspecialchars($pc->cliente_nombre . ' ' . $pc->cliente_apellido_paterno) ?>
                                                </td>

                                                <?php $cuota_cercana = $this->cobros->listar_proximo_pago_diario($pc->id_prestamos); ?>

                                                <td style="color: <?= $style ?>; font-weight: bold;">
                                                    <?= $cuota_cercana ? 'S/ ' . number_format($cuota_cercana->pago_diario_monto, 2) : 'Deuda no acordada' ?>
                                                </td>
                                                <td style="color: <?= $style ?>">
                                                    <?= date('d/m/Y', strtotime($fecha_cobro)) ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    // ── 2. LÓGICA DE BOTONES SEGÚN DÍAS Y CUOTAS ──

                                                    if ($diferencia_dias < 0) {
                                                        // 🔴 A) FECHA VENCIDA (Pasado)
                                                        if ($cuotas_restantes <= 1) {
                                                            // Es la ÚLTIMA cuota de todo el préstamo
                                                            ?>
                                                            <button onclick="preguntar('¿El préstamo ya venció, desea convertirlo a un préstamo antiguo?', 'cambiar_prestamo_a_antiguo', 'Sí, convertir', 'Cancelar', <?= $pc->id_prestamos ?>)"
                                                                    class="btn btn-sm btn-danger font-weight-bold shadow-sm"
                                                                    title="La última cuota venció. Préstamo finalizado sin pago total.">
                                                                <i class="fa fa-ban"></i> Préstamo Vencido
                                                            </button>
                                                            <?php
                                                        } else {
                                                            // Le quedan más cuotas por delante (Mora regular)
                                                            ?>
                                                            <button onclick="window.location.href='<?= _SERVER_ ?>cobros/pagar/<?= $pc->id_prestamos ?>'"
                                                                    class="btn btn-sm text-white font-weight-bold shadow-sm" style="background-color: #fd7e14; border-color: #fd7e14;"
                                                                    title="El cliente tiene una cuota atrasada. Debe regularizar.">
                                                                <i class="fa fa-exclamation-circle"></i> Regularizar (Mora)
                                                            </button>
                                                            <br>
                                                            <small class="text-muted" style="font-size: 0.75rem;">Faltan <?= $cuotas_restantes ?> cuotas</small>
                                                            <?php
                                                        }

                                                    } elseif ($diferencia_dias >= 0 && $diferencia_dias <= 2) {
                                                        // 🔵 B) COBRO PRÓXIMO (Vence Hoy, Mañana o Pasado Mañana)
                                                        // Añadimos el botón azul primario para ir a cobrar directamente
                                                        ?>
                                                        <button onclick="window.location.href='<?= _SERVER_ ?>cobros/pagar/<?= $pc->id_prestamos ?>'"
                                                                class="btn btn-sm btn-primary font-weight-bold shadow-sm"
                                                                title="La cuota vence pronto. Ir a la caja a cobrar.">
                                                            <i class="fa fa-money-bill-wave"></i> Cobrar Cuota
                                                        </button>
                                                        <br>
                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                            <?= $diferencia_dias == 0 ? 'Vence HOY' : 'Vence en ' . $diferencia_dias . ' día(s)' ?>
                                                        </small>
                                                        <?php

                                                    } else {
                                                        // 🟢 C) COBRO LEJANO (Faltan más de 2 días)
                                                        ?>
                                                        <span class="badge bg-success text-white p-2" style="font-size: 0.85em;">
                <i class="fa fa-check-circle"></i> Al día
            </span>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="mt-3 p-2 rounded" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                    <strong>Referencias:</strong>&nbsp;&nbsp;
                                    <span style="color: #C9A227; font-weight: bold; font-size: 16px;">●</span>
                                    <span style="font-weight: bold; color: #C9A227;"> Vence hoy</span>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <span style="color: red; font-weight: bold; font-size: 16px;">●</span>
                                    <span style="font-weight: bold; color: red;"> Ya venció</span>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <span style="color: green; font-weight: bold; font-size: 16px;">●</span>
                                    <span style="font-weight: bold; color: green;"> Por vencer</span>
                                </div>
                            </div>
<!--                            <a href="cobros/inicio" class="btn btn-secondary mt-3">-->
<!--                                <i class="fa fa-list fa-sm text-white-50"></i> Ver Todos-->
<!--                            </a>-->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- Acciones Rápidas -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-success">
                            <h6 class="m-0 font-weight-bold text-white">Acciones Rápidas</h6>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <a href="<?= _SERVER_ ?>Prestamos/prestamos_antiguos" class="btn btn-secondary">
                                    <i class="fa fa-history mr-2"></i> Préstamos Antiguos
                                </a>
                                <a href="<?= _SERVER_ ?>Ventas/inicio" class="btn btn-secondary">
                                    <i class="fa fa-eject mr-2"></i> Ventas a Plazo
                                </a>
                                <!--<a href="Inventario.php" class="btn btn-secondary">
                                    <i class="fa fa-chain mr-2"></i> Gestión Garantías
                                </a>-->
                                <a href="<?= _SERVER_ ?>Admin/documentos" class="btn btn-secondary">
                                    <i class="fa fa-folder mr-2"></i> Documentos
                                </a>
                                <a href="<?= _SERVER_ ?>Admin/reportes" class="btn btn-secondary">
                                    <i class="fa fa-folder mr-2"></i> Reportes
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Central -->
<!--                    <div class="card shadow">-->
<!--                        <div class="card-body text-center">-->
<!--                            <img style="width: 300px; height: 300px;"-->
<!--                                 src="--><?php //= _SERVER_._STYLES_bt5_ ?><!--assets/img/icons/unicons/MG1.png"-->
<!--                                 alt="Logo MG"-->
<!--                                 class="img-fluid">-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div class="col-lg-12">
                    <!-- Actualizaciones Requeridas -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-info">
                            <h6 class="m-0 font-weight-bold text-white">Los siguientes clientes deben ser actualizados</h6>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" id="dataTable">
                                    <thead class="text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Dirección</th>
                                        <th>Última Actualización</th>
                                        <th>Editar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($actualizar_clientes as $ac){
                                        ?>
                                        <tr class="text-center">
                                            <td><?= $ac->cliente_nombre . ' ' . $ac->cliente_apellido_paterno . ' ' . $ac->cliente_apellido_materno ?></td>
                                            <td><?= $ac->cliente_direccion ?></td>
                                            <td><?= $ac->cliente_fecha ?></td>
<!--                                            <td>-->
<!--                                                <a href="#" class="btn btn-sm btn-warning">-->
<!--                                                    <i class="fa fa-edit"></i>-->
<!--                                                </a>-->
<!--                                            </td>-->
                                            <td>
                                                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gestionCliente" onclick="editar_clientes(<?= $ac->id_cliente ?>)">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>clientes.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

