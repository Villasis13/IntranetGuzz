<div class="pagar-page">

    <!-- INPUTS GLOBALES -->
    <input type="hidden" id="id_prestamo" name="id_prestamo" value="<?= $id_prestamo ?>">

    <!-- ===== HEADER ===== -->
    <div class="pagar-header">
        <div class="pagar-title-group">
            <a href="<?= _SERVER_ ?>prestamos/prestamos" class="pagar-back-link">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
            <h1 class="pagar-title">Realizar Pago</h1>
            <p class="pagar-subtitle">Registra el pago de la cuota seleccionada de forma ordenada y segura.</p>
        </div>
        <div class="pagar-header-actions">
            <a target="_blank" href="<?= _SERVER_ ?>Cobros/generar_constancia/<?= $id_prestamo ?>"
               class="btn btn-outline-primary btn-sm">
                <i class="fa fa-file-pdf-o me-1"></i> Descargar constancia del crédito
            </a>
            <div class="pagar-status-pill">
                <i class="fa fa-circle me-1" style="font-size:9px;"></i> Préstamo activo
            </div>
        </div>
    </div>

    <!-- ===== SECCIÓN 1: Información del préstamo ===== -->
    <section class="pagar-card pagar-section">
        <div class="pagar-section-header">
            <h2>Información del préstamo</h2>
            <span>Datos informativos del cliente y deuda</span>
        </div>
        <div class="pagar-info-grid">
            <div class="pagar-info-box">
                <h3>Información General</h3>
                <div class="pagar-data-list">
                    <div class="pagar-data-row">
                        <span class="pagar-label">Nombre</span>
                        <span class="pagar-value"><?= $cliente_data->cliente_nombre . ' ' . $cliente_data->cliente_apellido_paterno . ' ' . $cliente_data->cliente_apellido_materno ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">DNI</span>
                        <span class="pagar-value"><?= $cliente_data->cliente_dni ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">Tipo de Pago</span>
                        <span class="pagar-value"><?= strtoupper($prestamos_data->prestamo_tipo_pago) ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">Fecha de Emisión</span>
                        <span class="pagar-value"><?= date('d/m/Y', strtotime($prestamos_data->prestamo_fecha_emision)) ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">Vencimiento del Préstamo</span>
                        <span class="pagar-value">
                            <?= !empty($fecha_fin_prestamo) ? date('d/m/Y', strtotime($fecha_fin_prestamo)) : '<span class="text-muted">No programado</span>' ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="pagar-info-box">
                <h3>Resumen Financiero</h3>
                <div class="pagar-data-list">
                    <div class="pagar-data-row pagar-data-row-secondary">
                        <span class="pagar-label">Capital pendiente sin interés</span>
                        <span class="pagar-value">S/ <?= number_format($capital_pendiente, 2) ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">Resta por pagar</span>
                        <span class="pagar-value pagar-amount">S/ <?= number_format($saldo_total_pendiente, 2) ?></span>
                    </div>
                </div>
                <input type="hidden" id="input_resta_por_pagar" value="<?= $valor_resta_por_pagar ?>">
            </div>
        </div>
    </section>

    <!-- ===== INFORMACIÓN DEL CRÉDITO (sección compartida) ===== -->
    <?php require _VIEW_PATH_ . 'cobros/informacion_credito.php'; ?>

    <!-- ===== PAGOS ANTERIORES DEL CLIENTE ===== -->
    <?php
    // El saldo restante de cada pago llega ya calculado desde Cobros::historial_pagos_con_saldo(),
    // la misma fuente que usa la constancia PDF.
    $pagos_hist = (isset($pagos_anteriores) && is_array($pagos_anteriores)) ? $pagos_anteriores : [];

    $total_pagado_hist    = 0;
    $total_descuento_hist = 0;
    foreach ($pagos_hist as $p_hist) {
        $total_pagado_hist    += floatval($p_hist->pago_monto);
        $total_descuento_hist += floatval($p_hist->pago_descuento_monto ?? 0);
    }
    ?>
    <section class="pagar-card pagar-section">
        <div class="pagar-section-header">
            <h2><i class="fa fa-history me-2"></i>Pagos anteriores del cliente</h2>
            <span>Historial del crédito antes de registrar un nuevo pago</span>
        </div>

        <?php if (!empty($pagos_hist)): ?>
            <div class="pagar-history-wrap">
                <table class="pagar-history-table">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th class="text-end">Monto pagado</th>
                        <th>Método de pago</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Saldo restante</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pagos_hist as $p_hist):
                        $es_amortizacion_h = empty($p_hist->fecha_cuota);
                        $descuento_hist    = floatval($p_hist->pago_descuento_monto ?? 0);
                        ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($p_hist->pago_fecha)) ?></td>
                            <td>
                                <?php if ($es_amortizacion_h): ?>
                                    <span class="pagar-history-tag amort">Amortización</span>
                                <?php else: ?>
                                    <span class="pagar-history-tag cuota">Cuota</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pagar-history-paid">S/ <?= number_format(floatval($p_hist->pago_monto), 2) ?></td>
                            <td><?= htmlspecialchars($p_hist->metodo_nombre ?? '-') ?></td>
                            <td class="text-end">
                                <?= $descuento_hist > 0
                                    ? '<span class="pagar-history-disc">- S/ ' . number_format($descuento_hist, 2) . '</span>'
                                    : '<span class="pagar-history-empty">-</span>' ?>
                            </td>
                            <td class="text-end pagar-history-balance">S/ <?= number_format($p_hist->saldo_restante ?? 0, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2">Total (<?= count($pagos_hist) ?> pago<?= count($pagos_hist) === 1 ? '' : 's' ?>)</td>
                        <td class="text-end pagar-history-paid">S/ <?= number_format($total_pagado_hist, 2) ?></td>
                        <td></td>
                        <td class="text-end">
                            <?= $total_descuento_hist > 0
                                ? '<span class="pagar-history-disc">- S/ ' . number_format($total_descuento_hist, 2) . '</span>'
                                : '<span class="pagar-history-empty">-</span>' ?>
                        </td>
                        <td class="text-end pagar-history-balance">S/ <?= number_format(floatval($saldo_total_pendiente), 2) ?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <p class="pagar-history-note">
                <i class="fa fa-info-circle me-1"></i>
                El saldo restante corresponde a la deuda que quedó después de cada pago.
                <a href="<?= _SERVER_ ?>cobros/pagos/<?= $prestamos_data->id_prestamos ?>">Ver detalle completo</a>
            </p>
        <?php else: ?>
            <p class="pagar-history-none">
                <i class="fa fa-inbox me-2"></i>Este crédito aún no registra pagos. Este sería el primero.
            </p>
        <?php endif; ?>
    </section>

    <?php if (!empty($cuota_a_pagar)): ?>
    <?php $proxima_cuota = $cuotas_pendientes[1] ?? null; ?>

    <!-- ===== SECCIÓN 2: Cuotas ===== -->
    <section class="pagar-card pagar-section">
        <div class="pagar-section-header">
            <h2>Cuotas del préstamo</h2>
            <span>Selecciona una o varias cuotas pendientes para cobrarlas en una sola operación</span>
        </div>

        <!-- Selector de cuotas pendientes (lista compacta con desplazamiento horizontal) -->
        <div class="pagar-quota-picker">
            <div class="pagar-quota-picker-head">
                <strong><i class="fa fa-list-ul me-1"></i> Cuotas pendientes</strong>
                <span id="picker_resumen" class="pagar-quota-picker-count">1 cuota seleccionada</span>
            </div>
            <div class="pagar-quota-strip" id="pagar_quota_strip">
                <?php foreach ($cuotas_pendientes as $idx_c => $cuota_p):
                    $num_cuota   = $numero_de_cuota[$cuota_p->id_pago_diario] ?? ($idx_c + 1);
                    $monto_cuota = number_format($cuota_p->pago_diario_monto, 2, '.', '');
                    $vencida     = strtotime(date('Y-m-d')) > strtotime($cuota_p->pago_diario_fecha);
                    ?>
                    <label class="pagar-quota-chip<?= $idx_c === 0 ? ' is-selected' : '' ?><?= $vencida ? ' is-late' : '' ?>">
                        <input type="checkbox"
                               class="cuota-check"
                               name="id_pagos[]"
                               value="<?= $cuota_p->id_pago_diario ?>"
                               data-monto="<?= $monto_cuota ?>"
                               data-numero="<?= $num_cuota ?>"
                               data-fecha="<?= date('Y-m-d', strtotime($cuota_p->pago_diario_fecha)) ?>"
                            <?= $idx_c === 0 ? 'checked' : '' ?>>
                        <span class="chip-body">
                            <span class="chip-num">
                                Cuota <?= $num_cuota ?>
                                <?php if ($idx_c === 0): ?><em class="chip-tag">Actual</em><?php endif; ?>
                                <?php if ($vencida): ?><em class="chip-tag late">Vencida</em><?php endif; ?>
                            </span>
                            <span class="chip-fecha"><i class="fa fa-calendar-day me-1"></i><?= date('d/m/Y', strtotime($cuota_p->pago_diario_fecha)) ?></span>
                            <span class="chip-monto">S/ <?= number_format($cuota_p->pago_diario_monto, 2) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
            <p class="pagar-quota-picker-hint">
                <i class="fa fa-info-circle me-1"></i>
                Marca las cuotas adicionales que el cliente desea adelantar. Debe quedar al menos una cuota seleccionada.
            </p>
        </div>

        <div class="pagar-quota-grid">
            <!-- Cuota actual + descuento -->
            <div>
                <article class="pagar-quota-card pagar-quota-current">
                    <small id="label_total_pagar">Total a pagar (1 cuota)</small>
                    <p class="pagar-quota-money" id="cuota_monto_display">
                        S/ <?= number_format($cuota_a_pagar->pago_diario_monto, 2) ?>
                    </p>
                    <div id="quota_discount_detail" class="pagar-quota-discount-detail" style="display:none;">
                        <span class="pagar-quota-original" id="quota_original_amount"></span>
                        <span class="pagar-quota-discount-badge" id="quota_discount_badge"></span>
                    </div>
                    <div class="pagar-quota-date highlight">
                        <i class="fa fa-calendar-times me-1"></i>
                        Vencimiento cuota actual: <strong><?= date('d/m/Y', strtotime($cuota_a_pagar->pago_diario_fecha)) ?></strong>
                    </div>
                    <!-- Inputs de control -->
                    <input type="hidden" id="id_pago"           name="id_pago"           value="<?= $cuota_a_pagar->id_pago_diario ?>">
                    <input type="hidden" id="monto_cuota_actual"                          value="<?= number_format($cuota_a_pagar->pago_diario_monto, 2, '.', '') ?>">
                    <input type="hidden" id="total_cuotas_sel"                            value="<?= number_format($cuota_a_pagar->pago_diario_monto, 2, '.', '') ?>">
                    <input type="hidden" id="monto_pagar"       name="monto_pagar"        value="<?= number_format($cuota_a_pagar->pago_diario_monto, 2, '.', '') ?>">
                    <input type="hidden" id="prestamo_prox_cobro" name="prestamo_prox_cobro" value="<?= $proxima_cuota ? date('Y-m-d', strtotime($proxima_cuota->pago_diario_fecha)) : 'Préstamo Finalizado' ?>">
                </article>

                <!-- Toggle descuento -->
                <div class="pagar-discount-box">
                    <div class="pagar-discount-header">
                        <strong>Aplicar descuento</strong>
                        <div class="pagar-switch-options">
                            <span id="disc_switch_si" class="pagar-switch-opt">Sí</span>
                            <span id="disc_switch_no" class="pagar-switch-opt active">No</span>
                        </div>
                    </div>
                    <div id="div_descontar" style="display:none;" class="pagar-discount-input-wrap">
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="text" inputmode="decimal" class="form-control"
                                   id="descontar_cantidad" name="descontar_cantidad"
                                   placeholder="0.00">
                        </div>
                        <span id="etiqueta_descuento" class="pagar-discount-hint" style="display:none;">
                            <i class="fa fa-tags me-1"></i> Con descuento aplicado
                        </span>
                    </div>
                </div>
            </div>

            <!-- Próxima cuota (se recalcula según las cuotas seleccionadas) -->
            <article class="pagar-quota-card pagar-quota-next">
                <small>Próxima cuota tras el pago</small>
                <div id="next_quota_pending" <?= $proxima_cuota ? '' : 'style="display:none;"' ?>>
                    <p class="pagar-quota-money" id="next_quota_monto">
                        S/ <?= $proxima_cuota ? number_format($proxima_cuota->pago_diario_monto, 2) : '0.00' ?>
                    </p>
                    <div class="pagar-quota-date">
                        <i class="fa fa-calendar-day me-1"></i>
                        Fecha: <strong id="next_quota_fecha"><?= $proxima_cuota ? date('d/m/Y', strtotime($proxima_cuota->pago_diario_fecha)) : '-' ?></strong>
                    </div>
                </div>
                <div id="next_quota_done" <?= $proxima_cuota ? 'style="display:none;"' : '' ?>>
                    <p class="pagar-quota-money" style="font-size:22px; color:#16a34a;">
                        <i class="fa fa-check-circle me-1"></i> Última cuota
                    </p>
                    <div class="pagar-quota-date" style="color:#16a34a; font-weight:600;">
                        <i class="fa fa-trophy me-1"></i> Con esta operación el préstamo quedará cancelado.
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- ===== GRID PRINCIPAL: Formulario + Resumen ===== -->
    <div class="pagar-main-grid">

        <!-- Formulario de pago -->
        <section class="pagar-card pagar-section">
            <div class="pagar-section-header">
                <h2>Formulario de pago</h2>
                <span>Completa los datos del pago realizado</span>
            </div>

            <!-- Datos del monto -->
            <div class="pagar-form-divider">Datos del monto</div>
            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="pagar-form-label">
                        Monto recibido (S/) <span class="text-danger">*</span>
                    </label>
                    <input type="text" inputmode="decimal"
                           id="monto_recibido" name="monto_recibido"
                           class="form-control"
                           onkeyup="validar_numeros_decimales_dos(this.id); calcular_vuelto()"
                           placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label class="pagar-form-label" id="label_vuelto">Diferencia / Vuelto (S/)</label>
                    <input type="text" id="monto_vuelto" class="form-control fw-bold text-muted"
                           readonly value="0.00">
                    <input type="hidden" id="monto_vuelto_db" name="monto_vuelto" value="0">
                </div>
            </div>
            <div id="grupo_dar_vuelto" class="pagar-check-row mb-3" style="display:none;">
                <input class="form-check-input" type="checkbox" id="dar_vuelto" name="dar_vuelto"
                       onchange="calcular_vuelto()">
                <label class="form-check-label pagar-dar-vuelto-label" for="dar_vuelto">
                    <i class="fa fa-reply me-1"></i> Dar vuelto al cliente
                </label>
            </div>

            <!-- Datos del método de pago -->
            <div class="pagar-form-divider">Datos del método de pago</div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="pagar-form-label">Método de Pago <span class="text-danger">*</span></label>
                    <select id="pago_metodo" name="pago_metodo" class="form-select"
                            onchange="cambiar_metodo_pago()" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($metodos_pago as $metodo): ?>
                            <option value="<?= $metodo->id_metodo_pago ?>"
                                    data-tipo="<?= strtolower($metodo->metodo_pago_nombre) ?>">
                                <?= $metodo->metodo_pago_nombre ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cuenta receptora: dato INTERNO de conciliación -->
                <div class="col-md-6">
                    <label class="pagar-form-label">
                        Cuenta / titular receptor
                        <span class="pagar-badge-interno"><i class="fa fa-lock me-1"></i>Uso interno</span>
                    </label>
                    <input type="text" id="cuenta_receptora" name="cuenta_receptora"
                           class="form-control" maxlength="120"
                           list="lista_cuentas_receptoras"
                           placeholder="Ej. Plin Guzmán, Yape Ana, Transferencia BCP">
                    <datalist id="lista_cuentas_receptoras">
                        <?php foreach (($cuentas_receptoras ?? []) as $cuenta_r): ?>
                            <option value="<?= htmlspecialchars($cuenta_r->cuenta) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                    <small class="pagar-hint-interno">
                        No aparece en el voucher del cliente. Solo para conciliación de caja.
                    </small>
                </div>
            </div>

            <!-- Operación y Titular (Yape / Plin / Transferencia) -->
            <div id="grupo_operacion_titular" class="row g-3 mb-3" style="display:none;">
                <div class="col-md-6">
                    <label class="pagar-form-label">Número de Operación</label>
                    <input type="text" id="num_operacion" name="num_operacion"
                           class="form-control" placeholder="Ej. 00045891">
                </div>
                <div class="col-md-6">
                    <label class="pagar-form-label">Nombre del Titular</label>
                    <input type="text" id="nombre_titular" name="nombre_titular"
                           class="form-control" placeholder="Nombre de quien realizó el pago">
                </div>
            </div>

            <!-- Banco y Fecha (Transferencia) -->
            <div id="grupo_transferencia" class="row g-3 mb-3" style="display:none;">
                <div class="col-md-6">
                    <label class="pagar-form-label">Banco o Entidad</label>
                    <select id="banco_entidad" name="banco_entidad" class="form-select">
                        <option value="">Seleccione Banco...</option>
                        <?php foreach ($bancos as $banco): ?>
                            <option value="<?= $banco->id_banco ?>">
                                <?= $banco->banco_nombre ?> (<?= $banco->banco_abreviado ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="pagar-form-label">Fecha de Transferencia</label>
                    <input type="date" id="fecha_transferencia" name="fecha_transferencia"
                           class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <!-- Observación -->
            <div class="pagar-form-divider">Observación</div>
            <div class="mb-1">
                <label class="pagar-form-label">Observación / Detalles</label>
                <input type="text" id="pago_observacion" name="pago_observacion"
                       class="form-control"
                       placeholder="Agregar algún detalle adicional del pago...">
            </div>
        </section>

        <!-- Resumen de operación -->
        <aside class="pagar-summary-card pagar-card">
            <h2 class="pagar-summary-title">Resumen de operación</h2>
            <p class="pagar-summary-sub">Verifica los montos antes de confirmar el pago.</p>

            <div class="pagar-summary-list">
                <div class="pagar-summary-item">
                    <span id="label_resumen_cuotas">Cuotas seleccionadas (1)</span>
                    <strong id="resumen_cuota">S/ 0.00</strong>
                </div>
                <div class="pagar-summary-detalle" id="resumen_cuotas_detalle"></div>
                <div class="pagar-summary-item pagar-summary-discount" id="li_resumen_descuento" style="display:none;">
                    <span><i class="fa fa-arrow-down me-1"></i> Descuento aplicado</span>
                    <strong id="resumen_descuento">- S/ 0.00</strong>
                </div>
            </div>

            <div class="pagar-summary-total">
                <span>Monto final a pagar</span>
                <strong id="resumen_total">S/ 0.00</strong>
            </div>

            <div class="pagar-summary-list pagar-summary-after-total">
                <div class="pagar-summary-item">
                    <span id="label_monto_recibido">Monto Recibido</span>
                    <strong class="text-success" id="resumen_recibido">S/ 0.00</strong>
                </div>
                <div class="pagar-summary-item" id="li_resumen_vuelto" style="display:none;">
                    <span id="label_resumen_diferencia">
                        <i class="fa fa-check-circle me-1"></i> Diferencia / Vuelto
                    </span>
                    <strong class="text-muted" id="resumen_vuelto">S/ 0.00</strong>
                </div>
            </div>
        </aside>
    </div>

    <!-- ===== GARANTÍAS ===== -->
    <section class="pagar-card pagar-section">
        <div class="pagar-section-header">
            <h2><i class="fa fa-shield-alt me-2"></i>Garantías</h2>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="pagar-data-row">
                    <span class="pagar-label">Garantía</span>
                    <span class="pagar-value"><?= $prestamos_data->prestamo_garantia ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pagar-data-row">
                    <span class="pagar-label">Garante</span>
                    <span class="pagar-value"><?= $garante->cliente_nombre . ' ' . $garante->cliente_apellido_paterno . ' ' . $garante->cliente_apellido_materno ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ACCIONES ===== -->
    <div class="pagar-actions">
        <a href="<?= _SERVER_ ?>prestamos/prestamos" class="btn btn-outline-secondary btn-lg">
            <i class="fa fa-times me-2"></i>Cancelar
        </a>
        <?php /* AMORTIZAR oculto temporalmente
        if ($puede_amortizar):
        ?>
        <a href="<?= _SERVER_ ?>cobros/amortizar/<?= $id_prestamo ?>" class="btn btn-success btn-lg">
            <i class="fa fa-hand-holding-usd me-2"></i>Amortizar
        </a>
        <?php
        endif;
        */ ?>
        <button id="btn-guardar-pago" onclick="guardar_pago_prestamo()" type="button" class="btn btn-primary btn-lg">
            <i class="fa fa-check-circle me-2"></i>Confirmar Pago
        </button>
    </div>

    <?php else: ?>
    <!-- Préstamo finalizado -->
    <section class="pagar-card pagar-section text-center py-5">
        <i class="fa fa-check-circle fa-4x text-success mb-3 d-block"></i>
        <h4>¡Préstamo Finalizado!</h4>
        <p class="text-muted mb-0">Este cliente ya no tiene cuotas pendientes de pago para este préstamo.</p>
    </section>
    <?php endif; ?>

</div>

<!-- ===== ESTILOS ===== -->
<style>
:root {
    --pg-primary:      #2563eb;
    --pg-primary-dark: #1d4ed8;
    --pg-success:      #16a34a;
    --pg-warning:      #f59e0b;
    --pg-danger:       #dc2626;
    --pg-muted:        #6b7280;
    --pg-border:       #e5e7eb;
    --pg-soft-blue:    #eff6ff;
    --pg-soft-green:   #ecfdf5;
    --pg-soft-orange:  #fff7ed;
    --pg-shadow:       0 8px 24px rgba(15,23,42,0.07);
    --pg-radius:       16px;
}

.pagar-page { padding: 8px 20px 32px; }

/* Header */
.pagar-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 22px;
    flex-wrap: wrap;
}
.pagar-back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    color: var(--pg-muted);
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}
.pagar-back-link:hover { color: var(--pg-primary); }
.pagar-title { margin: 0 0 4px; font-size: 26px; font-weight: 800; letter-spacing: -.4px; }
.pagar-subtitle { margin: 0; color: var(--pg-muted); font-size: 14px; }
.pagar-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.pagar-status-pill {
    padding: 8px 14px;
    border-radius: 999px;
    background: var(--pg-soft-blue);
    color: var(--pg-primary-dark);
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    margin-top: 4px;
}

