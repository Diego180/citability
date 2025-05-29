<?php

$title = "Adicionar Nova Consulta";

ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Adicionar Nova Consulta para "<?php echo htmlspecialchars($brandName); ?>"</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalhes da Consulta</h6>
        </div>
        <div class="card-body">
            <form action="<?php echo APP_URL; ?>/queries/store" method="POST">
                <div class="mb-3">
                    <label for="text" class="form-label">Texto da Consulta (Pergunta)</label>
                    <textarea class="form-control" id="text" name="text" rows="3" required placeholder="Ex: Quais são os principais concorrentes da [Nome da Marca] em IA generativa?"></textarea>
                    <div class="form-text">Faça a pergunta que você gostaria que a IA respondesse sobre sua marca. Máximo 500 caracteres.</div>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Categoria (Opcional)</label>
                    <input type="text" class="form-control" id="category" name="category" placeholder="Ex: Análise de Concorrência, Reputação, Recomendações">
                </div>

                <button type="submit" class="btn btn-primary">Adicionar Consulta (Simulado)</button>
                <a href="<?php echo APP_URL; ?>/queries" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

