<?php
require_once("templates/header.php");
?>

<div class="container">
    <h2>Criar Nova Publicação</h2>
    <form id="publicationForm">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="texto">Texto:</label>
            <textarea class="form-control" id="texto" name="texto" required></textarea>
        </div>
<!--        <div class="form-group">-->
<!--            <label for="imagem">Imagem:</label>-->
<!--            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">-->
<!--        </div>-->
<!--        <div class="form-group">-->
<!--            <label for="video">Vídeo:</label>-->
<!--            <input type="file" class="form-control" id="video" name="video" accept="video/*">-->
<!--        </div>-->
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
    // function encodeImageFileAsURL(file) {
    //     return new Promise((resolve, reject) => {
    //         var reader = new FileReader();
    //         reader.onloadend = function() {
    //             console.log('RESULT', reader.result); // Log the Base64 string to verify
    //             resolve(reader.result.split(',')[1]); // Return the Base64 string without the prefix
    //         }
    //         reader.onerror = error => reject(error);
    //         reader.readAsDataURL(file);
    //     });
    // }

    document.getElementById("publicationForm").addEventListener("submit", function(event) {
        event.preventDefault();

        var editor = JSON.parse(localStorage.getItem("usuario")) || {};
        var titulo = document.getElementById("titulo").value;
        var texto = document.getElementById("texto").value;
        var categoria = document.getElementById("categoria").value;
        var visibilidadeVip = document.getElementById("visibilidadeVip").checked;
        var data = new Date().toISOString();

        // var imagemInput = document.getElementById("imagem");
        // var videoInput = document.getElementById("video");

        // var imagemFile = imagemInput.files[0];
        // var videoFile = videoInput.files[0];

        var publicacao = {
            editor: editor,
            titulo: titulo,
            data: data,
            texto: texto,
            // imagem: null,
            // video: null,
            categoria: categoria,
            visibilidadeVip: visibilidadeVip,
            curtidas: 0,
            comentarios: []
        };

        Promise.all([
            // imagemFile ? encodeImageFileAsURL(imagemFile) : Promise.resolve(null),
            // videoFile ? encodeImageFileAsURL(videoFile) : Promise.resolve(null)
        ]).then(([imagemBase64, videoBase64]) => {
            // if (imagemBase64) publicacao.imagem = imagemBase64;
            // if (videoBase64) publicacao.video = videoBase64;

            fetch('http://localhost:8080/publicacao', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(publicacao)
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
        })
            .catch(error => {
                console.error('Error converting files:', error);
                alert('Erro ao processar os arquivos. Verifique os arquivos selecionados.');
            });
    });
</script>

<?php
include_once("templates/footer.php");
?>
