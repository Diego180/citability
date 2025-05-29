<!-- Header -->
<nav class="navbar navbar-expand navbar-light bg-white navbar-custom shadow-sm">
    <div class="container-fluid">
        <!-- You can add toggler for mobile sidebar if needed -->
        <!-- <button class="btn btn-link d-md-none rounded-circle me-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button> -->

        <!-- Navbar content on the right -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUserLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo htmlspecialchars($_SESSION["username"] ?? "Usuário"); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUserLink">
                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/profile">Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/logout">Sair</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

