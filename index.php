<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão Escolar</title>
    <!-- Link para o Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    body {
        background-color: #343a40;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Ajuste para garantir que a altura mínima da tela seja ocupada */
        margin: 0;
        transition: background-color 0.3s;
    }

    .login-container {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-container h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
        text-align: center;
    }

    /* Ajuste do ícone no campo de senha */
    .password-toggle {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #aaa;
    }

    .password-toggle:hover {
        color: #333;
    }

    .form-label {
        font-size: 14px;
        color: #555;
    }

    .form-control {
        position: relative;
        padding-left: 40px;
    }

    .form-icon {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: #aaa;
        z-index: 2;
    }

    .theme-toggle {
        position: fixed;
        top: 20px;
        right: 20px;
        cursor: pointer;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 50%;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s;
    }

    .theme-toggle:hover {
        background-color: #f0f0f0;
    }

    .login-image {
        margin-top: 20px;
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .animated-logo {
        animation: colorChange 4s infinite;
        font-weight: bold;
        text-align: center;
    }

    @keyframes colorChange {
        0% { color: yellow; }
        25% { color: red; }
        50% { color: green; }
        75% { color: black; }
        100% { color: yellow; }
    }
</style>

</head>
<body>

<div class="login-container">
    <img src="login/imagem/image.png" alt="Imagem de Login" class="login-image">
    <h2>Sistema de Gestão Escolar</h2>
    <h3 class="animated-logo mt-4">DESTP</h3>

    <form method="POST" action="login/login_process.php" id="loginForm">
        <!-- Campo de Email -->
        <div class="mb-3 position-relative">
            <label for="email" class="form-label">Email:</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu email" required>
            
            </div>
        </div>

        <!-- Campo de Senha -->
        <div class="mb-3 position-relative">
            <label for="senha" class="form-label">Senha:</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite sua senha" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </button>
               
            </div>
        </div>

        <!-- Botão de Login -->
        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
            <i class="fas fa-sign-in-alt me-2"></i> Entrar
        </button>
    </form>
</div>

    <!-- Link para o JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // Validação do formulário
                const loginForm = document.getElementById('loginForm');
                loginForm.addEventListener('submit', function(event) {
                    if (!loginForm.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    loginForm.classList.add('was-validated');
                });


                // Alternar visibilidade da senha
        const togglePassword = document.getElementById('togglePassword');
        const senhaInput = document.getElementById('senha');

        togglePassword.addEventListener('click', function() {
            const type = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
            senhaInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

            </script>
</body>
</html>