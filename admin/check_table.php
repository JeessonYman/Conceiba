<?php
require_once 'Database.php';

echo "<h2>🔍 Diagnóstico de Estructura de Base de Datos</h2>";

try {
    $db = new Database();
    
    // 1. Verificar qué tablas existen
    echo "<h3>1. Tablas existentes:</h3>";
    $tablas = $db->fetchAll("SHOW TABLES");
    foreach ($tablas as $tabla) {
        $nombreTabla = array_values($tabla)[0];
        echo "<p>• $nombreTabla</p>";
    }
    
    // 2. Verificar estructura de tabla perfiles
    echo "<h3>2. Estructura de tabla 'perfiles':</h3>";
    $estructuraPerfiles = $db->fetchAll("DESCRIBE perfiles");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Clave</th><th>Default</th><th>Extra</th></tr>";
    foreach ($estructuraPerfiles as $campo) {
        echo "<tr>
                <td>{$campo['Field']}</td>
                <td>{$campo['Type']}</td>
                <td>{$campo['Null']}</td>
                <td>{$campo['Key']}</td>
                <td>{$campo['Default']}</td>
                <td>{$campo['Extra']}</td>
              </tr>";
    }
    echo "</table>";
    
    // 3. Verificar claves foráneas de la tabla perfiles
    echo "<h3>3. Claves foráneas de 'perfiles':</h3>";
    $foreignKeys = $db->fetchAll("
        SELECT 
            CONSTRAINT_NAME,
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'point_digital' 
        AND TABLE_NAME = 'perfiles' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    if (!empty($foreignKeys)) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Constraint</th><th>Columna</th><th>Tabla Referenciada</th><th>Columna Referenciada</th></tr>";
        foreach ($foreignKeys as $fk) {
            echo "<tr>
                    <td>{$fk['CONSTRAINT_NAME']}</td>
                    <td>{$fk['COLUMN_NAME']}</td>
                    <td style='color: red;'>{$fk['REFERENCED_TABLE_NAME']}</td>
                    <td>{$fk['REFERENCED_COLUMN_NAME']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron claves foráneas</p>";
    }
    
    // 4. Verificar si existe tabla 'cuentas'
    echo "<h3>4. Verificación de tabla 'cuentas':</h3>";
    try {
        $cuentas = $db->fetchAll("SELECT COUNT(*) as total FROM cuentas LIMIT 1");
        echo "<p style='color: green;'>✅ La tabla 'cuentas' existe</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ La tabla 'cuentas' NO existe</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    
    // 5. Verificar tabla 'cuentas_proveedor'
    echo "<h3>5. Verificación de tabla 'cuentas_proveedor':</h3>";
    try {
        $cuentasProveedor = $db->fetchAll("SELECT COUNT(*) as total FROM cuentas_proveedor LIMIT 1");
        echo "<p style='color: green;'>✅ La tabla 'cuentas_proveedor' existe</p>";
        
        // Mostrar algunas cuentas
        $ejemplos = $db->fetchAll("SELECT id, usuario FROM cuentas_proveedor LIMIT 5");
        echo "<p><strong>Ejemplos de cuentas:</strong></p>";
        foreach ($ejemplos as $cuenta) {
            echo "<p>• ID {$cuenta['id']}: {$cuenta['usuario']}</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error con 'cuentas_proveedor': " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general: " . $e->getMessage() . "</p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { margin: 10px 0; border-collapse: collapse; width: 100%; }
    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #f5f5f5; }
</style>