/* Cards y secciones */
.pagar-card {
    background: #fff;
    border: 1px solid var(--pg-border);
    border-radius: var(--pg-radius);
    box-shadow: var(--pg-shadow);
}
.pagar-section { padding: 22px; margin-bottom: 20px; }
.pagar-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}
.pagar-section-header h2 { margin: 0; font-size: 17px; font-weight: 700; }
.pagar-section-header span { color: var(--pg-muted); font-size: 13px; }

/* Info grid */
.pagar-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}
.pagar-info-box {
    border: 1px solid var(--pg-border);
    border-radius: 12px;
    padding: 18px;
    background: #fbfdff;
}
.pagar-info-box h3 { margin: 0 0 14px; font-size: 14px; font-weight: 700; color: #111827; }
.pagar-data-list { display: grid; gap: 10px; }
.pagar-data-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 8px;
    border-bottom: 1px dashed var(--pg-border);
}
.pagar-data-row:last-child { padding-bottom: 0; border-bottom: none; }
.pagar-label { color: var(--pg-muted); font-size: 13px; }
.pagar-value { font-weight: 700; font-size: 13px; text-align: right; }
.pagar-amount { font-size: 17px; font-weight: 800; color: #dc2626; }
/* Dato secundario: informativo, no debe competir con "Resta por pagar" */
.pagar-data-row-secondary .pagar-label,
.pagar-data-row-secondary .pagar-value {
    font-size: 12px;
    color: var(--pg-muted);
}
.pagar-data-row-secondary .pagar-value { font-weight: 600; }

/* Campo interno de conciliación (cuenta receptora) */
.pagar-badge-interno {
    display: inline-block;
    margin-left: 6px;
    padding: 1px 8px;
    border-radius: 999px;
    background: #fff7ed;
    color: #b45309;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.pagar-hint-interno { display: block; margin-top: 4px; font-size: 11.5px; color: var(--pg-muted); }

/* Selector de cuotas pendientes */
.pagar-quota-picker { margin-bottom: 18px; }
.pagar-quota-picker-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
    font-size: 13px;
}
.pagar-quota-picker-count {
    background: var(--pg-soft-blue);
    color: var(--pg-primary-dark);
    font-size: 12px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 999px;
}
.pagar-quota-strip {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    overflow-y: hidden;
    padding: 4px 2px 10px;
    scroll-snap-type: x proximity;
}
.pagar-quota-strip::-webkit-scrollbar { height: 8px; }
.pagar-quota-strip::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
.pagar-quota-strip::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 999px; }
.pagar-quota-chip {
    position: relative;
    flex: 0 0 auto;
    min-width: 158px;
    margin: 0;
    padding: 10px 12px;
    border: 1px solid var(--pg-border);
    border-radius: 12px;
    background: #fff;
    cursor: pointer;
    scroll-snap-align: start;
    transition: border-color .15s, background .15s, box-shadow .15s;
}
.pagar-quota-chip:hover { border-color: #93c5fd; }
.pagar-quota-chip input { position: absolute; opacity: 0; pointer-events: none; }
.pagar-quota-chip.is-selected {
    border-color: var(--pg-primary);
    background: var(--pg-soft-blue);
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.pagar-quota-chip .chip-body { display: flex; flex-direction: column; gap: 3px; }
.pagar-quota-chip .chip-num { font-size: 12px; font-weight: 700; color: #111827; }
.pagar-quota-chip .chip-fecha { font-size: 11px; color: var(--pg-muted); }
.pagar-quota-chip .chip-monto { font-size: 15px; font-weight: 800; color: var(--pg-primary-dark); }
.pagar-quota-chip .chip-tag {
    display: inline-block;
    margin-left: 4px;
    padding: 1px 6px;
    border-radius: 999px;
    background: #e0e7ff;
    color: #3730a3;
    font-size: 9px;
    font-style: normal;
    font-weight: 700;
    text-transform: uppercase;
}
.pagar-quota-chip .chip-tag.late { background: #fee2e2; color: #b91c1c; }
.pagar-quota-chip.is-late .chip-monto { color: var(--pg-danger); }
.pagar-quota-picker-hint { margin: 0; font-size: 12px; color: var(--pg-muted); }
.pagar-summary-detalle {
    display: grid;
    gap: 3px;
    margin: 2px 0 4px;
    padding-left: 2px;
    font-size: 11.5px;
    color: var(--pg-muted);
}
.pagar-summary-detalle span { display: flex; justify-content: space-between; gap: 10px; }
.pagar-summary-detalle em { font-style: normal; }

/* Pagos anteriores */
.pagar-history-wrap {
    max-height: 320px;
    overflow: auto;
    border: 1px solid var(--pg-border);
    border-radius: 12px;
}
.pagar-history-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.pagar-history-table th,
.pagar-history-table td { padding: 9px 12px; white-space: nowrap; }
.pagar-history-table thead th {
    position: sticky;
    top: 0;
    z-index: 1;
    background: #f8fafc;
    color: var(--pg-muted);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
    border-bottom: 1px solid var(--pg-border);
}
.pagar-history-table tbody tr { border-bottom: 1px dashed var(--pg-border); }
.pagar-history-table tbody tr:last-child { border-bottom: none; }
.pagar-history-table tbody tr:hover { background: var(--pg-soft-blue); }
.pagar-history-table tfoot td {
    background: #f8fafc;
    border-top: 1px solid var(--pg-border);
    font-weight: 700;
    position: sticky;
    bottom: 0;
}
.pagar-history-table .text-end { text-align: right; }
.pagar-history-paid { font-weight: 700; color: var(--pg-success); }
.pagar-history-balance { font-weight: 700; color: #111827; }
.pagar-history-disc { color: var(--pg-warning); font-weight: 600; }
.pagar-history-empty { color: #cbd5e1; }
.pagar-history-tag {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.pagar-history-tag.cuota  { background: var(--pg-soft-blue);   color: var(--pg-primary-dark); }
.pagar-history-tag.amort  { background: var(--pg-soft-orange); color: #b45309; }
.pagar-history-note { margin: 10px 2px 0; font-size: 12px; color: var(--pg-muted); }
.pagar-history-note a { color: var(--pg-primary); font-weight: 600; text-decoration: none; }
.pagar-history-note a:hover { text-decoration: underline; }
.pagar-history-none {
    margin: 0;
    padding: 18px;
    text-align: center;
    color: var(--pg-muted);
    font-size: 13px;
    background: #f8fafc;
    border: 1px dashed var(--pg-border);
    border-radius: 12px;
}

/* Quota grid */
.pagar-quota-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 16px;
}
.pagar-quota-card {
    padding: 20px;
    border-radius: var(--pg-radius);
    border: 1px solid var(--pg-border);
    background: #fff;
}
.pagar-quota-current {
    border-color: rgba(37,99,235,.2);
    background: linear-gradient(135deg, #fff 0%, var(--pg-soft-blue) 100%);
}
.pagar-quota-next {
    background: linear-gradient(135deg, #fff 0%, var(--pg-soft-green) 100%);
}
.pagar-quota-card small {
    display: block;
    color: var(--pg-muted);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    font-size: 11px;
    margin-bottom: 8px;
}
.pagar-quota-money {
    margin: 0 0 4px;
    font-size: 30px;
    font-weight: 900;
}
.pagar-quota-money.discounted { color: #22c55e; }
.pagar-quota-discount-detail {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}
.pagar-quota-original {
    color: var(--pg-danger);
    text-decoration: line-through;
    font-size: 13px;
}
.pagar-quota-discount-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 6px;
    background: #f59e0b;
    color: #111827;
    font-size: 12px;
    font-weight: 800;
}
.pagar-quota-date { margin-top: 10px; color: var(--pg-muted); font-size: 13px; }
.pagar-quota-date.highlight { color: #ef4444; font-weight: 700; }

/* Discount box */
.pagar-discount-box {
    margin-top: 14px;
    padding: 16px;
    border-radius: 12px;
    background: var(--pg-soft-orange);
    border: 1px solid #fed7aa;
}
.pagar-discount-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.pagar-discount-header strong { font-size: 14px; }
.pagar-switch-options {
    display: inline-flex;
    padding: 3px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid var(--pg-border);
}
.pagar-switch-opt {
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    color: var(--pg-muted);
    cursor: pointer;
    transition: all .15s;
    user-select: none;
}
.pagar-switch-opt.active {
    color: #fff;
    background: var(--pg-warning);
}
.pagar-discount-input-wrap { display: flex; flex-direction: column; gap: 8px; }
.pagar-discount-hint { font-size: 12px; color: #92400e; font-weight: 600; }

/* Main grid */
.pagar-main-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.5fr);
    gap: 20px;
    align-items: start;
    margin-bottom: 20px;
}

/* Form */
.pagar-form-divider {
    font-size: 13px;
    font-weight: 800;
    color: #111827;
    border-top: 1px solid var(--pg-border);
    padding-top: 14px;
    margin: 16px 0 14px;
}
.pagar-form-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--pg-muted);
    margin-bottom: 6px;
}
.pagar-check-row {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 14px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
}
.pagar-dar-vuelto-label { font-weight: 700; color: #15803d; font-size: 14px; cursor: pointer; }

/* Summary */
.pagar-summary-card {
    position: sticky;
    top: 22px;
    padding: 22px;
}
.pagar-summary-title { margin: 0 0 4px; font-size: 17px; font-weight: 700; }
.pagar-summary-sub { margin: 0 0 16px; color: var(--pg-muted); font-size: 13px; }
.pagar-summary-list { display: grid; gap: 0; }
.pagar-summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    padding: 10px 0;
    border-bottom: 1px dashed var(--pg-border);
}
.pagar-summary-item:last-child { border-bottom: none; }
.pagar-summary-item > span:first-child { color: var(--pg-muted); }
.pagar-summary-discount strong { color: var(--pg-danger); }
.pagar-summary-total {
    margin-top: 16px;
    padding: 18px;
    border-radius: 14px;
    background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
    color: #fff;
    box-shadow: 0 8px 18px rgba(17,24,39,.16);
}
.pagar-summary-total span {
    display: block;
    color: #d1d5db;
    font-size: 12px;
    margin-bottom: 4px;
}
.pagar-summary-total strong { font-size: 26px; font-weight: 900; }
.pagar-summary-after-total { margin-top: 14px; padding-top: 4px; }

/* Actions */
.pagar-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

/* Responsive */
@media (max-width: 900px) {
    .pagar-info-grid,
    .pagar-quota-grid,
    .pagar-main-grid { grid-template-columns: 1fr; }
    .pagar-summary-card { position: static; }
}
@media (max-width: 600px) {
    .pagar-header { flex-direction: column; }
    .pagar-actions { flex-direction: column-reverse; }
    .pagar-actions .btn { width: 100%; }
}
</style>

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>
