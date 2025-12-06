<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Horarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #dbeafe;
            --muted: #64748b;
            --text-main: #0f172a;
            --bg: #ffffff;
            --surface: #f8fafc;
            --border: #e2e8f0;
            --radius: 12px;
            --success: #10b981;
            --success-bg: #d1fae5;
            --error: #ef4444;
            --error-bg: #fee2e2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Open Sans", system-ui, -apple-system, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-main);
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        /* --------------------------- CARD PRINCIPAL --------------------------- */
        .login-card {
            background: var(--bg);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* --------------------------- HEADER --------------------------- */
        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            padding: 40px 30px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .login-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .login-icon i {
            font-size: 32px;
            color: #fff;
        }

        .login-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .login-subtitle {
            font-size: 0.92rem;
            opacity: 0.9;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* --------------------------- BODY --------------------------- */
        .login-body {
            padding: 40px 30px;
        }

        /* --------------------------- ALERTAS --------------------------- */
        .alert-custom {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: start;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: var(--error-bg);
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-danger i {
            color: var(--error);
            font-size: 18px;
            margin-top: 1px;
        }

        .alert-success {
            background: var(--success-bg);
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-success i {
            color: var(--success);
            font-size: 18px;
            margin-top: 1px;
        }

        .alert-text {
            flex: 1;
            font-size: 0.90rem;
            font-weight: 600;
            line-height: 1.5;
        }

        /* --------------------------- FORM --------------------------- */
        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid var(--border);
            background: var(--surface);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            color: var(--text-main);
            font-weight: 500;
        }

        .form-input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        /* --------------------------- BUTTON --------------------------- */
        .btn-login {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* --------------------------- RESPONSIVE --------------------------- */
        @media (max-width: 500px) {
            .login-header {
                padding: 30px 20px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .login-title {
                font-size: 1.4rem;
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- HEADER -->
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="login-title">Sistema de Horarios</h1>
                <p class="login-subtitle">Instituto Tecnológico de Chilpancingo</p>
            </div>

            <!-- BODY -->
            <div class="login-body">
                <!-- ALERTAS DE ERROR -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert-custom alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="alert-text">
                            <?php
                            switch ($_GET['error']) {
                                case 'invalid':
                                    echo 'Usuario o contraseña incorrectos';
                                    break;
                                case 'session':
                                    echo 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.';
                                    break;
                                case 'access':
                                    echo 'No tiene permisos para acceder a esa sección';
                                    break;
                                default:
                                    echo 'Error al iniciar sesión';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ALERTA DE SUCCESS -->
                <?php if (isset($_GET['success']) && $_GET['success'] == 'logout'): ?>
                    <div class="alert-custom alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-text">
                            Sesión cerrada correctamente
                        </div>
                    </div>
                <?php endif; ?>

                <!-- FORMULARIO -->
                <form action="includes/process_login.php" method="POST">
                    <div class="form-group">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" 
                               class="form-input" 
                               id="usuario" 
                               name="usuario" 
                               placeholder="Ingrese su usuario"
                               required 
                               autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" 
                               class="form-input" 
                               id="password" 
                               name="password" 
                               placeholder="Ingrese su contraseña"
                               required>
                    </div>

                    <button type="submit" class="btn-login">
                        Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Efecto de focus automático mejorado
        document.addEventListener('DOMContentLoaded', function() {
            const usuarioInput = document.getElementById('usuario');
            if (usuarioInput) {
                usuarioInput.focus();
            }

            // Animación suave al enviar el formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const button = form.querySelector('.btn-login');
                button.textContent = 'Iniciando...';
                button.style.opacity = '0.7';
            });
        });
    </script>
</body>
</html>