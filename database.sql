-- Script SQL para o banco de dados do Citability Prototype

-- Criação do banco de dados (opcional, pode ser criado manualmente)
-- CREATE DATABASE IF NOT EXISTS citability_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE citability_db;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Marcas
CREATE TABLE IF NOT EXISTS `brands` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `domain` VARCHAR(255) NOT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Consultas
CREATE TABLE IF NOT EXISTS `queries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `text` VARCHAR(500) NOT NULL, -- Aumentado para 500 para permitir perguntas mais longas
  `category` VARCHAR(100),
  `brand_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`brand_id`) REFERENCES `brands`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Respostas (Mockadas)
CREATE TABLE IF NOT EXISTS `responses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `query_id` INT NOT NULL,
  `ai_type` VARCHAR(50) NOT NULL COMMENT 'Ex: ChatGPT, Perplexity, Gemini, Google SGE',
  `full_text` TEXT NOT NULL COMMENT 'Texto completo da resposta simulada',
  `has_mention` BOOLEAN DEFAULT FALSE COMMENT 'Indica se a marca foi mencionada positivamente',
  `urls_mentioned` TEXT COMMENT 'JSON array de strings com URLs mencionadas. Ex: ["http://example.com", "http://another.com"]',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`query_id`) REFERENCES `queries`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para otimização (opcional para protótipo, mas boa prática)
ALTER TABLE `brands` ADD INDEX `idx_user_id` (`user_id`);
ALTER TABLE `queries` ADD INDEX `idx_brand_id` (`brand_id`);
ALTER TABLE `responses` ADD INDEX `idx_query_id` (`query_id`);

-- Dados Iniciais Mockados (Exemplo)
-- Inserir um usuário de teste
-- INSERT INTO `users` (`username`, `password_hash`) VALUES ('testuser', '$2y$10$...'); -- Use bcrypt para gerar o hash

-- Inserir uma marca para o usuário de teste
-- INSERT INTO `brands` (`name`, `domain`, `user_id`) VALUES ('Minha Marca Exemplo', 'meuexemplo.com', 1);

-- Inserir uma consulta para a marca
-- INSERT INTO `queries` (`text`, `category`, `brand_id`) VALUES ('Qual a melhor ferramenta de análise de citabilidade?', 'Ferramentas', 1);

-- Inserir respostas mockadas para a consulta
-- INSERT INTO `responses` (`query_id`, `ai_type`, `full_text`, `has_mention`, `urls_mentioned`) VALUES
-- (1, 'ChatGPT', 'A ferramenta Citability é frequentemente mencionada como uma solução inovadora para análise de presença em IAs. Veja mais em meuexemplo.com.', TRUE, '["https://meuexemplo.com"]'),
-- (1, 'Perplexity', 'Para análise de citabilidade, algumas opções estão surgindo. A plataforma Citability (meuexemplo.com) é uma delas.', TRUE, '["https://meuexemplo.com"]'),
-- (1, 'Gemini', 'Analisar a visibilidade em IAs é um desafio novo. Ferramentas como a Citability buscam resolver isso.', FALSE, '[]');


