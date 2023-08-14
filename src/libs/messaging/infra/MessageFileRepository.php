<?php

namespace App\libs\messaging\infra;

use App\libs\messaging\application\MessageRepository;
use App\libs\messaging\domain\entity\Message;

class MessageFileRepository implements MessageRepository
{
    private string $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function save(Message $message): void
    {
        $messages = $this->getAllMessages();

        $messages[$message->getId()] = $message;

        file_put_contents($this->path, json_encode($messages));
    }

    public function getAllOfUser(string $author): array
    {
        // TODO: Implement getAllOfUser() method.
        return [];
    }

    public function getById(string $id): Message|null
    {
        // TODO: Implement getById() method.
        $messages = $this->getAllMessages();

        if (array_key_exists($id, $messages)) {
            return $messages[$id];
        }

        return null;
    }

    private function getAllMessages(): array
    {
        $fileContent = file_get_contents($this->path);

        if ($fileContent === false) {
            throw new \Exception('File don\'t exist');
        }

        $datas = json_decode($fileContent, true);
        $messages = [];
        foreach ($datas as $data) {
            $data['publishedAt'] = new \DateTime($data['publishedAt']['date']);
            $message = Message::fromData($data);
            $messages[$message->getId()] = $message;
        }

        return $messages;
    }
}