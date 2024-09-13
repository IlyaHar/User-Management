<?php
header('Content-type: text/html');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../resources/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="container mt-5">
    <div class="mb-3">
        <button class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
        <div class="btn-group">
            <select class="form-select actionSelect">
                <option value="">-Please Select-</option>
                <option value="set-active">Set Active</option>
                <option value="set-not-active">Set Not Active</option>
                <option value="delete">Delete</option>
            </select>
            <button class="btn btn-primary mx-2 btnActionOk">OK</button>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col"><input type="checkbox" id="selectAll"></th>
            <th scope="col">#</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Status</th>
            <th scope="col">Role</th>
            <th scope="col">Options</th>
        </tr>
        </thead>
        <tbody id="tableBody">
        <?php
            $users = \App\Models\User::getAll();

            foreach ($users as $user):
        ?>
        <tr>
            <td><input type="checkbox" class="user-checkbox" data-id="<?= $user['id'] ?>"></td>
            <th scope="row"><?= $user['id'] ?></th>
            <td><?= $user['first_name'] ?></td>
            <td><?= $user['last_name'] ?></td>
            <td><span class="<?= $user['status'] ? 'status-active' : 'status-not-active' ?>"></span></td>
            <td><?= $user['role'] ?></td>
            <td>
                <button class="btn btn-outline-secondary edit-user" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-id="<?= $user['id'] ?>">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-outline-secondary delete-user"  data-bs-target="#deleteModal" data-bs-toggle="modal"
                        data-id="<?= $user['id'] ?>" data-name="<?= $user['first_name'] ?>" data-lastname="<?= $user['last_name'] ?>">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        </tr>
        <?php
            endforeach;
        ?>
        </tbody>
    </table>
    <div class="mb-3">
        <button class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
        <div class="btn-group">
            <select class="form-select actionSelect">
                <option value="">-Please Select-</option>
                <option value="set-active">Set Active</option>
                <option value="set-not-active">Set Not Active</option>
                <option value="delete">Delete</option>
            </select>
            <button class="btn btn-primary mx-2 btnActionOk">OK</button>
        </div>
    </div>
    <div class="modal fade main_modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
                    <button type="button" class="btn-close close_btn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="user_form">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First name</label>
                            <input type="text" class="form-control" id="first_name">
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last name</label>
                            <input type="text" class="form-control" id="last_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-check-label mb-1" for="status">Status</label>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="status">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1" for="role">Role</label>
                            <select class="form-select" aria-label="Default select example" id="role">
                                <option selected>-Please Select-</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </form>
                    <div class="text-danger" id="error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close_btn" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save_btn">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Delete Confirmation</h1>
                    <button type="button" class="btn-close close_btn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body confirm_delete_content">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close_btn" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" data-bs-target="#exampleModalToggle2" data-bs-dismiss="modal" id="deleteConfirm">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-warning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 warning-title" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body warning-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../../resources/js/main.js"></script>
</body>
</html>