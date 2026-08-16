INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('company_ruc', ''),
('company_address', ''),
('company_phone', '')
ON DUPLICATE KEY UPDATE setting_key=setting_key;
