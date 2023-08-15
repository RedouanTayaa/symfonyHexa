<?php

namespace App\libs\messaging\infra;

use \App\libs\messaging\application\DateProvider as DateProviderInterface;

class DateProvider implements DateProviderInterface
{
    public function getNow(): \DateTime
    {
        return new \DateTime();
    }
}