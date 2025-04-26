<?php
/**
 * Created by PhpStorm
 * User: WagnerVillasis1304
 * Date: 04/06/2023
 * Time: 00:56
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?=_TITLE_;?> - Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?=_SERVER_ . _ICON_;?>"/>
<!--    Estilos de CSS-->
    <link rel="stylesheet" type="text/css" href="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/bootstrap/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alumni+Sans+Inline+One:ital@0;1&family=Hind+Madurai:wght@300;400;500;600;700&family=Noto+Serif+Vithkuqi:wght@400;500;600;700&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!--    <link href="https://fonts.googleapis.com/css2?family=Hind+Madurai:wght@300;400;500;600;700&family=Noto+Serif+Vithkuqi:wght@400;500;600;700&family=Signika:wght@300;400;500;600;700&display=swap" rel="stylesheet">-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //=_SERVER_ . _STYLES_LOGIN_;?><!--fonts/font-awesome-4.7.0/css/font-awesome.min.css">-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //=_SERVER_ . _STYLES_LOGIN_;?><!--fonts/Linearicons-Free-v1.0.0/icon-font.min.css">-->
    <link rel="stylesheet" type="text/css" href="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?=_SERVER_ . _STYLES_LOGIN_;?>css/util.css">
    <link rel="stylesheet" type="text/css" href="<?=_SERVER_ . _STYLES_LOGIN_;?>css/main.css">
    <link rel="stylesheet" href="<?=_SERVER_ . _LIBS_;?>sweetalert/sweetalert2.min.css">
<!--  -------------------------------------------------------------------------------------------------->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=_SERVER_ . _STYLES_ASSETS_;?>css/demo.css" />
    <link rel="stylesheet" href="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/css/pages/page-auth.css" />
    <script src="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/js/helpers.js"></script>
    <script src="<?=_SERVER_ . _STYLES_ASSETS_;?>js/config.js"></script>

<!--    -------------------------------------------------------------->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=_SERVER_ . _TODO_LOGIN_;?>css/style.css">
</head>
<body>

<!--NUEVO LOGIN------------------------------------------------------->
<section class="vh-100" style="background-image: url('<?= _SERVER_._TODO_LOGIN_ ?>images/1.jpg'); background-size: cover">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-6">
                <div class="card" style="border-radius: 1rem; background-color: #ffffff; opacity: 0.9">
                    <div class="row g-0">
<!--                        Imagen-->
                        <div class="col-md-6 col-lg-12 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">
                                <form>
<!--                                    <div class="d-flex align-items-center mb-3 pb-1">-->
<!--                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>-->
<!--                                        <span class="h1 fw-bold mb-0">Logo</span>-->
<!--                                    </div>-->
                                    <h4 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px; color: #00003C">BIENVENIDO A GUZZ!</h4>
                                    <div class="form-outline mb-4">
                                        <input type="email" id="usuario_nickname" class="form-control form-control-lg" />
                                        <label class="form-label" for="usuario_nickname">Usuario</label>
                                    </div>
                                    <div class="form-outline mb-4">
                                        <input type="password" id="usuario_contrasenha" class="form-control form-control-lg" />
                                        <label class="form-label" for="usuario_contrasenha">Contraseña</label>
                                    </div>
                                    <div class="pt-1 mb-4">
                                        <button style="background-color: #fa607e" id="btn-iniciar-sesion" onclick="validar_usuario()" class="btn btn-lg btn-block text-white" type="button">Iniciar Sesión</button>
                                    </div>
<!--                                    <a class="small text-muted" href="#!">Olvidé Contraseña</a>-->
<!--                                    <p class="mb-5 pb-lg-2" style="color: #5056a1;">No tengo cuenta <a href="#!"-->
<!--                                                                                                              style="color: #393f81;">Registrar aquí</a></p>-->
                                    <a href="#!" class="small text-muted">Términos de uso.</a>
                                    <a href="#!" class="small text-muted">Política de privacidad</a>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
<script src="<?=_SERVER_ . _TODO_LOGIN_;?>js/jquery.min.js"></script>
<script src="<?=_SERVER_ . _TODO_LOGIN_;?>js/popper.js"></script>
<script src="<?=_SERVER_ . _TODO_LOGIN_;?>js/bootstrap.min.js"></script>
<script src="<?=_SERVER_ . _TODO_LOGIN_;?>js/main.js"></script>

<script src="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/libs/popper/popper.js"></script>
<script src="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/js/bootstrap.js"></script>
<script src="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?=_SERVER_ . _STYLES_ASSETS_;?>vendor/js/menu.js"></script>
<script src="<?=_SERVER_ . _STYLES_ASSETS_;?>js/main.js"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!--Importacion de Javascript-->
<script src="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/bootstrap/js/popper.js"></script>
<script src="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=_SERVER_ . _STYLES_LOGIN_;?>vendor/select2/select2.min.js"></script>
<script src="<?=_SERVER_ . _LIBS_;?>sweetalert/sweetalert2.min.js"></script>
<script src="<?=_SERVER_ . _JS_;?>main-sweet.js"></script>
<script src="<?=_SERVER_ . _JS_;?>domain.js"></script>
<script src="<?=_SERVER_ . _JS_;?>login.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#usuario_contrasenha').keypress(function(e){
            if(e.which === 13){
                validar_usuario();
            }
        });
        $('#usuario_nickname').keypress(function(e){
            if(e.which === 13){
                validar_usuario();
            }
        });
    });
</script>
</body>
</html>