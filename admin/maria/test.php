<?php
// Script de prueba rápido para comprobar endpoints
echo "Probando admin/maria endpoints...\n";
$base = dirname(__FILE__);
echo "GET top_products (mes actual)\n";
$res = file_get_contents($base . '/data.php?type=top_products&period=month');
echo $res . "\n";

echo "POST interpretar top_products\n";
$payload = json_encode(['question'=>'Resume top productos','type'=>'top_products','period'=>'month']);
$opts = [
  'http' => [
    'method'  => 'POST',
    'header'  => "Content-Type: application/json\r\n",
    'content' => $payload,
    'timeout' => 30
  ]
];
$context  = stream_context_create($opts);
$res2 = file_get_contents($base . '/interpret.php', false, $context);
echo $res2 . "\n";

?>
