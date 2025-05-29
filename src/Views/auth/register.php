<?php

// Use App\Core\View;

$title = "Cadastro - Citability";

ob_start();
?>

<div class="auth-card card mx-auto mt-5">
    <div class="card-body p-5">
        <h3 class="card-title text-center mb-4">Criar Conta Citability</h3>
        <form action="<?php echo APP_URL; ?>/register" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
        <hr>
        <p class="text-center mb-0">Já tem uma conta? <a href="<?php echo APP_URL; ?>/login">Faça login</a></p>
    </div>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

