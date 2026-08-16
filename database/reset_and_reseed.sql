-- ============================================================
-- LIMPIEZA DEFINITIVA (vaciar y volver a sembrar una sola vez)
-- Usa esto si cleanup_duplicates.sql no resolvió el problema
-- (puede pasar si los duplicados ya no tienen valores idénticos,
-- por ejemplo si algunos se agregaron luego desde el admin).
--
-- ADVERTENCIA: esto borra TODO el contenido actual de estas 4
-- tablas y lo reemplaza por un único registro base de cada una.
-- Si ya agregaste aliados/artesanos/estadísticas nuevas desde el
-- admin que quieres conservar, NO corras esto — mejor bórralos
-- manualmente uno por uno desde el panel.
-- ============================================================

TRUNCATE TABLE `allies`;
TRUNCATE TABLE `impact_stats`;
TRUNCATE TABLE `yarn_specs`;
TRUNCATE TABLE `artisans`;

INSERT INTO `impact_stats` (`label`, `image`, `sort_order`) VALUES
('28 familias productoras', 'impacto.png', 1),
('Cientos de árboles puestos en valor', 'impacto2.png', 2);

INSERT INTO `allies` (`image`, `sort_order`) VALUES
('aliado1.png', 1),
('aliado2.png', 2),
('aliado3.png', 3);

INSERT INTO `yarn_specs` (`title`, `photo`, `description`, `composition`, `yarn_title`, `presentation`, `production`, `needles`, `weight`, `uses`, `care_instructions`, `sort_order`) VALUES
('HILADO ARTESANAL DE KAPOK', 'hilado-artesanal-de-kapok.jpg', 'Hilado de Fibra antialérgica antibacterial denominada seda vegetal fruto del árbol de ceibo.', '100% fibra vegetal de kapok', '8/2', 'Madeja de 100 gr.', '100% producción artesanal', 'Agujas de galga 5 y galga 7 / Aguja crochet 2.5 y 2.', '100 gr.', 'Tejidos de prendas, accesorios, peluches.', 'Lavado suave a mano. No usar detergente, solo shampú.', 1);

INSERT INTO `artisans` (`name`, `age`, `community`, `bio`, `photo`, `sort_order`) VALUES
('Rosita', 59, 'Comunidad de Bolívar, Cajamarca', 'Desde su juventud comenzó a hilar y mantener esta tradición hasta el día de hoy.\n\nCombina esta linda actividad tradicional que realiza con orgullo entre el cuidado de su chacra y su hogar.\n\nDesde el año 2017 integra su producción artesanal de hilado de kapok con Conceiba.', 'Tarjeta-Rosita-artesana-1536x864.jpg', 1);
