<h1><?php the_title(); ?></h1>

<form method="post">
  <p>Ingresar la cantidad de posts a generar</p>
  <input type="number" name="cantidad" min="1" max="100" required>
  <p>Ingresar el tema del post a generar</p>
  <input type="text" name="tema" required>
  <button type="submit">Generar</button>
</form>

<?php if ($_POST) {
  $cantidad = $_POST['cantidad'];
  $tema = $_POST['tema'];

  for ($i = 0; $i < $cantidad; $i++) {
    



    $url = 'https://api.openai.com/v1/chat/completions';
    $response = wp_remote_post($url, [
      'headers' => [
        'Authorization' => 'Bearer '.APIKEY_CHATGPT,
        'Content-Type' => 'application/json',
      ],
      'body' => json_encode([
        "model" => "gpt-3.5-turbo",
        'messages' => [
          ['role' => 'system', 'content' => 'Del tema que te voy a pasar entre comillas vas a redactar un post para mi blog'],
          ['role' => 'system', 'content' => 'Solo responde como JSON con el siguiente formato: {titulo: $titulo, contenido: $contenido}'],
          ['role' => 'user', 'content' => '"'.$tema.'"']
        ]
      ])
    ]);
    if (is_wp_error($response)) {
      echo 'Error: '.$response->get_error_message();
      return;
    } else {
      // echo '<pre>'.print_r($response, 1).'</pre>';
      $data = json_decode($response['body']);
      if ($data->choices) {
        $choice = $data->choices[0];
        $json = json_decode($choice->message->content);
        // print_r($json);




        $args = [
          'post_title' => $json->titulo,
          'post_content' => $json->contenido,
          'post_status' => 'publish'
        ];
        $post_id = wp_insert_post($args);
        echo '<p>Post generado: '.get_permalink($post_id).'</p>';





      }
    }








    
  }

}