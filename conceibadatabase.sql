-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2025 a las 19:49:08
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
-- Estructura de tabla para la tabla `cart`
--
-- Error leyendo la estructura de la tabla conceibadatabase.cart: #1932 - Table &#039;conceibadatabase.cart&#039; doesn&#039;t exist in engine
-- Error leyendo datos de la tabla conceibadatabase.cart: #1064 - Algo está equivocado en su sintax cerca &#039;FROM `conceibadatabase`.`cart`&#039; en la linea 1

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
(1, 3, 1, 'Cojín bordado Circular', '<p>Coj&iacute;n bordado relleno de kapok.</p>\r\n', 'cojin-bordado-circular', 150, 150, 150, 150, 15, 'cojin-bordado-circular.png', '2025-10-30', 1),
(2, 3, 1, 'Cojín bordado eliseos', '<p>Coj&iacute;n decorativo bordado relleno de kapok.</p>\r\n', 'cojin-bordado-eliseos', 150, 150, 150, 162, 15, 'cojin-bordado-eliseos.png', '2025-10-01', 0),
(3, 3, 1, 'Cojín bordado Hoja', '<p>Coj&iacute;n bordado relleno de kapok.</p>\r\n', 'cojin-bordado-hoja', 150, 150, 150, 150, 15, 'cojin-bordado-hoja.png', '2025-10-01', 0),
(4, 3, 1, 'Cojín bordado Jardín', '<p>Coj&iacute;n bordado relleno de kapok.</p>\r\n', 'cojin-bordado-jardin', 150, 150, 150, 150, 15, 'cojin-bordado-jardin.png', '2025-10-30', 1),
(5, 3, 1, 'Cojín decorativo Arabic', '<p>Coj&iacute;n decorativo relleno de kapok.</p>\r\n', 'cojin-decorativo-arabic', 110, 110, 110, 110, 11, 'cojin-decorativo-arabic.png', '2025-10-01', 0),
(6, 3, 1, 'Cojín decorativo Florinda', '<p>Coj&iacute;n decorativo relleno de kapok</p>\r\n', 'cojin-decorativo-florinda', 110, 110, 110, 110, 11, 'cojin-decorativo-florinda.png', '2025-10-01', 0),
(7, 3, 1, 'Cojín decorativo Tulipán', '<p>Coj&iacute;n decorativo relleno de kapok.</p>\r\n', 'cojin-decorativo-tulipan', 110, 110, 110, 110, 11, 'cojin-decorativo-tulipan.png', '2025-10-01', 0),
(8, 2, 1, 'Coneja oreja larga', '<p>Peluche relleno de la fibra natural del kapok.</p>\r\n', 'coneja-oreja-larga', 90, 90, 90, 0, 9, 'coneja-oreja-larga.png', '2025-10-28', 1),
(9, 2, 1, 'Conejita', '<p>Peluche relleno de la fibra natural del kapok.</p>\r\n', 'conejita', 80, 80, 80, 80, 8, 'conejita.png', '2025-10-01', 0),
(10, 1, 1, 'Sombrero de Kapok y ovino', '<p>Sombrero de kapok</p>\r\n', 'sombrero-de-kapok-y-ovino', 170, 170, 170, 0, 17, 'sombrero-de-kapok-y-ovino.png', '2025-10-24', 1),
(11, 1, 1, 'Sombrero de kapok y ovino bicolor', '<p>Sombrero de kapok</p>\r\n', 'sombrero-de-kapok-y-ovino-bicolor', 170, 170, 170, 0, 17, 'sombrero-de-kapok-y-ovino-bicolor.png', '2025-11-07', 1),
(12, 1, 1, 'Sombrero de kapok y ovino blanco', '<p>Sombrero de kapok</p>\r\n', 'sombrero-de-kapok-y-ovino-blanco', 170, 170, 170, 170, 17, 'sombrero-de-kapok-y-ovino-blanco.png', '2025-11-21', 1),
(13, 2, 1, 'Oso de peluche', '<p><strong>Peluche relleno de la fibra natural del kapok.</strong></p>\r\n', 'oso-de-peluche', 100, 100, 100, 100, 10, 'oso-de-peluche.jpg', '2025-10-01', 0),
(14, 4, 1, 'Hilado artesanal de kapok', '<h3><strong>Hilado vegetal antial&eacute;rgica, anti &aacute;caros de kapok</strong></h3>\r\n', 'hilado-artesanal-de-kapok', 60, 60, 60, 60, 6, 'hilado-artesanal-de-kapok.jpg', '2025-11-06', 1);

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
(4, 'jeessonyman1234@gmail.com', 1, '2025-11-07 23:57:08');

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
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_products_provider` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
