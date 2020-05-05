var table;
var table_partner;

$(document).ready(function () {   
    table = $('#category').DataTable({ 
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

    table_partner = $('#partner').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "partner/show-list-partner",
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
            },
            {
                "targets": [-1],
                "className": "text-center"
            }
        ],
    });

    const flashData = $('.flash-data').data('flashdata');
    const title = $('.flash-data').data('title');

    if (flashData) {
        Swal.fire({
            title: title,
            text: flashData,
            icon: 'success'
        });
    }

    //delete category
    $(document).on('click', '.btn-delete-category', function(e){
        e.preventDefault();
        const CategoryID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This category will be deleted",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "category/delete-category",
                    data: { category_id : CategoryID },
                    success: function (res) {
                        Swal.fire({
                            title: "Category",
                            text: "Category has been deleted",
                            icon: 'success'
                        });
                        table.ajax.reload();
                    }
                });
            }
        })
    });

    //disable category
    $(document).on('click', '.btn-disable-category', function() {
        const CategoryID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This category will be disabled",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "category/disable-category",
                    data: { category_id : CategoryID },
                    success: function (res) {
                        Swal.fire({
                            title: "Category",
                            text: "Category has been disabled",
                            icon: 'success'
                        });
                        table.ajax.reload();
                    }
                });
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
                    title: "Category",
                    text: "Category has been enable",
                    icon: 'success'
                });
                table.ajax.reload();
            }
        });
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
                    $('#modal-add').modal('hide');
                    Swal.fire({
                        title: "Category",
                        text: "Category has been added",
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
                        title: "Category",
                        text: "Category has been updated",
                        icon: 'success'
                    });
                    table.ajax.reload();
                }
            });
            return false;
        }
    });

    //tambah mitra
    $('#form-save-partner').submit(function(e) {
        var nama_toko = $('#nama_toko').val();
        var nama_pemilik = $('#nama_pemilik').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var alamat = $('#alamat').val();

        if (nama_toko == "" || nama_pemilik == "" || phone == "" || email == "" || alamat == "") {
            e.preventDefault();
            alert('Field tidak boleh ada yang kosong');
        } else {
            $.ajax({
                url: 'partner/save-partner',
                type: 'post',
                data: { nama_toko: nama_toko, nama_pemilik: nama_pemilik, phone: phone, email: email, alamat: alamat },
                success: function() {
                    $('[name="nama_toko"]').val("");
                    $('[name="nama_pemilik"]').val("");
                    $('[name="phone"]').val("");
                    $('[name="email"]').val("");
                    $('[name="alamat"]').val("");

                    $('#modal-add-partner').modal('hide');
                    Swal.fire({
                        title: "Partners",
                        text: "Partners has been added",
                        icon: 'success'
                    });
                    table_partner.ajax.reload();
                }
            });
            return false
        }
    });

    $(document).on('click', '.btn-detail-partner', function() {
        const uniqueID = $(this).data('uniqueid');
        const nama_toko = $(this).data('nama_toko');
        const nama_pemilik = $(this).data('nama_pemilik');
        const alamat = $(this).data('alamat');
        const phone = $(this).data('phone');
        const email = $(this).data('email');

        $('#det-part-uniqueID').empty();
        $('#det-part-nama-toko').empty();
        $('#det-part-nama-pemilik').empty();
        $('#det-part-alamat').empty();
        $('#det-part-phone').empty();
        $('#det-part-email').empty();

        $('#det-part-uniqueID').html(": " + uniqueID);
        $('#det-part-nama-toko').html(": " + nama_toko);
        $('#det-part-nama-pemilik').html(": " + nama_pemilik);
        $('#det-part-alamat').html(": " + alamat);
        $('#det-part-phone').html(": " + phone);
        $('#det-part-email').html(": " + email);
    });

    $(document).on('click', '.btn-edit-partner', function() {
        const id = $(this).data('id');
        const uniqueID = $(this).data('uniqueid');
        const nama_toko = $(this).data('nama_toko');
        const nama_pemilik = $(this).data('nama_pemilik');
        const alamat = $(this).data('alamat');
        const phone = $(this).data('phone');
        const email = $(this).data('email');

        $('#modal-edit-partner').modal('show');
        $('[name="partner_id"]').val(id);
        $('[name="partner_uniqueid_edit"]').val(uniqueID);
        $('[name="partner_nama_toko_edit"]').val(nama_toko);
        $('[name="partner_nama_pemilik_edit"]').val(nama_pemilik);
        $('[name="partner_alamat_edit"]').val(alamat);
        $('[name="partner_phone_edit"]').val(phone);
        $('[name="partner_email_edit"]').val(email);
    });

    $('#form-edit-partner').submit(function(e) {
        var id = $('#partner_id').val();
        var uniqueID = $('#partner_uniqueid_edit').val();
        var nama_toko = $('#partner_nama_toko_edit').val();
        var nama_pemilik = $('#partner_nama_pemilik_edit').val();
        var phone = $('#partner_phone_edit').val();
        var email = $('#partner_email_edit').val();
        var alamat = $('#partner_alamat_edit').val();

        if (nama_toko == "" || nama_pemilik == "" || phone == "" || email == "" || alamat == "") {
            e.preventDefault();
            alert('Field tidak boleh ada yang kosong');
        } else {
            $.ajax({
                url: 'partner/edit-partner',
                type: 'post',
                data: { uniqueid: uniqueID, nama_toko: nama_toko, nama_pemilik: nama_pemilik, phone: phone, email: email, alamat: alamat },
                success: function() {
                    $('[name="partner_id"]').val("");
                    $('[name="partner_uniqueid_edit"]').val("");
                    $('[name="partner_nama_toko_edit"]').val("");
                    $('[name="partner_nama_pemilik_edit"]').val("");
                    $('[name="partner_alamat_edit"]').val("");
                    $('[name="partner_phone_edit"]').val("");
                    $('[name="partner_email_edit"]').val("");

                    $('#modal-edit-partner').modal('hide');
                    Swal.fire({
                        title: "Partners",
                        text: "Partners has been updated",
                        icon: 'success'
                    });
                    table_partner.ajax.reload();
                },
                error: function(err) {
                    console.log(err);
                }
            });
            return false
        }
    });

    $(document).on('click', '.btn-delete-partner', function(e){
        e.preventDefault();
        const PartnerID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This partner will be deleted",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "partner/delete-partner",
                    data: { partner_id : PartnerID },
                    success: function () {
                        Swal.fire({
                            title: "Partner",
                            text: "Partner has been deleted",
                            icon: 'success'
                        });
                        table_partner.ajax.reload();
                    }
                });
            }
        })
    });
});



