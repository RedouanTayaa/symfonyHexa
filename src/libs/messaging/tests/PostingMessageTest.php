<?php

namespace App\libs\messaging\tests;

use App\libs\messaging\application\DateProvider;
use App\libs\messaging\application\MessageRepository;
use App\libs\messaging\application\usescases\PostMessageUseCase;
use App\libs\messaging\domain\entity\Message;
use App\libs\messaging\domain\types\MessageEmptyException;
use App\libs\messaging\domain\types\MessageTooLongException;
use App\libs\messaging\infra\MessageFileRepository;
use App\libs\messaging\infra\StubDateProvider;
use PHPUnit\Framework\TestCase;

class PostingMessageTest extends TestCase
{
    private MessageRepository $fileMessageRepository;

    private DateProvider $stubDateProvider;

    private \Exception|null $exception;

    protected function setUp(): void
    {
        $this->stubDateProvider = new StubDateProvider();
        $path = dirname(__FILE__).'/message.json';
        $this->fileMessageRepository = new MessageFileRepository($path);
        file_put_contents($path, json_encode([]));
        $this->exception = null;
    }

    public function testBobCanPostMessageOnHisTimeline(): void
    {
        $this->givenNowIs(new \DateTime('2023-08-10T15:00:00.000Z'));

        $this->whenUserPostMessage([
            'id' => 12345,
            'text' => 'Hello, it\'s Bob',
            'author' => 'Bob',
        ]);

        $this->thenMessageShouldBe(Message::fromData([
            'id' => 12345,
            'text' => 'Hello, it\'s Bob',
            'author' => 'Bob',
            'publishedAt' => new \DateTime('2023-08-10T15:00:00.000Z'),
        ]));
    }

    public function testBobCantPostMessageMoreThan280Caracters(): void
    {
        $this->givenNowIs(new \DateTime('2023-08-10T15:00:00.000Z'));

        $this->whenUserPostMessage([
            'id' => 12345,
            'text' => 'Nam quis nulla. Integer malesuada. In in enim a arcu imperdiet malesuada. Sed vel lectus. Donec odio urna, tempus molestie, porttitor ut, iaculis quis, sem. Phasellus rhoncus. Aenean id metus id velit ullamcorper pulvinar. Vestibulum fermentum tortor id mi. Pellentesque ipsum. Nul',
            'author' => 'Bob',
        ]);

        $this->thenThrowTooLongMessageError();
    }

    public function testBobCantPostEmptyMessage(): void
    {
        $this->givenNowIs(new \DateTime('2023-08-10T15:00:00.000Z'));

        $this->whenUserPostMessage([
            'id' => 12345,
            'text' => '   ',
            'author' => 'Bob',
        ]);

        $this->thenThrowEmptyMessageError();
    }

    private function givenNowIs(\DateTime $date): void
    {
        $this->stubDateProvider->now = $date;
    }

    private function whenUserPostMessage($message): void
    {
        try {
            $postMessageUseCase = new PostMessageUseCase($this->fileMessageRepository, $this->stubDateProvider);
            $postMessageUseCase->handle($message);
        } catch (\Exception $exception) {
            $this->exception = $exception;
        }

    }

    private function thenMessageShouldBe(Message $expectedMessage): void
    {
        $post = $this->fileMessageRepository->getById($expectedMessage->getId());
        $this->assertEquals($expectedMessage, $post);
    }

    private function thenThrowTooLongMessageError(): void
    {
        $messageToLong = new MessageTooLongException();
        $this->assertNotNull($this->exception);
        $this->assertEquals($messageToLong->getMessage(), $this->exception->getMessage());
    }

    private function thenThrowEmptyMessageError(): void
    {
        $messageToLong = new MessageEmptyException();
        $this->assertNotNull($this->exception);
        $this->assertEquals($messageToLong->getMessage(), $this->exception->getMessage());
    }
}