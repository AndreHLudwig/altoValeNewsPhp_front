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
    <div id="title-container">
        <h1>Alto Vale News</h1>
        <p>O seu portal de notícias do Alto Vale do Itajaí</p>
    </div>
    <div id="posts-container">
    <?php foreach ($posts as $post) : ?>
        <div class="post-box">
            <img src="<?= $BASE_URL ?>/img/<?= $post['imagem'] ?>" alt="<?= $post['titulo'] ?>">
            <h2 class="post-title">
                <a href="<?= $BASE_URL ?>post.php?id=<?= $post['publicacaoId'] ?>"><?= $post['titulo'] ?></a>
            </h2>
            <p class="post-description">
                <?= substr($post['texto'], 0, 280) ?><?= strlen($post['texto']) > 280 ? "..." : "" ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>

</main>

<?php
include_once("templates/footer.php")
?>
