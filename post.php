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
                        <div class="like-dislike-container">
                            <button onclick="curtirPublicacao(<?= $post['publicacaoId'] ?>)" class="btn btn-info btn-sm"><i class="fa-regular fa-thumbs-up"></i></button>
                            <span id="curtidas-publicacao"><?= $post['curtidas'] ?></span>
                            <button onclick="descurtirPublicacao(<?= $post['publicacaoId'] ?>)" class="btn btn-secondary btn-sm"><i class="fa-regular fa-thumbs-down"></i></button>
                        </div>
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
                <hr>
                <div class="row col-md-12 mt-4">
                    <div class="col-md-10">
                        <h4>Comentários</h4>
                        <ul id="comments-list" class="list-group">
                            <?php foreach ($post['comentarios'] as $comment): ?>
                                <li class="mb-4 p-3 border rounded bg-light">
                                    <p><strong>Por:</strong> <?= $comment['usuario']['nome'] ?> <?= $comment['usuario']['sobrenome'] ?></p>
                                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($comment['data'])) ?></p>
                                    <p><?= $comment['texto'] ?></p>
                                    <div class="like-dislike-container">
                                        <button onclick="curtirComentario(<?= $comment['comentarioId'] ?>)" class="btn btn-info btn-sm"><i class="fa-regular fa-thumbs-up"></i></button>
                                        <span id="curtidas-comentario-<?= $comment['comentarioId'] ?>"><?= $comment['curtidas'] ?></span>
                                        <button onclick="descurtirComentario(<?= $comment['comentarioId'] ?>)" class="btn btn-secondary btn-sm"><i class="fa-regular fa-thumbs-down"></i></button>
                                    </div>
                                    <?php
                                    if (isset($comment['comentarioId'])) {
                                        $commentId = $comment['comentarioId'];
                                        $commentUserId = $comment['usuario']['userId']; // Supondo que exista um campo userId em usuário
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

                function deletarComentario(id) {
                    fetch(`http://localhost:8080/comentario/${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload(); // Recarrega a página após a exclusão do comentário
                        }
                    });
                }
            </script>
            </body>
            </html>
            <?php
        } else {
            echo "Erro ao decodificar os dados da postagem.";
        }
    } else {
        echo "Erro ao obter os dados da postagem.";
    }
} else {
    echo "ID da postagem não fornecido.";
}

include_once("templates/footer.php");
?>
