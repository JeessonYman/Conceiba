<?php
/**
 * Carga la configuración global del sitio (nombre de tienda, logo,
 * colores, identidad de Maria) desde la tabla site_settings.
 * Se cachea en una variable estática para no consultar la BD
 * más de una vez por request, aunque se incluya varias veces.
 *
 * Uso: $settings = getSiteSettings($pdo);
 *      echo $settings['store_name'];
 */
function getSiteSettings($pdo){
	static $cached = null;

	if($cached !== null){
		return $cached;
	}

	// Valores por defecto (si la tabla no existe todavía o falla la conexión,
	// el sitio sigue funcionando con estos valores tal como estaba antes).
	$defaults = [
		'store_name' => 'Conceiba',
		'store_tagline' => 'La naturaleza en tu habitación',
		'logo' => 'logo.png',
		'color_primary' => '#0f5132',
		'color_primary_dark' => '#0b3d2e',
		'color_accent' => '#e0ac2b',
		'assistant_name' => 'M.A.R.I.A',
		'assistant_avatar' => 'maria-avatar.png',
		'assistant_tagline' => 'Modelo Avanzado de Respuesta e Interacción Automatizada',
		'assistant_tts_backend_url' => '',
		'company_ruc' => '',
		'company_address' => '',
		'company_phone' => '',
	];

	try{
		$conn = $pdo->open();
		$stmt = $conn->prepare("SELECT setting_key, setting_value FROM site_settings");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
		$pdo->close();

		$cached = array_merge($defaults, $rows);
	}
	catch(Exception $e){
		$cached = $defaults;
	}

	return $cached;
}
/**
 * Versión genérica de getSiteSettings() para cualquier tabla
 * clave-valor (home_content, contact_content, etc).
 */
function getKeyValueSettings($pdo, $table, $defaults = []){
	static $cache = [];

	if(isset($cache[$table])){
		return $cache[$table];
	}

	try{
		$conn = $pdo->open();
		$stmt = $conn->prepare("SELECT setting_key, setting_value FROM `".preg_replace('/[^a-zA-Z0-9_]/', '', $table)."`");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
		$pdo->close();

		$cache[$table] = array_merge($defaults, $rows);
	}
	catch(Exception $e){
		$cache[$table] = $defaults;
	}

	return $cache[$table];
}
?>
