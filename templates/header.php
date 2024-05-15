<?php

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
            <li><a href="#" class="nav-link">Educação</a></li>
            <li><a href="#" class="nav-link">Esportes</a></li>
            <li><a href="#" class="nav-link">Política</a></li>
            <li><a href="#" class="nav-link">Regionais</a></li>
            <li><a href="<?= $BASE_URL ?>contato.php" class="nav-link">Contato</a></li>
            <!-- Adicionando o link para a página de cadastro e login -->
            <li><a href="<?= $BASE_URL ?>auth.php" class="nav-link">Cadastro/Login</a></li>
        </ul>
    </nav>
</header>
