<?php

namespace App\Kernel\Container;
use App\Controllers\UserController;
use App\Kernel\Config\Config;
use App\Kernel\Config\ConfigInterface;
use App\Kernel\Database\Model;
use App\Kernel\Database\ModelInterface;
use App\Kernel\Validator\Validator;
use App\Validators\UserValidator;

class Container
{
    public readonly ConfigInterface $config;
    public readonly ModelInterface $database;
    public readonly Validator $validator;

    public function __construct()
    {
        $this->registerServices();
        (new UserController())->handleRequest();
    }

    private function registerServices(): void
    {
        $this->config = new Config();
        $this->validator = new UserValidator();
        $this->database = new Model($this->config);
    }
}