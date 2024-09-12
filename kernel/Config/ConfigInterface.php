<?php

namespace App\Kernel\Config;

interface ConfigInterface
{
    public function get(string $key): string;
}