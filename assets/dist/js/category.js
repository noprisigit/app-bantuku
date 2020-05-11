$(document).ready(function() {
    var table = $('#category').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "category/show-list-category",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
                "className": "text-center"
            },
            {
                "targets": [3,4,5],
                "className": "text-center"
            }
        ],
    });

    //delete category
    $(document).on('click', '.btn-delete-category', function(e){
        e.preventDefault();
        const CategoryID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "Kategori ini akan dihapus",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "category/delete-category",
                    data: { category_id : CategoryID },
                    success: function (res) {
                        Swal.fire({
                            title: "Kategori",
                            text: "Kategori berhasil dihapus",
                            icon: 'success'
                        });
                        table.ajax.reload();
                    }
                });
                return false;
            }
        })
    });

    //disable category
    $(document).on('click', '.btn-disable-category', function() {
        const CategoryID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "Kategori ini akan dinonaktifkan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Nonaktifkan'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "category/disable-category",
                    data: { category_id : CategoryID },
                    success: function () {
                        Swal.fire({
                            title: "Kategori",
                            text: "Kategori telah dinonaktifkan",
                            icon: 'success'
                        });
                        table.ajax.reload();
                    }
                });
                return false;
            }
        })
    });

    //enable category
    $(document).on('click', '.btn-enable-category', function() {
        const CategoryID = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "category/enable-category",
            data: { category_id : CategoryID },
            success: function (res) {
                Swal.fire({
                    title: "Kategori",
                    text: "Kategori telah diaktifkan",
                    icon: 'success'
                });
                table.ajax.reload();
            }
        });
        return false;
    });

    //create category
    $('#form-save-category').submit(function(e) {
        var name = $('#category_name').val();
        var description = $('#category_description').val();
        var icon = $('#category_icon').val();

        if (name == "" || description == "" || icon == "") {
            e.preventDefault();
            alert('Harap isi seluruh field');
        } else {
            $.ajax({
                url: 'category/create-category',
                type: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function() {
                    $('[name="category_name"]').val("");
                    $('[name="category_description"]').val("");
                    $('[name="category_icon"]').val("");

                    $('#modal-add').modal('hide');
                    Swal.fire({
                        title: "Kategori",
                        text: "Kategori baru berhasil ditambahkan",
                        icon: 'success'
                    });
                    table.ajax.reload();
                }
            });
            return false;
        }
    });

    //show data category in model
    $(document).on('click', '.btn-edit-category', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');

        $('#modal-edit-category').modal('show');
        $('[name="category_id"]').val(id);
        $('[name="category_name_edit"]').val(name);
        $('[name="category_description_edit"]').val(description);
    });

    //edit category
    $('#form-edit-category').submit(function(e) {
        
        var name = $('#category_name_edit').val();
        var description = $('#category_description_edit').val();

        if (name == "" || description == "") {
            e.preventDefault();
            alert('Harap isi seluruh field');
        } else {
            $.ajax({
                url: 'category/edit-category',
                type: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function() {
                    $('#modal-edit-category').modal('hide');
                    Swal.fire({
                        title: "Kategori",
                        text: "Kategori berhasil diperbaharui",
                        icon: 'success'
                    });
                    table.ajax.reload();
                }
            });
            return false;
        }
    });
});