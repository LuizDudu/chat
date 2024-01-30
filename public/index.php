<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

// http && http2
$server->on('request', function (Request $request, Response $response) {
    $page = file_get_contents(__DIR__ . '/../views/index.php');
    $response->end($page);
});

// websocket
$server->on('message', function (Server $server, Frame $frame) {
    $data = json_decode($frame->data);

    if (empty($data?->nickname) || empty($data?->message)) {
        return;
    }

    $chatMessage = (new \Luizdudu\Chat\Entities\ChatMessage($data->nickname, $data->message))->toArray();

    foreach ($server->connections as $connection) {
        if ($server->getClientInfo($connection)['websocket_status'] !== 3) {
            continue;
        }

        if (!$server->isEstablished($frame->fd)) {
            continue;
        }

        $server->push($connection, json_encode($chatMessage));
    }
});

$server->start();
