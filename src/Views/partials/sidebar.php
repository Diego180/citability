<!-- Sidebar -->
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
    <a href="<?php echo APP_URL; ?>/dashboard" class="sidebar-brand d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-bullseye me-2"></i>
        <span class="fs-4">Citability</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <!-- Example: Add active class based on current route -->
            <a href="<?php echo APP_URL; ?>/dashboard" class="nav-link <?php echo (strpos($_GET["url"] ?? ", "dashboard") !== false) ? "active" : "text-white"; ?>">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="<?php echo APP_URL; ?>/brand" class="nav-link <?php echo (strpos($_GET["url"] ?? ", "brand") !== false) ? "active" : "text-white"; ?>">
                <i class="bi bi-bookmark-star"></i>
                Minha Marca
            </a>
        </li>
        <li>
            <a href="<?php echo APP_URL; ?>/queries" class="nav-link <?php echo (strpos($_GET["url"] ?? ", "queries") !== false) ? "active" : "text-white"; ?>">
                <i class="bi bi-patch-question"></i>
                Consultas
            </a>
        </li>
        <li>
            <a href="<?php echo APP_URL; ?>/results" class="nav-link <?php echo (strpos($_GET["url"] ?? ", "results") !== false) ? "active" : "text-white"; ?>">
                <i class="bi bi-graph-up-arrow"></i>
                Resultados
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i>
            <strong><?php echo htmlspecialchars($_SESSION["username"] ?? "Usuário"); ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/profile">Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/logout">Sair</a></li>
        </ul>
    </div>
</div>

