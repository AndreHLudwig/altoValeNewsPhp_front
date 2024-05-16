<?php
session_start();

// Inclua os arquivos necessários
include_once("helpers/url.php");
include_once("data/posts.php");
include_once("data/categories.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alto Vale News</title>
    <!--ESTILOS DO PROJETO-->
    <link rel="stylesheet" href="<?= $BASE_URL ?>/css/styles.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.css"
          integrity="sha512-drnvWxqfgcU6sLzAJttJv7LKdjWn0nxWCSbEAtxJ/YYaZMyoNLovG7lPqZRdhgL1gAUfa+V7tbin8y+2llC1cw=="
          crossorigin="anonymous"/>
    <!--GOOGLE FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
</head>
<body>
<header>
    <a href="<?= $BASE_URL ?>" id="logo">
        <img src="<?= $BASE_URL ?>/img/logo.svg" alt="Alto Vale News">
    </a>
    <nav>
        <ul id="navbar">
            <li><a href="<?= $BASE_URL ?>" class="nav-link">Home</a></li>
            <li><a href="#" class="nav-link">Cultura</a></li>
            <li><a href="#" class="nav-link">Economia</a></li>
            <li><a href="#" class="nav-link">Esportes</a></li>
            <li><a href="#" class="nav-link">Política</a></li>
            <li><a href="#" class="nav-link">Regionais</a></li>
            <li><a href="<?= $BASE_URL ?>contato.php" class="nav-link">Contato</a></li>
            <li id="editprofile-btn" style="display: none;"><a href="<?= $BASE_URL ?>editprofile.php" class="nav-link">Editar Perfil</a></li>
            <li id="logout-btn" style="display: none;"><a href="<?= $BASE_URL ?>logout.php" class="nav-link">Sair</a></li>
            <li id="login-btn"><a href="<?= $BASE_URL ?>auth.php" class="nav-link">Cadastro/Login</a></li>
        </ul>
    </nav>
</header>

<script>
    // Verificar se o usuário está autenticado no localStorage
    var autenticado = localStorage.getItem("autenticado") === "true";

    // Atualizar dinamicamente a exibição dos botões com base na autenticação
    var logoutBtn = document.getElementById("logout-btn");
    var loginBtn = document.getElementById("login-btn");
    var editprofileBtn = document.getElementById("editprofile-btn");

    if (autenticado) {
        logoutBtn.style.display = "block"; // Exibir o botão Sair se o usuário estiver autenticado
        loginBtn.style.display = "none"; // Ocultar o botão Cadastro/Login se o usuário estiver autenticado
        editprofileBtn.style.display = "block"; // Exibir o botão Editar Perfil se o usuário estiver autenticado
    } else {
        logoutBtn.style.display = "none"; // Ocultar o botão Sair se o usuário não estiver autenticado
        loginBtn.style.display = "block"; // Exibir o botão Cadastro/Login se o usuário não estiver autenticado
        editprofileBtn.style.display = "none"; // Ocultar o botão Editar Perfil se o usuário não estiver autenticado
    }
</script>
</body>
</html>