<?php

namespace App\libs\messaging\tests;

use App\libs\messaging\application\usescases\PostMessageUseCase;
use PHPUnit\Framework\TestCase;

class PostingMessageTest extends TestCase
{
    private \DateTime $now;

    private array $post;

    protected function setUp(): void
    {
        $this->post = [];
    }

    public function testBobCanPostMessageOnHisTimeline(): void
    {
        $this->givenNowIs(new \DateTime('2023-08-10T15:00:00.000Z'));

        $this->whenUserPostMessage([
            'id' => '12345',
            'text' => 'Hello, it\'s Bob',
            'author' => 'Bob',
        ]);

        $this->thenMessageShouldBe([
            'id' => '12345',
            'text' => 'Hello, it\'s Bob',
            'author' => 'Bob',
            'publishedAt' => new \DateTime('2023-08-10T15:00:00.000Z'),
        ]);
    }

    private function givenNowIs(\DateTime $date): void
    {
        $this->now = $date;
    }

    private function whenUserPostMessage($message): void
    {
        $postMessageUseCase = new PostMessageUseCase();
        $this->post = $postMessageUseCase->handle($message, $this->now);
    }

    private function thenMessageShouldBe($expectedMessage): void
    {
        $this->assertEquals($expectedMessage, $this->post);
    }
}