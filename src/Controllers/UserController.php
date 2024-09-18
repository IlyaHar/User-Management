<?php

namespace App\Controllers;

use App\Kernel\Controller\Controller;
use App\Models\User;
use App\Validators\UserValidator;

class UserController extends Controller
{
    protected function handleGet(): void
    {
        echo match ($this->type) {
            'users' => json_encode(['status' => true, 'code' => 200, 'error' => null, 'users' => User::getAll()]),
            'user' => json_encode(['status' => true, 'code' => 200, 'error' => null, 'user' => User::find($_GET['id'])])
        };
    }

    protected function handlePost(): void
    {
        $ids = $_POST['ids'] ?? null;
        unset($_POST['ids']);

        if (isset($_POST['first_name'])) {
            $errors = UserValidator::validate($_POST);
            $this->checkErrors($errors);
        }

        echo match ($this->type) {
            'users' => json_encode(['status' => true, 'code' => 201,'error' => null, 'user' => User::create($_POST), 'users' => User::getAll()]),
            'users/update' => json_encode(['status' => true, 'code' => 200,'error' => null, 'user' => User::updateMany($_POST, $ids), 'users' => User::getAll()]),
            'users/delete' => json_encode(['status' => true, 'code' => 200,'error' => null, 'user' => User::deleteMany($ids), 'users' => User::getAll()]),
        };
    }

    protected function handlePut(): void
    {
        parse_str(file_get_contents('php://input'), $put_vars);

        $errors = UserValidator::validate($put_vars);
        $this->checkErrors($errors);

        echo match ($this->type) {
            'users' => json_encode(['status' => true, 'code' => 201,'error' => null, 'user' => User::update($put_vars, $_GET['id']), 'users' => User::getAll()]),
        };
    }

    protected function handleDelete(): void
    {
        echo match ($this->type) {
            'users' => json_encode(['status' => true, 'code' => 201,'error' => null, 'user' => User::delete($_GET['id']), 'users' => User::getAll()]),
        };
    }

    private function checkErrors(array $errors): void
    {
        if (!empty($errors)) {
            echo json_encode(['status' => false, 'code' => 422, 'error' => $errors]);
            exit();
        }
    }
}