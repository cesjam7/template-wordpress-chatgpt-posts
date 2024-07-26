<h1><?php the_title(); ?></h1>

<form method="post">
  <p>Ingresar la cantidad de posts a generar</p>
  <input type="number" name="cantidad" min="1" max="100" required>
  <button type="submit">Generar</button>
</form>

<?php if ($_POST) {
  $cantidad = $_POST['cantidad'];

  for ($i = 0; $i < $cantidad; $i++) {
    $content = file_get_contents('http://loripsum.net/api');
    $args = [
      'post_title' => 'Post falso con Lorem Ipsum '.$i,
      'post_content' => $content,
      'post_status' => 'publish'
    ];
    $post_id = wp_insert_post($args);
    echo '<p>Post generado: '.get_permalink($post_id).'</p>';
  }

}