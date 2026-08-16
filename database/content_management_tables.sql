-- ============================================================
-- Contenido editable de la página de Inicio
-- ============================================================
CREATE TABLE IF NOT EXISTS `home_content` (
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `home_content` (`setting_key`, `setting_value`) VALUES
('about_title', '¿Quiénes somos?'),
('about_text', 'Somos una empresa que aprovecha sosteniblemente la fibra vegetal de kapok, para elaborar artículos textiles. Generamos ingresos en comunidades, y contribuimos a la preservación de bosques secos.'),
('about_image', 'relleno1.jpg'),
('impact_title', 'NUESTRO IMPACTO'),
('allies_title', 'NUESTROS ALIADOS')
ON DUPLICATE KEY UPDATE setting_key=setting_key;

-- Estadísticas de impacto (los 2 recuadros con número + ícono)
CREATE TABLE IF NOT EXISTS `impact_stats` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `impact_stats` (`label`, `image`, `sort_order`) VALUES
('28 familias productoras', 'impacto.png', 1),
('Cientos de árboles puestos en valor', 'impacto2.png', 2);

-- Aliados (logos + redes sociales)
CREATE TABLE IF NOT EXISTS `allies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) DEFAULT NULL,
  `facebook_url` VARCHAR(255) DEFAULT NULL,
  `twitter_url` VARCHAR(255) DEFAULT NULL,
  `instagram_url` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `allies` (`image`, `sort_order`) VALUES
('aliado1.png', 1),
('aliado2.png', 2),
('aliado3.png', 3);

-- ============================================================
-- Contenido editable de la página de Contacto
-- ============================================================
CREATE TABLE IF NOT EXISTS `contact_content` (
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `contact_content` (`setting_key`, `setting_value`) VALUES
('intro_title', 'Envíanos un mensaje'),
('map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3904.711830214369!2d-77.04075408255615!3d-11.855433499999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105d7d4c0fdb70d%3A0xdf26579b5a250cc7!2sCONCEIBA!5e0!3m2!1ses-419!2spe!4v1660168626871!5m2!1ses-419!2spe')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
