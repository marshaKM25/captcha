<?php

/**
 * Este script PHP maneja la verificación de un código ingresado en un formulario.
 * Requiere el uso de sesiones y funciones auxiliares definidas en 'funcs/funcs.php'.
**/

session_start();

$maxIntentos = 3;

if(!isset($_SESSION['intentos'])){
    $_SESSION['intentos'] = 0;
}

include_once 'funcs/funcs.php';

//revisa si la variable existe y no es null
//si no hay valor entonces se le asigna ''
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$contraseña = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

//verifica si no es null,'', false
if (empty($nombre) || empty($codigo) || empty($contraseña)) {
    setFlashData('error', 'Debe llenar todos los datos');
    redirect('index.php');
}

$codigoVerificacion = isset($_SESSION['codigo_verificacion']) ? $_SESSION['codigo_verificacion'] : '';
$captcha = sha1($codigo);

// Verificamos si ya llegó al máximo de intentos
if($_SESSION['intentos'] >= $maxIntentos){
    setFlashData('error','Ha superado el número máximo de intentos.');
    redirect('bloqueado.php');
}

if ($codigoVerificacion !== $captcha) {
    // Aumentamos el número de intentos
    $_SESSION['intentos']++;

    $_SESSION['codigo_verificacion'] = '';

    // Incluimos los intentos en el mensaje
    setFlashData('error', 'El código de verificación es incorrecto. Intento número: ' . $_SESSION['intentos']);
    redirect('index.php');

} else {
    // Si acierta, reiniciamos los intentos
    $_SESSION['intentos'] = 0;
    echo "Bienvenido, $nombre";
}
