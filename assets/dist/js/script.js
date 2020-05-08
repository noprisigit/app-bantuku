var table;
var table_partner;
var table_slider;
var table_product;

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

    table_slider = $('#slider').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "slider/show-list-slider",
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

    table_product = $('#product').DataTable({
        // "scrollX":        true,
        // "scrollCollapse": true,
        // "width": "100%",
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "product/show-list-products",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
                "className": "text-center"
            },
            { 
                "targets": [ 1 ], 
                "className": "text-center"
            },
            {
                "targets": [ 3,4,5,6 ],
                "className": "text-center"
            },
            { 
                "targets": [ 8,9 ], 
                "className": "text-center"
            },
        ],
    });


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
                    $('[name="category_name"]').val("");
                    $('[name="category_description"]').val("");
                    $('[name="category_icon"]').val("");

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
        var provinsi = $('#provinsi').val();
        var kabupaten = $('#kabupaten').val();
        var kode_pos = $('#kode-pos').val();
        var gambar = $('#gambar_toko').val();

        if (nama_toko == "" || nama_pemilik == "" || phone == "" || email == "" || alamat == "" || provinsi == "" || kabupaten == "" || kode_pos == "" || gambar == "") {
            e.preventDefault();
            toastr.error('Please fill all the field');
        } else {
            $.ajax({
                url: 'partner/save-partner',
                type: 'post',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.status == false) {
                        toastr.error(res.msg);
                    } else {
                        
                        $('[name="nama_toko"]').val("");
                        $('[name="nama_pemilik"]').val("");
                        $('[name="phone"]').val("");
                        $('[name="email"]').val("");
                        $('[name="alamat"]').val("");
                        $('[name="provinsi"]').empty();
                        $('[name="provinsi"]').append('<option selected disabled>Provinsi</option>');
                        for (var i = 0; i < res.provinsi.length; i++) {
                            $('[name="provinsi"]').append('<option value="' + res.provinsi[i]['ProvinceID'] + '">' + res.provinsi[i]['ProvinceName'] + '</option>')
                        }
                            
                        $('[name="kabupaten"]').empty();
                        $('[name="kabupaten"]').append('<option selected disabled>Kabupaten</option>')
                        $('[name="kode_pos"]').val("");
                        $('[name="gambar_toko"]').val("");
    
                        $('#modal-add-partner').modal('hide');
                        Swal.fire({
                            title: "Partners",
                            text: "Partners has been added",
                            icon: 'success'
                        });
                        table_partner.ajax.reload();
                    }
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
        const provinsi = $(this).data('provinsi');
        const kabupaten = $(this).data('kabupaten');
        const pos = $(this).data('pos');
        const gambar = $(this).data('gambar');

        $('#det-part-uniqueID').empty();
        $('#det-part-nama-toko').empty();
        $('#det-part-nama-pemilik').empty();
        $('#det-part-alamat').empty();
        $('#det-part-phone').empty();
        $('#det-part-email').empty();

        $('#det-part-uniqueID').html(": " + uniqueID);
        $('#det-part-nama-toko').html(": " + nama_toko);
        $('#det-part-nama-pemilik').html(": " + nama_pemilik);
        $('#det-part-alamat').html(": " + alamat + " " + kabupaten + " " + provinsi + " " + pos);
        $('#det-part-phone').html(": " + phone);
        $('#det-part-email').html(": " + email);
        $('#shop-picture').attr('src', 'assets/dist/img/partners/' + gambar);
    });

    $(document).on('click', '.btn-edit-partner', function() {
        const id = $(this).data('id');
        const uniqueID = $(this).data('uniqueid');
        const nama_toko = $(this).data('nama_toko');
        const nama_pemilik = $(this).data('nama_pemilik');
        const alamat = $(this).data('alamat');
        const phone = $(this).data('phone');
        const email = $(this).data('email');
        const provinsi = $(this).data('provinsi');
        const kabupaten = $(this).data('kabupaten');
        const pos = $(this).data('pos');
        const gambar = $(this).data('gambar');

        $.ajax({
            url: 'partner/province_get_by_name',
            type: 'post',
            dataType: 'json',
            data: { ProvinceName: provinsi },
            success: function(res) {
                $('#partner_provinsi_edit').empty();
                $('#partner_provinsi_edit').append('<option selected disabled>Provinsi</option>');
                for (var i = 0; i < res.all_provinsi.length; i++) {
                    if (res.all_provinsi[i]['ProvinceID'] == res.provinsi['ProvinceID']) {
                        $('#partner_provinsi_edit').append('<option value="'+ res.all_provinsi[i]['ProvinceID'] +'" selected>'+ res.all_provinsi[i]['ProvinceName'] +'</option>');
                    } else {
                        $('#partner_provinsi_edit').append('<option value="'+ res.all_provinsi[i]['ProvinceID'] +'">'+ res.all_provinsi[i]['ProvinceName'] +'</option>');
                    }
                }
                
                $('#partner_kabupaten_edit').empty();
                $('#partner_kabupaten_edit').append('<option selected disabled>Kabupaten</option>');
                for (var i = 0; i < res.kabupaten.length; i++) {
                    if (res.kabupaten[i]['DistrictName'] == kabupaten) {
                        $('#partner_kabupaten_edit').append('<option value="'+ res.kabupaten[i]['DistrictName'] +'" selected>'+ res.kabupaten[i]['DistrictName'] +'</option>');
                    } else {
                        $('#partner_kabupaten_edit').append('<option value="'+ res.kabupaten[i]['DistrictName'] +'">'+ res.kabupaten[i]['DistrictName'] +'</option>');
                    }
                }
            }
        });

        $('#modal-edit-partner').modal('show');
        $('[name="partner_id"]').val(id);
        $('[name="partner_uniqueid_edit"]').val(uniqueID);
        $('[name="partner_nama_toko_edit"]').val(nama_toko);
        $('[name="partner_nama_pemilik_edit"]').val(nama_pemilik);
        $('[name="partner_alamat_edit"]').val(alamat);
        $('[name="partner_phone_edit"]').val(phone);
        $('[name="partner_email_edit"]').val(email);
        $('[name="partner_kode_pos_edit"]').val(pos);
        
    });

    $('#form-edit-partner').submit(function(e) {
        var id = $('#partner_id').val();
        var uniqueID = $('#partner_uniqueid_edit').val();
        var nama_toko = $('#partner_nama_toko_edit').val();
        var nama_pemilik = $('#partner_nama_pemilik_edit').val();
        var phone = $('#partner_phone_edit').val();
        var email = $('#partner_email_edit').val();
        var alamat = $('#partner_alamat_edit').val();
        var provinsi = $('#partner_provinsi_edit').val();
        var kabupaten = $('#partner_kabupaten_edit').val();
        var kode_pos = $('#partner_kode_pos_edit').val();
        
        if (nama_toko == "" || nama_pemilik == "" || phone == "" || email == "" || alamat == "" || provinsi == "" || kabupaten == "" || kode_pos == "") {
            e.preventDefault();
            toastr.error('Please fill all the field');
        } else {
            $.ajax({
                url: 'partner/edit-partner',
                type: 'post',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if(res.status == false) {
                        toastr.error(res.msg);
                    } else {
                        $('[name="partner_id"]').val("");
                        $('[name="partner_uniqueid_edit"]').val("");
                        $('[name="partner_nama_toko_edit"]').val("");
                        $('[name="partner_nama_pemilik_edit"]').val("");
                        $('[name="partner_alamat_edit"]').val("");
                        $('[name="partner_phone_edit"]').val("");
                        $('[name="partner_email_edit"]').val("");
                        $('[name="partner_kode_pos_edit"]').val("");
                        $('[name="partner_provinsi_edit"]').empty();
                        $('[name="partner_kabupaten_edit"]').empty();
                        $('[name="partner_gambar_toko_edit"]').val("");
    
                        $('#modal-edit-partner').modal('hide');
                        Swal.fire({
                            title: "Partners",
                            text: "Partners has been updated",
                            icon: 'success'
                        });
                        table_partner.ajax.reload();
                    }
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

    $('#form-save-slider').on('submit', function(e) {
        const name = $('#slider-name').val();
        const description = $('#slider-description').val();
        const start_date = $('#slider-start').val();
        const end_date = $('#slider-end').val();
        const picture = $('#slider-picture').val();

        const checkStartDate = new Date($('#slider-start').val());
        const checkEndDate = new Date($('#slider-end').val());
        const currentDate = new Date();

        if (name == "" || description == "" || start_date == "" || end_date == "" || picture == "") {
            e.preventDefault();
            toastr.error('Please fill all the field');
        } else {
            if (checkStartDate < currentDate) {
               e.preventDefault();
               toastr.error('Tanggal mulai sudah lewat');
            } else if (checkStartDate > checkEndDate) {
                e.preventDefault();
                toastr.error('Tanggal mulai tidak boleh lebih besar dari tanggal berakhir');
            } else {
                $.ajax({
                    url: 'slider/slider-save',
                    method: 'post',
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(res) {
                        if (res.status == false) {
                            toastr.error(res.msg);
                        } else {
                            $('[name="name"]').val("");
                            $('[name="description"]').val("");
                            $('[name="start_date"]').val("");
                            $('[name="end_date"]').val("");
                            $('[name="picture"]').val("");
    
                            $('#modal-add-slider').modal('hide');
    
                            Swal.fire({
                                title: "Slider",
                                text: "Slider has been added",
                                icon: 'success'
                            });
                            table_slider.ajax.reload();
                        }
                    }
                });
                return false;
            }
        }
    });

    $(document).on('click', '.btn-detail-slider', function() {
        $('#modal-detail-slider').modal('show');

        const name = $(this).data('name');
        const description = $(this).data('description');
        const start = $(this).data('start');
        const end = $(this).data('end');
        const picture = $(this).data('picture');

        $('#img-slider').attr('src', 'assets/dist/img/sliders/' + picture);
        $('#det-slider-name').html(': ' + name);
        $('#det-slider-description').html(': ' + description);
        $('#det-slider-start').html(': ' + start);
        $('#det-slider-end').html(': ' + end);
    });

    $(document).on('click', '.btn-edit-slider', function() {
        $('#modal-edit-slider').modal('show');

        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const start = $(this).data('start');
        const end = $(this).data('end');

        $('#slider_id').val(id);
        $('#edit-slider-name').val(name);
        $('#edit-slider-description').val(description);
        $('#edit-slider-start').val(start);
        $('#edit-slider-end').val(end);
    });

    $('#form-edit-slider').on('submit', function(e) {
        const name = $('#edit-slider-name').val();
        const description = $('#edit-slider-description').val();
        const start_date = $('#edit-slider-start').val();
        const end_date = $('#edit-slider-end').val();

        const checkStartDate = new Date($('#edit-slider-start').val());
        const checkEndDate = new Date($('#edit-slider-end').val());
        const currentDate = new Date();

        if (name == "" || description == "" || start_date == "" || end_date == "") {
            e.preventDefault();
            toastr.error('Please fill all the field');
        } else {
            if (checkStartDate < currentDate) {
                e.preventDefault();
                toastr.error('Tanggal mulai sudah lewat');
            } else if (checkStartDate > checkEndDate) {
                e.preventDefault();
                toastr.error('Tanggal mulai tidak boleh lebih besar dari tanggal berakhir');
            } else {
                $.ajax({
                    url: 'slider/slider-edit',
                    method: 'post',
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(res) {
                        if(res.status == false) {
                            toastr.error(res.msg);
                        } else {
                            $('[name="edit_slider_name"]').val("");
                            $('[name="edit_slider_description"]').val("");
                            $('[name="edit_slider_start_date"]').val("");
                            $('[name="edit_slider_end_date"]').val("");
                            $('[name="edit_slider_picture"]').val("");
    
                            $('#modal-edit-slider').modal('hide');
    
                            Swal.fire({
                                title: "Slider",
                                text: "Slider has been updated",
                                icon: 'success'
                            });
                            table_slider.ajax.reload();
                        }
                    }
                });
                return false;
            }
        }
    });

    $(document).on('click', '.btn-delete-slider', function(e){
        e.preventDefault();
        const SliderID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This slider will be deleted",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "slider/slider-delete",
                    data: { SliderID : SliderID },
                    success: function () {
                        Swal.fire({
                            title: "Slider",
                            text: "Slider has been deleted",
                            icon: 'success'
                        });
                        table_slider.ajax.reload();
                    }
                });
            }
        })
    });

    $('#form-save-product').submit(function(e) {
        const name = $('#product_name_save').val();
        const price = $('#product_name_save').val();
        const stock = $('#product_stock_save').val();
        const weight = $('#product_weight_save').val();
        const category = $('#product_category_save').val();
        const partner = $('#product_partner_save').val();
        const image = $('#product_image_save').val();
        const desc = $('#product_desc_save').val();

        if (name == "" || price == "" || stock == "" || weight == "" || category == "" || partner == "" || image == "" || desc == "") {
            e.preventDefault();
            toastr.error('Please fill all the fields');
        } else {
            $.ajax({
                url: 'product/product-save',
                type: 'post',
                dataType: 'json',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.status == false) {
                        e.preventDefault();
                        toastr.error(res.msg);
                    } else {
                        $('[name="product_name"]').val("");
                        $('[name="product_price"]').val("");
                        $('[name="product_stock"]').val("");
                        $('[name="product_weight"]').val("");                    
                        $('[name="product_image"]').val("");
                        $('[name="product_desc"]').val("");
    
                        $('[name="product_category"]').empty();
                        $('[name="product_category"]').append('<option selected="selected" disabled>Kategori Produk</option>');
                        
                        for (var i = 0; i < res.categories.length; i++) {
                            $('[name="product_category"]').append('<option value="'+ res.categories[i]['CategoryID'] +'">'+ res.categories[i]['CategoryName'] +'</option>');
                        }

                        $('[name="product_partner"]').empty();
                        $('[name="product_partner"]').append('<option selected="selected" disabled>Nama Toko</option>');
                        
                        for (var i = 0; i < res.partners.length; i++) {
                            $('[name="product_partner"]').append('<option value="'+ res.partners[i]['PartnerID'] +'">'+ res.partners[i]['CompanyName'] +'</option>');
                        }

                        $('#modal-add-product').modal('hide');

                        Swal.fire({
                            title: "Product",
                            text: "Product has been added",
                            icon: 'success'
                        });

                        table_product.ajax.reload();
                    }

                },
                error: function(err) {
                    console.log(err.responseText);
                }
            });
            return false;
        }
    });

    $(document).on('click', '.btn-delete-product', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This product will be deleted",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "product/product-delete",
                    data: { uniqueID : id },
                    success: function () {
                        Swal.fire({
                            title: "Product",
                            text: "Product has been deleted",
                            icon: 'success'
                        });
                        table_product.ajax.reload();
                    }
                });
            }
        })
    });

    $(document).on('click', '.btn-detail-product', function() {
        const uniqueID = $(this).data('id');
        const nama = $(this).data('nama');
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        const weight = $(this).data('weight');
        const desc = $(this).data('desc');
        const image = $(this).data('image');
        const toko = $(this).data('toko');
        const kategori = $(this).data('kategori');       
                
        $('#modal-detail-product').modal('show');

        $('#img-product').attr('src', 'assets/dist/img/products/' + image);
        $('#det-product-uniqueID').html(': ' + uniqueID);
        $('#det-product-name').html(': ' + nama);
        $('#det-product-price').html(': Rp ' + price);
        $('#det-product-stock').html(': ' + stock + ' buah');
        $('#det-product-weight').html(': ' + weight + ' gram');
        $('#det-product-shop').html(': ' + toko);
        $('#det-product-category').html(': ' + kategori);
        $('#det-product-desc').html(': ' + desc);
    });

    $(document).on('click', '.btn-activated-product', function() {
        const uniqueID = $(this).data('id');
        
        $.ajax({
            type: "POST",
            url: "product/product-activated",
            data: { uniqueID : uniqueID },
            success: function (res) {
                Swal.fire({
                    title: "Product",
                    text: "Product has been activated",
                    icon: 'success'
                });
                table_product.ajax.reload();
            }
        });
        return false;
    });

    $(document).on('click', '.btn-deactivated-product', function() {
        const uniqueID = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This product will be deactivated",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "product/product-deactivated",
                    data: { uniqueID : uniqueID },
                    success: function (res) {
                        Swal.fire({
                            title: "Product",
                            text: "Product has been deactivated",
                            icon: 'success'
                        });
                        table_product.ajax.reload();
                    }
                });
                return false;
            }
        })
    });

    $(document).on('click', '.btn-edit-product', function() {
        const uniqueID = $(this).data('id');
        const nama = $(this).data('nama');
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        const weight = $(this).data('weight');
        const desc = $(this).data('desc');
        const image = $(this).data('image');
        const toko = $(this).data('toko');
        const kategori = $(this).data('kategori');
        const partnerID = $(this).data('partnerid');

        $('#modal-edit-product').modal('show');

        $('#product_unique_edit').val(uniqueID);
        $('#product_name_edit').val(nama);
        $('#product_price_edit').val(price);
        $('#product_stock_edit').val(stock);
        $('#product_weight_edit').val(weight);
        $('#product_desc_edit').html(desc);

        $('#product_category_edit').empty()
        $('#product_category_edit').append('<option selected disabled>Kategori Produk</option>');
        
        $('#product_partner_edit').empty()
        $('#product_partner_edit').append('<option selected disabled>Nama Toko</option>');
        
        $.ajax({
            url: 'product/load_data_edit',
            type: 'get',
            dataType: 'json',
            success: function (res) {
                var category = res.categories;
                var partner = res.partners;

                for (var i = 0; i < category.length; i++) {
                    if (category[i]['CategoryName'] == kategori) {
                        $('#product_category_edit').append('<option value="'+ category[i]['CategoryID'] +'" selected>'+ category[i]['CategoryName'] +'</option>');
                    } else {
                        $('#product_category_edit').append('<option value="'+ category[i]['CategoryID'] +'">'+ category[i]['CategoryName'] +'</option>');
                    }
                }

                for (var i = 0; i < partner.length; i++) {
                    if (partner[i]['PartnerUniqueID'] == partnerID) {
                        $('#product_partner_edit').append('<option value="'+ partner[i]['PartnerID'] +'" selected>'+ partner[i]['CompanyName'] +'</option>');
                    } else {
                        $('#product_partner_edit').append('<option value="'+ partner[i]['PartnerID'] +'">'+ partner[i]['CompanyName'] +'</option>');
                    }
                }
            }
        });
    });

    $('#form-edit-product').submit(function(e) {
        const name = $('#product_name_edit').val();
        const price = $('#product_name_edit').val();
        const stock = $('#product_stock_edit').val();
        const weight = $('#product_weight_edit').val();
        const category = $('#product_category_edit').val();
        const partner = $('#product_partner_edit').val();
        const image = $('#product_image_edit').val();
        const desc = $('#product_desc_edit').val();

        if (name == "" || price == "" || stock == "" || weight == "" || category == "" || partner == "" || desc == "") {
            e.preventDefault();
            toastr.error('Please fill all the fields');
        } else {
            e.preventDefault();
            $.ajax({
                url: 'product/product-edit',
                type: 'post',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.status == false) {
                        e.preventDefault();
                        toastr.error(res.msg);
                    } else {
                        $('[name="product_name"]').val("");
                        $('[name="product_price"]').val("");
                        $('[name="product_stock"]').val("");
                        $('[name="product_weight"]').val("");                    
                        $('[name="product_image"]').val("");
                        $('[name="product_desc"]').val("");
                        
                        $('#modal-edit-product').modal('hide');
                        Swal.fire({
                            title: "Product",
                            text: "Product has been updated",
                            icon: 'success'
                        });
                        table_product.ajax.reload();
                    }
                }
            });
            return false
        }
    });
});



