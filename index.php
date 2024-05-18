<?php
include_once("templates/header.php");

// URL da API
$api_url = "http://localhost:8080/publicacao";

// Faz a solicitação para a API e obtém a resposta
$response = file_get_contents($api_url);

// Verifica se houve erro na solicitação
if ($response === false) {
    echo "Erro ao fazer solicitação para a API.";
    exit; // Encerra o script em caso de erro
}

// Decodifica os dados da resposta JSON para um array PHP
$posts = json_decode($response, true);

?>

<main>
    <div class="container">
        <div class="py-5 text-center">
            <h1 class="display-4">Alto Vale News</h1>
            <p class="lead">O seu portal de notícias do Alto Vale do Itajaí</p>
        </div>

        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= $BASE_URL ?>/img/<?= $post['imagem'] ?>" class="card-img-top"
                             alt="<?= $post['titulo'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><a
                                        href="<?= $BASE_URL ?>post.php?id=<?= $post['publicacaoId'] ?>"><?= $post['titulo'] ?></a>
                            </h5>
                            <p class="card-text post-description"><?= substr($post['texto'], 0, 280) ?><?= strlen($post['texto']) > 280 ? "..." : "" ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>


<?php
include_once("templates/footer.php")
?>