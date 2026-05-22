<!-- MODAL PARA EDITAR MONTOS -->
<div class="modal fade" id="editar_monto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" onsubmit="return false;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleAlumno">Editar Monto de Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- NOMBRES -->
                    <div class="col-md-6">
                        <label class="form-label">Monto Actual/Por Cambiar</label>
                        <input type="number" id="pago_monto" name="pago_monto" class="form-control"  required>
                        <input type="hidden" id="id_pago" name="id_pago" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="contrasenha" name="pago_monto" class="form-control"  required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-monto" onclick="guardar_monto()">
                    <i class="fa fa-save fa-sm text-white-50"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h5 class="mb-3 font-weight-bold text-primary">Historial ingresos de hoy (<?=$fecha?>)</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle small" width="100%">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>N° Recibo</th>
                                    <th>Fecha Cuota</th>
                                    <th>Fecha Registro</th>
                                    <th>Usuario</th>
                                    <th>Cuota Original</th>
                                    <th>Descuento</th>
                                    <th>Monto Final</th>
                                    <th>Monto Recibido</th>
                                    <th>Diferencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $con = 1; foreach ($pagos_hoy as $cg):
                                $diferencia     = floatval($cg->pago_monto_vuelto ?? 0);
                                $tiene_descuento = !empty($cg->pago_descuento_estado) && $cg->pago_descuento_estado == 1;
                                if ($diferencia > 0)      $cls_dif = 'text-success fw-semibold';
                                elseif ($diferencia < 0)  $cls_dif = 'text-danger fw-semibold';
                                else                      $cls_dif = 'text-muted';
                            ?>
                                <tr class="text-center">
                                    <td><?= $con ?></td>
                                    <td class="fw-semibold"><?= str_pad($cg->id_pago, 6, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= !empty($cg->pago_diario_fecha) ? date('d/m/Y', strtotime($cg->pago_diario_fecha)) : '—' ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($cg->pago_fecha)) ?></td>
                                    <td><?= htmlspecialchars($cg->usuario_nickname ?? $cg->pago_recepcion) ?></td>
                                    <td>S/ <?= number_format($cg->pago_diario_monto ?? 0, 2) ?></td>
                                    <td class="<?= $tiene_descuento ? 'text-danger' : 'text-muted' ?>">
                                        <?= $tiene_descuento ? '- S/ ' . number_format($cg->pago_descuento_monto, 2) : '—' ?>
                                    </td>
                                    <td class="fw-semibold text-primary">S/ <?= number_format($cg->pago_monto, 2) ?></td>
                                    <td>S/ <?= number_format($cg->pago_monto_recibido ?? 0, 2) ?></td>
                                    <td class="<?= $cls_dif ?>">
                                        <?php
                                        if ($diferencia > 0)      echo '+ S/ ' . number_format($diferencia, 2);
                                        elseif ($diferencia < 0)  echo '- S/ ' . number_format(abs($diferencia), 2);
                                        else                       echo 'S/ 0.00';
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-xs btn-info btne me-1"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#detalle-<?= $cg->id_pago ?>"
                                                title="Ver detalle método de pago">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button data-bs-toggle="modal"
                                                data-bs-target="#editar_monto"
                                                onclick="pago_x_id(<?= $cg->id_pago ?>)"
                                                class="btn btn-xs btn-warning btne"
                                                title="Editar monto">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="collapse" id="detalle-<?= $cg->id_pago ?>">
                                    <td colspan="11" class="p-0">
                                        <div class="px-4 py-3 bg-light border-top border-bottom">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <p class="text-primary fw-bold mb-2 small text-uppercase">
                                                        <i class="fa fa-credit-card me-1"></i> Método de Pago
                                                    </p>
                                                    <table class="table table-sm table-borderless mb-0 small">
                                                        <tr>
                                                            <td class="text-muted" style="width:40%">Método</td>
                                                            <td class="fw-semibold"><?= htmlspecialchars($cg->metodo_pago_nombre ?? '—') ?></td>
                                                        </tr>
                                                        <?php if (!empty($cg->pago_operacion)): ?>
                                                        <tr>
                                                            <td class="text-muted">N° Operación</td>
                                                            <td><?= htmlspecialchars($cg->pago_operacion) ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <?php if (!empty($cg->pago_oper_titular)): ?>
                                                        <tr>
                                                            <td class="text-muted">Titular</td>
                                                            <td><?= htmlspecialchars($cg->pago_oper_titular) ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <?php if (!empty($cg->banco_nombre)): ?>
                                                        <tr>
                                                            <td class="text-muted">Banco / Entidad</td>
                                                            <td><?= htmlspecialchars($cg->banco_nombre) ?> (<?= htmlspecialchars($cg->banco_abreviado) ?>)</td>
                                                        </tr>
                                                        <?php endif; ?>
                                                        <?php if (!empty($cg->pago_fecha_operacion)): ?>
                                                        <tr>
                                                            <td class="text-muted">Fecha Operación</td>
                                                            <td><?= date('d/m/Y', strtotime($cg->pago_fecha_operacion)) ?></td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </table>
                                                </div>
                                                <?php if (!empty($cg->pago_observacion)): ?>
                                                <div class="col-md-6">
                                                    <p class="text-primary fw-bold mb-2 small text-uppercase">
                                                        <i class="fa fa-comment me-1"></i> Observación
                                                    </p>
                                                    <p class="small mb-0"><?= htmlspecialchars($cg->pago_observacion) ?></p>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            <?php $con++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!--                            <a href="cobros/inicio" class="btn btn-secondary mt-3">-->
                    <!--                                <i class="fa fa-list fa-sm text-white-50"></i> Ver Todos-->
                    <!--                            </a>-->
                </div>
            </div>
            <a href="<?= _SERVER_ ?>Caja/inicio" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Regresar
            </a>
        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>

<script>
    function actualizarHora() {
        const fechaCampo = document.getElementById("fecha");
        const fechaActual = new Date();
        const hora = fechaActual.getHours().toString().padStart(2, '0');
        const minutos = fechaActual.getMinutes().toString().padStart(2, '0');
        const segundos = fechaActual.getSeconds().toString().padStart(2, '0');
        const fechaFormateada = `${fechaActual.getFullYear()}-${(fechaActual.getMonth() + 1).toString().padStart(2, '0')}-${fechaActual.getDate().toString().padStart(2, '0')} ${hora}:${minutos}:${segundos}`;
        fechaCampo.value = fechaFormateada;
    }

    // Actualizar la hora cada segundo (1000 ms)
    setInterval(actualizarHora, 1000);

    // Llamar a la función para mostrar la hora actual
    actualizarHora();
</script>