<?php

namespace App\libs\messaging\application\usescases;

class PostMessageUseCase
{
    public function __construct()
    {
    }

    public function handle($message, $now): array
    {
        $message['publishedAt'] = $now;

        return $message;
    }
}