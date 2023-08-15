<?php

namespace App\libs\messaging\domain\types;

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class MessageTooLongException extends \Exception
{
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $message = "Message too long",
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $code = 0,
        #[LanguageLevelTypeAware(['8.0' => 'Throwable|null'], default: 'Throwable')] $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}