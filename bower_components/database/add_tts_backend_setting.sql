-- Agrega el nuevo ajuste "URL del backend de voz (Colab/XTTS)" a una
-- instalación que ya tenía site_settings creada antes de esta actualización.
INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('assistant_tts_backend_url', '')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
