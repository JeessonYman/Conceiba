-- ============================================================
-- Galería de imágenes por producto (múltiples fotos/ángulos)
-- No reemplaza la columna products.photo (que sigue siendo la
-- foto principal/portada) — esto es un complemento.
-- ============================================================

CREATE TABLE IF NOT EXISTS `product_images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
