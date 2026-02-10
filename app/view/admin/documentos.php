<div class="container-fluid">
    <div class="contenido">

        <div class="row" style="margin-top: 20px; align-items: stretch;">
            <div class="col-md-2 h-100 d-flex"></div>
            <!-- Tarjeta 1: Contratos -->
            <div class="col-md-4 h-100 d-flex">
                <div class="panel panel-primary w-100 h-100 d-flex flex-column">
                    <div class="panel-heading">
                        <h3 class="panel-title">Contratos Guzmán</h3>
                    </div>
                    <div class="panel-body document-list flex-grow-1 overflow-auto">
                        <ul class="list-group">
                            <!-- Lista de documentos -->
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/1-CONTRATO_DE_PRESTAMO_DE_DINERO_pago_diario.docx" download>
                                    <i class="fa fa-file-word-o"></i> 1-CONTRATO DE PRESTAMO DE DINERO pago diario.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/2-CONTRATO_DE_PRESTAMO_DE_DINERO_pago_mensual.docx" download>
                                    <i class="fa fa-file-word-o"></i> 2-CONTRATO DE PRESTAMO DE DINERO pago mensual.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/A-CONTRATO_DE_PRESTAMO_DE_DINERO_CON_GARANTE_pago_mensual.docx" download>
                                    <i class="fa fa-file-word-o"></i> A-CONTRATO DE PRESTAMO DE DINERO CON GARANTE pago mensual.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/B-CONTRATO_DE_PRESTAMO_DE_DINERO_CON_GARANTE_pago_diario.docx" download>
                                    <i class="fa fa-file-word-o"></i> B-CONTRATO DE PRESTAMO DE DINERO CON GARANTE pago diario.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/CONTRATO_DE_PRESTAMO_DE_DINERO-2024.docx" download>
                                    <i class="fa fa-file-word-o"></i> CONTRATO DE PRESTAMO DE DINERO-2024.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/I-CONTRATO_DE_PRESTAMO_DE_DINERO_CON_GARANTIA_pago_mensual.docx" download>
                                    <i class="fa fa-file-word-o"></i> I-CONTRATO DE PRESTAMO DE DINERO CON GARANTIA pago mensual.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta1/IIII-CONTRATO_DE_PRESTAMO_DE_DINERO_CON_GARANTIA_pago_diario.docx" download>
                                    <i class="fa fa-file-word-o"></i> IIII-CONTRATO DE PRESTAMO DE DINERO CON GARANTIA pago diario.docx
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Tarjeta 2: Otros Contratos -->
            <div class="col-md-4 h-100 d-flex">
                <div class="panel panel-success w-100 h-100 d-flex flex-column">
                    <div class="panel-heading">
                        <h3 class="panel-title">Otros Contratos Guzmán</h3>
                    </div>
                    <div class="panel-body document-list flex-grow-1 overflow-auto">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta2/solicitud_prestamo_guzz.xlsx" download>
                                    <i class="fa fa-file-excel-o"></i> solicitud prestamo guzz.xlsx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta2/cronogroma_p-diario2024.docx" download>
                                    <i class="fa fa-file-word-o"></i> cronogroma p-diario2024.docx
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="<?= _SERVER_ ?>uploads/documentos/carpeta2/RECONOCIMIENT_DE_DEUDA-titular_Carlos_Guzman_Villacorta.docx" download>
                                    <i class="fa fa-file-word-o"></i> RECONOCIMIENT DE DEUDA-titular Carlos Guzman Villacorta.docx
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .document-list .list-group-item {
        transition: all 0.3s ease;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .document-list .list-group-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .document-list a {
        color: #333;
        text-decoration: none;
    }

    .document-list a:hover {
        color: #007bff;
    }

    .panel-title {
        font-weight: bold;
        font-size: 16px;
    }

    .panel {
        min-height: 439px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .panel-heading {
        border-radius: 8px 8px 0 0;
        padding: 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .panel-primary {
        border: none;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #eee;
        padding: 12px 15px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fa-file-pdf-o { color: #e74c3c; }
    .fa-file-word-o { color: #2b579a; }
    .fa-file-excel-o { color: #217346; }

    /* Scroll personalizado */
    .panel-body::-webkit-scrollbar {
        width: 6px;
    }

    .panel-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
</style>