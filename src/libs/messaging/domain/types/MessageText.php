<?php

namespace App\libs\messaging\domain\types;

class MessageText
{
    public static function of(string $text): string
    {
        if (strlen($text) > 280) {
            throw new MessageTooLongException();
        }

        if (strlen(trim($text)) === 0) {
            throw new MessageEmptyException();
        }

        return $text;
    }
}