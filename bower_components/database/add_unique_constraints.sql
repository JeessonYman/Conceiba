-- ============================================================
-- Prevenir duplicados a futuro: agrega restricciones únicas.
-- Corre esto UNA vez, ahora que ya limpiaste los duplicados
-- manualmente (si quedara algún duplicado, este script fallará
-- con un error claro indicando cuál — límpialo y vuelve a intentar).
-- ============================================================

ALTER TABLE `artisans` ADD UNIQUE KEY `uniq_artisan_name` (`name`);
ALTER TABLE `yarn_specs` ADD UNIQUE KEY `uniq_yarn_title` (`title`);
ALTER TABLE `impact_stats` ADD UNIQUE KEY `uniq_impact_label` (`label`(191));
ALTER TABLE `allies` ADD UNIQUE KEY `uniq_ally_image` (`image`);
