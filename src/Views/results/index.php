<?php

$title = "Resultados da Consulta (Simulado)";

ob_start();
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">Resultados Simulados</h1>
            <p class="mb-4">Consulta: <strong>"<?php echo htmlspecialchars($query->text); ?>"</strong></p>
        </div>
        <a href="<?php echo APP_URL; ?>/queries" class="btn btn-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar para Consultas
        </a>
    </div>

    <!-- Chart Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Visão Geral das Menções por IA (Simulado)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="mentionsChart"></canvas>
                    </div>
                    <p class="mt-2 text-center small text-muted">Este gráfico mostra a contagem simulada de menções positivas vs. neutras/nenhuma por IA.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Responses Row -->
    <div class="row">
        <div class="col-lg-12">
            <h4 class="mb-3">Respostas Simuladas por IA</h4>
            <?php if (!empty($responses)): ?>
                <?php foreach ($responses as $response): ?>
                    <div class="card shadow mb-3">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">IA: <?php echo htmlspecialchars($response->ai_type); ?></h6>
                            <span class="badge bg-<?php echo $response->has_mention ? 'success' : 'secondary'; ?>">
                                <?php echo $response->has_mention ? '<i class="bi bi-check-circle-fill me-1"></i> Menção Positiva' : '<i class="bi bi-dash-circle me-1"></i> Neutra / Sem Menção'; ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($response->full_text)); ?></p>
                            <?php if (!empty($response->urls_mentioned)): ?>
                                <hr>
                                <p class="mb-1 fw-bold">URLs Mencionadas:</p>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($response->urls_mentioned as $url): ?>
                                        <li><a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($url); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                             <p class="card-text mt-2"><small class="text-muted">Simulado em: <?php echo date("d/m/Y H:i", strtotime($response->created_at)); ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhuma resposta simulada gerada para esta consulta.</div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById("mentionsChart").getContext("2d");
    const chartData = <?php echo $chartData; ?>;

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: "Menções Positivas",
                    data: chartData.mentions,
                    backgroundColor: "rgba(28, 200, 138, 0.7)", // Bootstrap success color with transparency
                    borderColor: "rgba(28, 200, 138, 1)",
                    borderWidth: 1
                },
                {
                    label: "Neutra / Sem Menção",
                    data: chartData.noMentions,
                    backgroundColor: "rgba(108, 117, 125, 0.7)", // Bootstrap secondary color with transparency
                    borderColor: "rgba(108, 117, 125, 1)",
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Ensure integer steps for counts
                    }
                }
            },
            plugins: {
                tooltip: {
                    mode: "index",
                    intersect: false
                },
                legend: {
                    position: "top",
                }
            }
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require ROOT_PATH . "/src/Views/layouts/app.php";
?>

