<div class="modal fade" id="dinero_caja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" onsubmit="return false;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleAlumno">Editar Monto de Ingreso</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Monto a añadir</label>
                        <input type="number" id="monto_caja" name="monto_caja" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="contrasenha" name="pago_monto" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-agregar-monto" onclick="guardar_movimiento_caja()">
                    <i class="fa fa-save fa-sm text-white-50"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_apertura_caja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #f1f5f9; border-radius: 16px 16px 0 0; padding: 20px 24px;">
                <h5 class="modal-title" style="font-weight: 700; color: #1e293b; font-size: 1.15rem;">
                    <i class="fas fa-cash-register mr-2" style="color: #10b981;"></i> Apertura de Caja
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: #94a3b8; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 18px; text-align: center; margin-bottom: 24px;">
                    <span style="color: #059669; font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Último saldo registrado</span>
                    <div style="color: #064e3b; font-size: 32px; font-weight: 800; margin-top: 4px; font-family: monospace;">
                        S/ <?= number_format($ultima_caja->monto_caja, 2) ?>
                    </div>
                </div>
                <div class="form-group text-center" style="margin-bottom: 0;">
                    <label style="font-weight: 600; color: #475569; font-size: 14.5px; margin-bottom: 12px;">¿Con qué monto iniciará la caja hoy?</label>
                    <div class="input-group mx-auto" style="max-width: 85%; border-radius: 10px; overflow: hidden; border: 2px solid #cbd5e1; background: #fff;" onfocusin="this.style.borderColor='#10b981'" onfocusout="this.style.borderColor='#cbd5e1'">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="background: transparent; border: none; color: #64748b; font-weight: 700; font-size: 20px; padding-left: 20px;">S/</span>
                        </div>
                        <input type="number" step="0.01" id="caja_monto" name="caja_monto" class="form-control text-center" value="<?= $ultima_caja->monto_caja ?>" onkeyup="validar_numeros_decimales_dos(this.id)" required style="border: none; font-size: 26px; font-weight: 700; color: #0f172a; height: 64px; outline: none; box-shadow: none; font-family: monospace;">
                    </div>
                    <small style="color: #94a3b8; font-size: 12px; display: block; margin-top: 16px;">Mantenga el monto sugerido para una <b>apertura rápida</b>, o modifíquelo si hubo un ingreso/retiro extra.</small>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none; padding: 0 24px 24px 24px; justify-content: center; gap: 12px;">
                <button type="button" class="btn" data-dismiss="modal" style="background: #f1f5f9; border: none; color: #475569; font-weight: 600; border-radius: 8px; padding: 12px 24px;">Cancelar</button>
                <button type="button" class="btn" onclick="gestionar_caja()" style="background: #10b981; border: none; color: #fff; font-weight: 600; border-radius: 8px; padding: 12px 28px; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">
                    <i class="fa fa-check-circle mr-2"></i> Abrir Caja Ahora
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_editar_apertura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" onsubmit="return false;">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-edit text-warning"></i> Corregir Monto de Apertura</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group text-center">
                    <label class="form-label font-weight-bold">Ingrese el monto real de apertura:</label>
                    <div class="input-group mx-auto" style="max-width: 60%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold bg-light">S/</span>
                        </div>
                        <input type="hidden" id="edit_id_caja" value="<?= $ultima_caja->id_caja ?>">
                        <input type="number" step="0.01" id="nuevo_monto_apertura" class="form-control text-center font-weight-bold" style="font-size: 1.2rem;" value="<?= $ultima_caja->monto_apertura_caja ?>" required>
                    </div>
                    <small class="text-danger d-block mt-3"><i class="fa fa-exclamation-triangle"></i> El saldo total actual de la caja se recalculará automáticamente.</small>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning text-dark font-weight-bold" onclick="editar_monto_apertura()">
                    <i class="fa fa-save"></i> Guardar Corrección
                </button>
            </div>
        </form>
    </div>
</div>


