-- ============================================================
-- LIMPIEZA DE DUPLICADOS
-- Causa: los archivos content_tables.sql y content_management_tables.sql
-- se importaron más de una vez. Esas tablas no tenían protección contra
-- duplicados (a diferencia de site_settings/home_content que sí la tienen).
-- Este script conserva solo el registro más antiguo (menor id) de cada
-- grupo duplicado y borra el resto.
-- ============================================================

-- Aliados duplicados (mismo image = mismo aliado)
DELETE a1 FROM allies a1
INNER JOIN allies a2
WHERE a1.id > a2.id
AND a1.image = a2.image;

-- Estadísticas de impacto duplicadas (mismo label)
DELETE i1 FROM impact_stats i1
INNER JOIN impact_stats i2
WHERE i1.id > i2.id
AND i1.label = i2.label;

-- Fichas de hilado duplicadas (mismo title)
DELETE y1 FROM yarn_specs y1
INNER JOIN yarn_specs y2
WHERE y1.id > y2.id
AND y1.title = y2.title;

-- Artesanos duplicados (mismo name)
DELETE ar1 FROM artisans ar1
INNER JOIN artisans ar2
WHERE ar1.id > ar2.id
AND ar1.name = ar2.name;
