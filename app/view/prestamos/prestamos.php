<style>
    .color-rojo {
        color: red !important;
    }
    .fila-anulada {
        opacity: 0.72;
    }
    .fila-anulada td {
        color: #6c757d !important;
        text-decoration: line-through;
        text-decoration-color: rgba(220, 53, 69, 0.45);
    }
    .fila-anulada .badge,
    .fila-anulada .btn {
        text-decoration: none;
    }

    /* Bloque compacto de fechas del préstamo */
    .bloque-fechas {
        display: inline-grid;
        gap: 1px;
        text-align: left;
        font-size: 11.5px;
        line-height: 1.35;
        min-width: 168px;
    }
    .bf-modalidad {
        justify-self: start;
        margin-bottom: 3px;
        padding: 1px 8px;
        border-radius: 999px;
        background: #e7f1ff;
        color: #0d6efd;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .bf-linea {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }
    .bf-etq { color: #6c757d; }
    .bf-val { font-weight: 600; color: #212529; white-space: nowrap; }
    .bf-prox { margin-top: 2px; padding-top: 3px; border-top: 1px dashed #dee2e6; }
    .bf-prox .bf-val { color: #0d6efd; }
    .bf-alerta { color: #dc3545 !important; }
    .bf-nd { color: #adb5bd; font-style: normal; font-weight: 500; }
    .bf-cuotas { margin-top: 3px; color: #6c757d; font-size: 10.5px; }
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header bg-gradient-primary py-3">
					<div class="d-flex justify-content-between align-items-center">
                        <h4 style="font-weight: bold;">
                            Lista de Todos los Préstamos
                        </h4>
                        <a href="<?= _SERVER_ ?>prestamos/inicio" class="btn btn-success">
                            <i class="fa fa-arrow-left me-2"></i>Volver
                        </a>

                        <!--<button onclick="limpiar_clientes()" data-toggle="modal" data-target="#gestionCliente" class="btn btn-success btn-sm shadow-sm">
							<i class="fa fa-plus-circle me-2"></i>Nuevo Cliente
						</button>-->
					</div>
				</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered" id="dataTable">
							<thead class="thead-light">
							<tr class="text-center">
								<th>#</th>
								<th>Datos</th>
								<th>Monto Prestado</th>
								<th>Fechas del préstamo</th>
								<th>Días de Mora</th>
								<th>Motivo de Préstamo</th>
								<th><small>Comentarios</small></th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
							</thead>

							<tbody>
							<?php
							$a = 1;
							foreach ($prestamos_general as $c){


								$tipo_pago = $c->prestamo_tipo_pago;
								$color_fila = '';

								// Un préstamo con saldo en cero está cancelado aunque su estado
								// no se haya actualizado: se muestra como historial, sin cobrar.
								$saldo_pendiente_fila = floatval($c->prestamo_saldo_pagar ?? 0);
								$esta_cancelado = ($saldo_pendiente_fila <= 0)
									|| in_array(intval($c->prestamo_estado), [2, 4]);

								if ($c->prestamo_estado == 5) {
									$color_fila = 'background-color: #fde8ea;'; // rojo claro — anulado
								} elseif ($tipo_pago == 'Diario') {
									$color_fila = 'background-color: #e3f2fd;'; // azul claro
								} elseif ($tipo_pago == 'Semanal') {
									$color_fila = 'background-color: #e8f5e9;'; // verde claro
								} elseif ($tipo_pago == 'Mensual') {
									$color_fila = 'background-color: #fff8e1;'; // amarillo claro
								}


								?>
								<?php
								$clase_texto  = ($c->prestamo_estado == 3 || $c->prestamo_estado == 4) ? 'color-rojo' : '';
								$clase_texto .= ($c->prestamo_estado == 5) ? ' fila-anulada' : '';
								?>
                                <tr class="text-center <?= $clase_texto ?>" style="<?= $color_fila ?>">
									<td><?= $a  ?></td>
									<td style="width:50px">
                                        <small>DNI: <b><?= $c->cliente_dni ?></b></small> <br>
									    <small>Nombre: </small>
                                        <small><b>
                                            <?= $c->cliente_nombre .' '.
                                            $c->cliente_apellido_paterno.' '.
                                            $c->cliente_apellido_materno ?></b> </small></td>
                                    <td>
                                        <small>Total:   <?= $c->prestamo_monto + $c->prestamo_monto_interes ?></small><br>
                                      <small>Saldo:   <?= $c->prestamo_saldo_pagar ?> </small><br>
                                       <b> <?= $c->prestamo_tipo_pago ?></b>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        <?php
                                        // Bloque compacto de fechas: evita tener que abrir el cronograma impreso
                                        $fmt_f = function ($valor) {
                                            if (empty($valor) || $valor === '0000-00-00' || !strtotime($valor)) return null;
                                            return date('d/m/Y', strtotime($valor));
                                        };
                                        $f_emision = $fmt_f($c->prestamo_fecha_emision);
                                        $f_inicio  = $fmt_f($c->prestamo_fecha_inicio);
                                        $f_fin     = $fmt_f($c->fecha_fin_prestamo);
                                        // Próximo cobro: la cuota pendiente real; prestamo_prox_cobro solo como respaldo
                                        $f_prox    = $fmt_f($c->proxima_cuota_fecha) ?: $fmt_f($c->prestamo_prox_cobro);
                                        $vencido_f = !$esta_cancelado && $f_fin
                                                     && strtotime(date('Y-m-d')) > strtotime($c->fecha_fin_prestamo);
                                        ?>
                                        <div class="bloque-fechas">
                                            <span class="bf-modalidad"><?= htmlspecialchars($c->prestamo_tipo_pago) ?></span>
                                            <span class="bf-linea">
                                                <span class="bf-etq">Emisión</span>
                                                <span class="bf-val"><?= $f_emision ?: '—' ?></span>
                                            </span>
                                            <span class="bf-linea">
                                                <span class="bf-etq">Inicio</span>
                                                <span class="bf-val"><?= $f_inicio ?: '—' ?></span>
                                            </span>
                                            <span class="bf-linea">
                                                <span class="bf-etq">Vencimiento</span>
                                                <span class="bf-val<?= $vencido_f ? ' bf-alerta' : '' ?>">
                                                    <?= $f_fin ?: '<em class="bf-nd">No definida</em>' ?>
                                                </span>
                                            </span>
                                            <span class="bf-linea bf-prox">
                                                <span class="bf-etq">Próximo cobro</span>
                                                <span class="bf-val">
                                                    <?php if ($esta_cancelado): ?>
                                                        <em class="bf-nd">Sin cobros pendientes</em>
                                                    <?php else: ?>
                                                        <?= $f_prox ?: '<em class="bf-nd">No definido</em>' ?>
                                                    <?php endif; ?>
                                                </span>
                                            </span>
                                            <?php if (!$esta_cancelado && isset($c->cuotas_total) && $c->cuotas_total > 0): ?>
                                                <span class="bf-cuotas">
                                                    <?= (int)$c->cuotas_pendientes ?> de <?= (int)$c->cuotas_total ?> cuotas pendientes
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>0</td>
                                    <td><?= $c->prestamo_motivo ?></td>
                                    <td><?= $c->prestamo_comentario ?></td>
                                    <td>
                                        <?php
                                            if ($c->prestamo_estado == 1 && $esta_cancelado) {
                                                // Saldo en cero: el crédito ya está cancelado aunque el estado no se haya actualizado
                                                echo '<span class="badge bg-primary text-white" style="font-size: 0.85em; padding: 6px 10px; border-radius: 6px;">
                    <i class="fa fa-check-square me-1"></i> Cancelado
                  </span>';
                                            } else if ($c->prestamo_estado == 1) {
                                                // ACTIVO: Verde brillante con icono de check
                                                echo '<span class="badge bg-success text-white" style="font-size: 0.85em; padding: 6px 10px; border-radius: 6px;">
                    <i class="fa fa-check-circle me-1"></i> Activo
                  </span>';
                                            } else if ($c->prestamo_estado == 2) {
                                                // CANCELADO (Pagado): Azul con icono de doble check
                                                echo '<span class="badge bg-primary text-white" style="font-size: 0.85em; padding: 6px 10px; border-radius: 6px;">
                    <i class="fa fa-check-square me-1"></i> Cancelado
                  </span>';
                                            } else if ($c->prestamo_estado == 3) {
                                                // ANTIGUO: Amarillo con icono de reloj/historial
                                                echo '<span class="badge bg-warning text-dark" style="font-size: 0.85em; padding: 6px 10px; border-radius: 6px;">
                    <i class="fa fa-history me-1"></i> P. Antiguo
                  </span>';
                                            } else if ($c->prestamo_estado == 4) {
                                                // ANTIGUO CANCELADO: Gris con icono de archivo
                                                echo '<span class="badge bg-secondary text-white" style="font-size: 0.85em; padding: 6px 10px; border-radius: 6px;">
                    <i class="fa fa-archive me-1"></i> P. Antiguo Cancelado
                  </span>';
                                            } else if($c->prestamo_estado == 5) {
                                                // ANULADO: Etiqueta roja destacada
                                                echo '<span style="
                                                    display:inline-flex;
                                                    align-items:center;
                                                    gap:5px;
                                                    background:#dc3545;
                                                    color:#fff;
                                                    font-weight:900;
                                                    font-size:0.80rem;
                                                    letter-spacing:2.5px;
                                                    padding:6px 14px;
                                                    border-radius:4px;
                                                    text-transform:uppercase;
                                                    border-left:5px solid #9a1526;
                                                    box-shadow:0 2px 8px rgba(220,53,69,0.5);
                                                ">
                                                    <i class="fa fa-ban"></i> ANULADO
                                                </span>';
                                            }
                                            ?>
                                    </td>
                                    <td>
                                        <!--<a href="<?php /*= _SERVER_ */?>prestamos/detalles/<?php /*= $c->id_prestamos */?>" class="btn-sm btn-warning text-white">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <br>-->
                                        <?php
                                        // Sin saldo pendiente no hay nada que cobrar: solo historial
                                        if(($c->prestamo_estado == 1 || $c->prestamo_estado == 3) && !$esta_cancelado){
                                            ?>
                                            <a href="<?= _SERVER_ ?>cobros/pagar/<?= $c->id_prestamos ?>"
                                               style="cursor: pointer" class="btn-sm btn-warning text-white">
                                                <i class="fa fa-money"></i> Pagar
                                            </a>
                                        <?php
										}
                                        ?>


										<?php
										if($c->prestamo_estado != 3 && $c->prestamo_estado != 5){
											?>
                                            <a onclick="preguntar('...','transferir_prestamo','SI','NO','<?= $c->id_prestamos ?>')"
                                               class="btn btn-sm btn-secondary text-white mt-1"
                                               style="cursor:pointer; white-space:nowrap; display:none; align-items:center; gap:6px;">
                                                <i class="fa fa-refresh"></i> Transferir
                                            </a>
											<?php
										}
										?>

                                        <br>
                                        <a class="text-white btn btn-sm btn-primary m-1" href="<?= _SERVER_ ?>Cobros/pagos/<?= $c->id_prestamos ?>" style="white-space:nowrap">
                                            <i class="fa fa-eye"></i>Previsualización
                                        </a> <br>

                                        <a target="_blank" class="text-white btn btn-sm btn-danger" href="<?= _SERVER_ ?>Prestamos/generar_documento/<?= $c->id_prestamos ?>">
                                            <i class="fa fa-file-pdf-o"></i> Imprimir
                                        </a>
                                    </td>
								</tr>
								<?php
								$a++;
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

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>