<?php

$title = "Cadastrar Nova Marca";

ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Cadastrar Nova Marca</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informações da Marca</h6>
        </div>
        <div class="card-body">
            <form action="<?php echo APP_URL; ?>/brand/store" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nome da Marca</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Ex: Minha Empresa Inc.">
                </div>
                <div class="mb-3">
                    <label for="domain" class="form-label">Domínio Principal</label>
                    <input type="text" class="form-control" id="domain" name="domain" required placeholder="Ex: minhaempresa.com">
                    <div class="form-text">Informe o domínio principal sem http:// ou https://.</div>
                </div>
                <button type="submit" class="btn btn-primary">Salvar Marca</button>
                <a href="<?php echo APP_URL; ?>/dashboard" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

