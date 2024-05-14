<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro e Login</title>
    <!-- Seus estilos CSS -->
</head>
<body>
<?php require_once("templates/header.php"); ?>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row" id="auth-row">
            <div class="col-md-4" id="login-container">
                <h2>Entrar</h2>
                <form id="login-form">
                    <div class="form-group">
                        <label for="login-email">E-mail:</label>
                        <input type="email" class="form-control" id="login-email" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Senha:</label>
                        <input type="password" class="form-control" id="login-password" placeholder="Digite sua senha" required>
                    </div>
                    <button type="submit" class="btn card-btn">Entrar</button>
                </form>
            </div>
            <div class="col-md-4" id="register-container">
                <h2>Criar Conta</h2>
                <form id="register-form">
                    <div class="form-group">
                        <label for="register-email">E-mail:</label>
                        <input type="email" class="form-control" id="register-email" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="register-name">Nome:</label>
                        <input type="text" class="form-control" id="register-name" placeholder="Digite seu nome" required>
                    </div>
                    <div class="form-group">
                        <label for="register-lastname">Sobrenome:</label>
                        <input type="text" class="form-control" id="register-lastname" placeholder="Digite seu sobrenome" required>
                    </div>
                    <div class="form-group">
                        <label for="register-password">Senha:</label>
                        <input type="password" class="form-control" id="register-password" placeholder="Digite sua senha" required>
                    </div>
                    <div class="form-group">
                        <label for="register-confirmpassword">Confirmação de senha:</label>
                        <input type="password" class="form-control" id="register-confirmpassword" placeholder="Confirme sua senha" required>
                    </div>
                    <button type="submit" class="btn card-btn">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once("templates/footer.php"); ?>

<script>
    // Função para enviar uma requisição POST com AJAX
    function enviarRequisicaoPOST(url, data, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(xhr.responseText);
            }
        };
        xhr.send(JSON.stringify(data));
    }

    // Manipulador de evento para o formulário de login
    document.getElementById("login-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        var email = document.getElementById("login-email").value;
        var password = document.getElementById("login-password").value;

        // Dados para o login de usuário
        var loginData = {
            email: email,
            password: password
        };

        // Enviar requisição POST para fazer login
        enviarRequisicaoPOST("http://localhost:8080/usuario/login", loginData, function (response) {
            // Lógica para manipular a resposta do servidor após o login
            console.log("Resposta do login: ", response);
        });
    });

    // Manipulador de evento para o formulário de cadastro
    document.getElementById("register-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        var email = document.getElementById("register-email").value;
        var name = document.getElementById("register-name").value;
        var lastname = document.getElementById("register-lastname").value;
        var password = document.getElementById("register-password").value;
        var confirmPassword = document.getElementById("register-confirmpassword").value;

        // Verifica se as senhas coincidem
        if (password !== confirmPassword) {
            alert("As senhas não coincidem.");
            return;
        }

        // Dados para o cadastro de usuário
        var registerData = {
            email: email,
            name: name,
            lastname: lastname,
            password: password
        };

        // Enviar requisição POST para cadastrar um usuário
        enviarRequisicaoPOST("http://localhost:8080/usuario", registerData, function (response) {
            // Lógica para manipular a resposta do servidor após o cadastro
            console.log("Resposta do cadastro: ", response);
        });
    });
</script>
</body>
</html>
