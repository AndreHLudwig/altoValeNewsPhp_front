<?php include_once("templates/header.php"); ?>

<h1>Lista de Usuários</h1>

<table id="usuariosTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo de Usuário</th>
        <th>Ação</th>
    </tr>
    </thead>
    <tbody>
    <!-- As linhas serão preenchidas dinamicamente com JavaScript -->
    </tbody>
</table>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var admin = JSON.parse(localStorage.getItem("usuario")) || {};
        var tipoUsuario = admin.tipo;

        if (tipoUsuario !== 3) {
            window.location.href = "acesso-negado.php";
        }

        fetchUsuarios();

        function fetchUsuarios() {
            fetch('http://localhost:8080/usuario')
                .then(response => response.json())
                .then(data => {
                    var usuariosTableBody = document.getElementById('usuariosTable').getElementsByTagName('tbody')[0];
                    usuariosTableBody.innerHTML = '';
                    data.forEach(usuario => {
                        var row = usuariosTableBody.insertRow();
                        row.insertCell(0).innerText = usuario.userId;
                        row.insertCell(1).innerText = usuario.nome;
                        row.insertCell(2).innerText = usuario.email;
                        var tipoCell = row.insertCell(3);
                        var tipoRadios = `
                            <input type="radio" name="tipo-${usuario.userId}" value="0" ${usuario.tipo === 0 ? 'checked' : ''}> Usuário
                            <input type="radio" name="tipo-${usuario.userId}" value="1" ${usuario.tipo === 1 ? 'checked' : ''}> Usuário VIP
                            <input type="radio" name="tipo-${usuario.userId}" value="2" ${usuario.tipo === 2 ? 'checked' : ''}> Editor
                            <input type="radio" name="tipo-${usuario.userId}" value="3" ${usuario.tipo === 3 ? 'checked' : ''}> Administrador
                        `;
                        tipoCell.innerHTML = tipoRadios;

                        var actionCell = row.insertCell(4);
                        var saveButton = document.createElement('button');
                        saveButton.innerText = 'Salvar';
                        saveButton.onclick = function () {
                            var selectedTipo = document.querySelector(`input[name="tipo-${usuario.userId}"]:checked`).value;
                            updateTipoUsuario(usuario.userId, selectedTipo);
                        };
                        actionCell.appendChild(saveButton);
                    });
                });
        }

        function updateTipoUsuario(id, tipo) {
            // Converte o tipo para Number para garantir que seja um valor inteiro
            tipo = Number(tipo);

            // Verifica se tipo não é NaN (Not a Number)
            if (!isNaN(tipo)) {
                fetch(`http://localhost:8080/usuario/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ tipo: tipo })
                })
                    .then(response => {
                        if (response.ok) {
                            alert('Tipo de usuário atualizado com sucesso');
                            fetchUsuarios();
                        } else {
                            alert('Erro ao atualizar tipo de usuário');
                        }
                    });
            } else {
                alert('Tipo de usuário não é um número válido');
            }
        }
    });
</script>

<?php include_once("templates/footer.php"); ?>
