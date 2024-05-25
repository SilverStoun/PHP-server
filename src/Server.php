<?php

declare(strict_types=1);

namespace App;

use App\Exception\ServerException;
use App\Http\Request;
use App\Http\Response;
use Socket;

final readonly class Server
{
    private Socket $socket;

    public function __construct(
        private string $host, 
        private int $port,
    ) {
        $this->initSocket();
    }

    public function listen(callable $callback): void
    {
        while (true) {
            socket_listen($this->socket);
            $client = socket_accept($this->socket);

            if ($client === false) {
                continue;
            }

            $request = Request::tryFromHeaderString(socket_read($client, 1024));
            $response = call_user_func($callback, $request);

            if (!$response || !$response instanceof Response) {
                $response = Response::createByStatusCode(404);
            }

            $response = (string) $response;
            socket_write($client, $response, strlen($response));
            socket_close($client);
        }
    }

    /**
     * @throws ServerException
     */
    private function initSocket(): void
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);

        if (!$socket instanceof Socket) {
            throw new ServerException('Cannot create socket');
        }

        if (!socket_bind($socket, $this->host, $this->port)) {
            throw new ServerException('Cannot bind socket: ' . socket_strerror(socket_last_error()));
        }

        $this->socket = $socket;
    }
}
