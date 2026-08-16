-- ============================================================
-- Especificaciones de hilado (reemplaza la imagen estática de
-- la ficha técnica por datos reales editables desde el admin)
-- ============================================================
CREATE TABLE IF NOT EXISTS `yarn_specs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `description` TEXT,
  `composition` VARCHAR(255) DEFAULT NULL,
  `yarn_title` VARCHAR(100) DEFAULT NULL,
  `presentation` VARCHAR(150) DEFAULT NULL,
  `production` VARCHAR(150) DEFAULT NULL,
  `needles` VARCHAR(255) DEFAULT NULL,
  `weight` VARCHAR(100) DEFAULT NULL,
  `uses` TEXT,
  `care_instructions` TEXT,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `yarn_specs` (`title`, `photo`, `description`, `composition`, `yarn_title`, `presentation`, `production`, `needles`, `weight`, `uses`, `care_instructions`, `sort_order`) VALUES
('HILADO ARTESANAL DE KAPOK', 'hilado-artesanal-de-kapok.jpg', 'Hilado de Fibra antialérgica antibacterial denominada seda vegetal fruto del árbol de ceibo.', '100% fibra vegetal de kapok', '8/2', 'Madeja de 100 gr.', '100% producción artesanal', 'Agujas de galga 5 y galga 7 / Aguja crochet 2.5 y 2.', '100 gr.', 'Tejidos de prendas, accesorios, peluches.', 'Lavado suave a mano. No usar detergente, solo shampú.', 1);

-- ============================================================
-- Artesanas/artesanos de Conceiba (permite agregar más de una)
-- ============================================================
CREATE TABLE IF NOT EXISTS `artisans` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `age` INT(11) DEFAULT NULL,
  `community` VARCHAR(150) DEFAULT NULL,
  `bio` TEXT,
  `photo` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `artisans` (`name`, `age`, `community`, `bio`, `photo`, `sort_order`) VALUES
('Rosita', 59, 'Comunidad de Bolívar, Cajamarca', 'Desde su juventud comenzó a hilar y mantener esta tradición hasta el día de hoy.\n\nCombina esta linda actividad tradicional que realiza con orgullo entre el cuidado de su chacra y su hogar.\n\nDesde el año 2017 integra su producción artesanal de hilado de kapok con Conceiba.', 'Tarjeta-Rosita-artesana-1536x864.jpg', 1);
