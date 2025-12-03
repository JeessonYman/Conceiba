-- =====================================================
-- Sistema Conceiba - Actualización de Base de Datos
-- Fecha: 2025-11-24
-- Descripción: Tablas para roles, permisos, soporte, María (IA) y facturas
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- =====================================================
-- TABLA: roles
-- Descripción: Roles de usuario en el sistema
-- =====================================================

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Insertar roles predefinidos
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Administrador con acceso completo al sistema', NOW()),
(2, 'Manager', 'Gerente con permisos de gestión', NOW()),
(3, 'Seller', 'Vendedor con acceso a ventas', NOW()),
(4, 'Warehouse', 'Encargado de almacén', NOW());

-- =====================================================
-- TABLA: permissions
-- Descripción: Permisos disponibles en el sistema
-- =====================================================

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `module` varchar(50) NOT NULL,
  `action` enum('VIEW','CREATE','EDIT','DELETE','MANAGE') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_permission` (`module`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Insertar permisos básicos
INSERT INTO `permissions` (`name`, `description`, `module`, `action`) VALUES
-- Ventas
('Ver Ventas', 'Permite ver el historial de ventas', 'sales', 'VIEW'),
('Crear Ventas', 'Permite crear nuevas ventas', 'sales', 'CREATE'),
('Editar Ventas', 'Permite editar ventas existentes', 'sales', 'EDIT'),
('Eliminar Ventas', 'Permite eliminar ventas', 'sales', 'DELETE'),
-- Productos
('Ver Productos', 'Permite ver productos', 'products', 'VIEW'),
('Crear Productos', 'Permite agregar productos', 'products', 'CREATE'),
('Editar Productos', 'Permite editar productos', 'products', 'EDIT'),
('Eliminar Productos', 'Permite eliminar productos', 'products', 'DELETE'),
-- Ingresos
('Ver Ingresos', 'Permite ver ingresos', 'income', 'VIEW'),
('Crear Ingresos', 'Permite registrar ingresos', 'income', 'CREATE'),
('Editar Ingresos', 'Permite editar ingresos', 'income', 'EDIT'),
('Eliminar Ingresos', 'Permite eliminar ingresos', 'income', 'DELETE'),
-- Usuarios
('Ver Usuarios', 'Permite ver usuarios', 'users', 'VIEW'),
('Crear Usuarios', 'Permite crear usuarios', 'users', 'CREATE'),
('Editar Usuarios', 'Permite editar usuarios', 'users', 'EDIT'),
('Eliminar Usuarios', 'Permite eliminar usuarios', 'users', 'DELETE'),
('Gestionar Roles', 'Permite gestionar roles y permisos', 'users', 'MANAGE'),
-- Reportes
('Ver Reportes', 'Permite ver reportes', 'reports', 'VIEW'),
('Crear Reportes', 'Permite generar reportes', 'reports', 'CREATE'),
-- María IA
('Usar María', 'Permite usar el asistente María', 'maria', 'VIEW'),
('Configurar María', 'Permite configurar María', 'maria', 'MANAGE');

-- =====================================================
-- TABLA: role_permissions
-- Descripción: Relación muchos a muchos entre roles y permisos
-- =====================================================

CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_permission` (`role_id`, `permission_id`),
  KEY `fk_role_permissions_role` (`role_id`),
  KEY `fk_role_permissions_permission` (`permission_id`),
  CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Asignar todos los permisos al Admin
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, id FROM `permissions`;

-- =====================================================
-- TABLA: user_roles
-- Descripción: Relación muchos a muchos entre usuarios y roles
-- =====================================================

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_role` (`user_id`, `role_id`),
  KEY `fk_user_roles_user` (`user_id`),
  KEY `fk_user_roles_role` (`role_id`),
  CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Asignar rol Admin a usuarios tipo 1
INSERT INTO `user_roles` (`user_id`, `role_id`)
SELECT id, 1 FROM `users` WHERE type = 1;

-- =====================================================
-- TABLA: support_tickets
-- Descripción: Sistema de tickets de soporte técnico
-- =====================================================

CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_support_tickets_user` (`user_id`),
  KEY `fk_support_tickets_assigned` (`assigned_to`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  CONSTRAINT `fk_support_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_support_tickets_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- =====================================================
-- TABLA: support_comments
-- Descripción: Comentarios en tickets de soporte
-- =====================================================

CREATE TABLE IF NOT EXISTS `support_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_support_comments_ticket` (`ticket_id`),
  KEY `fk_support_comments_user` (`user_id`),
  CONSTRAINT `fk_support_comments_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_support_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- =====================================================
-- TABLA: maria_conversations
-- Descripción: Historial de conversaciones con María (IA)
-- =====================================================

CREATE TABLE IF NOT EXISTS `maria_conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `response` text NOT NULL,
  `context` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_maria_conversations_user` (`user_id`),
  KEY `idx_session` (`session_id`),
  CONSTRAINT `fk_maria_conversations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- =====================================================
-- TABLA: maria_automations
-- Descripción: Automatizaciones configuradas en María
-- =====================================================

CREATE TABLE IF NOT EXISTS `maria_automations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `trigger_type` enum('schedule','event','threshold') NOT NULL,
  `trigger_config` text NOT NULL,
  `action_type` enum('email','alert','report','reorder') NOT NULL,
  `action_config` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_run` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_maria_automations_user` (`created_by`),
  CONSTRAINT `fk_maria_automations_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- =====================================================
-- TABLA: invoices
-- Descripción: Facturas y recibos del sistema
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `customer_email` varchar(200) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `issue_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `fk_invoices_sale` (`sale_id`),
  KEY `fk_invoices_user` (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_invoices_sale` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- =====================================================
-- TABLA: invoice_items
-- Descripción: Items/líneas de las facturas
-- =====================================================

CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_items_invoice` (`invoice_id`),
  KEY `fk_invoice_items_product` (`product_id`),
  CONSTRAINT `fk_invoice_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_invoice_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- =====================================================
-- TABLA: user_preferences
-- Descripción: Preferencias de usuario (tema, idioma, etc.)
-- =====================================================

CREATE TABLE IF NOT EXISTS `user_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `theme` enum('light','dark') NOT NULL DEFAULT 'light',
  `language` varchar(10) NOT NULL DEFAULT 'es',
  `notifications_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk_user_preferences_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

COMMIT;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================
