<?php

require __DIR__.'/vendor/autoload.php';

$client = new GuzzleHttp\Client;

try {
    $response = $client->post('http://api.local/oauth/token', [
        'form_params' => [
            'client_id' => 4,
            // The secret generated when you ran: php artisan passport:install
            'client_secret' => 'eVMGbhfF7m99NhtKVoGXvM6yIQ4t9ptXbj04P8b2',
            'grant_type' => 'password',
            'username' => 'admin@me.com',
            'password' => '123456',
            'scope' => '*',
        ]
    ]);

    // You'd typically save this payload in the session
    $auth = json_decode( (string) $response->getBody() );
echo $auth->access_token; exit;
    $response = $client->get('http://api.local/api/todos', [
        'headers' => [
            'Authorization' => 'Bearer '.$auth->access_token,
        ]
    ]);

    $todos = json_decode( (string) $response->getBody() );

    $todoList = "";
    foreach ($todos as $todo) {
        $todoList .= "<li>{$todo->task}".($todo->done ? '✅' : '')."</li>";
    }

    echo "<ul>{$todoList}</ul>";

} catch (GuzzleHttp\Exception\BadResponseException $e) {
    echo "Unable to retrieve access token.";
}
