<?php

$title = "Editar Marca";

ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Editar Marca</h1>

    <?php if ($brand): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Atualizar Informações da Marca</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo APP_URL; ?>/brand/update" method="POST">
                    <!-- Hidden input to send brand ID -->
                    <input type="hidden" name="brand_id" value="<?php echo htmlspecialchars($brand->id); ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome da Marca</label>
                        <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($brand->name); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domínio Principal</label>
                        <input type="text" class="form-control" id="domain" name="domain" required value="<?php echo htmlspecialchars($brand->domain); ?>" placeholder="Ex: minhaempresa.com">
                        <div class="form-text">Informe o domínio principal sem http:// ou https://.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="<?php echo APP_URL; ?>/brand" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Marca não encontrada para edição.
        </div>
    <?php endif; ?>

</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

