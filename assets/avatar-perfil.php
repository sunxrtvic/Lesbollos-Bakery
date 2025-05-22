<?php
// Permite solo archivos con extensiones válidas
$archivo = basename($_GET['archivo'] ?? '');
$extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

//tomamos la extensión que recibimos del archivo
$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

//Si la extensión del archivo no es una de las permitidas, mostrar el mensaje de error
if (!in_array($extension, $extensiones_permitidas)) {
    http_response_code(403);
    exit("Extensión no permitida.");
}

// Ruta dentro del directorio temporal del sistema
$ruta = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $archivo;

//Si el archivo no existe...
if (!file_exists($ruta)) {
    http_response_code(404);
    exit("Archivo no encontrado.");
}

// Establecemos el tipo de archivos queremos recibir
$tipos= [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
];

//Le decimos al navegador que se prepare para recibir archivos de este tipo y extensión
header("Content-Type: " . $tipos[$extension]);
//Le pasamos el peso del archivo
header("Content-Length: " . filesize($ruta));
//Mandamos el archivo a perfil.php para que muestre la nueva foto de perfil
readfile($ruta);
exit;
