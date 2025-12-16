<?php
// L1: Definición de la función para leer la entrada (necesaria en algunos entornos PHP)
if (!function_exists('readline')) {
    function readline($prompt = null) {
        if ($prompt) {
            echo $prompt;
        }
        $fp = fopen("php://stdin", "r");
        $line = rtrim(fgets($fp, 1024));
        return $line;
    }
}

// L2: Pedir la contraseña al usuario
$password_plano = readline("Ingresa la contraseña a hashear (NO se guardará en este archivo): ");

// L3: Verificar si se ingresó algo
if (empty($password_plano)) {
    die("Error: No se ingresó ninguna contraseña.\n");
}

// Generar el hash usando el algoritmo BCRYPT
$hash_seguro = password_hash($password_plano, PASSWORD_BCRYPT);

echo "¡COPIA ESTE HASH! -> " . $hash_seguro . "\n";
?>