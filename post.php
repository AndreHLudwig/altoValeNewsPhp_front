<?php
  include_once("templates/header.php");

  if(isset($_GET['id'])) {
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

<main id="post-container">
  <div class="content-container">
    <h1 id="main-title"><?= $post['titulo'] ?></h1>
    <p id="post-description"><?= $post['texto'] ?></p>
    <?php if ($post['imagem'] !== null): ?>
      <div class="img-container">
        <img src="<?= $post['imagem'] ?>" alt="<?= $post['titulo'] ?>">
      </div>
    <?php endif; ?>
    <?php if ($post['video'] !== null): ?>
      <div class="video-container">
        <video controls>
          <source src="<?= $post['video'] ?>" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    <?php endif; ?>
  </div>
  <aside id="nav-container">
    <h3 id="categories-title">Categoria</h3>
    <ul id="categories-list">
      <li><a href="#"><?= $post['categoria'] ?></a></li>
    </ul>
    <h3 id="comments-title">Comentários</h3>
    <ul id="comment-list">
      <?php foreach($post['comentarios'] as $comment): ?>
        <li>
          <p><?= $comment['texto'] ?></p>
          <p>Por: <?= $comment['usuario']['nome'] ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>
</main>

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
