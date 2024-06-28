<?php
include_once("templates/header.php");

$api_url = "http://localhost:8080/publicacao";
$response = file_get_contents($api_url);
if ($response === false) {
    echo "Erro ao fazer solicitação para a API.";
    exit;
}
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
                            <?php if (isset($post['imagem']) && isset($post['imagem']['data'])) : ?>
                                <?php
                                $imageData = $post['imagem']['data'];
                                $imageBase64 = 'data:' . $post['imagem']['fileType'] . ';base64,' . $imageData;
                                ?>
                                <a href="<?= $BASE_URL ?>post.php?id=<?= $post['publicacaoId'] ?>">
                                    <img src="<?= $imageBase64 ?>" class="card-img-top"
                                         alt="<?= $post['titulo'] ?>">
                                </a>
                            <?php endif; ?>
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

<?php include_once("templates/footer.php") ?>