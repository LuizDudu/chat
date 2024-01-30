<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Luizdudu\Chat\Entities\ChatMessage;
use Swoole\Http\{
    Request,
    Response
};
use Swoole\WebSocket\{
    Frame,
    Server
};

$server = new Server("0.0.0.0", 8080);

$server->set(['open_http2_protocol' => true]);

$env = parse_ini_file(__DIR__ . '/../.env');
if (empty($env['DONT_USE_WSS'])) {
    $env['DONT_USE_WSS'] = "false";
}

// http && http2
$server->on('request', function (Request $request, Response $response) use ($env) {
    $response->header('Content-Type', 'text/html');
    ob_start();
    include __DIR__ . '/../views/index.php';
    $page = ob_get_clean();
    $response->end($page);
});

// websocket
$server->on('message', function (Server $server, Frame $frame) {
    $data = json_decode($frame->data);

    if (empty($data?->nickname) || empty($data?->message)) {
        return;
    }

    $chatMessage = (new ChatMessage($data->nickname, $data->message))->toArray();

    foreach ($server->connections as $connection) {
        if (!$server->isEstablished($connection)) {
            continue;
        }

        $server->push(
            $connection,
            json_encode($chatMessage)
        );
    }
});

$server->start();
