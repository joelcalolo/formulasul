-- Schema completo do banco de dados para o sistema FormulaSul
-- Baseado nas migrações Laravel analisadas

-- Configurações do banco
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Criar banco de dados (descomente se necessário)
-- CREATE DATABASE IF NOT EXISTS `formulasul` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `formulasul`;

-- =====================================================
-- TABELA: users (Usuários)
-- =====================================================
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('cliente','admin') NOT NULL,
  `phone` varchar(255) NULL,
  `country` varchar(2) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: password_reset_tokens (Tokens de reset de senha)
-- =====================================================
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: failed_jobs (Jobs falhados)
-- =====================================================
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL UNIQUE,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: personal_access_tokens (Tokens de acesso pessoal)
-- =====================================================
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL UNIQUE,
  `abilities` text NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: cars (Carros)
-- =====================================================
CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `marca` varchar(255) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `caixa` varchar(255) NOT NULL COMMENT 'Manual ou Automática',
  `tracao` varchar(255) NOT NULL COMMENT 'FWD, RWD, 4WD, etc.',
  `lugares` int(11) NOT NULL,
  `combustivel` varchar(255) NOT NULL COMMENT 'Gasolina, Diesel, Elétrico',
  `status` varchar(255) NOT NULL DEFAULT 'disponivel',
  `image_cover` varchar(255) NULL,
  `image_1` varchar(255) NULL,
  `image_2` varchar(255) NULL,
  `image_3` varchar(255) NULL,
  `has_gallery` tinyint(1) NOT NULL DEFAULT 0,
  `cor` varchar(255) NULL,
  `transmissao` varchar(255) NULL,
  `descricao` text NULL,
  `price` decimal(10,2) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: price_tables (Tabelas de preços)
-- =====================================================
CREATE TABLE `price_tables` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `preco_dentro_com_motorista` decimal(10,2) NOT NULL,
  `preco_dentro_sem_motorista` decimal(10,2) NOT NULL,
  `preco_fora_com_motorista` decimal(10,2) NOT NULL,
  `preco_fora_sem_motorista` decimal(10,2) NOT NULL,
  `taxa_entrega_recolha` decimal(10,2) NULL,
  `plafond_km_dia` int(11) NOT NULL DEFAULT 100,
  `preco_km_extra` decimal(10,2) NULL,
  `caucao` decimal(10,2) NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_tables_car_id_foreign` (`car_id`),
  CONSTRAINT `price_tables_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: rental_requests (Solicitações de aluguel)
