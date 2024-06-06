<?php
require_once("templates/header.php");

$_SESSION = array();

session_destroy();

error_log("Sessão iniciada");
error_log("Variáveis de sessão limpas");
error_log("Sessão destruída");

echo '<script>';
echo 'localStorage.removeItem("usuario");';
echo 'localStorage.setItem("autenticado", "false");';
echo 'window.location.href = "index.php";';
echo '</script>';

exit;
?>
