<?php
// views/auth/login.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Requerimientos - Iniciar Sesión</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/responsive.css">
    <style>
        body {
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 900px;
            width: 100%;
            display: flex;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .login-sidebar {
            background-color: #0275d8;
            color: white;
            padding: 2rem;
            width: 50%;
            display: flex;
            flex-direction: column;
        }
        .login-content {
            background-color: white;
            padding: 2rem;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-logo {
            margin-bottom: 2rem;
            max-width: 200px;
        }
        .login-sidebar h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .login-sidebar p {
            font-size: 1.1rem;
            margin-top: 2rem;
        }
        .login-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .login-subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
        }
        .form-control {
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: 0.375rem;
        }
        .form-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1rem;
            color: #6c757d;
        }
        .form-input-icon {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-input-icon input {
            padding-left: 3rem;
        }
        .btn-login {
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border-radius: 0.375rem;
            width: 100%;
            background-color: #0275d8;
            border-color: #0275d8;
        }
        .remember-me {
            margin-bottom: 1rem;
        }
        .copyright {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #6c757d;
        }
        .folder-icon {
            margin-top: auto;
            text-align: right;
            opacity: 0.5;
            font-size: 3rem;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 1rem;
            }
            .login-sidebar, .login-content {
                width: 100%;
            }
            .login-sidebar {
                padding-bottom: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-sidebar">
            <img src="<?= BASE_URL ?>assets/img/logo.png" alt="Tema Litoclean Perú" class="login-logo">
            <h1>Bienvenido de nuevo</h1>
            <p>Sistema de Gestión de Requerimientos Digitales</p>
            <div class="folder-icon">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>
        <div class="login-content">
            <h2 class="login-title">Iniciar Sesión</h2>
            <p class="login-subtitle">Ingresa tus credenciales para acceder al sistema</p>
            
            <?php
            // Mostrar mensajes de error o éxito
            $message = getMessage();
            if ($message): ?>
            <div class="alert alert-<?= $message['type'] == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <?= $message['text'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>login" method="post">
                <div class="mb-3">
                    <label for="correo" class="form-label">Usuario</label>
                    <div class="form-input-icon">
                        <i class="fas fa-user form-icon"></i>
                        <input class="form-control" id="correo" name="correo" type="email" placeholder="Correo electrónico" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="form-input-icon position-relative">
                        <i class="fas fa-lock form-icon"></i>
                        <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña" required>
                        <i class="fas fa-eye-slash password-toggle" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d;"></i>
                    </div>
                </div>
                <div class="remember-me">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="recordar" name="recordar">
                        <label class="form-check-label" for="recordar">
                            Recordar sesión
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-login">
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="copyright">
                © <?= date('Y') ?> Sistema de Gestión. Todos los derechos reservados.
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts propios -->
    <script src="<?= BASE_URL ?>assets/js/validation.js"></script>
    
    <!-- Script para mostrar/ocultar contraseña -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.password-toggle');
            const password = document.querySelector('#password');
            
            togglePassword.addEventListener('click', function () {
                // Cambiar tipo de input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Cambiar ícono
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>
</html>