-- =====================================================
CREATE TABLE `rental_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `carro_principal_id` bigint(20) UNSIGNED NOT NULL,
  `carro_secundario_id` bigint(20) UNSIGNED NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `local_entrega` varchar(255) NOT NULL,
  `observacoes` text NULL,
  `status` enum('pendente','rejeitado','confirmado') NOT NULL DEFAULT 'pendente',
  `email_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rental_requests_user_id_foreign` (`user_id`),
  KEY `rental_requests_carro_principal_id_foreign` (`carro_principal_id`),
  KEY `rental_requests_carro_secundario_id_foreign` (`carro_secundario_id`),
  CONSTRAINT `rental_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rental_requests_carro_principal_id_foreign` FOREIGN KEY (`carro_principal_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rental_requests_carro_secundario_id_foreign` FOREIGN KEY (`carro_secundario_id`) REFERENCES `cars` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: rentals (Alugueis confirmados)
-- =====================================================
CREATE TABLE `rentals` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `carro_id` bigint(20) UNSIGNED NOT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  `local_entrega` varchar(255) NOT NULL,
  `pago` tinyint(1) NOT NULL DEFAULT 0,
  `codigo_confirmacao` varchar(255) NOT NULL UNIQUE,
  `status` enum('ativo','finalizado','cancelado') NOT NULL DEFAULT 'ativo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rentals_user_id_foreign` (`user_id`),
  KEY `rentals_carro_id_foreign` (`carro_id`),
  CONSTRAINT `rentals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rentals_carro_id_foreign` FOREIGN KEY (`carro_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: transfers (Transferências e passeios)
-- =====================================================
CREATE TABLE `transfers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NULL,
  `origem` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `flight_number` varchar(255) NULL,
  `flight_time` time NULL,
  `flight_date` date NULL,
  `airline` varchar(255) NULL,
  `special_requests` text NULL,
  `num_pessoas` int(11) NULL,
  `data_hora` datetime NOT NULL,
  `tipo` varchar(255) NULL COMMENT 'transfer, passeio',
  `observacoes` text NULL,
  `status` enum('pendente','confirmado','rejeitado') NOT NULL DEFAULT 'pendente',
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text NULL,
  `confirmation_method` varchar(255) NULL COMMENT 'email, sms, phone',
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0,
  `email_enviado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transfers_user_id_foreign` (`user_id`),
  KEY `transfers_admin_id_foreign` (`admin_id`),
  CONSTRAINT `transfers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transfers_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: faqs (Perguntas frequentes)
-- =====================================================
CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: regulations (Regulamentos)
-- =====================================================
CREATE TABLE `regulations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rule` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: passeios (Passeios turísticos)
-- =====================================================
CREATE TABLE `passeios` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `subtitulo` varchar(255) NULL,
  `imagem_principal` varchar(255) NOT NULL,
  `localizacao` varchar(255) NOT NULL,
  `duracao` varchar(255) NOT NULL,
  `avaliacao` decimal(2,1) NOT NULL DEFAULT 0,
  `total_avaliacoes` int(11) NOT NULL DEFAULT 0,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `tamanho_grupo` varchar(255) NOT NULL,
  `idioma` varchar(255) NOT NULL,
  `galeria` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ÍNDICES ADICIONAIS PARA OTIMIZAÇÃO
-- =====================================================

-- Índices para users
CREATE INDEX `users_email_index` ON `users` (`email`);
CREATE INDEX `users_role_index` ON `users` (`role`);

-- Índices para cars
CREATE INDEX `cars_status_index` ON `cars` (`status`);
CREATE INDEX `cars_marca_modelo_index` ON `cars` (`marca`, `modelo`);

-- Índices para rental_requests
CREATE INDEX `rental_requests_status_index` ON `rental_requests` (`status`);
CREATE INDEX `rental_requests_data_inicio_index` ON `rental_requests` (`data_inicio`);
CREATE INDEX `rental_requests_data_fim_index` ON `rental_requests` (`data_fim`);

-- Índices para rentals
CREATE INDEX `rentals_status_index` ON `rentals` (`status`);
CREATE INDEX `rentals_data_inicio_index` ON `rentals` (`data_inicio`);
CREATE INDEX `rentals_data_fim_index` ON `rentals` (`data_fim`);
CREATE INDEX `rentals_codigo_confirmacao_index` ON `rentals` (`codigo_confirmacao`);

-- Índices para transfers
CREATE INDEX `transfers_status_index` ON `transfers` (`status`);
CREATE INDEX `transfers_tipo_index` ON `transfers` (`tipo`);
CREATE INDEX `transfers_data_hora_index` ON `transfers` (`data_hora`);
CREATE INDEX `transfers_flight_date_index` ON `transfers` (`flight_date`);

-- Índices para passeios
CREATE INDEX `passeios_localizacao_index` ON `passeios` (`localizacao`);
CREATE INDEX `passeios_preco_index` ON `passeios` (`preco`);

-- =====================================================
-- DADOS INICIAIS (OPCIONAL)
-- =====================================================

-- Inserir usuário admin padrão (senha: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
('Administrador', 'admin@formulasul.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW());

-- Inserir algumas FAQs de exemplo
INSERT INTO `faqs` (`question`, `answer`, `created_at`, `updated_at`) VALUES
('Como faço para alugar um carro?', 'Para alugar um carro, você precisa criar uma conta, escolher o veículo desejado e fazer uma solicitação de aluguel.', NOW(), NOW()),
('Quais documentos são necessários?', 'É necessário apresentar carteira de habilitação válida, documento de identidade e comprovante de residência.', NOW(), NOW()),
('Qual é a política de cancelamento?', 'Cancelamentos podem ser feitos até 24 horas antes do início do aluguel sem custos adicionais.', NOW(), NOW());

-- Inserir algumas regras de exemplo
INSERT INTO `regulations` (`rule`, `created_at`, `updated_at`) VALUES
('É proibido fumar dentro dos veículos.', NOW(), NOW()),
('O limite de velocidade deve ser respeitado conforme a legislação local.', NOW(), NOW()),
('É obrigatório o uso de cinto de segurança por todos os ocupantes.', NOW(), NOW()),
('O veículo deve ser devolvido com o tanque de combustível cheio.', NOW(), NOW());

COMMIT; 