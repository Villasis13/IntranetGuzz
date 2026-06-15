<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* ── Variables de color (mismo login) ─────────────────── */
    :root {
        --navy-900: #0d1f3c;
        --navy-800: #122545;
        --navy-700: #162d56;
        --navy-600: #1a3a6b;
        --blue-500: #2e7fdb;
        --blue-300: #7eb8f7;
        --sidebar-w: 260px;
    }

    body { font-family: 'Inter', sans-serif; }

    /* ════════════════════════════════════════════════════════
       SIDEBAR
    ════════════════════════════════════════════════════════ */
    #layout-menu,
    .layout-menu.menu-vertical {
        background: linear-gradient(180deg, var(--navy-900) 0%, var(--navy-700) 60%, var(--navy-600) 100%) !important;
        border-right: none !important;
        box-shadow: 4px 0 24px rgba(13,31,60,0.35) !important;
    }

    /* Brand / Logo area */
    .app-brand.demo {
        background: rgba(0,0,0,0.20) !important;
        border-bottom: 1px solid rgba(255,255,255,0.07) !important;
        padding: 0 !important;
        height: 64px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0 20px !important;
    }
    .app-brand-link {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        text-decoration: none !important;
    }
    .app-brand-logo img {
        width: 34px !important;
        height: 34px !important;
        margin: 0 !important;
        border-radius: 8px !important;
        background: rgba(255,255,255,0.10) !important;
        padding: 4px !important;
        object-fit: contain !important;
    }
    .app-brand-name-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    .app-brand-name-text .brand-main {
        font-size: 15px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.5px;
    }
    .app-brand-name-text .brand-sub {
        font-size: 9.5px;
        font-weight: 400;
        color: rgba(255,255,255,0.40);
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* Mobile toggle chevron */
    .layout-menu-toggle .bx-chevron-left {
        color: rgba(255,255,255,0.7) !important;
    }

    /* Shadow separator debajo del brand */
    .menu-inner-shadow {
        background: linear-gradient(var(--navy-900) 41%, rgba(13,31,60,0)) !important;
        height: 60px !important;
    }

    /* ── Items del menú ────────────────────────────────────── */
    .menu-inner > .menu-item > .menu-link {
        color: rgba(255,255,255,0.62) !important;
        border-radius: 8px !important;
        margin: 1px 10px !important;
        padding: 10px 14px !important;
        transition: background 0.18s, color 0.18s !important;
    }
    .menu-inner > .menu-item > .menu-link:hover {
        background: rgba(255,255,255,0.07) !important;
        color: rgba(255,255,255,0.95) !important;
    }
    .menu-inner > .menu-item.active > .menu-link,
    .menu-inner > .menu-item.active > .menu-toggle {
        background: linear-gradient(135deg, rgba(46,127,219,0.55), rgba(46,127,219,0.30)) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(46,127,219,0.25) !important;
    }
    .menu-inner > .menu-item.open > .menu-toggle {
        background: rgba(255,255,255,0.07) !important;
        color: rgba(255,255,255,0.95) !important;
    }

    /* Íconos del menú */
    .menu-link .menu-icon,
    .menu-link i {
        color: rgba(255,255,255,0.45) !important;
        font-size: 1.1rem !important;
    }
    .menu-item.active > .menu-link .menu-icon,
    .menu-item.active > .menu-link i,
    .menu-item:hover  > .menu-link .menu-icon,
    .menu-item:hover  > .menu-link i {
        color: var(--blue-300) !important;
    }

    /* Flecha toggle → blanca */
    .menu-vertical .menu-inner > .menu-item > .menu-link.menu-toggle::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='rgba(255,255,255,0.45)' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E") !important;
    }
    .menu-vertical .menu-inner > .menu-item.open > .menu-link.menu-toggle::after {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='rgba(255,255,255,0.80)' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E") !important;
    }

    /* Sub-menú */
    .menu-sub {
        background: rgba(0,0,0,0.15) !important;
    }
    .menu-sub .menu-item .menu-link {
        color: rgba(255,255,255,0.55) !important;
        font-size: 13px !important;
        transition: color 0.15s !important;
    }
    .menu-sub .menu-item .menu-link::before {
        background-color: rgba(255,255,255,0.30) !important;
    }
    .menu-sub .menu-item.active .menu-link,
    .menu-sub .menu-item .menu-link:hover {
        color: var(--blue-300) !important;
    }
    .menu-sub .menu-item.active .menu-link::before {
        background-color: var(--blue-300) !important;
    }

    /* Texto del menú */
    .menu-inner > .menu-item > .menu-link > div,
    .menu-sub .menu-item .menu-link > div {
        color: inherit !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
    }
    .menu-inner > .menu-item.active > .menu-link > div {
        font-weight: 600 !important;
    }

    /* ════════════════════════════════════════════════════════
       TOPBAR
    ════════════════════════════════════════════════════════ */
    #layout-navbar.layout-navbar {
        background: #ffffff !important;
        border-bottom: 1px solid #e8ecf4 !important;
        box-shadow: 0 2px 16px rgba(13,31,60,0.07) !important;
        padding: 0 24px !important;
        height: 64px !important;
        margin-bottom: 0 !important;
    }

    /* Módulo activo (izquierda del topbar) */
    .topbar-module-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 14px;
        background: #f0f4f8;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .topbar-module-info i {
        font-size: 15px;
        color: var(--blue-500);
    }
    .topbar-module-info span {
        font-size: 13px;
        font-weight: 600;
        color: var(--navy-900);
        letter-spacing: 0.2px;
    }

    /* User info */
    .topbar-user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        line-height: 1.3;
    }
    .topbar-user-info .u-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--navy-900);
    }
    .topbar-user-info .u-role {
        font-size: 11px;
        color: #6b7a99;
    }

    /* Avatar */
    .topbar-avatar-wrap {
        position: relative;
        cursor: pointer;
    }
    .topbar-avatar-wrap img {
        width: 38px !important;
        height: 38px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        border: 2px solid #e2e8f0 !important;
        transition: border-color 0.2s !important;
    }
    .topbar-avatar-wrap:hover img {
        border-color: var(--blue-500) !important;
    }
    .topbar-avatar-online {
        position: absolute;
        bottom: 1px; right: 1px;
        width: 9px; height: 9px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #fff;
    }

    /* Dropdown del usuario */
    .topbar-user-dropdown .dropdown-menu {
        border: 1px solid #e8ecf4 !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 32px rgba(13,31,60,0.14) !important;
        padding: 8px !important;
        min-width: 220px !important;
        margin-top: 8px !important;
    }
    .topbar-user-dropdown .dropdown-header-custom {
        padding: 10px 12px 14px !important;
        border-bottom: 1px solid #f0f4f8 !important;
        margin-bottom: 6px !important;
    }
    .topbar-user-dropdown .dropdown-header-custom img {
        width: 42px !important;
        height: 42px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
    }
    .topbar-user-dropdown .dropdown-header-custom .dh-name {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--navy-900);
    }
    .topbar-user-dropdown .dropdown-header-custom .dh-role {
        font-size: 11.5px;
        color: #6b7a99;
    }
    .topbar-user-dropdown .dropdown-item {
        border-radius: 7px !important;
        font-size: 13px !important;
        padding: 8px 12px !important;
        color: #374259 !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: background 0.15s !important;
    }
    .topbar-user-dropdown .dropdown-item:hover {
        background: #f0f4f8 !important;
        color: var(--navy-900) !important;
    }
    .topbar-user-dropdown .dropdown-item.text-danger {
        color: #dc3545 !important;
    }
    .topbar-user-dropdown .dropdown-item.text-danger:hover {
        background: #fff0f0 !important;
    }
    .topbar-user-dropdown .dropdown-divider {
        margin: 6px 0 !important;
        border-color: #f0f4f8 !important;
    }

    /* Mobile menu toggle */
    .layout-menu-toggle .bx-menu {
        color: var(--navy-800) !important;
        font-size: 22px !important;
    }

    /* ── Content area ──────────────────────────────────────── */
    .layout-page {
        background: #f0f4f8 !important;
    }
    .content-wrapper {
        padding-top: 0 !important;
    }

    /* ── Footer ────────────────────────────────────────────── */
    .content-footer.footer {
        background: transparent !important;
        border-top: 1px solid #e2e8f0 !important;
    }
