$(document).ready(function() {
   var table_partner = $('#partner').DataTable({
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
            "targets": [ 1 ], 
            "width": "200px",
         },
         { 
            "targets": [ 2 ], 
            "width": "200px",
         },
         {
            "targets": [-1],
            "width": "150px",
            "className": "text-center"
         }
      ],
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
      var latitude = $('#latitude').val();
      var longitude = $('#longitude').val();
      var kode_pos = $('#kode-pos').val();
      var gambar = $('#gambar_toko').val();

      if (nama_toko == "" || nama_pemilik == "" || phone == "" || email == "" || alamat == "" || provinsi == null || kabupaten == null || kode_pos == "" || gambar == "") {
         e.preventDefault();
         toastr.error('Harap isi semua kolom');
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
                  $('[name="latitude"]').val("");
                  $('[name="longitude"]').val("");
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
                     title: "Mitra",
                     text: "Mitra berhasil ditambahkan",
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
      var uniqueID = $(this).data('uniqueid');
      var nama_toko = $(this).data('nama_toko');
      var nama_pemilik = $(this).data('nama_pemilik');
      var alamat = $(this).data('alamat');
      var phone = $(this).data('phone');
      var email = $(this).data('email');
      var latitude = $(this).data('latitude');
      var longitude = $(this).data('longitude');
      var provinsi = $(this).data('provinsi');
      var kabupaten = $(this).data('kabupaten');
      var pos = $(this).data('pos');
      var gambar = $(this).data('gambar');

      $('#det-part-uniqueID').empty();
      $('#det-part-nama-toko').empty();
      $('#det-part-nama-pemilik').empty();
      $('#det-part-alamat').empty();
      $('#det-part-phone').empty();
      $('#det-part-email').empty();
      $('#det-part-latitude').empty();
      $('#det-part-longitude').empty();

      $('#det-part-uniqueID').html(": " + uniqueID);
      $('#det-part-nama-toko').html(": " + nama_toko);
      $('#det-part-nama-pemilik').html(": " + nama_pemilik);
      $('#det-part-alamat').html(": " + alamat + " " + kabupaten + " " + provinsi + " " + pos);
      $('#det-part-phone').html(": " + phone);
      $('#det-part-email').html(": " + email);
      if (latitude === "" && longitude === "") {
         $('#det-part-latitude').html(": Belum ada data");
         $('#det-part-longitude').html(": Belum ada data");
      } else {
         $('#det-part-latitude').html(": " + latitude);
         $('#det-part-longitude').html(": " + longitude);
      }
      $('#shop-picture').attr('src', 'assets/dist/img/partners/' + gambar);
   });

   $(document).on('click', '.btn-edit-partner', function() {
      var id = $(this).data('id');
      var uniqueID = $(this).data('uniqueid');
      var nama_toko = $(this).data('nama_toko');
      var nama_pemilik = $(this).data('nama_pemilik');
      var alamat = $(this).data('alamat');
      var phone = $(this).data('phone');
      var email = $(this).data('email');
      var provinsi = $(this).data('provinsi');
      var kabupaten = $(this).data('kabupaten');
      var pos = $(this).data('pos');
      var gambar = $(this).data('gambar');
      var latitude = $(this).data('latitude');
      var longitude = $(this).data('longitude');

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
      $('[name="partner_latitude_edit"]').val(latitude);
      $('[name="partner_longitude_edit"]').val(longitude);
   });

   $('#form-edit-partner').submit(function(e) {
      var id = $('#partner_id').val();
      var uniqueID = $('#partner_uniqueid_edit').val();
      var nama_toko = $('#partner_nama_toko_edit').val();
      var nama_pemilik = $('#partner_nama_pemilik_edit').val();
      var phone = $('#partner_phone_edit').val();
      var email = $('#partner_email_edit').val();
      var alamat = $('#partner_alamat_edit').val();
      var latitude = $('#partner_latitude_edit').val();
      var longitude = $('#partner_longitude_edit').val();
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
                  $('[name="partner_latitude_edit"]').val("");
                  $('[name="partner_longitude_edit"]').val("");
                  $('[name="partner_provinsi_edit"]').empty();
                  $('[name="partner_kabupaten_edit"]').empty();
                  $('[name="partner_gambar_toko_edit"]').val("");

                  $('#modal-edit-partner').modal('hide');
                  Swal.fire({
                     title: "Mitra",
                     text: "Mitra berhasil diperbaharui",
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
         text: "Mitra ini akan dihapus",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Hapus'
      }).then((result) => {
         if (result.value) {
            $.ajax({
                  type: "POST",
                  url: "partner/delete-partner",
                  data: { partner_id : PartnerID },
                  success: function () {
                     Swal.fire({
                        title: "Mitra",
                        text: "Mitra berhasil dihapus",
                        icon: 'success'
                     });
                     table_partner.ajax.reload();
                  }
            });
         }
      })
   });
});