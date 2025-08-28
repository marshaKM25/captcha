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

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';

if (empty($nombre) || empty($codigo)) {
    setFlashData('error', 'Debe llenar todos los datos');
    redirect('index.php');
}

$codigoVerificacion = isset($_SESSION['codigo_verificacion']) ? $_SESSION['codigo_verificacion'] : '';
$captcha = sha1($codigo);

if($_SESSION['intentos'] >= $maxIntentos){
    setFlashData('error','Ha superado el número máximo de intentos.');

    redirect('bloqueado.php');
}


if ($codigoVerificacion !== $captcha) {

    $_SESSION['intentos']++;

    $_SESSION['codigo_verificacion'] = '';
    setFlashData('error', 'El código de verificación es incorrecto');
    redirect('index.php');

}else{

    $_SESSION['intentos'] = 0;
    echo "Binvenido, $nombre";
}