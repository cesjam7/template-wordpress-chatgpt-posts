<h1><?php the_title(); ?></h1>

<form method="post">
  <p>Ingresar el tema del post a generar</p>
  <input type="text" name="tema" required>
  <button type="submit">Generar</button>
</form>

<?php if ($_POST) {
  $tema = $_POST['tema'];

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
      print_r($json);
    }
  }

}