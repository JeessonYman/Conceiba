-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-08-2026 a las 22:51:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `conceibadatabase`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cat_slug` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`, `cat_slug`) VALUES
(1, 'Sombreros', 'Sombreros'),
(2, 'Peluches', 'Peluches'),
(3, 'Cojines', 'Cojines'),
(4, 'Hilado', 'Hilado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `details`
--

CREATE TABLE `details` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `details`
--

INSERT INTO `details` (`id`, `sales_id`, `product_id`, `quantity`) VALUES
(173, 105, 10, 170),
(174, 105, 11, 170),
(175, 105, 12, 170),
(176, 106, 8, 90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `details_inputs`
--

CREATE TABLE `details_inputs` (
  `id` int(11) NOT NULL,
  `id_inputs` int(11) NOT NULL,
  `id_products` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `details_inputs`
--

INSERT INTO `details_inputs` (`id`, `id_inputs`, `id_products`, `quantity`, `price`) VALUES
(6, 7, 1, 150, 150),
(7, 8, 1, 150, 150),
(8, 8, 2, 150, 150),
(9, 8, 3, 150, 150),
(10, 8, 3, 150, 150),
(11, 8, 4, 150, 150),
(12, 8, 5, 110, 110),
(13, 8, 6, 110, 110),
(14, 8, 7, 110, 110),
(15, 8, 8, 90, 90),
(16, 8, 9, 80, 80),
(17, 8, 14, 60, 60),
(18, 8, 13, 100, 100),
(19, 8, 10, 170, 170),
(20, 8, 11, 170, 170),
(21, 8, 12, 170, 170),
(22, 9, 12, 170, 170),
(23, 10, 2, 12, 150);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inputs`
--

CREATE TABLE `inputs` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inputs`
--

INSERT INTO `inputs` (`id`, `code`, `provider_id`, `user_id`, `entry_date`, `total`) VALUES
(4, 'INV-001', 1, 2, '2025-09-23', 100.5),
(5, 'INV-002', 2, 1, '2025-09-24', 250.75),
(6, 'INV-003', 1, 1, '2025-09-25', 150),
(7, '', 1, 1, '2025-10-02', 22500),
(8, '', 1, 1, '2025-10-07', 263600),
(9, '', 2, 1, '2025-10-28', 28900),
(10, '', 1, 1, '2025-11-08', 1800);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maria_automations`
--

CREATE TABLE `maria_automations` (
  `id` int(11) NOT NULL,
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
  `last_run` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maria_conversations`
--

CREATE TABLE `maria_conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `response` text NOT NULL,
  `context` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `module` varchar(50) NOT NULL,
  `action` enum('VIEW','CREATE','EDIT','DELETE','MANAGE') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `module`, `action`, `created_at`) VALUES
(1, 'Ver Ventas', 'Permite ver el historial de ventas', 'sales', 'VIEW', '2025-11-25 05:28:50'),
(2, 'Crear Ventas', 'Permite crear nuevas ventas', 'sales', 'CREATE', '2025-11-25 05:28:50'),
(3, 'Editar Ventas', 'Permite editar ventas existentes', 'sales', 'EDIT', '2025-11-25 05:28:50'),
(4, 'Eliminar Ventas', 'Permite eliminar ventas', 'sales', 'DELETE', '2025-11-25 05:28:50'),
(5, 'Ver Productos', 'Permite ver productos', 'products', 'VIEW', '2025-11-25 05:28:50'),
(6, 'Crear Productos', 'Permite agregar productos', 'products', 'CREATE', '2025-11-25 05:28:50'),
(7, 'Editar Productos', 'Permite editar productos', 'products', 'EDIT', '2025-11-25 05:28:50'),
(8, 'Eliminar Productos', 'Permite eliminar productos', 'products', 'DELETE', '2025-11-25 05:28:50'),
(9, 'Ver Ingresos', 'Permite ver ingresos', 'income', 'VIEW', '2025-11-25 05:28:50'),
(10, 'Crear Ingresos', 'Permite registrar ingresos', 'income', 'CREATE', '2025-11-25 05:28:50'),
(11, 'Editar Ingresos', 'Permite editar ingresos', 'income', 'EDIT', '2025-11-25 05:28:50'),
(12, 'Eliminar Ingresos', 'Permite eliminar ingresos', 'income', 'DELETE', '2025-11-25 05:28:50'),
(13, 'Ver Usuarios', 'Permite ver usuarios', 'users', 'VIEW', '2025-11-25 05:28:50'),
(14, 'Crear Usuarios', 'Permite crear usuarios', 'users', 'CREATE', '2025-11-25 05:28:50'),
(15, 'Editar Usuarios', 'Permite editar usuarios', 'users', 'EDIT', '2025-11-25 05:28:50'),
(16, 'Eliminar Usuarios', 'Permite eliminar usuarios', 'users', 'DELETE', '2025-11-25 05:28:50'),
(17, 'Gestionar Roles', 'Permite gestionar roles y permisos', 'users', 'MANAGE', '2025-11-25 05:28:50'),
(18, 'Ver Reportes', 'Permite ver reportes', 'reports', 'VIEW', '2025-11-25 05:28:50'),
(19, 'Crear Reportes', 'Permite generar reportes', 'reports', 'CREATE', '2025-11-25 05:28:50'),
(20, 'Usar María', 'Permite usar el asistente María', 'maria', 'VIEW', '2025-11-25 05:28:50'),
(21, 'Configurar María', 'Permite configurar María', 'maria', 'MANAGE', '2025-11-25 05:28:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(200) NOT NULL,
  `price` double NOT NULL,
  `cost` double NOT NULL,
  `price_normal` double NOT NULL,
  `stock` int(20) NOT NULL,
  `stock_minimum` int(20) DEFAULT NULL,
  `photo` varchar(200) NOT NULL,
  `date_view` date NOT NULL,
  `counter` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `category_id`, `provider_id`, `name`, `description`, `slug`, `price`, `cost`, `price_normal`, `stock`, `stock_minimum`, `photo`, `date_view`, `counter`) VALUES
(1, 3, 1, 'Cojín bordado Circular', '<p>Coj&iacute;n bordado relleno de kapok.</p>\r\n', 'cojin-bordado-circular', 150, 150, 150, 150, 15, 'cojin-bordado-circular.png', '2025-11-24', 4),
(2, 3, 1, 'Cojín bordado eliseos', '<p>Coj&iacute;n decorativo bordado relleno de kapok.</p>\r\n', 'cojin-bordado-eliseos', 150, 150, 150, 162, 15, 'cojin-bordado-eliseos.png', '2025-11-24', 4),
(3, 3, 1, 'Cojín bordado Hoja', '<p>Coj&iacute;n bordado relleno de kapok.</p>\r\n', 'cojin-bordado-hoja', 150, 150, 150, 150, 15, 'cojin-bordado-hoja.png', '2025-11-24', 4),
(4, 3, 1, 'Cojín bordado Jardín', '<p>Coj&iacute;n bordado relleno de kapok.</p>\r\n', 'cojin-bordado-jardin', 150, 150, 150, 150, 15, 'cojin-bordado-jardin.png', '2025-11-24', 4),
(5, 3, 1, 'Cojín decorativo Arabic', '<p>Coj&iacute;n decorativo relleno de kapok.</p>\r\n', 'cojin-decorativo-arabic', 110, 110, 110, 110, 11, 'cojin-decorativo-arabic.png', '2025-11-24', 4),
(6, 3, 1, 'Cojín decorativo Florinda', '<p>Coj&iacute;n decorativo relleno de kapok</p>\r\n', 'cojin-decorativo-florinda', 110, 110, 110, 110, 11, 'cojin-decorativo-florinda.png', '2025-11-24', 4),
(7, 3, 1, 'Cojín decorativo Tulipán', '<p>Coj&iacute;n decorativo relleno de kapok.</p>\r\n', 'cojin-decorativo-tulipan', 110, 110, 110, 110, 11, 'cojin-decorativo-tulipan.png', '2025-11-24', 4),
(8, 2, 1, 'Coneja oreja larga', '<p>Peluche relleno de la fibra natural del kapok.</p>\r\n', 'coneja-oreja-larga', 90, 90, 90, 0, 9, 'coneja-oreja-larga.png', '2025-11-24', 4),
(9, 2, 1, 'Conejita', '<p>Peluche relleno de la fibra natural del kapok.</p>\r\n', 'conejita', 80, 80, 80, 80, 8, 'conejita.png', '2025-11-24', 4),
(10, 1, 1, 'Sombrero de Kapok y ovino', '<p>Sombrero de kapok</p>\r\n', 'sombrero-de-kapok-y-ovino', 170, 170, 170, 0, 17, 'sombrero-de-kapok-y-ovino.png', '2025-11-24', 4),
(11, 1, 1, 'Sombrero de kapok y ovino bicolor', '<p>Sombrero de kapok</p>\r\n', 'sombrero-de-kapok-y-ovino-bicolor', 170, 170, 170, 0, 17, 'sombrero-de-kapok-y-ovino-bicolor.png', '2025-11-24', 281),
(12, 1, 1, 'Sombrero de kapok y ovino blanco', '<p>Sombrero de kapok</p>\r\n', 'sombrero-de-kapok-y-ovino-blanco', 170, 170, 170, 170, 17, 'sombrero-de-kapok-y-ovino-blanco.png', '2026-01-05', 2),
(13, 2, 1, 'Oso de peluche', '<p><strong>Peluche relleno de la fibra natural del kapok.</strong></p>\r\n', 'oso-de-peluche', 100, 100, 100, 100, 10, 'oso-de-peluche.jpg', '2025-11-24', 298),
(14, 4, 1, 'Hilado artesanal de kapok', '<h3><strong>Hilado vegetal antial&eacute;rgica, anti &aacute;caros de kapok</strong></h3>\r\n', 'hilado-artesanal-de-kapok', 60, 60, 60, 60, 6, 'hilado-artesanal-de-kapok.jpg', '2025-11-24', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provider`
--

CREATE TABLE `provider` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `pro_slug` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `provider`
--

INSERT INTO `provider` (`id`, `name`, `pro_slug`) VALUES
(1, 'Campo 4', 'Campo-4'),
(2, 'Vallenorte', 'Vallenorte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Administrador con acceso completo al sistema', '2025-11-25 05:28:50'),
(2, 'Manager', 'Gerente con permisos de gestión', '2025-11-25 05:28:50'),
(3, 'Seller', 'Vendedor con acceso a ventas', '2025-11-25 05:28:50'),
(4, 'Warehouse', 'Encargado de almacén', '2025-11-25 05:28:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`) VALUES
(1, 1, 9, '2025-11-25 05:28:50'),
(2, 1, 10, '2025-11-25 05:28:50'),
(3, 1, 11, '2025-11-25 05:28:50'),
(4, 1, 12, '2025-11-25 05:28:50'),
(5, 1, 20, '2025-11-25 05:28:50'),
(6, 1, 21, '2025-11-25 05:28:50'),
(7, 1, 5, '2025-11-25 05:28:50'),
(8, 1, 6, '2025-11-25 05:28:50'),
(9, 1, 7, '2025-11-25 05:28:50'),
(10, 1, 8, '2025-11-25 05:28:50'),
(11, 1, 18, '2025-11-25 05:28:50'),
(12, 1, 19, '2025-11-25 05:28:50'),
(13, 1, 1, '2025-11-25 05:28:50'),
(14, 1, 2, '2025-11-25 05:28:50'),
(15, 1, 3, '2025-11-25 05:28:50'),
(16, 1, 4, '2025-11-25 05:28:50'),
(17, 1, 13, '2025-11-25 05:28:50'),
(18, 1, 14, '2025-11-25 05:28:50'),
(19, 1, 15, '2025-11-25 05:28:50'),
(20, 1, 16, '2025-11-25 05:28:50'),
(21, 1, 17, '2025-11-25 05:28:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_id` varchar(50) NOT NULL,
  `sales_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `pay_id`, `sales_date`) VALUES
(105, 2, 'PAYID-ND6AS6A92432397AS808554H', '2025-10-24'),
(106, 2, 'PAYID-NEAV6HQ3G4285288X092953F', '2025-10-28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `status`, `created_on`) VALUES
(3, 'jeessonyman12345@gmail.com', 1, '2025-11-07 23:16:47'),
(4, 'jeessonyman1234@gmail.com', 1, '2025-11-07 23:57:08'),
(5, 'foo-bar@example.com', 1, '2025-11-24 19:45:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support_comments`
--

CREATE TABLE `support_comments` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(60) NOT NULL,
  `type` int(1) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `contact_info` varchar(100) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  `activate_code` varchar(15) NOT NULL,
  `reset_code` varchar(15) NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `type`, `firstname`, `lastname`, `address`, `contact_info`, `photo`, `status`, `activate_code`, `reset_code`, `created_on`) VALUES
(1, 'jeessonyman1@gmail.com', '$2y$10$Y94VQJ3k8MYSvLQyHCglf.y9ufGp75tqw4/E0TY8nDIhJ8tejMoH6', 1, 'jeesson', 'yman', '', '', 'foto.jpg', 1, '54AgToplYB8V', 'HSkJwnaugvtpQT7', '2022-08-11'),
(2, 'bvq2912@gmail.com', '$2y$10$jbBDC7aM0jYWIQpPm5NbQ.NgP8sSV4n9X9kBs2xRSAgKMhPzY4Jye', 0, 'Caroline Brigitte', 'Rodriguez Quispe', 'mi casa', '987654321', 'perfil.jpg', 1, '82A1WmIbnuVJ', '', '2023-02-24'),
(58, 'estefanysilvestre06@gmail.com', '$2y$10$YiAihWQSo7OQ/e5jLPCwbuzdoVli3qO894lGUX/9zCbjmmroxIl0a', 0, 'Estefany', 'Silvestre', '', '', '', 1, 'azCeNm8EB4Ii', '', '2025-08-25'),
(59, 'LISSETHTARQUI@GMAIL.COM', '$2y$10$C0N2q/UUg1aJjoy2VbbDQ.A1RscCgmLQfGySi8vAetQ3JePsT.y6K', 0, 'yesenia', 'tarqui', '', '', '', 1, 'AiTJVgkDXKyL', '', '2025-08-25'),
(60, 'anthonymeta200@gmail.com', '$2y$10$a/aKA/wRqjWL1CfteljJYOE/pp7EWdenGTdj9WiCYaMCcjfEyMQ1a', 0, 'Anthony', 'Castillo Ramirez', '', '', '', 1, 'Pxt3HSlNnbvV', '', '2025-08-26'),
(62, 'victorvargas0238@gmail.com', '$2y$10$P.HgsSvWhClytRTSsb5Fxul0hs5K4Ex.GZYn9quLbxlmmMttiRWxq', 1, 'victor', 'vargas', '', '', '', 1, 'I7g2CkweEUzu', '', '2025-09-02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme` enum('light','dark') NOT NULL DEFAULT 'light',
  `language` varchar(10) NOT NULL DEFAULT 'es',
  `notifications_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `assigned_at`, `assigned_by`) VALUES
(1, 1, 1, '2025-11-25 05:28:50', NULL),
(2, 62, 1, '2025-11-25 05:28:50', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_details_sales` (`sales_id`),
  ADD KEY `fk_details_product` (`product_id`);

--
-- Indices de la tabla `details_inputs`
--
ALTER TABLE `details_inputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_details_inputs_inputs` (`id_inputs`),
  ADD KEY `fk_details_inputs_product` (`id_products`);

--
-- Indices de la tabla `inputs`
--
ALTER TABLE `inputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inputs_user` (`user_id`),
  ADD KEY `fk_inputs_provider` (`provider_id`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `fk_invoices_sale` (`sale_id`),
  ADD KEY `fk_invoices_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indices de la tabla `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_invoice_items_invoice` (`invoice_id`),
  ADD KEY `fk_invoice_items_product` (`product_id`);

--
-- Indices de la tabla `maria_automations`
--
ALTER TABLE `maria_automations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_maria_automations_user` (`created_by`);

--
-- Indices de la tabla `maria_conversations`
--
ALTER TABLE `maria_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_maria_conversations_user` (`user_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_permission` (`module`,`action`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `fk_products_provider` (`provider_id`);

--
-- Indices de la tabla `provider`
--
ALTER TABLE `provider`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_permission` (`role_id`,`permission_id`),
  ADD KEY `fk_role_permissions_role` (`role_id`),
  ADD KEY `fk_role_permissions_permission` (`permission_id`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sales_user` (`user_id`);

--
-- Indices de la tabla `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `support_comments`
--
ALTER TABLE `support_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_support_comments_ticket` (`ticket_id`),
  ADD KEY `fk_support_comments_user` (`user_id`);

--
-- Indices de la tabla `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_support_tickets_user` (`user_id`),
  ADD KEY `fk_support_tickets_assigned` (`assigned_to`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_role` (`user_id`,`role_id`),
  ADD KEY `fk_user_roles_user` (`user_id`),
  ADD KEY `fk_user_roles_role` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `details`
--
ALTER TABLE `details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT de la tabla `details_inputs`
--
ALTER TABLE `details_inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `inputs`
--
ALTER TABLE `inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maria_automations`
--
ALTER TABLE `maria_automations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `maria_conversations`
--
ALTER TABLE `maria_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `provider`
--
ALTER TABLE `provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `support_comments`
--
ALTER TABLE `support_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `details`
--
ALTER TABLE `details`
  ADD CONSTRAINT `fk_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_details_sales` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `details_inputs`
--
ALTER TABLE `details_inputs`
  ADD CONSTRAINT `fk_details_inputs_inputs` FOREIGN KEY (`id_inputs`) REFERENCES `inputs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_details_inputs_product` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inputs`
--
ALTER TABLE `inputs`
  ADD CONSTRAINT `fk_inputs_provider` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inputs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_sale` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `fk_invoice_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_invoice_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `maria_automations`
--
ALTER TABLE `maria_automations`
  ADD CONSTRAINT `fk_maria_automations_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `maria_conversations`
--
ALTER TABLE `maria_conversations`
  ADD CONSTRAINT `fk_maria_conversations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_products_provider` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `support_comments`
--
ALTER TABLE `support_comments`
  ADD CONSTRAINT `fk_support_comments_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_support_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `fk_support_tickets_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_support_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `fk_user_preferences_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
