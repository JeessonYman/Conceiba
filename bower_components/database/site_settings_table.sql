-- ============================================================
-- Configuración global del sitio (nombre de tienda, logo,
-- paleta de colores, identidad del asistente IA)
-- Diseño clave-valor: fácil de extender sin migraciones futuras.
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('store_name', 'Conceiba'),
('store_tagline', 'La naturaleza en tu habitación'),
('logo', 'logo.png'),
('color_primary', '#0f5132'),
('color_primary_dark', '#0b3d2e'),
('color_accent', '#e0ac2b'),
('assistant_name', 'M.A.R.I.A'),
('assistant_avatar', 'maria-avatar.png'),
('assistant_tagline', 'Modelo Avanzado de Respuesta e Interacción Automatizada'),
('assistant_tts_backend_url', '')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
