<?php
include_once("templates/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Alto Vale News</title>
    <style>
        /* Estilos para campos readonly */
        .readonly-field {
            background-color: #f0f0f0; /* Cor de fundo para campos readonly */
            border: 1px solid #ccc; /* Borda para campos readonly */
            color: #666; /* Cor do texto para campos readonly */
        }

        /* Estilos para campos editáveis */
        .editable-field {
            background-color: #fff; /* Cor de fundo para campos editáveis */
            border: 1px solid #ccc; /* Borda para campos editáveis */
        }
    </style>
</head>

<body>
<main>
    <div class="container my-3">
        <h1>Editar Perfil</h1>
        <!-- Formulário de edição de perfil -->
        <form id="editProfileForm">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control readonly-field" id="nome" name="nome" readonly>
            </div>
            <div class="form-group">
                <label for="sobrenome">Sobrenome</label>
                <input type="text" class="form-control readonly-field" id="sobrenome" name="sobrenome" readonly>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control readonly-field" id="email" name="email" readonly>
            </div>
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control readonly-field" id="cpf" name="cpf" readonly>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" class="form-control editable-field" id="endereco" name="endereco" required>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control editable-field" id="cidade" name="cidade" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" class="form-control editable-field" id="estado" name="estado" required>
            </div>
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control editable-field" id="cep" name="cep" required>
            </div>
            <div class="form-group">
                <label for="senha">Nova Senha</label>
                <input type="password" class="form-control editable-field" id="senha" name="senha">
            </div>
            <div class="form-group">
                <label for="confirmarSenha">Confirmar Nova Senha</label>
                <input type="password" class="form-control editable-field" id="confirmarSenha" name="confirmarSenha">
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var usuario = JSON.parse(localStorage.getItem("usuario")); // Obtém o objeto usuario do localStorage

            if (usuario) {
                document.getElementById("nome").value = usuario.nome || "";
                document.getElementById("sobrenome").value = usuario.sobrenome || "";
                document.getElementById("email").value = usuario.email || "";
                document.getElementById("cpf").value = usuario.cpf || "";
                document.getElementById("endereco").value = usuario.endereco || "";
                document.getElementById("cidade").value = usuario.cidade || "";
                document.getElementById("estado").value = usuario.estado || "";
                document.getElementById("cep").value = usuario.cep || "";
            }

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
        });

        document.getElementById("editProfileForm").addEventListener("submit", function (event) {
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
            var confirmarSenha = document.getElementById("confirmarSenha").value || null;

            // Verificar se as senhas correspondem
            if (senha !== confirmarSenha) {
                alert("As senhas digitadas não correspondem. Por favor, tente novamente.");
                return;
            }

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
                })
                .catch(error => {
                    console.error("Erro:", error);
                    alert("Ocorreu um erro ao atualizar o perfil.");
                });
        });
    </script>
</main>
</body>
</html>
<?php include_once("templates/footer.php") ?>
