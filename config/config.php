<?php

// Configurações do Banco de Dados (Mock)
// Em um ambiente real, use variáveis de ambiente
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Usuário mock
define('DB_PASS', '');     // Senha mock
define('DB_NAME', 'citability_db'); // Nome do banco mock

// Configurações da Aplicação
define('APP_URL', 'http://localhost:8000'); // URL base (ajuste se necessário)
define('SESSION_NAME', 'citability_session');

// Habilitar exibição de erros para desenvolvimento (desabilitar em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

