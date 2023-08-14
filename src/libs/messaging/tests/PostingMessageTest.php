<?php

namespace App\libs\messaging\tests;

use App\libs\messaging\application\DateProvider;
use App\libs\messaging\application\MessageRepository;
use App\libs\messaging\application\usescases\PostMessageUseCase;
use App\libs\messaging\domain\entity\Message;
use App\libs\messaging\infra\MessageFileRepository;
use App\libs\messaging\infra\StubDateProvider;
use PHPUnit\Framework\TestCase;

class PostingMessageTest extends TestCase
{
    private MessageRepository $fileMessageRepository;

    private DateProvider $stubDateProvider;

    protected function setUp(): void
    {
        $this->stubDateProvider = new StubDateProvider();
        $this->fileMessageRepository = new MessageFileRepository(dirname(__FILE__).'/message.json');
    }

    public function testBobCanPostMessageOnHisTimeline(): void
    {
        $this->givenNowIs(new \DateTime('2023-08-10T15:00:00.000Z'));

        $this->whenUserPostMessage([
            'id' => '12345',
            'text' => 'Hello, it\'s Bob',
            'author' => 'Bob',
        ]);

        $this->thenMessageShouldBe(Message::fromData([
            'id' => '12345',
            'text' => 'Hello, it\'s Bob',
            'author' => 'Bob',
            'publishedAt' => new \DateTime('2023-08-10T15:00:00.000Z'),
        ]));
    }

    private function givenNowIs(\DateTime $date): void
    {
        $this->stubDateProvider->now = $date;
    }

    private function whenUserPostMessage($message): void
    {
        $postMessageUseCase = new PostMessageUseCase($this->fileMessageRepository, $this->stubDateProvider);
        $postMessageUseCase->handle($message);
    }

    private function thenMessageShouldBe(Message $expectedMessage): void
    {
        $post = $this->fileMessageRepository->getById($expectedMessage->getId());
        $this->assertEquals($expectedMessage, $post);
    }
}