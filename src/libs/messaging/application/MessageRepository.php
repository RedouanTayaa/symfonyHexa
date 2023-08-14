<?php

namespace App\libs\messaging\application;

use App\libs\messaging\domain\entity\Message;

interface MessageRepository
{
    public function save(Message $message): void;
    public function getAllOfUser(string $author): array;
    public function getById(string $id): Message|null;
}