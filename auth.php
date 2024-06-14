<?php
require_once("templates/header.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro e Login</title>
</head>
<body>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Entrar</h2>
                    <form id="login-form">
                        <div class="form-group">
                            <label for="login-email">E-mail:</label>
                            <input type="email" class="form-control" id="login-email" placeholder="Digite seu e-mail"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Senha:</label>
                            <input type="password" class="form-control" id="login-password"
                                   placeholder="Digite sua senha"
                                   required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </div>
                        <div id="login-error" class="alert alert-danger" style="display: none;">
                            Usuário ou senha inválidos, tente novamente.
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Criar Conta</h2>
                    <form id="register-form">
                        <div class="form-group">
                            <label for="register-email">E-mail:</label>
                            <input type="email" class="form-control" id="register-email"
                                   placeholder="Digite seu e-mail" required>
                        </div>
                        <div class="form-group">
                            <label for="register-name">Nome:</label>
                            <input type="text" class="form-control" id="register-name"
                                   placeholder="Digite seu nome" required>
                        </div>
                        <div class="form-group">
                            <label for="register-lastname">Sobrenome:</label>
                            <input type="text" class="form-control" id="register-lastname"
                                   placeholder="Digite seu sobrenome" required>
                        </div>
                        <div class="form-group">
                            <label for="register-cpf">CPF:</label>
                            <input type="text" class="form-control" id="register-cpf" placeholder="Digite seu CPF"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="register-endereco">Endereço:</label>
                            <input type="text" class="form-control" id="register-endereco"
                                   placeholder="Digite seu endereço" required>
                        </div>
                        <div class="form-group">
                            <label for="register-cidade">Cidade:</label>
                            <input type="text" class="form-control" id="register-cidade"
                                   placeholder="Digite sua cidade" required>
                        </div>
                        <div class="form-group">
                            <label for="register-estado">Estado:</label>
                            <input type="text" class="form-control" id="register-estado"
                                   placeholder="Digite seu estado" required>
                        </div>
                        <div class="form-group">
                            <label for="register-cep">CEP:</label>
                            <input type="text" class="form-control" id="register-cep" placeholder="Digite seu CEP"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Senha:</label>
                            <input type="password" class="form-control" id="register-password"
                                   placeholder="Digite sua senha" required>
                        </div>
                        <div class="form-group">
                            <label for="register-confirmpassword">Confirmação de senha:</label>
                            <input type="password" class="form-control" id="register-confirmpassword"
                                   placeholder="Confirme sua senha" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                        <div id="register-error" class="alert alert-danger" style="display: none;">
                            Erro interno, por favor tente novamente mais tarde ou contate o administrador do portal.
                        </div>
                        <div id="cpf-error" class="alert alert-danger" style="display: none;">
                            CPF inválido. Por favor, verifique e tente novamente.
                        </div>
                        <div id="email-error" class="alert alert-danger" style="display: none;">
                            E-mail já cadastrado. Por favor, use outro e-mail.
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para enviar uma requisição POST com AJAX
    function enviarRequisicaoPOST(url, data, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log("Status:", xhr.status);
                console.log("Response:", xhr.responseText);
                callback(xhr.status, xhr.responseText);
            }
        };
        xhr.send(JSON.stringify(data));
    }

    // Função para exibir e ocultar mensagens de erro
    function mostrarMensagemErro(elemento, mensagem) {
        elemento.style.display = "block";
        elemento.innerHTML = mensagem;
        setTimeout(function() {
            elemento.style.display = "none";
        }, 5000);
    }

    // Manipulador de evento para o formulário de login
    document.getElementById("login-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        var email = document.getElementById("login-email").value;
        var password = document.getElementById("login-password").value;

        // Dados para o login de usuário
        var loginData = {
            email: email,
            senha: password
        };

        // Enviar requisição POST para fazer login
        enviarRequisicaoPOST("http://localhost:8080/usuario/login", loginData, function (status, response) {
            if (status === 200) {
                // Autenticação bem-sucedida, acessar detalhes do usuário
                var usuario = JSON.parse(response);
                console.log("Usuário autenticado: ", usuario);
                // Armazenar dados do usuário no localStorage
                localStorage.setItem("usuario", JSON.stringify(usuario));
                // Definir sinalizador de autenticação
                localStorage.setItem("autenticado", "true");
                // Redireciona para o index
                window.location.href = "index.php";
            } else {
                // Exibir mensagem de erro de autenticação
                var loginError = document.getElementById("login-error");
                mostrarMensagemErro(loginError, "Usuário ou senha inválidos, tente novamente.");
                // Autenticação falhou
                console.log("Autenticação falhou");
                // Definir sinalizador de autenticação como falso
                localStorage.setItem("autenticado", "false");
            }
        });
    });

    // Manipulador de evento para o formulário de cadastro
    document.getElementById("register-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        var email = document.getElementById("register-email").value;
        var name = document.getElementById("register-name").value;
        var lastname = document.getElementById("register-lastname").value;
        var cpf = document.getElementById("register-cpf").value;
        var endereco = document.getElementById("register-endereco").value;
        var cidade = document.getElementById("register-cidade").value;
        var estado = document.getElementById("register-estado").value;
        var cep = document.getElementById("register-cep").value;
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
            nome: name,
            sobrenome: lastname,
            cpf: cpf,
            endereco: endereco,
            cidade: cidade,
            estado: estado,
            cep: cep,
            senha: password,
            tipo: 0
        };

        // Enviar requisição POST para cadastrar um usuário
        enviarRequisicaoPOST("http://localhost:8080/usuario", registerData, function (status, response) {
            if (status === 201) {
                // Cadastro bem-sucedido, acessar detalhes do usuário
                var usuario = JSON.parse(response);
                console.log("Usuário cadastrado: ", usuario);

                // Armazenar dados do usuário no localStorage
                localStorage.setItem("usuario", JSON.stringify(usuario));
                // Definir sinalizador de cadastro bem-sucedido
                localStorage.setItem("autenticado", "true");

                // Redirecionar para index.php após o cadastro bem-sucedido
                window.location.href = "index.php";
            } else if (status === 400) {
                // CPF inválido
                var cpfError = document.getElementById("cpf-error");
                mostrarMensagemErro(cpfError, "CPF inválido. Por favor, verifique e tente novamente.");
            } else if (status === 409) {
                // E-mail já cadastrado
                var emailError = document.getElementById("email-error");
                mostrarMensagemErro(emailError, "E-mail já cadastrado. Por favor, use outro e-mail.");
            } else {
                // Outro erro
                var registerError = document.getElementById("register-error");
                mostrarMensagemErro(registerError, "Erro interno, por favor tente novamente mais tarde ou contate o administrador do portal.");
                console.log("Cadastro falhou");
                localStorage.setItem("autenticado", "false");
            }
        });
    });
</script>
</body>
<?php require_once("templates/footer.php"); ?>
</html>
