<!--Contenido-->
<div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Préstamos al Instante</h1>
            <a href="<?= _SERVER_?>Prestamos/prestamos" class="d-none d-sm-inline-block btn btn-secondary shadow-sm text-white">
                <i class="fa fa-list text-white"></i> Ver Todos los Préstamos
            </a>
        </div>

    <?php
	if($listar_estado_caja == 1){
		?>
        <div class="card shadow mb-4">
            <div class="card-header bg-primary py-3">
                <h6 class="m-0 font-weight-bold text-white">Registro de Nuevo Préstamo</h6>
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
                        <div class="col-md-3">
                            <label class="form-label">Línea de Crédito</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input id="linea_actual" name="linea_actual" type="text" class="form-control text-danger fw-bold" 
                                       value="<?= $data_cliente->cliente_credito ?? 0 ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <?php
                            if($data_cliente){
                                ?>
                                <a href="<?= _SERVER_ ?>Prestamos/aumentarLineaCredito/<?= $data_cliente->id_cliente ?>" class="btn btn-primary w-100" style="margin-top: 29px;">
                                    <i class="fas fa-chart-line"></i> Aumentar Crédito
                                </a>
                            <?php
							}
                            ?>
                            
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label for="monto_prestamo" class="form-label">Monto del Préstamo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input oninput="validar_monto_linea()" onkeyup="validar_numeros_decimales_dos(this.id)" 
                                       type="text" class="form-control" name="monto_prestamo" id="monto_prestamo" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tasa de Interés <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input onkeyup="validar_numeros_decimales_dos(this.id)" 
                                       type="text" class="form-control" name="interes" id="interes" value="15">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Número de Cuotas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="prestamo_num_cuotas" id="prestamo_num_cuotas" min="1" 
                                   max="31" value="1" onkeyup="validar_numeros(this.id)">
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
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
                            <input  type="date" class="form-control"  id="fecha_prestamo2" name="fecha_prestamo2" value="<?= date('Y-m-d') ?>" >
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Próximo Cobro</label>
                            <input  type="date" class="form-control" id="fecha_prox_cobro2" name="fecha_prox_cobro2"  value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                        </div>
                    </div>

                    <div id="div_diario_domingos" class="col-lg-2">
                        <label for="select_domingos">¿Incluir domingos?</label>
                        <select onchange="cambiar_proximo_cobro('diario')" class="form-control" id="select_domingos" name="select_domingos">
                            <option value="si">Sí</option>
                            <option value="no">No</N></option>
                        </select>
                    </div>



                    <div class="row mt-2">
                        <div class="col-lg-6">
                            <label for="prestamo_garantia" class="form-label">Garantía</label>
                            <input type="text" class="form-control" name="prestamo_garantia" id="prestamo_garantia" >
                        </div>

                        <div class="col-md-6">
                            <label for="prestamo_garante" class="form-label">Garante</label>
                            <input type="text" class="form-control" id="prestamo_garante" name="prestamo_garante" 
                                   value="<?= $garante ?>" >
                        </div>

                        <div class="col-md-12">
                            <label for="prestamo_motivo" class="form-label">Motivo del Préstamo</label>
                            <textarea class="form-control" name="prestamo_motivo" id="prestamo_motivo" rows="2"></textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="prestamo_comentario" class="form-label">Comentarios Adicionales</label>
                            <textarea id="prestamo_comentario" class="form-control" name="prestamo_comentario" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a onclick="guardar_prestamo()" type="submit" class="btn text-white btn-primary px-5">
                            <i class="fa fa-save me-2"></i> Registrar Préstamo
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
            <p>Para registrar un nuevo préstamo, primero debes abrir la caja.</p>
            <hr>
<!--            <p class="mb-0">Por favor, contacta al administrador del sistema.</p>-->
    <?php
    }
    ?>
        
    </div>
<script src="<?php echo _SERVER_ . _JS_;?>domain.js"></script>
<script src="<?php echo _SERVER_ . _JS_;?>prestamos.js"></script>