</style>

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- ══ SIDEBAR ══════════════════════════════════════════ -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

            <div class="app-brand demo">
                <a href="<?= _SERVER_ ?>" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="<?php echo _SERVER_ . _ICON_;?>" alt="Logo"/>
                    </span>
                    <span class="app-brand-name-text">
                        <span class="brand-main">GUZZ</span>
                        <span class="brand-sub">Sistema Interno</span>
                    </span>
                </a>
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1" style="margin-top: 8px;">
                <li class="menu-item">
                    <a href="<?= _SERVER_ ?>" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Inicio</div>
                    </a>
                </li>

                <?php
                $raioz = 1;
                $restricciones = $this->nav->listar_restricciones($this->encriptar->desencriptar($_SESSION['ru'],_FULL_KEY_));
                foreach ($navs as $nav){
                    $active = "";
                    $active_o = "";
                    if($nav->menu_controlador == $_SESSION['controlador']){
                        $active = "active";
                        $active_o = "open";
                        $_SESSION['controlador'] = $nav->menu_nombre;
                        $_SESSION['icono'] = $nav->menu_icono;
                    }?>
                    <li class="menu-item <?= $active;?> <?= $active_o;?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="<?= $nav->menu_icono;?>" style="margin-right: 15px;"></i>
                            <div data-i18n="Layouts"><?= $nav->menu_nombre;?></div>
                        </a>
                        <ul class="menu-sub">
                            <?php
                            $option = $this->nav->listar_opciones($nav->id_menu);
                            foreach ($option as $o){
                                ($_SESSION['accion']==$o->opcion_funcion) ? $active_ = "active" : $active_ = "";
                                $mostrar = true;
                                foreach ($restricciones as $r){
                                    if($r->id_opcion == $o->id_opcion){
                                        $mostrar = false;
                                    }
                                }
                                if($mostrar){ ?>
                                    <li class="menu-item <?= $active_;?>">
                                        <a href="<?= _SERVER_. $nav->menu_controlador . '/'. $o->opcion_funcion;?>" class="menu-link">
                                            <div data-i18n="<?= $o->opcion_nombre;?>"><?= $o->opcion_nombre;?></div>
                                        </a>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </li>
                    <?php $raioz++;
                } ?>
            </ul>
        </aside>

        <!-- ══ CONTENT ══════════════════════════════════════════ -->
        <div class="layout-page">

            <!-- TOPBAR -->
            <nav style="margin-bottom: 15px"
                 class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                 id="layout-navbar">

                <!-- Mobile toggle -->
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">

                    <!-- Módulo activo -->
                    <div class="topbar-module-info d-none d-md-flex">
                        <i class="fa fa-th-large"></i>
                        <span><?= htmlspecialchars($_SESSION['controlador'] ?? 'Inicio') ?></span>
                    </div>

                    <ul class="navbar-nav flex-row align-items-center ms-auto gap-3">

                        <!-- Usuario -->
                        <li class="nav-item topbar-user-dropdown dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center gap-3"
                               href="javascript:void(0);" data-toggle="dropdown">
                                <div class="topbar-user-info d-none d-md-flex">
                                    <span class="u-name"><?= $this->encriptar->desencriptar($_SESSION['p_n'],_FULL_KEY_); ?></span>
                                    <span class="u-role"><?= $this->encriptar->desencriptar($_SESSION['rn'],_FULL_KEY_); ?></span>
                                </div>
                                <div class="topbar-avatar-wrap">
                                    <img src="<?= _SERVER_ . _STYLES_ASSETS_;?>img/avatars/1.png" alt="avatar"/>
                                    <span class="topbar-avatar-online"></span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <div class="dropdown-header-custom d-flex align-items-center gap-3">
                                        <img src="<?= _SERVER_ . $this->encriptar->desencriptar($_SESSION['u_i'],_FULL_KEY_);?>" alt="avatar"/>
                                        <div>
                                            <div class="dh-name"><?= $this->encriptar->desencriptar($_SESSION['p_n'],_FULL_KEY_); ?></div>
                                            <div class="dh-role"><?= $this->encriptar->desencriptar($_SESSION['rn'],_FULL_KEY_); ?></div>
                                        </div>
                                    </div>
                                </li>
                                <li><div class="dropdown-divider"></div></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= _SERVER_;?>Admin/finalizar_sesion">
                                        <i class="bx bx-power-off"></i>
                                        <span>Cerrar Sesión</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
            <!-- / Topbar -->

            <!-- Content wrapper -->
