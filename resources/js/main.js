$(document).ready(() => {
    let users = []
    let editingUserId = null;
    let selectedIds = []
    let error = null

    function getUsers() {
        $.ajax({
            url: 'http://localhost:82/users',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                users = response.users
                let html = ''

                users.forEach(user => {
                    html += `
                      <tr>
                        <td><input type="checkbox" class="user-checkbox" data-id="${user.id}"></td>
                        <th scope="row">${user.id}</th>
                        <td>${user.first_name}</td>
                        <td>${user.last_name}</td>
                        <td><span class="${user.status ? 'status-active' : 'status-not-active'}"></span></td>
                        <td>${user.role}</td>
                        <td>
                            <button class="btn btn-outline-secondary edit-user" data-bs-toggle="modal" data-bs-target="#exampleModal"
                            data-id="${user.id}">
                            <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-outline-secondary delete-user"  data-bs-target="#deleteModal" data-bs-toggle="modal"
                              data-id="${user.id}" data-name="${user.first_name}" data-lastname="${user.last_name}">
                              <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                     </tr>`;
                });
                $('#tableBody').html(html)
            },
        });
    }

    getUsers();

    $('.close_btn').click(function () {
        editingUserId = null
        $('#user_form')[0].reset()
        $('#exampleModalLabel').text('Add User')
        $('#save_btn').text('Add')
        selectedIds = []
        error = null
        $('#error').text('')
    });

    $('#save_btn').click(function() {
        $('#error').empty();
        const first_name = $('#first_name').val();
        const last_name = $('#last_name').val();
        const status = $('#status').is(':checked') ? 1 : 0;
        const role = $('#role').val();


        if (editingUserId) {
            $.ajax({
                url: `http://localhost:82/users?id=${editingUserId}`,
                type: 'PUT',
                data: {first_name, last_name, status, role},
                success: function (response) {
                    if (response.error) {
                        error = response.error
                        $('#error').text(error.shift())
                    } else {
                        $('.main_modal').modal('hide')
                        getUsers()
                        editingUserId = null
                        $('#exampleModalLabel').text('Add User')
                        $('#save_btn').text('Add')
                        $('#user_form')[0].reset()
                        error = null
                    }
                }
            })
        } else {

            $.ajax({
                url: 'http://localhost:82/users',
                type: 'POST',
                data: {first_name, last_name, status, role},
                success: function (response) {
                    if (response.error) {
                        error = response.error
                        $('#error').text(error.shift())
                    } else {
                        $('.main_modal').modal('hide')
                        getUsers()
                        $('#user_form')[0].reset()
                        error = null
                    }
                },
            });
        }
    });

    $(document).on('click', '.edit-user', function () {
        const userId = $(this).data('id');
        editingUserId = userId;
        const user = users.find(user => user.id === userId)

        $('#first_name').val(user.first_name)
        $('#last_name').val(user.last_name)
        $('#status').prop('checked', user.status)
        $('#role').val(user.role)

        $('#exampleModalLabel').text('Update User')
        $('#save_btn').text('Update')
    });

    $(document).on('click', '.delete-user', function () {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userLastName = $(this).data('lastname');

        $('.confirm_delete_content').text(`Are you sure you want to delete  ${userName} ${userLastName}`);
        $('#deleteConfirm').data('id', userId);
    });

    $('#deleteConfirm').click(function () {
        const userId = $(this).data('id')

        $.ajax({
            url: `http://localhost:82/users?id=${userId}`,
            type: 'DELETE',
            success: function (response) {
                getUsers()

            }
        })
    });


    $('.btnActionOk').click(function () {
        const action = $(this).siblings('.actionSelect').val();
          selectedIds = $('.user-checkbox:checked').map(function () {
            return $(this).data('id')
        }).get()

        if (selectedIds.length === 0 && action) {
            $('.warning-title').text('Select at least one user')
            $('.warning-body').text('You didn\'t select any user')

            $('#modal-warning').modal('show')
        }

        if (selectedIds.length > 0 && !action) {
            $('.warning-title').text('You need to select action')
            $('.warning-body').text('You didn\'t select any action')
            $('#modal-warning').modal('show')
        }


        if (action === 'set-active' || action === 'set-not-active') {
            $(this).removeAttr('data-bs-target');
            const status = action === 'set-active' ? 1 : 0

            $.ajax({
                url: 'http://localhost:82/users/update',
                type: 'POST',
                data: {ids: selectedIds, status: status},
                success: function (response) {
                    getUsers()
                    $('#selectAll').prop('checked', false);
                    $('.user-checkbox').prop('checked', false);
                },
            })
        } else if (action === 'delete' && selectedIds.length > 0) {
            const names = selectedIds.map(id => {
                const user = users.find(user => user.id === id);
                return user ? `${user.first_name} ${user.last_name}` : '';
            }).filter(name => name).join(', ');

            $('#deleteModal').modal('show');

            $('.confirm_delete_content').text(`Are you sure you want to delete: ${names}`);

            $('#deleteConfirm').click(function () {
                $.ajax({
                    url: 'http://localhost:82/users/delete',
                    type: 'POST',
                    data: {ids: selectedIds},
                    success: function (response) {
                        getUsers()
                        $('#selectAll').prop('checked', false);
                        $('.user-checkbox').prop('checked', false);
                    }
                })
            });
        }
    });


    $(document).on('change', '.user-checkbox', function() {
        const allChecked = $('.user-checkbox').length === $('.user-checkbox:checked').length;
        $('#selectAll').prop('checked', allChecked);
    });

    $('#selectAll').click(function () {
        const isChecked = $(this).prop('checked')
        $('.user-checkbox').prop('checked', isChecked)
    });
});
