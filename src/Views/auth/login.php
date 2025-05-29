<?php

// Use App\Core\View;

$title = "Login - Citability";

ob_start();
?>

<div class="auth-card card mx-auto mt-5">
    <div class="card-body p-5">
        <h3 class="card-title text-center mb-4">Login Citability</h3>
        <form action="<?php echo APP_URL; ?>/login" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <!-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Lembrar-me</label>
            </div> -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>
        <hr>
        <p class="text-center mb-0">Não tem uma conta? <a href="<?php echo APP_URL; ?>/register">Cadastre-se</a></p>
    </div>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . 
"/src/Views/layouts/app.php";
?>

