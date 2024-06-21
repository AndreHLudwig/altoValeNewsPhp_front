<?php
include_once("templates/header.php");

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Endpoint da API
    $api_url = "http://localhost:8080/publicacao/$postId";

    // Fazendo solicitação HTTP para obter os dados da postagem
    $response = file_get_contents($api_url);

    if ($response !== false) {
        $post = json_decode($response, true);

        // Verifica se a decodificação JSON foi bem-sucedida
        if ($post !== null) {
            ?>
            <body>
            <main id="post-container" class="container py-4">
                <div class="row col-md-12">
                    <div class="col-md-10">
                        <h1 id="main-title" class="mb-4"><?= $post['titulo'] ?></h1>
                        <p><strong>Publicado por:</strong> <?= $post['editor']['nome'] ?> <?= $post['editor']['sobrenome'] ?></p>
                        <p><strong>Data de Publicação:</strong> <?= date('d/m/Y', strtotime($post['data'])) ?></p>
                        <p id="post-content" class="post-content"><?= $post['texto'] ?></p>
                        <?php if ($post['imagem'] !== null): ?>
                            <div class="img-container mb-4">
                                <img src="<?= $post['imagem'] ?>" class="img-fluid rounded" alt="<?= $post['titulo'] ?>">
                            </div>
                        <?php endif; ?>
                        <?php if ($post['video'] !== null): ?>
                            <div class="video-container mb-4">
                                <video controls class="img-fluid rounded">
                                    <source src="<?= $post['video'] ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-2">
                        <div id="nav-container" class="mb-4">
                            <h3 id="categories-title">Categoria</h3>
                            <ul id="categories-list" class="list-group">
                                <li class="list-group-item"><a href="#"><?= $post['categoria'] ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row col-md-10">
                    <div id="comments-container">
                        <h3 id="comments-title">Comentários</h3>
                        <div id="comment-box">
                            <textarea id="comment-text" class="form-control" placeholder="Digite seu comentário aqui"></textarea>
                            <div class="button-container" style="text-align: left;">
                                <button onclick="enviarComentario()" class="btn btn-primary">Enviar Comentário</button>
                            </div>
                        </div>
                        <div id="login-warning" class="alert alert-danger" style="display: none;">
                            Você precisa estar autenticado para enviar um comentário. <a href="auth.php" class="alert-link">Clique aqui para fazer login</a>.
                        </div>
                        <ul id="comment-list" class="list-unstyled">
                            <?php foreach ($post['comentarios'] as $comment): ?>
                                <li class="mb-4 p-3 border rounded bg-light">
                                    <p><strong>Por:</strong> <?= $comment['usuario']['nome'] ?> <?= $comment['usuario']['sobrenome'] ?></p>
                                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($comment['data'])) ?></p>
                                    <p><?= $comment['texto'] ?></p>
                                    <?php
                                    // Verifica se o ID do comentário está definido e não é nulo
                                    if (isset($comment['comentarioId'])) {
                                        $commentId = $comment['comentarioId'];
                                        // Inclui o ID do comentário de forma segura usando json_encode
                                        echo '<button onclick="deletarComentario(' . json_encode($commentId) . ')" class="btn btn-danger btn-sm">Deletar</button>';
                                    }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                </div>
            </main>
            </body>
            <?php
        } else {
            echo "Erro ao decodificar os dados da API.";
        }
    } else {
        echo "Erro ao fazer solicitação para a API.";
    }
}
include_once("templates/footer.php");
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var currentUser = JSON.parse(localStorage.getItem('usuario'));
        var currentUserId = currentUser ? currentUser.userId : null;
        var currentUserType = currentUser ? currentUser.tipo : null;

        var comentarios = document.querySelectorAll('#comment-list .btn-danger');
        comentarios.forEach(function(botao) {
            var idComentario = botao.getAttribute('data-comentario-id');
            var idUsuarioComentario = botao.getAttribute('data-usuario-id');

            // Verifica se o ID do usuário atual corresponde ao ID do usuário associado ao comentário
            if (idUsuarioComentario == currentUserId || currentUserType == 3) {
                botao.style.display = 'block'; // Mostra o botão de deletar
                console.log("idComentario:");
                console.log(idComentario);
                console.log("idUsuarioComentario:");
                console.log(idUsuarioComentario);
                console.log("currentUserId:");
                console.log(currentUserId);
                console.log("currentUserType");
                console.log(currentUserType);
                console.log("----------------------------:");
            }
        });
    });


    function deletarComentario(comentarioId) {
        // Confirmação de exclusão
        if (!confirm("Tem certeza que deseja deletar este comentário?")) {
            return;
        }

        // Envia a requisição DELETE para o servidor
        fetch(`http://localhost:8080/comentario/${comentarioId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (response.ok) {
                    // Comentário deletado com sucesso
                    console.log('Comentário deletado');
                    location.reload();
                } else {
                    throw new Error('Erro ao deletar o comentário');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
    }

    // Função para enviar o comentário
    function enviarComentario() {
        // Obtém o texto do comentário do usuário
        var comentario = document.getElementById("comment-text").value.trim();

        // Verifica se o campo de comentário está vazio
        if (comentario === "") {
            alert("Por favor, escreva algo antes de enviar o comentário.");
            return;
        }

        // Verifica se o usuário está autenticado
        if (localStorage.getItem("autenticado") === "true") {
            // Se autenticado, envia o comentário
            enviarComentarioAutenticado(comentario);
        } else {
            // Se não autenticado, exibe o aviso
            document.getElementById("login-warning").style.display = "block";
        }
    }

    function enviarComentarioAutenticado(comentario) {
        // Obtém o ID da publicação
        var postId = <?php echo json_encode($postId); ?>;

        // Obtém o objeto de usuário do localStorage e extrai o userId
        var userId = localStorage.getItem("usuario") ? JSON.parse(localStorage.getItem("usuario")).userId : null;

        // Verifica se o userId foi obtido corretamente
        if (userId === null) {
            console.error('Erro: userId não encontrado no localStorage');
            return;
        }

        // Cria o objeto JSON com os dados do comentário
        var comentarioData = {
            "publicacaoId": postId,
            "usuario": {
                "userId": userId
            },
            "data": new Date().toISOString().split('T')[0], // Data atual
            "texto": comentario,
            "curtidas": 0
        };

        // Envia o comentário para o servidor
        fetch('http://localhost:8080/comentario', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(comentarioData),
        })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Erro ao enviar o comentário');
            })
            .then(data => {
                // Comentário enviado com sucesso
                console.log('Comentário enviado:', data);
                location.reload();
            })
            .catch(error => {
                console.error('Erro:', error);
            });
    }

</script>
