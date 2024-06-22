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
                        <h1 id="main-title" class="mb-4"><?= htmlspecialchars($post['titulo']) ?></h1>
                        <p><strong>Publicado por:</strong> <?= htmlspecialchars($post['editor']['nome']) ?> <?= htmlspecialchars($post['editor']['sobrenome']) ?></p>
                        <p><strong>Data de Publicação:</strong> <?= date('d/m/Y', strtotime($post['data'])) ?></p>
<!--                        --><?php //if ($post['imagem'] !== null): ?>
<!--                            <div class="img-container mb-4">-->
<!--                                --><?php
//                                // Converte o array de bytes em uma string base64
//                                $imageData = base64_encode(implode(array_map("chr", $post['imagem']['data'])));
//                                $imageSrc = "data:" . $post['imagem']['fileType'] . ";base64," . $imageData;
//                                ?>
<!--                                <img src="--><?php //= htmlspecialchars($imageSrc) ?><!--" class="img-fluid rounded" alt="--><?php //= htmlspecialchars($post['titulo']) ?><!--">-->
<!--                            </div>-->
<!--                        --><?php //endif; ?>
                        <p id="post-content" class="post-content"><?= htmlspecialchars($post['texto']) ?></p>
<!--                        --><?php //if ($post['video'] !== null): ?>
<!--                            <div class="video-container mb-4">-->
<!--                                <video controls class="img-fluid rounded">-->
<!--                                    <source src="--><?php //= htmlspecialchars($post['video']['filePath']) ?><!--" type="video/mp4">-->
<!--                                    Your browser does not support the video tag.-->
<!--                                </video>-->
<!--                            </div>-->
<!--                        --><?php //endif; ?>
                        <div class="like-dislike-container">
                            <button onclick="curtirPublicacao(<?= htmlspecialchars($post['publicacaoId']) ?>)" class="btn btn-info btn-sm"><i class="fa-regular fa-thumbs-up"></i></button>
                            <span id="curtidas-publicacao"><?= htmlspecialchars($post['curtidas']) ?></span>
                            <button onclick="descurtirPublicacao(<?= htmlspecialchars($post['publicacaoId']) ?>)" class="btn btn-secondary btn-sm"><i class="fa-regular fa-thumbs-down"></i></button>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div id="nav-container" class="mb-4">
                            <h3 id="categories-title">Categoria</h3>
                            <ul id="categories-list" class="list-group">
                                <li class="list-group-item"><a href="#"><?= htmlspecialchars($post['categoria']) ?></a></li>
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
                                    <p><strong>Por:</strong> <?= htmlspecialchars($comment['usuario']['nome']) ?> <?= htmlspecialchars($comment['usuario']['sobrenome']) ?></p>
                                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($comment['data'])) ?></p>
                                    <p><?= htmlspecialchars($comment['texto']) ?></p>
                                    <div class="like-dislike-container">
                                        <button onclick="curtirComentario(<?= htmlspecialchars($comment['comentarioId']) ?>)" class="btn btn-info btn-sm"><i class="fa-regular fa-thumbs-up"></i></button>
                                        <span id="curtidas-comentario-<?= htmlspecialchars($comment['comentarioId']) ?>"><?= htmlspecialchars($comment['curtidas']) ?></span>
                                        <button onclick="descurtirComentario(<?= htmlspecialchars($comment['comentarioId']) ?>)" class="btn btn-secondary btn-sm"><i class="fa-regular fa-thumbs-down"></i></button>
                                    </div>
                                    <button onclick="deletarComentario(<?= htmlspecialchars($comment['comentarioId']) ?>)" class="btn btn-danger btn-sm" data-comentario-id="<?= htmlspecialchars($comment['comentarioId']) ?>" data-usuario-id="<?= htmlspecialchars($comment['usuario']['userId']) ?>">Deletar</button>
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
                                location.reload();
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
