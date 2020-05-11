$(document).ready(function() {
   var table_product = $('#product').DataTable({
      "scrollX":        true,
      "scrollCollapse": true,
      "width": "100%",
      "autoWidth": true,
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
            "width": "100px", 
            "className": "text-center"
         },
         { 
            "targets": [ 1 ], 
            "width": "50px",
            "className": "text-center"
         },
         {
            "targets": [ 2 ],
            "width": "400px"
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
                        title: "Produk",
                        text: "Produk berhasil ditambahkan",
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
         text: "Produk ini akan dihapus",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Hapus'
      }).then((result) => {
         if (result.value) {
            $.ajax({
                  type: "POST",
                  url: "product/product-delete",
                  data: { uniqueID : id },
                  success: function () {
                     Swal.fire({
                        title: "Produk",
                        text: "Produk berhasil dihapus",
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
                  title: "Produk",
                  text: "Produk telah diaktifkan",
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
         text: "Produk ini akan dinonaktifkan",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Nonaktifkan'
      }).then((result) => {
         if (result.value) {
            $.ajax({
                  type: "POST",
                  url: "product/product-deactivated",
                  data: { uniqueID : uniqueID },
                  success: function (res) {
                     Swal.fire({
                        title: "Produk",
                        text: "Produk telah dinonaktifkan",
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
                        title: "Produk",
                        text: "Produk berhasil diperbaharui",
                        icon: 'success'
                     });
                     table_product.ajax.reload();
                  }
            }
         });
         return false
      }
   });

   $(document).on('click', '.btn-deactivated-promo', function() {
      const uniqueID = $(this).data('id');

      Swal.fire({
         title: 'Are you sure?',
         text: "Promo produk ini akan di nonaktifkan",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Nonaktifkan'
      }).then((result) => {
         if (result.value) {
            $.ajax({
                  type: "POST",
                  url: "product/product-promo-deactivated",
                  data: { uniqueID : uniqueID },
                  success: function (res) {
                     Swal.fire({
                        title: "Produk",
                        text: "Promo telah dinonaktifkan",
                        icon: 'success'
                     });
                     table_product.ajax.reload();
                  }
            });
            return false;
         }
      })
   });

   $(document).on('click', '.btn-activated-promo', function() {
      const uniqueID = $(this).data('id');
      const nama = $(this).data('nama');
      const price = $(this).data('price');
      const stock = $(this).data('stock');
      const weight = $(this).data('weight');
      const desc = $(this).data('desc');
      const image = $(this).data('image');
      const toko = $(this).data('toko');
      const kategori = $(this).data('kategori');       
            
      $('#modal-add-product-promo').modal('show');

      $('.promo-img-product').attr('src', 'assets/dist/img/products/' + image);
      $('.promo-product-uniqueID').html(': ' + uniqueID);
      $('.promo-product-name').html(': ' + nama);
      $('.promo-product-price').html(': Rp ' + price);
      $('.promo-product-stock').html(': ' + stock + ' buah');
      $('.promo-product-weight').html(': ' + weight + ' gram');
      $('.promo-product-shop').html(': ' + toko);
      $('.promo-product-category').html(': ' + kategori);
      $('.promo-product-desc').html(': ' + desc);

      $('#promo_uniqueID').val(uniqueID);
   });

   $(document).on('click', '#btn-submit-promo', function(e) {
      const uniqueID = $('#promo_uniqueID').val();
      const nilai_promo = $('#product_nilai_promo').val();

      if (nilai_promo == "") {
         e.preventDefault();
         toastr.error('Masukkan besar nilai promo (dalam persen)');
      } else {
         $.ajax({
            url: 'product/product-promo-save',
            type: 'post',
            data: { uniqueID: uniqueID, nilai_promo: nilai_promo },
            success: function() {
                  $('[name="product_uniqueID"]').val("");
                  $('[name="product_nilai_promo"]').val("");

                  $('#modal-add-product-promo').modal('hide');

                  Swal.fire({
                     title: "Produk",
                     text: "Promo baru telah ditambahkan",
                     icon: 'success'
                  });
                  table_product.ajax.reload();
            }
         });
         return false;
      }
   });

   $(document).on('click', '.btn-edit-product-promo', function() {
      const uniqueID = $(this).data('id');
      const nama = $(this).data('nama');
      const price = $(this).data('price');
      const stock = $(this).data('stock');
      const weight = $(this).data('weight');
      const desc = $(this).data('desc');
      const image = $(this).data('image');
      const toko = $(this).data('toko');
      const kategori = $(this).data('kategori');       
            
      $('#modal-edit-product-promo').modal('show');

      $('.promo-img-product').attr('src', 'assets/dist/img/products/' + image);
      $('.promo-product-uniqueID').html(': ' + uniqueID);
      $('.promo-product-name').html(': ' + nama);
      $('.promo-product-price').html(': Rp ' + price);
      $('.promo-product-stock').html(': ' + stock + ' buah');
      $('.promo-product-weight').html(': ' + weight + ' gram');
      $('.promo-product-shop').html(': ' + toko);
      $('.promo-product-category').html(': ' + kategori);
      $('.promo-product-desc').html(': ' + desc);

      $('#edt_promo_uniqueID').val(uniqueID);
   });

   $(document).on('click', '#btn-edit-promo', function() {
      const uniqueID = $('#edt_promo_uniqueID').val();
      const nilai_promo = $('#edt_product_nilai_promo').val();

      if (nilai_promo == "") {
         e.preventDefault();
         toastr.error('Masukkan besar nilai promo (dalam persen)');
      } else {
         $.ajax({
            url: 'product/product-promo-edit',
            type: 'post',
            data: { uniqueID: uniqueID, nilai_promo: nilai_promo },
            success: function() {
                  $('[name="product_uniqueID"]').val("");
                  $('[name="product_nilai_promo"]').val("");

                  $('#modal-edit-product-promo').modal('hide');

                  Swal.fire({
                     title: "Produk",
                     text: "Nilai promo telah diperbaharui",
                     icon: 'success'
                  });
                  table_product.ajax.reload();
            }
         });
         return false;
      }
   });
});