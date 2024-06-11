<?php

namespace App\Providers;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Notification implements MessageComponentInterface
{
    protected SplObjectStorage $clients;
    protected array $clientInfo;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;
        $this->clientInfo = [];
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        echo "New connection! ($conn->resourceId)\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg);

        if (isset($data->type) && $data->type === 'identify') {
            $this->clientInfo[$from->resourceId] = $data->userId;
            echo "User identified: $data->userId (Connection: $from->resourceId)\n";
            return;
        }

        if (isset($data->type) && $data->type === 'new_message') {
            echo "New message for user: $data->userId\n";
            $this->sendToUser($data->userId, json_encode([
                'type' => 'chat',
                'userId' => $data->userId,
                'message' => $data->message,
                'message_id' => $data->message_id,
                'created_at' => $data->created_at,
                'is_read' => false
            ]));
        }

        if (isset($data->type) && $data->type === 'mark_read') {
            echo "Marking messages as read for user: $data->userId\n";
            $this->markMessagesAsRead($data->userId);
        }
    }

    public function sendToUser($userId, $msg): void
    {
        foreach ($this->clients as $client) {
            if (isset($this->clientInfo[$client->resourceId]) && $this->clientInfo[$client->resourceId] === $userId) {
                $client->send($msg);
                echo "Message sent to user $userId on connection $client->resourceId\n";
            }
        }
    }

    public function markMessagesAsRead($userId): void
    {
        foreach ($this->clients as $client) {
            if (isset($this->clientInfo[$client->resourceId]) && $this->clientInfo[$client->resourceId] === $userId) {
                $client->send(json_encode(['type' => 'status_update', 'userId' => $userId, 'is_read' => true]));
                echo "Marked messages as read for user $userId on connection $client->resourceId\n";
            }
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        unset($this->clientInfo[$conn->resourceId]);
        echo "Connection $conn->resourceId has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
