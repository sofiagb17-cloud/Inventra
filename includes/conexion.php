<?php
// =========================================
// INVENTRA - Conexión a Base de Datos
// =========================================
// Lee las credenciales desde variables de entorno (seguro para producción)
// Si no existen, usa los valores por defecto de Railway

$host     = getenv('DB_HOST') ?: 'shuttle.proxy.rlwy.net';
$port     = getenv('DB_PORT') ?: '5432';
$dbname   = getenv('DB_NAME') ?: 'inventario_db';
$user     = getenv('DB_USER') ?: 'postgres';
$password = getenv('DB_PASSWORD') ?: '47460';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require");

if (!$conn) {
    die("Error: No se pudo conectar a la base de datos.");
}
?>
