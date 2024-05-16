<?php

// Inclua os arquivos necessários
include_once("helpers/url.php");
include_once("templates/header.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Alto Vale News</title>
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

<div class="container mt-5">
    <h1>Editar Perfil</h1>
    <!-- Formulário de edição de perfil -->
    <form id="editProfileForm">
        <div class="form-group">
            <label for="endereco">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" required>
        </div>
        <div class="form-group">
            <label for="cidade">Cidade</label>
            <input type="text" class="form-control" id="cidade" name="cidade" required>
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <input type="text" class="form-control" id="estado" name="estado" required>
        </div>
        <div class="form-group">
            <label for="cep">CEP</label>
            <input type="text" class="form-control" id="cep" name="cep" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
    </form>
</div>

<script>
    document.getElementById("editProfileForm").addEventListener("submit", function(event) {
        event.preventDefault();

        var usuario = JSON.parse(localStorage.getItem("usuario")); // Obtém o objeto usuario do localStorage

        if (!usuario || !usuario.userId) {
            alert("Usuário não autenticado.");
            return;
        }

        var userId = usuario.userId;
        var endereco = document.getElementById("endereco").value;
        var cidade = document.getElementById("cidade").value;
        var estado = document.getElementById("estado").value;
        var cep = document.getElementById("cep").value;
        var senha = document.getElementById("senha").value || null;

        var payload = {
            endereco: endereco,
            cidade: cidade,
            estado: estado,
            cep: cep,
            senha: senha
        };

        fetch(`http://localhost:8080/usuario/${userId}`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            alert("Perfil atualizado com sucesso!");

            // Atualizar localStorage com os dados retornados
            localStorage.setItem("usuario", JSON.stringify(data));

            // Atualizar a exibição dos dados do usuário, se necessário
        })
        .catch(error => {
            console.error("Erro:", error);
            alert("Ocorreu um erro ao atualizar o perfil.");
        });
    });

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
