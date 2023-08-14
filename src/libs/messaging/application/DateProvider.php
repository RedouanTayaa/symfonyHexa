<?php

namespace App\libs\messaging\application;

interface DateProvider
{
    public function getNow(): \DateTime;
}