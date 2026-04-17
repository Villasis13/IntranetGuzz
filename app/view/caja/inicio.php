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
                        <input type="number" id="monto_caja" name="monto_caja" class="form-control"  required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" id="contrasenha" name="pago_monto" class="form-control"  required>
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
                <h5 class="modal-title" style="font-weight: 700; color: #1e293b; font-size: 1.15rem; font-family: var(--sans, sans-serif);">
                    <i class="fas fa-cash-register mr-2" style="color: #10b981;"></i> Apertura de Caja
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: #94a3b8; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 24px;">

                <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 18px; text-align: center; margin-bottom: 24px;">
                    <span style="color: #059669; font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                        Último saldo registrado
                    </span>
                    <div style="color: #064e3b; font-size: 32px; font-weight: 800; margin-top: 4px; font-family: var(--mono, monospace);">
                        S/ <?= number_format($ultima_caja->monto_caja, 2) ?>
                    </div>
                </div>

                <div class="form-group text-center" style="margin-bottom: 0;">
                    <label style="font-weight: 600; color: #475569; font-size: 14.5px; margin-bottom: 12px; font-family: var(--sans, sans-serif);">
                        ¿Con qué monto iniciará la caja hoy?
                    </label>

                    <div class="input-group mx-auto" style="max-width: 85%; border-radius: 10px; overflow: hidden; border: 2px solid #cbd5e1; background: #fff; transition: all 0.2s;" onfocusin="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.15)'" onfocusout="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="background: transparent; border: none; color: #64748b; font-weight: 700; font-size: 20px; padding-left: 20px;">S/</span>
                        </div>
                        <input type="number" step="0.01" id="caja_monto" name="caja_monto" class="form-control text-center" value="<?= $ultima_caja->monto_caja ?>" onkeyup="validar_numeros_decimales_dos(this.id)" required
                               style="border: none; font-size: 26px; font-weight: 700; color: #0f172a; height: 64px; padding-right: 20px; outline: none; box-shadow: none; font-family: var(--mono, monospace);">
                    </div>

                    <small style="color: #94a3b8; font-size: 12px; display: block; margin-top: 16px; line-height: 1.5;">
                        Mantenga el monto sugerido para una <b>apertura rápida</b>,<br>o modifíquelo si hubo un ingreso/retiro extra.
                    </small>
                </div>

            </div>

            <div class="modal-footer" style="border-top: none; padding: 0 24px 24px 24px; justify-content: center; gap: 12px;">
                <button type="button" class="btn" data-dismiss="modal" style="background: #f1f5f9; border: none; color: #475569; font-weight: 600; border-radius: 8px; padding: 12px 24px; font-size: 14.5px; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    Cancelar
                </button>
                <button type="button" class="btn" onclick="gestionar_caja()" style="background: #10b981; border: none; color: #fff; font-weight: 600; border-radius: 8px; padding: 12px 28px; font-size: 14.5px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'">
                    <i class="fa fa-check-circle mr-2"></i> Abrir Caja Ahora
                </button>
            </div>

        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-8 col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-left-primary">
                        <div class="card-body">
                            <?php
                            if($ultima_caja->estado_caja == 1){
                                ?>
                                <h4 class="text-success font-weight-bold mb-3"><i class="fa fa-lock-open"></i> CAJA ABIERTA</h4>
                                <h5>Tiene en caja: <span class="font-weight-bold text-primary">S/ <?= $monto_caja_abierta ?></span></h5>
                                <h5 class="text-muted mb-4" style="font-size: 15px;">Abierto el: <?= $fecha_caja ?></h5>

                                <input id="input_editar_monto" name="input_editar_monto" style="display: none"
                                       class="form-control w-25"
                                       placeholder="Ingrese nuevo monto"
                                       onkeyup="validar_numeros_decimales_dos(this.id)"
                                >
                                <button type="button"
                                        data-toggle="modal"
                                        data-target="#dinero_caja"
                                        onclick="limpiar_modal_alumno()"
                                        class="btn btn-sm btn-primary text-white">
                                    <i class="fa fa-plus"></i> Añadir dinero
                                </button>
                                <button type="button"
                                        id="btn-cerrar-caja"
                                        class="btn btn-sm btn-danger text-white ml-2"
                                        onclick="cerrar_caja(<?= $ultima_caja->id_caja ?>)">
                                    <i class="fa fa-lock"></i> Cerrar caja
                                </button>
                                <a style="display: none" id="btn_editar" onclick="guardar_nuevo_monto()" class="btn btn-sm btn-success text-white">
                                    <i class="fa fa-save"></i>
                                </a>
                                <?php
                            }else{
                                ?>
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
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 mt-3">
            <div class="card shadow mb-4">


                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detalle de Arqueo de Caja</h6>
                    <?php if($ultima_caja->estado_caja == 1): ?>
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <a href="<?= _SERVER_ ?>caja/exportar_pdf_arqueo/<?= $ultima_caja->id_caja ?>"
                               target="_blank"
                               class="btn btn-sm btn-danger text-white">
                                <i class="fa fa-file-pdf mr-1"></i> Exportar PDF
                            </a>
                            <span class="badge bg-success text-white px-3 py-2">Caja Activa</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if($ultima_caja->estado_caja != 1): ?>
                        <div class="text-center py-5">
                            <i class="fa fa-lock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay una caja abierta</h5>
                            <p class="text-muted">Debes aperturar la caja para ver el arqueo actual.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center align-middle" width="100%">
                                <thead class="bg-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Usuario / Cliente</th>
                                    <th>Descripción / Método</th>
                                    <th class="text-success">Ingresos</th>
                                    <th class="text-danger">Egresos</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Apertura de Caja</td>
                                </tr>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($fecha_caja)) ?></td>
                                    <td><?= date('H:i:s', strtotime($fecha_caja)) ?></td>
                                    <td><?= $usuario_actual ?></td>
                                    <td>Saldo del día Anterior / Monto inicial</td>
                                    <td class="text-success font-weight-bold">S/ <?= number_format($ultima_caja->monto_apertura_caja, 2) ?></td>
                                    <td></td>
                                </tr>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Pago de Cuotas</td>
                                </tr>
                                <?php
                                $suma_pagos = 0;
                                if(!empty($pagos_caja)):
                                    foreach($pagos_caja as $pago):
                                        $suma_pagos += $pago->pago_monto;
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($pago->pago_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($pago->pago_fecha)) ?></td>
                                            <td><?= $pago->cliente_nombre . ' ' . $pago->cliente_apellido_paterno ?></td>
                                            <td><?= ucfirst($pago->pago_metodo) ?></td>
                                            <td class="text-success">S/ <?= number_format($pago->pago_monto, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-muted fst-italic">No se han registrado pagos en este turno.</td></tr>
                                <?php endif; ?>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Préstamos</td>
                                </tr>
                                <?php
                                $suma_prestamos = 0;
                                if(!empty($prestamos_caja)):
                                    foreach($prestamos_caja as $prestamo):
                                        $suma_prestamos += $prestamo->prestamo_monto;
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($prestamo->prestamo_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($prestamo->prestamo_fecha)) ?></td>
                                            <td><?= $prestamo->cliente_nombre . ' ' . $prestamo->cliente_apellido_paterno ?></td>
                                            <td>Préstamo <?= ucfirst($prestamo->prestamo_tipo_pago) ?></td>
                                            <td></td>
                                            <td class="text-danger">S/ <?= number_format($prestamo->prestamo_monto, 2) ?></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-muted fst-italic">No se han otorgado préstamos en este turno.</td></tr>
                                <?php endif; ?>

                                <tr class="table-secondary">
                                    <td colspan="6" class="text-left font-weight-bold">Ingreso de Monto Manual</td>
                                </tr>
                                <?php
                                $suma_ingresos_manuales = 0;
                                if(!empty($ingresos_manuales)):
                                    foreach($ingresos_manuales as $mov):
                                        $suma_ingresos_manuales += $mov->caja_movimiento_monto;
                                        ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($mov->caja_movimiento_fecha)) ?></td>
                                            <td><?= date('H:i:s', strtotime($mov->caja_movimiento_fecha)) ?></td>
                                            <td><?= $usuario_actual ?></td>
                                            <td>Añadido a Caja Manualmente</td>
                                            <td class="text-success">S/ <?= number_format($mov->caja_movimiento_monto, 2) ?></td>
                                            <td></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                    <tr><td colspan="6" class="text-muted fst-italic">No hay ingresos manuales registrados.</td></tr>
                                <?php endif; ?>

                                </tbody>

                                <tfoot class="bg-light">
                                <?php
                                // Cálculo final para el cuadre
                                $monto_apertura = $ultima_caja->monto_apertura_caja; // Asegúrate de que esta sea la variable del saldo inicial
                                $total_ingresos = $suma_pagos + $suma_ingresos_manuales;
                                $total_egresos = $suma_prestamos;

                                // Saldo Actual = Apertura + Ingresos - Egresos
                                $saldo_actual_calculado = $monto_apertura + $total_ingresos - $total_egresos;
                                ?>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td class="text-right font-weight-bold">Total Ingresos Turno:</td>
                                    <td class="text-success font-weight-bold">S/ <?= number_format($total_ingresos, 2) ?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0"></td>
                                    <td class="text-right font-weight-bold">Total Egresos Turno:</td>
                                    <td></td>
                                    <td class="text-danger font-weight-bold">S/ <?= number_format($total_egresos, 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-0 bg-white"></td>
                                    <td class="text-right text-primary h5 font-weight-bold py-3">Saldo Actual en Caja:</td>
                                    <td colspan="2" class="text-center text-primary h5 font-weight-bold py-3 bg-white" style="border: 2px solid #4e73df;">
                                        S/ <?= number_format($saldo_actual_calculado, 2) ?>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
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