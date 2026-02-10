<!--Contenido-->

<div class="container-fluid">
    <!--<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Préstamos al Instante</h1>
        <a href="<?php /*= _SERVER_*/?>Prestamos/prestamos" class="d-none d-sm-inline-block btn btn-secondary shadow-sm text-white">
            <i class="fa fa-list text-white"></i> Ver Todos los Préstamos
        </a>
    </div>-->

	<?php
	if($listar_estado_caja == 1){
		?>
        <div class="card shadow mb-4">
            <div class="card-header bg-danger py-3">
                <h6 class="m-0 font-weight-bold text-white">Registro de Nueva Venta</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4 border-bottom pb-3">
                    <form method="post" action="inicio" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Buscar por DNI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="dni_post" id="dni_post" value="<?= $data_cliente->cliente_dni ?>" maxlength="8" >
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group">
                                <a href="<?= _SERVER_ ?>Clientes/inicio" class="btn btn-success text-white">
                                    <i class="fa fa-user-plus"></i> Nuevo Cliente
                                </a>
                                <a href="https://serviciosbiometricos.reniec.gob.pe/identifica/main.do" target="_blank" class="btn btn-info">
                                    RENIEC
                                </a>
                                <a href="https://soluciones.equifax.com.pe/efx-portal-web"
                                   target="_blank" class="btn btn-light border">
                                    Infocorp
                                </a>
                                <a href="https://www.sunarp.gob.pe/seccion/servicios/detalles/0/c3.html"
                                   target="_blank" class="btn btn-dark">
                                    SUNARP
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Formulario de Préstamo -->
                <!--                <form method="post" id="formulario_prestamo" name="formulario_prestamo" target="_blank">-->
                <input type="hidden" id="id_cliente" name="id_cliente" value="<?= $data_cliente->id_cliente ?>">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Cliente</label>
                        <input type="text" class="form-control bg-light" value="<?= $data_cliente->cliente_nombre . ' ' .
						$data_cliente->cliente_apellido_paterno . ' ' . $data_cliente->cliente_apellido_materno ?>" readonly>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="venta_producto" class="form-label">Producto a vender <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <!--<span class="input-group-text">S/</span>-->
                            <input type="text" class="form-control" name="venta_producto" id="venta_producto" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="venta_precio" class="form-label">Precio de Venta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <!--<span class="input-group-text">S/</span>-->
                            <input onkeyup="validar_numeros_decimales_dos(this.id)" type="text" class="form-control" name="venta_precio" id="venta_precio" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="venta_pago" class="form-label">Pago <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <!--<span class="input-group-text">S/</span>-->
                            <input onkeyup="validar_numeros_decimales_dos(this.id)" type="text" class="form-control" name="venta_pago" id="venta_pago" >
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <!--<div class="col-md-4">
                        <label class="form-label">Tipo de Pago <span class="text-danger">*</span></label>
                        <div class="form-check" style="margin-left: -24px;">
                            <div class="btn-group w-100" role="group">
                                <input onchange="cambiar_proximo_cobro('diario')" type="radio" class="btn-check" name="tipo_pago2" id="diario" value="Diario" checked>
                                <label class="btn btn-outline-primary" for="diario">Diario</label>

                                <input onchange="cambiar_proximo_cobro('semanal')" type="radio" class="btn-check" name="tipo_pago2" id="semanal" value="Semanal">
                                <label class="btn btn-outline-primary" for="semanal">Semanal</label>

                                <input onchange="cambiar_proximo_cobro('mensual')" type="radio" class="btn-check" name="tipo_pago2" id="mensual" value="Mensual">
                                <label class="btn btn-outline-primary" for="mensual">Mensual</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha de Préstamo</label>
                        <input  type="date" class="form-control"  id="fecha_prestamo2" name="fecha_prestamo2" value="<?php /*= date('Y-m-d') */?>" >
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Próximo Cobro</label>
                        <input  type="date" class="form-control" id="fecha_prox_cobro2" name="fecha_prox_cobro2"  value="<?php /*= date('Y-m-d', strtotime('+1 day')) */?>"
                    </div>
                </div>

                <div id="div_diario_domingos" class="col-lg-2">
                    <label for="select_domingos">¿Incluir domingos?</label>
                    <select onchange="cambiar_proximo_cobro('diario')" class="form-control" id="select_domingos" name="select_domingos">
                        <option value="si">Sí</option>
                        <option value="no">No</N></option>
                    </select>
                </div>-->



                <!--<div class="row mt-2">
                    <div class="col-lg-6">
                        <label for="prestamo_garantia" class="form-label">Garantía</label>
                        <input type="text" class="form-control" name="prestamo_garantia" id="prestamo_garantia" >
                    </div>

                    <div class="col-md-6">
                        <label for="prestamo_garante" class="form-label">Garante</label>
                        <select class="form-control" id="prestamo_garante" name="prestamo_garante">
                            <option value="">Seleccionar</option>
							<?php
/*							foreach ($clientes_g as $c) {
								*/?>
                                <option value="<?php /*= $c->id_cliente */?>"><?php /*= $c->cliente_dni . ' ' . $c->cliente_nombre */?></option>
								<?php
/*							}
							*/?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="prestamo_motivo" class="form-label">Motivo del Préstamo</label>
                        <textarea class="form-control" name="prestamo_motivo" id="prestamo_motivo" rows="2"></textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="prestamo_comentario" class="form-label">Comentarios Adicionales</label>
                        <textarea id="prestamo_comentario" class="form-control" name="prestamo_comentario" rows="3"></textarea>
                    </div>
                </div>-->

                <div class="text-center mt-5">
                    <a onclick="guardar_venta()" type="submit" class="btn text-white btn-primary px-5">
                        <i class="fa fa-save me-2"></i> Registrar Venta
                    </a>
                </div>
                <!--                </form>-->
            </div>
        </div>
		<?php
	}else{
	?>
    <div class="alert alert-danger text-center" role="alert">
        <h4 class="alert-heading">¡Caja Cerrada!</h4>
        <p>Para registrar una nueva venta, primero debes abrir la caja.</p>
        <hr>
        <!--            <p class="mb-0">Por favor, contacta al administrador del sistema.</p>-->
		<?php
		}
		?>
    </div>



            <div class="card shadow mb-4">
                <div class="card-header bg-gray py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white mb-0">Ventas Realizadas Pendientes de Pago:</h6>
                    <a target="_blank" href="<?= _SERVER_ ?>Prestamos/reporte_ventas" class="btn btn-danger btn-sm text-white">
                        <i class="fa fa-file-pdf-o"></i> Ver Reporte de Ventas
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4 pb-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="dataTable">
                                    <thead class="thead-light">
                                    <tr class="text-center">
                                        <th class="align-middle">#</th>
                                        <th class="align-middle">Cliente</th>
                                        <th class="align-middle">Fecha</th>
                                        <th class="align-middle">Producto</th>
                                        <th class="align-middle">Precio Total de Venta</th>
                                        <th class="align-middle">Saldo que falta pagar</th>
                                        <th class="align-middle">Pagar</th>
                                        <th class="align-middle">Historial</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$contador_vp = 1;
									foreach ($ventas_pendiente_pago as $vp){
										?>
                                        <tr class="text-center">
                                            <td class="align-middle"><?=$contador_vp?></td>
                                            <td class="align-middle"><?=$vp->cliente_nombre . ' ' . $vp->cliente_apellido_paterno?></td>

                                            <td class="align-middle"><?=$vp->venta_fecha?></td>
                                            <td class="align-middle"><?=$vp->venta_producto?></td>
                                            <td class="align-middle"><?=$vp->venta_precio?></td>
                                            <td class="align-middle">
                                                <?php
                                                $listar_pagos_venta = $this->ventas->listar_pagos_ventas($vp->id_venta)->total;
                                                ?>
                                                <?= $vp->venta_precio - $listar_pagos_venta ?>
                                            </td>
                                            <td>
                                                <a href="<?= _SERVER_ ?>ventas/pagar_venta/<?= $vp->id_venta ?>"
                                                   style="cursor: pointer" class="btn-sm btn-warning text-white">
                                                    <i class="fa fa-money"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="text-white btn-sm btn-primary" href="<?= _SERVER_ ?>ventas/pagos_venta/<?= $vp->id_venta ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
										<?php
										$contador_vp++;
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header bg-gray py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white mb-0">Ventas Realizadas:</h6>
                    <a target="_blank" href="<?= _SERVER_ ?>Prestamos/reporte_ventas" class="btn btn-danger btn-sm text-white">
                        <i class="fa fa-file-pdf-o"></i> Ver Reporte de Ventas
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4 pb-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="dataTable">
                                    <thead class="thead-light">
                                    <tr class="text-center">
                                        <th class="align-middle">#</th>
                                        <th class="align-middle">Cliente</th>
                                        <th class="align-middle">Fecha</th>
                                        <th class="align-middle">Producto</th>
                                        <th class="align-middle">Precio Total de Venta</th>
                                        <th class="align-middle">Saldo que falta pagar</th>
<!--                                        <th class="align-middle">Pagar</th>-->
                                        <th class="align-middle">Historial</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$contador_vp = 1;
									foreach ($ventas_realizadas as $vp){
										?>
                                        <tr class="text-center">
                                            <td class="align-middle"><?=$contador_vp?></td>
                                            <td class="align-middle"><?=$vp->cliente_nombre . ' ' . $vp->cliente_apellido_paterno?></td>

                                            <td class="align-middle"><?=$vp->venta_fecha?></td>
                                            <td class="align-middle"><?=$vp->venta_producto?></td>
                                            <td class="align-middle"><?=$vp->venta_precio?></td>
                                            <td class="align-middle">
                                                <?php
                                                $listar_pagos_venta = $this->ventas->listar_pagos_ventas($vp->id_venta)->total;
                                                ?>
                                                <?= $vp->venta_precio - $listar_pagos_venta ?>
                                            </td>
                                            <!--<td>
                                                <a href="<?php /*= _SERVER_ */?>ventas/pagar_venta/<?php /*= $vp->id_venta */?>"
                                                   style="cursor: pointer" class="btn-sm btn-warning text-white">
                                                    <i class="fa fa-money"></i>
                                                </a>
                                            </td>-->
                                            <td>
                                                <a class="text-white btn-sm btn-primary" href="<?= _SERVER_ ?>ventas/pagos_venta/<?= $vp->id_venta ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
										<?php
										$contador_vp++;
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    
   

<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>ventas.js"></script>
