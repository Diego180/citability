<?php

$title = "Meu Perfil";

ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Meu Perfil</h1>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações do Usuário</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome de Usuário:</label>
                        <p><?php echo htmlspecialchars($user->username); ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Membro desde:</label>
                        <p><?php echo date("d/m/Y", strtotime($user->created_at)); ?></p>
                    </div>
                    <hr>
                    <a href="<?php echo APP_URL; ?>/profile/edit" class="btn btn-warning">
                        <i class="bi bi-key-fill me-1"></i> Alterar Senha
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- Placeholder for other profile settings if needed -->
            <div class="card shadow mb-4">
                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Configurações Adicionais</h6>
                </div>
                 <div class="card-body">
                    <p class="text-muted">Nenhuma configuração adicional disponível no momento.</p>
                 </div>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

