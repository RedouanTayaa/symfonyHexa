<?php

namespace App\libs\messaging\application\usescases;

use App\libs\messaging\application\DateProvider;
use App\libs\messaging\application\MessageRepository;
use App\libs\messaging\domain\entity\Message;

class PostMessageUseCase
{
    private MessageRepository $messageRepository;

    private DateProvider $dateProvider;

    public function __construct(MessageRepository $messageRepository, DateProvider $dateProvider)
    {
        $this->messageRepository = $messageRepository;
        $this->dateProvider = $dateProvider;
    }

    public function handle($messageCommand): void
    {
        $messageCommand['publishedAt'] = $this->dateProvider->getNow();
        $this->messageRepository->save(Message::fromData($messageCommand));
    }
}