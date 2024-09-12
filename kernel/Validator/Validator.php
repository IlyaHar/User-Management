<?php

namespace App\Kernel\Validator;

abstract class Validator
{
    abstract public static function validate(array $data): array;
}