<?php

namespace App\libs\messaging\infra;

use App\libs\messaging\application\DateProvider;

class StubDateProvider implements DateProvider
{
    public \DateTime $now;
    public function getNow(): \DateTime
    {
        return $this->now;
    }
}