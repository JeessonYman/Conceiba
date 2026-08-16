-- ============================================================
-- Borra las filas duplicadas más nuevas (conserva la más antigua,
-- id=1, en cada tabla). Más simple y seguro que el script anterior
-- ya que confirmamos que solo hay 1 duplicado por tabla.
-- Revisa antes con SELECT * FROM <tabla>; que los id sean correctos
-- para tu caso -- si tienes más de un duplicado o ids distintos,
-- ajusta el WHERE según lo que veas en phpMyAdmin.
-- ============================================================

DELETE FROM artisans WHERE id = 2;
DELETE FROM yarn_specs WHERE id = 2;
DELETE FROM impact_stats WHERE id > 2;   -- esta tabla tiene 2 filas originales (no 1), borra desde la 3ra en adelante
DELETE FROM allies WHERE id > 3;         -- esta tabla tiene 3 filas originales, borra desde la 4ta en adelante
