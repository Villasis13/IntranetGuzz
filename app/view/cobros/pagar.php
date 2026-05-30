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
        <div class="pagar-status-pill">
            <i class="fa fa-circle me-1" style="font-size:9px;"></i> Préstamo activo
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
                    <div class="pagar-data-row">
                        <span class="pagar-label">Monto Inicial de la deuda</span>
                        <span class="pagar-value">S/ <?= number_format($prestamos_data->prestamo_monto + ($prestamos_data->prestamo_monto * $prestamos_data->prestamo_interes / 100), 2) ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">Capital pendiente</span>
                        <span class="pagar-value">S/ <?= number_format($capital_pendiente, 2) ?></span>
                    </div>
                    <div class="pagar-data-row">
                        <span class="pagar-label">Interés Aplicado</span>
                        <span class="pagar-value"><?= $prestamos_data->prestamo_interes ?>%</span>
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

    <?php if (!empty($cuota_a_pagar)): ?>
    <?php $proxima_cuota = $cuotas_pendientes[1] ?? null; ?>

    <!-- ===== SECCIÓN 2: Cuotas ===== -->
    <section class="pagar-card pagar-section">
        <div class="pagar-section-header">
            <h2>Cuotas del préstamo</h2>
            <span>Cuota actual y próxima cuota</span>
        </div>

        <div class="pagar-quota-grid">
            <!-- Cuota actual + descuento -->
            <div>
                <article class="pagar-quota-card pagar-quota-current">
                    <small>Cuota a pagar</small>
                    <p class="pagar-quota-money" id="cuota_monto_display">
                        S/ <?= number_format($cuota_a_pagar->pago_diario_monto, 2) ?>
                    </p>
                    <div id="quota_discount_detail" class="pagar-quota-discount-detail" style="display:none;">
                        <span class="pagar-quota-original" id="quota_original_amount"></span>
                        <span class="pagar-quota-discount-badge" id="quota_discount_badge"></span>
                    </div>
                    <div class="pagar-quota-date highlight">
                        <i class="fa fa-calendar-times me-1"></i>
                        Vencimiento: <strong><?= date('d/m/Y', strtotime($cuota_a_pagar->pago_diario_fecha)) ?></strong>
                    </div>
                    <!-- Inputs de control -->
                    <input type="hidden" id="id_pago"           name="id_pago"           value="<?= $cuota_a_pagar->id_pago_diario ?>">
                    <input type="hidden" id="monto_cuota_actual"                          value="<?= number_format($cuota_a_pagar->pago_diario_monto, 2, '.', '') ?>">
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

            <!-- Próxima cuota -->
            <article class="pagar-quota-card pagar-quota-next">
                <?php if ($proxima_cuota): ?>
                    <small>Próxima cuota</small>
                    <p class="pagar-quota-money">S/ <?= number_format($proxima_cuota->pago_diario_monto, 2) ?></p>
                    <div class="pagar-quota-date">
                        <i class="fa fa-calendar-day me-1"></i>
                        Fecha: <strong><?= date('d/m/Y', strtotime($proxima_cuota->pago_diario_fecha)) ?></strong>
                    </div>
                <?php else: ?>
                    <small>Próxima cuota</small>
                    <p class="pagar-quota-money" style="font-size:22px; color:#16a34a;">
                        <i class="fa fa-check-circle me-1"></i> Última cuota
                    </p>
                    <div class="pagar-quota-date" style="color:#16a34a; font-weight:600;">
                        <i class="fa fa-trophy me-1"></i> Al pagar esta cuota el préstamo quedará cancelado.
                    </div>
                <?php endif; ?>
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
                    <span>Cuota original</span>
                    <strong id="resumen_cuota">S/ 0.00</strong>
                </div>
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
