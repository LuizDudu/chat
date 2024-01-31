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

setUpEnv();

$server = new Server("0.0.0.0", 8080);

$server->set([
    'open_websocket_ping_frame' => true,
    'open_websocket_pong_frame' => true,
    'open_http2_protocol' => true,
]);

// http && http2
$server->on('request', function (Request $request, Response $response) {
    $response->header('Content-Type', 'text/html');
    ob_start();
    include __DIR__ . '/../views/index.php';
    $page = ob_get_clean();
    $response->end($page);
});

// websocket
$server->on('message', function (Server $server, Frame $frame) {
    if (
        $frame->opcode == WEBSOCKET_OPCODE_BINARY
        && $frame->data == WEBSOCKET_OPCODE_PING
    ) {
        $server->push($frame->fd, WEBSOCKET_OPCODE_PONG, WEBSOCKET_OPCODE_BINARY);
        return;
    }

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
