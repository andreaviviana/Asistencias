<?php
// Contraseña en texto plano
$password_plano = "Profe123"; 

// Generar el hash usando el algoritmo BCRYPT
$hash_seguro = password_hash($password_plano, PASSWORD_BCRYPT);

echo "¡COPIA ESTE HASH! -> " . $hash_seguro;
?>