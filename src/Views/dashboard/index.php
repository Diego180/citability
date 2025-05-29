<?php

$title = "Dashboard";

ob_start();
?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <!-- Optional: Report button -->
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
    </div>

    <!-- Content Row: Stats Cards -->
    <div class="row">

        <!-- Total Queries Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Consultas Cadastradas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats["total_queries"]; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-patch-question-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Mentions Card (Simulated) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Menções Positivas (Simulado)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats["total_mentions"]; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-megaphone-fill fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Coverage Card (Simulated) -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cobertura de IA (Simulado)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $coveredCount = 0;
                                foreach ($stats["ai_coverage"] as $ai => $isCovered) {
                                    if ($isCovered) {
                                        echo "<span class=\"badge bg-info me-1\">".htmlspecialchars($ai)."</span>";
                                        $coveredCount++;
                                    }
                                }
                                if ($coveredCount == 0 && $stats["total_queries"] > 0) {
                                    echo "<span class=\"text-muted small\">Nenhuma resposta simulada ainda.</span>";
                                } elseif ($stats["total_queries"] == 0) {
                                     echo "<span class=\"text-muted small\">Adicione consultas para simular.</span>";
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-robot fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row: Call to Action / Next Steps -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Próximos Passos</h6>
                </div>
                <div class="card-body">
                    <?php if (!$userBrand): ?>
                        <p>Bem-vindo ao Citability! O primeiro passo é cadastrar a sua marca.</p>
                        <a href="<?php echo APP_URL; ?>/brand/create" class="btn btn-primary">Cadastrar Minha Marca</a>
                    <?php elseif ($stats["total_queries"] == 0): ?>
                         <p>Sua marca "<strong><?php echo htmlspecialchars($userBrand->name); ?></strong>" está cadastrada. Agora, adicione algumas consultas para simular a análise de citabilidade.</p>
                         <a href="<?php echo APP_URL; ?>/queries/create" class="btn btn-primary">Adicionar Consulta</a>
                    <?php else: ?>
                         <p>Você já cadastrou sua marca e adicionou consultas. Explore os resultados simulados!</p>
                         <a href="<?php echo APP_URL; ?>/queries" class="btn btn-info me-2">Ver Minhas Consultas</a>
                         <a href="<?php echo APP_URL; ?>/results?query_id=<?php echo $stats["total_queries"] > 0 ? $queryModel->findByBrandId((int)$userBrand->id)[0]->id : "; ?>" class="btn btn-success">Ver Últimos Resultados (Simulado)</a>
                    <?php endif; ?>
                    <hr>
                    <p class="text-muted small">Lembre-se: Este é um protótipo funcional. As respostas das IAs e as métricas são simuladas para fins de demonstração e validação da interface e do fluxo de uso.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

