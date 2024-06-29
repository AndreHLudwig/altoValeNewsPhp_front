<?php
include_once("templates/header.php");

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    $api_url = "http://localhost:8080/publicacao/$postId";

    $response = file_get_contents($api_url);

    if ($response !== false) {
        $post = json_decode($response, true);

        if ($post !== null) {
            ?>
            <body>
            <main id="post-container" class="container py-4">
                <div class="row col-md-12">
                    <div class="col-md-10">
                        <h1 id="main-title" class="mb-4"><?= htmlspecialchars($post['titulo']) ?></h1>
                        <p><strong>Publicado
                                por:</strong> <?= htmlspecialchars($post['editor']['nome']) ?> <?= htmlspecialchars($post['editor']['sobrenome']) ?>
                        </p>
                        <p><strong>Data de Publicação:</strong> <?= date('d/m/Y', strtotime($post['data'])) ?></p>
                        <?php if (isset($post['imagem']) && isset($post['imagem']['data'])): ?>
                            <div class="img-container mb-4" style="text-align: center;">
                                <?php
                                $imageData = $post['imagem']['data'];
                                $imageBase64 = 'data:' . $post['imagem']['fileType'] . ';base64,' . $imageData;
                                ?>
                                <img src="<?= $imageBase64 ?>" class="img-fluid rounded" alt="<?= $post['titulo'] ?>"
                                     style="max-height: 30vh; width: auto; display: inline-block;">
                            </div>
                        <?php endif; ?>
                        <p id="post-content" class="post-content"><?= htmlspecialchars($post['texto']) ?></p>
                        <div class="like-dislike-container">
                            <button onclick="curtirPublicacao(<?= htmlspecialchars($post['publicacaoId']) ?>)"
                                    class="btn btn-info btn-sm"><i class="fa-regular fa-thumbs-up"></i></button>
                            <span id="curtidas-publicacao"><?= htmlspecialchars($post['curtidas']) ?></span>
                            <button onclick="descurtirPublicacao(<?= htmlspecialchars($post['publicacaoId']) ?>)"
                                    class="btn btn-secondary btn-sm"><i class="fa-regular fa-thumbs-down"></i></button>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div id="nav-container" class="mb-4">
                            <h3 id="categories-title">Categoria</h3>
                            <ul id="categories-list" class="list-group">
                                <li class="list-group-item"><a href="#"><?= htmlspecialchars($post['categoria']) ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row col-md-10">
                    <div id="comments-container">
                        <h3 id="comments-title">Comentários</h3>
                        <div id="comment-box">
                            <textarea id="comment-text" class="form-control"
                                      placeholder="Digite seu comentário aqui"></textarea>
                            <div class="button-container" style="text-align: left;">
                                <button onclick="enviarComentario()" class="btn btn-primary">Enviar Comentário</button>
                            </div>
                        </div>
                        <div id="login-warning" class="alert alert-danger" style="display: none;">
                            Você precisa estar autenticado para enviar um comentário.
                            <a href="auth.php" class="alert-link">Clique aqui para fazer login</a>.
                        </div>
                        <ul id="comments-list" class="list-group">
                            <?php foreach ($post['comentarios'] as $comment): ?>
                                <li class="mb-4 p-3 border rounded bg-light">
                                    <p>
                                        <strong>Por:</strong> <?= $comment['usuario']['nome'] ?> <?= $comment['usuario']['sobrenome'] ?>
                                    </p>
                                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($comment['data'])) ?></p>
                                    <p><?= $comment['texto'] ?></p>
                                    <div class="like-dislike-container">
                                        <button onclick="curtirComentario(<?= $comment['comentarioId'] ?>)"
                                                class="btn btn-info btn-sm"><i class="fa-regular fa-thumbs-up"></i>
                                        </button>
                                        <span id="curtidas-comentario-<?= $comment['comentarioId'] ?>"><?= $comment['curtidas'] ?></span>
                                        <button onclick="descurtirComentario(<?= $comment['comentarioId'] ?>)"
                                                class="btn btn-secondary btn-sm"><i
                                                    class="fa-regular fa-thumbs-down"></i></button>
                                    </div>
                                    <?php
                                    if (isset($comment['comentarioId'])) {
                                        $commentId = $comment['comentarioId'];
                                        $commentUserId = $comment['usuario']['userId'];
                                        // Inclui o ID do comentário e o ID do usuário de forma segura usando json_encode
                                        echo '<button onclick="deletarComentario(' . json_encode($commentId) . ')" class="btn btn-danger btn-sm" data-comentario-id="' . htmlspecialchars($commentId) . '" data-usuario-id="' . htmlspecialchars($commentUserId) . '">Deletar</button>';
                                    }
                                    ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </main>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var currentUser = JSON.parse(localStorage.getItem('usuario'));
                    var currentUserId = currentUser ? currentUser.userId : null;
                    var currentUserType = currentUser ? currentUser.tipo : null;

                    var comentarios = document.querySelectorAll('#comments-list .btn-danger');
                    comentarios.forEach(function (botao) {
                        var idComentario = botao.getAttribute('data-comentario-id');
                        var idUsuarioComentario = botao.getAttribute('data-usuario-id');

                        if (idUsuarioComentario == currentUserId || currentUserType == 3) {
                            botao.style.display = 'block'; // Mostra o botão de deletar
                        } else {
                            botao.style.display = 'none'; // Esconde o botão de deletar
                        }
                    });
                });

                function curtirPublicacao(id) {
                    fetch(`http://localhost:8080/publicacao/${id}/like`, {
                        method: 'PATCH'
                    })
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('curtidas-publicacao').innerText = data.curtidas;
                        });
                }

                function descurtirPublicacao(id) {
                    fetch(`http://localhost:8080/publicacao/${id}/dislike`, {
                        method: 'PATCH'
                    })
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('curtidas-publicacao').innerText = data.curtidas;
                        });
                }

                function curtirComentario(id) {
                    fetch(`http://localhost:8080/comentario/${id}/like`, {
                        method: 'PATCH'
                    })
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById(`curtidas-comentario-${id}`).innerText = data.curtidas;
                        });
                }

                function descurtirComentario(id) {
                    fetch(`http://localhost:8080/comentario/${id}/dislike`, {
                        method: 'PATCH'
                    })
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById(`curtidas-comentario-${id}`).innerText = data.curtidas;
                        });
                }

                function deletarComentario(comentarioId) {
                    if (!confirm("Tem certeza que deseja deletar este comentário?")) {
                        return;
                    }

                    fetch(`http://localhost:8080/comentario/${comentarioId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                        });
                }

                function enviarComentario() {
                    var comentario = document.getElementById("comment-text").value.trim();

                    if (comentario === "") {
                        alert("Por favor, escreva algo antes de enviar o comentário.");
                        return;
                    }

                    if (localStorage.getItem("autenticado") === "true") {
                        enviarComentarioAutenticado(comentario);
                    } else {
                        document.getElementById("login-warning").style.display = "block";
                    }
                }

                function enviarComentarioAutenticado(comentario) {
                    var postId = <?= json_encode($postId); ?>;

                    var userId = localStorage.getItem("usuario") ? JSON.parse(localStorage.getItem("usuario")).userId : null;

                    if (userId === null) {
                        console.error('Erro: userId não encontrado no localStorage');
                        return;
                    }

                    var comentarioData = {
                        "publicacaoId": postId,
                        "usuario": {
                            "userId": userId
                        },
                        "data": new Date().toISOString().split('T')[0], // Data atual
                        "texto": comentario,
                        "curtidas": 0
                    };

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
                            location.reload();
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                        });
                }
            </script>
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