<style>
    /* ── Fuente uniforme para todo el arqueo ── */
    .arqueo-wrap { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #334155; }

    /* ── Títulos de bloque ── */
    .arqueo-bloque-titulo {
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        padding: 10px 20px 6px 20px;
        margin: 0;
    }

    /* ── Encabezados de sección dentro de tabla ── */
    .arqueo-sec-header {
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #1e293b;
        background: #f1f5f9 !important;
        padding: 7px 14px !important;
        border-left: 4px solid #94a3b8;
        border-top: 1px solid #e2e8f0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .arqueo-sec-header.pagos     { border-left-color: #1cc88a; }
    .arqueo-sec-header.prestamos { border-left-color: #e74a3b; }
    .arqueo-sec-header.manuales  { border-left-color: #36b9cc; }

    /* ── Encabezados de columna ── */
    .arqueo-col-header {
        font-family: 'Segoe UI', Arial, sans-serif !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8 !important;
        background: #fafafa !important;
        padding: 6px 10px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: none !important;
    }
    .arqueo-col-header.ing { color: #1cc88a !important; }
    .arqueo-col-header.egr { color: #e74a3b !important; }

    /* ── Celdas de datos ── */
    .arqueo-table td {
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 13px;
        padding: 7px 10px !important;
        vertical-align: middle !important;
        color: #334155;
        border-color: #f1f5f9 !important;
    }
    .arqueo-table tr.data-row:hover td { background: #f8fafc; }

    /* ── Fila vacía ── */
    .arqueo-empty td {
        font-family: 'Segoe UI', Arial, sans-serif;
        font-style: italic;
        font-size: 12px;
        color: #94a3b8 !important;
        padding: 9px 14px !important;
        background: #fafafa !important;
        border: none !important;
    }

    /* ── Montos ── */
    .m-ing { color: #1cc88a; font-weight: 700; }
    .m-egr { color: #e74a3b; font-weight: 700; }
</style>

<div class="container-fluid arqueo-wrap">

    <!-- ══ ESTADO DE CAJA ══ -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-left-primary mb-3">
                <div class="card-body">
                    <?php if($ultima_caja->estado_caja == 1): ?>
                        <h4 class="text-success font-weight-bold mb-3"><i class="fa fa-lock-open"></i> CAJA ABIERTA</h4>
                        <h5>Tiene en caja: <span class="font-weight-bold text-primary">S/ <?= $monto_caja_abierta ?></span></h5>
                        <h5 class="text-muted mb-4" style="font-size: 15px;">Abierto el: <?= $fecha_caja ?></h5>
                        <input id="input_editar_monto" name="input_editar_monto" style="display:none" class="form-control w-25" placeholder="Ingrese nuevo monto" onkeyup="validar_numeros_decimales_dos(this.id)">
                        <button type="button" data-toggle="modal" data-target="#dinero_caja" onclick="limpiar_modal_alumno()" class="btn btn-sm btn-primary text-white">
                            <i class="fa fa-plus"></i> Añadir dinero
                        </button>
                        <button type="button" data-toggle="modal" data-target="#modal_editar_apertura" class="btn btn-sm btn-warning text-dark ml-2 font-weight-bold">
                            <i class="fa fa-edit"></i> Editar Apertura
                        </button>
                        <button type="button" id="btn-cerrar-caja" class="btn btn-sm btn-danger text-white ml-2" onclick="cerrar_caja(<?= $ultima_caja->id_caja ?>)">
                            <i class="fa fa-lock"></i> Cerrar caja
                        </button>
                        <a style="display:none" id="btn_editar" onclick="guardar_nuevo_monto()" class="btn btn-sm btn-success text-white"><i class="fa fa-save"></i></a>
                    <?php else: ?>
                        <input type="hidden" id="id_caja">
                        <h4 class="text-danger font-weight-bold mb-4"><i class="fa fa-lock"></i> CAJA CERRADA</h4>
                        <div class="row align-items-end">
                            <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                                <label for="fecha" class="font-weight-bold text-muted">Fecha y Hora Actual</label>
                                <input id="fecha" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <button type="button" data-toggle="modal" data-target="#modal_apertura_caja" class="btn btn-success btn-lg w-100 shadow-sm font-weight-bold">
                                    <i class="fa fa-key mr-2"></i> Aperturar Caja
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ ARQUEO DE CAJA ══ -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">

                <!-- Header card -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary" style="font-family: 'Segoe UI', Arial, sans-serif;">Detalle de Arqueo de Caja</h6>
                    <?php if($ultima_caja->estado_caja == 1): ?>
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <a href="<?= _SERVER_ ?>caja/exportar_pdf_arqueo/<?= $ultima_caja->id_caja ?>" target="_blank" class="btn btn-sm btn-danger text-white">
                                <i class="fa fa-file-pdf mr-1"></i> Exportar PDF
                            </a>
                            <span class="badge bg-success text-white px-3 py-2">Caja Activa</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-body p-0">
                    <?php if($ultima_caja->estado_caja != 1): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-lock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay una caja abierta</h5>
                            <p class="text-muted">Debes aperturar la caja para ver el arqueo actual.</p>
                        </div>
                    <?php else: ?>

                        <!-- ┌─────────────────────────────────────┐ -->
                        <!-- │  BLOQUE 1 — APERTURA DE CAJA        │ -->
                        <!-- └─────────────────────────────────────┘ -->
                        <p class="arqueo-bloque-titulo" style="border-left: 4px solid #f6c23e; background: #fffdf0;">
                            <i class="fa fa-key mr-1" style="color:#f6c23e;"></i> Apertura de Caja
                        </p>
                        <div class="table-responsive" style="border-bottom: 3px solid #e2e8f0;">
                            <table class="table arqueo-table mb-0" width="100%">
                                <thead>
                                <tr>
                                    <th class="arqueo-col-header" style="width:110px;">Fecha</th>
                                    <th class="arqueo-col-header" style="width:90px;">Hora</th>
                                    <th class="arqueo-col-header">Usuario</th>
                                    <th class="arqueo-col-header">Descripción</th>
                                    <th class="arqueo-col-header text-right" style="width:160px;">Monto</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="data-row">
                                    <td class="align-middle"><?= date('d/m/Y', strtotime($fecha_caja)) ?></td>
                                    <td class="align-middle"><?= date('H:i:s', strtotime($fecha_caja)) ?></td>
                                    <td class="align-middle"><?= $usuario_actual ?></td>
                                    <td class="align-middle">Saldo del día Anterior / Monto inicial</td>
                                    <td class="text-right m-ing font-weight-bold align-middle text-success">S/ <?= number_format($ultima_caja->monto_apertura_caja, 2) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- ┌─────────────────────────────────────┐ -->
                        <!-- │  BLOQUE 2 — MOVIMIENTOS             │ -->
                        <!-- └─────────────────────────────────────┘ -->
                        <p class="arqueo-bloque-titulo mt-2" style="border-left: 4px solid #4e73df; background: #f0f4ff;">
                            <i class="fa fa-exchange-alt mr-1" style="color:#4e73df;"></i> Movimientos del Turno
                        </p>
                        <div class="table-responsive" style="border-bottom: 3px solid #e2e8f0;">
                            <table class="table arqueo-table mb-0" width="100%">
                                <tbody>

                                <!-- Pago de Cuotas -->
                                <tr>
                                    <td colspan="6" class="arqueo-sec-header pagos">
                                        <i class="fa fa-hand-holding-usd mr-1"></i> Pago de Cuotas
                                    </td>
                                </tr>
                                <tr>
                                    <th class="arqueo-col-header" style="width:110px;">Fecha</th>
                                    <th class="arqueo-col-header" style="width:90px;">Hora</th>
                                    <th class="arqueo-col-header">Cliente</th>
                                    <th class="arqueo-col-header">Método de Pago</th>
                                    <th class="arqueo-col-header ing text-right" style="width:140px;">Ingresos</th>
                                    <th class="arqueo-col-header egr text-right" style="width:140px;">Egresos</th>
                                </tr>
                                <?php
                                $suma_pagos = 0;
                                if(!empty($pagos_caja)):
                                    foreach($pagos_caja as $pago):
                                        $suma_pagos += $pago->pago_monto;
                                        ?>
                                        <tr class="data-row">
                                            <td><?= date('d/m/Y', strtotime($pago->pago_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($pago->pago_fecha)) ?></td>
                                            <td><?= $pago->cliente_nombre . ' ' . $pago->cliente_apellido_paterno ?></td>
                                            <td><?= ucfirst($pago->pago_metodo) ?></td>
                                            <td class="text-right m-ing">S/ <?= number_format($pago->pago_monto, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr class="arqueo-empty"><td colspan="6">No se han registrado pagos en este turno.</td></tr>
                                <?php endif; ?>

                                <!-- Préstamos -->
                                <tr>
                                    <td colspan="6" class="arqueo-sec-header prestamos">
                                        <i class="fa fa-money-bill-wave mr-1"></i> Préstamos Otorgados
                                    </td>
                                </tr>
                                <tr>
                                    <th class="arqueo-col-header" style="width:110px;">Fecha</th>
                                    <th class="arqueo-col-header" style="width:90px;">Hora</th>
                                    <th class="arqueo-col-header">Cliente</th>
                                    <th class="arqueo-col-header">Tipo de Pago</th>
                                    <th class="arqueo-col-header ing text-right" style="width:140px;">Ingresos</th>
                                    <th class="arqueo-col-header egr text-right" style="width:140px;">Egresos</th>
                                </tr>
                                <?php
                                $suma_prestamos = 0;
                                if(!empty($prestamos_caja)):
                                    foreach($prestamos_caja as $prestamo):
                                        $suma_prestamos += $prestamo->prestamo_monto;
                                        ?>
                                        <tr class="data-row">
                                            <td><?= date('d/m/Y', strtotime($prestamo->prestamo_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($prestamo->prestamo_fecha)) ?></td>
                                            <td><?= $prestamo->cliente_nombre . ' ' . $prestamo->cliente_apellido_paterno ?></td>
                                            <td>Préstamo <?= ucfirst($prestamo->prestamo_tipo_pago) ?></td>
                                            <td></td>
                                            <td class="text-right m-egr">S/ <?= number_format($prestamo->prestamo_monto, 2) ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr class="arqueo-empty"><td colspan="6">No se han otorgado préstamos en este turno.</td></tr>
                                <?php endif; ?>

                                <!-- Ingresos Manuales -->
                                <tr>
                                    <td colspan="6" class="arqueo-sec-header manuales">
                                        <i class="fa fa-plus-circle mr-1"></i> Ingreso de Monto Manual
                                    </td>
                                </tr>
                                <tr>
                                    <th class="arqueo-col-header" style="width:110px;">Fecha</th>
                                    <th class="arqueo-col-header" style="width:90px;">Hora</th>
                                    <th class="arqueo-col-header">Usuario</th>
                                    <th class="arqueo-col-header">Descripción</th>
                                    <th class="arqueo-col-header ing text-right" style="width:140px;">Ingresos</th>
                                    <th class="arqueo-col-header egr text-right" style="width:140px;">Egresos</th>
                                </tr>
                                <?php
                                $suma_ingresos_manuales = 0;
                                if(!empty($ingresos_manuales)):
                                    foreach($ingresos_manuales as $mov):
                                        $suma_ingresos_manuales += $mov->caja_movimiento_monto;
                                        ?>
                                        <tr class="data-row">
                                            <td><?= date('d/m/Y', strtotime($mov->caja_movimiento_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($mov->caja_movimiento_fecha)) ?></td>
                                            <td><?= $usuario_actual ?></td>
                                            <td>Añadido a Caja Manualmente</td>
                                            <td class="text-right m-ing">S/ <?= number_format($mov->caja_movimiento_monto, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr class="arqueo-empty"><td colspan="6">No hay ingresos manuales registrados.</td></tr>
                                <?php endif; ?>

                                </tbody>
                            </table>
                        </div>

                        <!-- ┌─────────────────────────────────────┐ -->
                        <!-- │  BLOQUE 3 — TOTALES                 │ -->
                        <!-- └─────────────────────────────────────┘ -->
                        <?php
                        $monto_apertura         = $ultima_caja->monto_apertura_caja;
                        $total_ingresos         = $suma_pagos + $suma_ingresos_manuales;
                        $total_egresos          = $suma_prestamos;
                        $saldo_actual_calculado = $monto_apertura + $total_ingresos - $total_egresos;
                        ?>
                        <div style="background: #f8fafc; border-top: 3px solid #4e73df; padding: 20px 28px;">
                            <p class="arqueo-bloque-titulo mb-3 p-0" style="border-left: 4px solid #4e73df; padding-left: 10px !important; background: transparent; font-family: 'Segoe UI', Arial, sans-serif;">
                                <i class="fa fa-calculator mr-1" style="color:#4e73df;"></i> Resumen del Turno
                            </p>
                            <div class="d-flex justify-content-end">
                                <table style="min-width: 400px; border-collapse: collapse; font-family: 'Segoe UI', Arial, sans-serif;">
                                    <tr>
                                        <td style="padding: 7px 24px 7px 0; font-size: 13px; font-weight: 600; color: #475569; text-align: right;">
                                            Monto de Apertura:
                                        </td>
                                        <td style="padding: 7px 0; font-size: 14px; font-weight: 700; color: #f6c23e; text-align: right; min-width: 150px;">
                                            S/ <?= number_format($ultima_caja->monto_apertura_caja, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 7px 24px 7px 0; font-size: 13px; font-weight: 600; color: #475569; text-align: right;">
                                            Total Ingresos del Turno:
                                        </td>
                                        <td style="padding: 7px 0; font-size: 14px; font-weight: 700; color: #1cc88a; text-align: right;">
                                            S/ <?= number_format($total_ingresos, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 7px 24px 7px 0; font-size: 13px; font-weight: 600; color: #475569; text-align: right;">
                                            Total Egresos del Turno:
                                        </td>
                                        <td style="padding: 7px 0; font-size: 14px; font-weight: 700; color: #e74a3b; text-align: right;">
                                           - S/ <?= number_format($total_egresos, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding: 6px 0 4px 0;">
                                            <div style="border-top: 2px solid #cbd5e1;"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 12px 24px 12px 16px; font-size: 15px; font-weight: 700; color: #fff; text-align: right; background: #4e73df; border-radius: 8px 0 0 8px;">
                                            Saldo Actual en Caja:
                                        </td>
                                        <td style="padding: 12px 18px; font-size: 20px; font-weight: 800; color: #fff; text-align: right; background: #4e73df; border-radius: 0 8px 8px 0; font-family: 'Segoe UI', Arial, sans-serif;">
                                            S/ <?= number_format($saldo_actual_calculado, 2) ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>caja.js"></script>
<script>
    function actualizarHora() {
        const fechaCampo = document.getElementById("fecha");
        if(fechaCampo){
            const fechaActual = new Date();
            const hora = fechaActual.getHours().toString().padStart(2, '0');
            const minutos = fechaActual.getMinutes().toString().padStart(2, '0');
            const segundos = fechaActual.getSeconds().toString().padStart(2, '0');
            const fechaFormateada = `${fechaActual.getFullYear()}-${(fechaActual.getMonth() + 1).toString().padStart(2, '0')}-${fechaActual.getDate().toString().padStart(2, '0')} ${hora}:${minutos}:${segundos}`;
            fechaCampo.value = fechaFormateada;
        }
    }
    setInterval(actualizarHora, 1000);
    actualizarHora();
</script>