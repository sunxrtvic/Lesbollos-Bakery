<?php
// Permite solo archivos con extensiones válidas
$archivo = basename($_GET['archivo'] ?? '');
$extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

if (!in_array($extension, $extensiones_permitidas)) {
    http_response_code(403);
    exit("Extensión no permitida.");
}

// Ruta segura dentro del directorio temporal del sistema
$ruta = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $archivo;

if (!file_exists($ruta)) {
    http_response_code(404);
    exit("Archivo no encontrado.");
}

// Establece el tipo de imagen permitidos
$tipos= [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
];

header("Content-Type: " . $tipos_mime[$extension]);
header("Content-Length: " . filesize($ruta));
readfile($ruta);
exit;
