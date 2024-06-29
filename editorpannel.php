<?php
require_once("templates/header.php");
?>

<div class="container">
    <h2>Criar Nova Publicação</h2>
    <form id="publicationForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="texto">Texto:</label>
            <textarea class="form-control" id="texto" name="texto" required></textarea>
        </div>
        <div class="form-group">
            <label for="imagem">Imagem:</label>
            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
        </div>
        <div class="form-group">
            <label for="video">Vídeo:</label>
            <input type="file" class="form-control" id="video" name="video" accept="video/*">
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <input type="text" class="form-control" id="categoria" name="categoria" required>
        </div>
        <div class="form-group">
            <label for="visibilidadeVip">Visibilidade VIP:</label>
            <input type="checkbox" id="visibilidadeVip" name="visibilidadeVip">
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var editor = JSON.parse(localStorage.getItem("usuario")) || {};
        var tipoUsuario = editor.tipo;

        if (tipoUsuario !== 2 && tipoUsuario !== 3) {
            window.location.href = "acesso-negado.php";
        }

        const dataPubli = new Date().toISOString().split('T')[0];

        document.getElementById("publicationForm").addEventListener("submit", function (event) {
            event.preventDefault();

            var formData = new FormData();
            formData.append("editorId", editor.userId);
            formData.append("titulo", document.getElementById("titulo").value);
            formData.append("texto", document.getElementById("texto").value);
            formData.append("categoria", document.getElementById("categoria").value);
            formData.append("visibilidadeVip", document.getElementById("visibilidadeVip").checked);
            formData.append("data", dataPubli);
            formData.append("curtidas", 0);
            formData.append("imagem", document.getElementById("imagem").files[0]);
            formData.append("video", document.getElementById("video").files[0]);

            fetch('http://localhost:8080/publicacao', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao enviar a publicação. Status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.publicacaoId) {
                        alert('Publicação criada com sucesso!');
                        document.getElementById("publicationForm").reset();
                    } else {
                        throw new Error('Erro ao criar a publicação.');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Erro ao criar a publicação. Verifique os campos e tente novamente.');
                });
        });
    });
</script>

<?php
include_once("templates/footer.php");
?>
