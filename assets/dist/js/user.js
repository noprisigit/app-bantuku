$(document).ready(function () {   

    var table_role = $('#role').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "role/show-list-role",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false,
                "className": "text-center"
            },
            { 
                "targets": [ 2 ],
                "className": "text-center" 
            },
        ],
    });

    var table_users = $('#users').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "user/show-list-users",
            "type": "post"
        },
        "columnDefs": [
            {
                "targets": [ 0 ],
                "orderable": false,
                "className": "text-center"
            },
            {
                "targets": [ 3,4 ],
                "orderable": false,
                "className": "text-center"
            },
            {
                "targets": [ 2,5,6 ],
                "className": "text-center"
            }
        ]
    });

    setInterval(function(){
        table_users.ajax.reload();
    }, 10000)


    $(document).on('click', '.btn-edit-role', function() {
        $('#modal-edit-role').modal('show');

        $('#edit_access_id').val($(this).data('id'));
        $('#edit_access_name').val($(this).data('name'));
    });

    $('#form-edit-role').submit(function(e) {
        var id = $('#edit_access_id').val();
        var name = $('#edit_access_name').val();

        if (name == "") {
            e.preventDefault();
            toastr.error('Please fill all the field');
        } else {
            $.ajax({
                url: 'role/role_edit',
                type: 'post',
                data: { id: id, name: name },
                success: function() {
                    $('[name="access_id"]').val("");
                    $('[name="access_name"]').val("");

                    $('#modal-edit-role').modal('hide');

                    Swal.fire({
                        title: "Role",
                        text: "Role telah diperbaharui",
                        icon: 'success'
                    });
                    table_role.ajax.reload();
                }
            });
            return false
        }
    });

    $(document).on('click', '.btn-change-password', function() {
        $('#modal-change-password').modal('show');
        var id = $(this).data('id');

        $('#admin_id').val(id);
    });

    $('#toggle-password').on('click', function() {
        var input = $('#password_baru');

        if (input.attr('type') === "password") {
            input.attr('type', 'text');
            $(this).html('<i class="fas fa-eye-slash"></i>');
        } else {
            input.attr('type', 'password');
            $(this).html('<i class="fas fa-eye"></i>');
        }
    });

    $('#toggle-confirm-password').on('click', function() {
        var input = $('#confirm_password_baru');

        if (input.attr('type') === "password") {
            input.attr('type', 'text');
            $(this).html('<i class="fas fa-eye-slash"></i>');
        } else {
            input.attr('type', 'password');
            $(this).html('<i class="fas fa-eye"></i>');
        }
    });

    $('#toggle-password-akun').on('click', function() {
        var input = $('#admin_password');

        if (input.attr('type') === "password") {
            input.attr('type', 'text');
            $(this).html('<i class="fas fa-eye-slash"></i>');
        } else {
            input.attr('type', 'password');
            $(this).html('<i class="fas fa-eye"></i>');
        }
    });

    $('#toggle-confirm-password-akun').on('click', function() {
        var input = $('#admin_confirm_password');

        if (input.attr('type') === "password") {
            input.attr('type', 'text');
            $(this).html('<i class="fas fa-eye-slash"></i>');
        } else {
            input.attr('type', 'password');
            $(this).html('<i class="fas fa-eye"></i>');
        }
    });

    $('#form-ubah-password').submit(function(e) {
        var id = $('#admin_id').val();
        var password = $('#password_baru').val();
        var confirm_password = $('#confirm_password_baru').val();

        if (password == "" || confirm_password == "") {
            e.preventDefault();
            toastr.error('Harap isi semua kolom');
        } else {
            if (confirm_password != password) {
                e.preventDefault();
                toastr.error('Password dan konfirmasi password harus sama');
            } else {
                $.ajax({
                    url: 'user/password_change',
                    type: 'post',
                    data: $(this).serialize(),
                    success: function() {
                        $('[name="password_baru"]').val("");
                        $('[name="confirm_password"]').val("");
                        $('#modal-change-password').modal('hide');

                        Swal.fire({
                            title: "Users",
                            text: "Password telah diperbaharui",
                            icon: 'success'
                        });
                        table_users.ajax.reload();
                    }
                });
                return false;
            }
        }
    });

    $('#form-save-account').submit(function(e) {
        var nama = $('#admin_name').val();
        var username = $('#admin_username').val();
        var password = $('#admin_password').val();
        var confirm_password = $('#admin_confirm_password').val();
        var status_access = $('#admin_status_akses').val();

        if (nama == "" || username == "" || password == "" || confirm_password == "" || status_access == "") {
            e.preventDefault();
            toastr.error('Harap isi semua kolom');
        } else {
            if (password != confirm_password) {
                e.preventDefault();
                toastr.error('Password dan konfirmasi password harus sama');
            } else {
                $.ajax({
                    url: 'user/user_save',
                    type: 'post',
                    data: $(this).serialize(),
                    success: function() {
                        $('[name="name"]').val("");
                        $('[name="username"]').val("");
                        $('[name="password"]').val("");
                        $('[name="confirm_password"]').val("");
                        $('[name="status_akses"]').empty();
                        $('[name="status_akses"]').append('<option selected disabled>Status Akses</option>');
                        $('[name="status_akses"]').append('<option value="1">Super Administrator</option>');
                        $('[name="status_akses"]').append('<option value="2">Administrator</option>');

                        $('#modal-add-account').modal('hide');

                        Swal.fire({
                            title: "Users",
                            text: "Akun baru berhasil ditambahkan",
                            icon: 'success'
                        });
                        table_users.ajax.reload();
                    }
                });
                return false;
            }
        }
    });

    $(document).on('click', '.btn-activate-account', function() {
        var id = $(this).data('id');

        $.ajax({
            url: 'user/activate-account',
            type: 'post',
            data: { AdminID : id },
            success: function() {
                Swal.fire({
                    title: "User",
                    text: "Akun berhasil diaktifkan",
                    icon: 'success'
                });
                table_users.ajax.reload();
            }
        });
        return false;
    });

    $(document).on('click', '.btn-nonactivate-account', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Akun ini akan dimatikan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Matikan!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "user/nonactivate-account",
                    data: { AdminID : id },
                    success: function (res) {
                        Swal.fire({
                            title: "User",
                            text: "Akun telah dimatikan",
                            icon: 'success'
                        });
                        table_users.ajax.reload();
                    }
                });
                return false;
            }
        });
    });
});



