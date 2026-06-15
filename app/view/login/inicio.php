<?php ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?=_TITLE_;?> — Acceso</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?=_SERVER_ . _ICON_;?>"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=_SERVER_ . _LIBS_;?>sweetalert/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        /* ── Panel izquierdo (marca) ───────────────────────────── */
        .brand-panel {
            width: 420px;
            min-width: 320px;
            background: linear-gradient(160deg, #0d1f3c 0%, #1a3a6b 60%, #1e4d8c 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 48px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .brand-logo {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.12);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 32px;
            border: 1px solid rgba(255,255,255,0.18);
        }
        .brand-logo i {
            font-size: 30px;
            color: #7eb8f7;
        }

        .brand-company {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 10px;
        }
        .brand-name {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.3;
            margin-bottom: 16px;
        }
        .brand-name span { color: #7eb8f7; }

        .brand-divider {
            width: 40px; height: 3px;
            background: #2e7fdb;
            border-radius: 2px;
            margin-bottom: 20px;
        }
        .brand-description {
            font-size: 13.5px;
            color: rgba(255,255,255,0.55);
            line-height: 1.7;
        }

        .brand-features {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: rgba(255,255,255,0.6);
        }
        .brand-feature i {
            width: 28px; height: 28px;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            color: #7eb8f7;
            flex-shrink: 0;
        }

        .brand-footer {
            font-size: 11.5px;
            color: rgba(255,255,255,0.3);
            line-height: 1.6;
        }
        .brand-footer strong { color: rgba(255,255,255,0.45); }

        /* ── Panel derecho (formulario) ───────────────────────── */
        .login-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: #f0f4f8;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 20px;
            padding: 48px 44px;
            box-shadow: 0 4px 32px rgba(13,31,60,0.10), 0 1px 4px rgba(0,0,0,0.06);
        }

        .login-header {
            margin-bottom: 36px;
        }
        .login-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #0d1f3c;
            margin-bottom: 6px;
        }
        .login-header p {
            font-size: 13.5px;
            color: #6b7a99;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #374259;
            letter-spacing: 0.3px;
            margin-bottom: 7px;
        }

        .input-wrapper {
            position: relative;
        }
        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 14px;
            pointer-events: none;
        }
        .input-wrapper input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1a202c;
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }
        .input-wrapper input::placeholder { color: #b0bec5; }
        .input-wrapper input:focus {
            border-color: #2e7fdb;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(46,127,219,0.12);
        }

        .toggle-password {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #a0aec0;
            font-size: 14px;
            padding: 2px 4px;
            transition: color .15s;
        }
        .toggle-password:hover { color: #4a5568; }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1a3a6b 0%, #2e7fdb 100%);
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.3px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: opacity .2s, transform .1s, box-shadow .2s;
            margin-top: 8px;
            box-shadow: 0 4px 14px rgba(46,127,219,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-login:hover {
            opacity: 0.92;
            box-shadow: 0 6px 18px rgba(46,127,219,0.45);
        }
        .btn-login:active { transform: scale(0.99); }
        .btn-login:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        .login-meta {
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .login-meta a {
            font-size: 11.5px;
            color: #a0aec0;
            text-decoration: none;
        }
        .login-meta a:hover { color: #718096; }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .brand-panel {
                width: 100%;
                min-width: unset;
                padding: 36px 28px 32px;
            }
            .brand-features { display: none; }
            .brand-footer { display: none; }
            .brand-name { font-size: 20px; }
            .login-card { padding: 36px 28px; }
        }
    </style>
</head>
<body>

<!-- Panel izquierdo: marca -->
<div class="brand-panel">
    <div>
        <div class="brand-logo">
            <i class="fa fa-university"></i>
        </div>
        <div class="brand-company">Sistema Interno</div>
        <div class="brand-name">Inversiones y<br>Multiservicios<br><span>GUZZ</span> E.I.R.L.</div>
        <div class="brand-divider"></div>
        <div class="brand-description">
            Plataforma de gestión interna para el control de clientes, préstamos, cobros y operaciones financieras.
        </div>

        <div class="brand-features">
            <div class="brand-feature">
                <i class="fa fa-users"></i>
                <span>Gestión de clientes y garantes</span>
            </div>
            <div class="brand-feature">
                <i class="fa fa-money"></i>
                <span>Control de préstamos y cobros</span>
            </div>
            <div class="brand-feature">
                <i class="fa fa-bar-chart"></i>
                <span>Reportes y análisis financiero</span>
            </div>
            <div class="brand-feature">
                <i class="fa fa-lock"></i>
                <span>Acceso restringido al personal autorizado</span>
            </div>
        </div>
    </div>

    <div class="brand-footer">
        <strong>Inversiones y Multiservicios GUZZ E.I.R.L.</strong><br>
        Uso exclusivo para personal interno.<br>
        &copy; <?= date('Y') ?> — Todos los derechos reservados.
    </div>
</div>

<!-- Panel derecho: formulario -->
<div class="login-panel">
    <div class="login-card">
        <div class="login-header">
            <h2>Iniciar Sesión</h2>
            <p>Ingrese sus credenciales para acceder al sistema</p>
        </div>

        <div class="form-group">
            <label for="usuario_nickname">Usuario</label>
            <div class="input-wrapper">
                <i class="fa fa-user input-icon"></i>
                <input type="text"
                       id="usuario_nickname"
                       name="usuario_nickname"
                       placeholder="Nombre de usuario"
                       autocomplete="username"
                       autofocus>
            </div>
        </div>

        <div class="form-group">
            <label for="usuario_contrasenha">Contraseña</label>
            <div class="input-wrapper">
                <i class="fa fa-lock input-icon"></i>
                <input type="password"
                       id="usuario_contrasenha"
                       name="usuario_contrasenha"
                       placeholder="••••••••"
                       autocomplete="current-password">
                <button type="button" class="toggle-password" onclick="togglePassword()" title="Mostrar/ocultar contraseña">
                    <i class="fa fa-eye" id="icon-toggle-password"></i>
                </button>
            </div>
        </div>

        <button id="btn-iniciar-sesion"
                type="button"
                class="btn-login"
                onclick="validar_usuario()">
            <i class="fa fa-sign-in"></i>
            Ingresar al Sistema
        </button>

        <div class="login-meta">
            <a href="#">Términos de uso</a>
            <a href="#">Política de privacidad</a>
        </div>
    </div>
</div>

<script src="<?=_SERVER_ . _TODO_LOGIN_;?>js/jquery.min.js"></script>
<script src="<?=_SERVER_ . _LIBS_;?>sweetalert/sweetalert2.min.js"></script>
<script src="<?=_SERVER_ . _JS_;?>main-sweet.js"></script>
<script src="<?=_SERVER_ . _JS_;?>domain.js"></script>
<script src="<?=_SERVER_ . _JS_;?>login.js"></script>
<script>
    $(document).ready(function () {
        $('#usuario_contrasenha, #usuario_nickname').keypress(function (e) {
            if (e.which === 13) validar_usuario();
        });
    });

    function togglePassword() {
        var input = document.getElementById('usuario_contrasenha');
        var icon  = document.getElementById('icon-toggle-password');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>
