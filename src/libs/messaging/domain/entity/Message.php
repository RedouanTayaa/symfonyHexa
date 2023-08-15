<?php

namespace App\libs\messaging\domain\entity;

use App\libs\messaging\domain\types\MessageText;

class Message implements \JsonSerializable
{
    private ?int $id;
    private string $author;
    private string $text;
    private \DateTime $publishedAt;

    /**
     * @param string $id
     * @param string $author
     * @param string $text
     * @param \DateTime $publishedAt
     */
    public function __construct(int|null $id, string $author, string $text, \DateTime $publishedAt)
    {
        $this->id = $id;
        $this->author = $author;
        $this->text = MessageText::of($text);
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    public function editText($text): void
    {
        $this->text = $text;
    }

    public static function fromData($data): Message
    {
        return new Message($data['id'] ?? null, $data['author'], $data['text'], $data['publishedAt']);
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}