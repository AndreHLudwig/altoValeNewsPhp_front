<?php
session_start();
require_once("templates/header.php");
echo "Sessão iniciada<br>";

$_SESSION = array(); // limpa a sessão
echo "Variáveis de sessão limpas<br>";

session_destroy();
echo "Sessão destruída<br>";

echo '<script>';
echo 'localStorage.removeItem("usuario");';
echo 'localStorage.setItem("autenticado", "false");';
echo 'window.location.href = "index.php";'; // Redireciona para a página inicial
echo '</script>';
echo "Dados do localStorage limpos e redirecionamento iniciado<br>";

exit;
?>
