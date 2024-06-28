<?php
session_start();

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
    <!-- ESTILOS DO PROJETO -->
    <link rel="stylesheet" href="<?= $BASE_URL ?>/css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.css"
          integrity="sha512-lp6wLpq/o3UVdgb9txVgXUTsvs0Fj1YfelAbza2Kl/aQHbNnfTYPMLiQRvy3i+3IigMby34mtcvcrh31U50nRw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= $BASE_URL ?>">
                <img src="<?= $BASE_URL ?>/img/logo.svg" alt="Alto Vale News" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $BASE_URL ?>">Home</a>
                    </li>
                    <li class="nav-item" id="editorpannel-btn" style="display: none;">
                        <a class="nav-link" href="<?= $BASE_URL ?>editorpannel.php">Painel do Editor</a>
                    </li>
                    <li class="nav-item" id="adminpannel-btn" style="display: none;">
                        <a class="nav-link" href="<?= $BASE_URL ?>adminpannel.php">Painel do Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $BASE_URL ?>contato.php">Contato</a>
                    </li>
                    <li class="nav-item" id="editprofile-btn" style="display: none;">
                        <a class="nav-link" href="<?= $BASE_URL ?>editprofile.php">Editar Perfil</a>
                    </li>
                    <li class="nav-item" id="logout-btn" style="display: none;">
                        <a class="nav-link" href="<?= $BASE_URL ?>logout.php">Sair</a>
                    </li>
                    <li class="nav-item" id="login-btn">
                        <a class="nav-link" href="<?= $BASE_URL ?>auth.php">Cadastro/Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.js"
        integrity="sha512-S6yRqs19PDY9UgEFlP/a70oEXchz3g5LChtboBtqTgrPOemOAw8NJ1mA5ZYDUZKq1E7s1suFMtwaUWKmMK7vkQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    // Verificar se o usuário está autenticado no localStorage
    var autenticado = localStorage.getItem("autenticado") === "true";
    var usuario = JSON.parse(localStorage.getItem("usuario")) || {};

    // Atualizar dinamicamente a exibição dos botões com base na autenticação
    var logoutBtn = document.getElementById("logout-btn");
    var loginBtn = document.getElementById("login-btn");
    var editprofileBtn = document.getElementById("editprofile-btn");
    var editorpannelBtn = document.getElementById("editorpannel-btn");
    var adminpannelBtn = document.getElementById("adminpannel-btn");

    if (autenticado) {
        logoutBtn.style.display = "block";
        loginBtn.style.display = "none";
        editprofileBtn.style.display = "block";
        if (usuario.tipo === 2 || usuario.tipo === 3) {
            editorpannelBtn.style.display = "block";
            if (usuario.tipo === 3){
                adminpannelBtn.style.display = "block";
            }
        } else {
            editorpannelBtn.style.display = "none";
            adminpannelBtn.style.display = "none";
        }
    } else {
        logoutBtn.style.display = "none";
        loginBtn.style.display = "block";
        editprofileBtn.style.display = "none";
        editorpannelBtn.style.display = "none";
        adminpannelBtn.style.display = "none";
    }
</script>
</html>
