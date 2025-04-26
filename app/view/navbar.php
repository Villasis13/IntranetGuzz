<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="<?= _SERVER_ ?>" class="app-brand-link">
              <span class="app-brand-logo demo">
                  <img src="<?php echo _SERVER_ . _ICON_;?>" style="margin-left: 54px; width: 8%;" alt="Logo"/>

              </span>
                    <!--<span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span>-->
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1" style="margin-top: 15px;">
                <!-- Dashboard -->
                <li class="menu-item ">
                    <a href="<?= _SERVER_ ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Inicio</div>
                    </a>
                </li>
                <!-- Nuevas Opciones  -->



                <!-- Nuevas Opciones  -->


                <!-- Layouts -->

                <?php
                //Variable usada como correlativo
                $raioz = 1;
                //Listamos las restricciones de opciones para el rol del usuario
                $restricciones = $this->nav->listar_restricciones($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
                foreach ($navs as $nav){
                    //Clases necesarias para mostrar en el navbar
                    /*$nav_link = "nav-link collapsed";
                    $aria_expanded = "false";
                    $collapse = "collapse";*/
                    $active = "";
                    $active_o = "";
                    //Validamos si es controlador en el que estamos ingresando
                    if($nav->menu_controlador == $_SESSION['controlador']){
                        $active = "active";
                        $active_o = "open";
                        //$nav_link = "nav-link";
                        //$aria_expanded = "true";
                        //$collapse = "collapse show";

                        $_SESSION['controlador'] = $nav->menu_nombre;
                        $_SESSION['icono'] = $nav->menu_icono;
                        //Obtener el Nombre del Controlador y de la Funcion
                        //$name = $this->nav->listar_nombre_opcion($_SESSION['controlador'], $_SESSION['accion']);
                        //(isset($name->opcion_nombre)) ? $_SESSION['accion'] = $name->opcion_nombre : $_SESSION['accion'] = "";
                        //Despues procedemos a llenar las las opciones del menú
                    }?>
                    <li class="menu-item <?= $active;?> <?= $active_o;?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="<?= $nav->menu_icono;?> " style="margin-right: 15px;"></i>
                            <div data-i18n="Layouts"><?= $nav->menu_nombre;?></div>
                        </a>
                        <ul class="menu-sub">
                            <?php
                            $option = $this->nav->listar_opciones($nav->id_menu);
                            foreach ($option as $o){
                                ($_SESSION['accion']==$o->opcion_funcion)?$active_ = "active":$active_ = "";;

                                //Validamos si la opcion no tiene restriccion por rol
                                $mostrar = true;
                                foreach ($restricciones as $r){
                                    //Si entra al if, quiere decir que la opcion esta restringida para el rol del usuario
                                    if($r->id_opcion == $o->id_opcion){
                                        //Si entra aquí, quiere decir que el usuario no puede acceder a la opción especificada
                                        $mostrar = false;
                                    }
                                }
                                if($mostrar){
                                    ?>
                                    <li class="menu-item <?= $active_;?> ">
                                        <a href="<?= _SERVER_. $nav->menu_controlador . '/'. $o->opcion_funcion;?>" class="menu-link">
                                            <div data-i18n="<?= $o->opcion_nombre;?>"><?= $o->opcion_nombre;?></div>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                    $raioz++;
                }
                ?>

            </ul>
        </aside>
        <div class="layout-page">
            <!-- Navbar -->

            <nav style="margin-bottom: 15px" class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                 id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <h3 class="text-primary" style="margin-top: 15px; margin-left: 645px">GUZZ</h3>
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item d-flex align-items-center">

                        </div>
                    </div>
                    <!-- /Search -->

                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- Place this tag where you want the button to render. -->
                        <li class="nav-item lh-1 me-3">

                        </li>

                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="<?= _SERVER_ . _STYLES_ASSETS_;?>img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="<?= _SERVER_ . $this->encriptar->desencriptar($_SESSION['u_i'],_FULL_KEY_);?>" alt class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold d-block"><?= $this->encriptar->desencriptar($_SESSION['p_n'],_FULL_KEY_);?></span>
                                                <small class="text-muted"><?= $this->encriptar->desencriptar($_SESSION['rn'],_FULL_KEY_);?></small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!--<li>
                                    <div class="dropdown-divider"></div>
                                </li>-->
                                <!--<li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-user me-2"></i>
                                        <span class="align-middle">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-cog me-2"></i>
                                        <span class="align-middle">Settings</span>
                                    </a>
                                </li>-->
                                <!--<li>
                                    <a class="dropdown-item" href="#">
                                <span class="d-flex align-items-center align-middle">
                                  <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                  <span class="flex-grow-1 align-middle">Billing</span>
                                  <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                </span>
                                    </a>
                                </li>-->
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= _SERVER_;?>Admin/finalizar_sesion">
                                        <i class="bx bx-power-off me-2"></i>
                                        <span class="align-middle">Cerrar Sesión</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                </div>
            </nav>


            <!-- / Navbar -->
            <!-- Content wrapper -->

