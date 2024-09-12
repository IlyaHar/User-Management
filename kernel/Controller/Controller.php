<?php

namespace App\Kernel\Controller;

abstract class Controller
{
    protected string $type;

    public function __construct()
    {
        $this->type = $_GET['q'] ?? '';
    }

    public function handleRequest(): void
    {
        if ($this->type === '') {
            require_once APP_DIR . '/resources/views/home.php';
            exit();
        }

        header('Content-type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->handleGet();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->handlePut();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->handleDelete();
        }
    }

    abstract protected function handleGet(): void;
    abstract protected function handlePost(): void;
    abstract protected function handlePut(): void;
    abstract protected function handleDelete(): void;
}