<?php

$title = "Minhas Consultas";

ob_start();
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Consultas para "<?php echo htmlspecialchars($brandName); ?>"</h1>
        <a href="<?php echo APP_URL; ?>/queries/create" class="btn btn-primary btn-sm shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Adicionar Nova Consulta
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Consultas Cadastradas</h6>
        </div>
        <div class="card-body">
            <?php if (!empty($queries)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableQueries" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Texto da Consulta</th>
                                <th>Categoria</th>
                                <th>Data de Criação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($queries as $query): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($query->text); ?></td>
                                    <td><?php echo htmlspecialchars($query->category ?? "N/A"); ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($query->created_at)); ?></td>
                                    <td>
                                        <!-- Placeholder for actions like view results -->
                                        <a href="<?php echo APP_URL; ?>/results?query_id=<?php echo $query->id; ?>" class="btn btn-info btn-sm" title="Ver Resultados (Simulado)">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <!-- Add delete/edit later if needed -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Nenhuma consulta cadastrada para esta marca ainda.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

