<?php

$title = "Minha Marca";

ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Minha Marca</h1>

    <?php if ($brand): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Detalhes da Marca</h6>
                <a href="<?php echo APP_URL; ?>/brand/edit" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Editar
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nome da Marca:</label>
                        <p><?php echo htmlspecialchars($brand->name); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Domínio:</label>
                        <p><?php echo htmlspecialchars($brand->domain); ?></p>
                    </div>
                </div>
                 <div class="row">
                     <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Cadastrada em:</label>
                        <p><?php echo date("d/m/Y H:i", strtotime($brand->created_at)); ?></p>
                    </div>
                 </div>
            </div>
        </div>

        <!-- Section for Queries related to this brand could go here -->
        <div class="mt-4">
             <a href="<?php echo APP_URL; ?>/queries" class="btn btn-info">
                <i class="bi bi-patch-question me-1"></i> Ver Consultas da Marca
             </a>
        </div>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            Nenhuma marca cadastrada ainda. <a href="<?php echo APP_URL; ?>/brand/create" class="alert-link">Cadastre sua marca</a> para começar.
        </div>
    <?php endif; ?>

</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

