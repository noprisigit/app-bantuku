$(document).ready(function() {
   getPaymentChannelData();

   function getPaymentChannelData()
   {
      $.ajax({
         url: 'getPaymentChannel',
         type: 'get',
         dataType: 'json',
         success: function(res) {
            $('#listPaymentChannel').html("");
            var no = 1;
            res.forEach(row => {
               $('#listPaymentChannel').append(`
                  <tr>
                     <td>${no}</td>
                     <td>${row.PaymentChannelCode}</td>
                     <td>${row.PaymentChannelName}</td>
                     <td>
                        <img src="../assets/dist/img/payment/${row.PaymentChannelLogo}" alt="Logo Payment" class="img-fluid" width="128">
                     </td>
                     <td>
                        <button class="btn btn-info btnEditPaymentChannel" data-kode="${row.PaymentChannelCode}" data-nama="${row.PaymentChannelName}" data-logo="${row.PaymentChannelLogo}"><i class="fas fa-pencil-alt"></i></button>
                        <a href="javascript:void(0)" data-kode="${row.PaymentChannelCode}" class="btn btn-danger btnDeletePaymentChannel"><i class="fas fa-trash-alt"></i></a>
                     </td>
                  </tr>
               `)
               no++;
            });
         }
      });
   }

   $('#formSavePaymentChannel').submit(function(e) {
      var kode = $('#inputKodePaymentChannel').val();
      var nama = $('#inputNamaPaymentChannel').val();
      var logo = $('#inputLogoPaymentChannel').val();

      if (kode === "" || nama === "" || logo === "") {
         e.preventDefault();
         toastr.error('Harap isi seluruh kolom');
      } else {
         $.ajax({
            url: 'savePaymentChannel',
            method: 'post',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
               if (res.status === false) {
                  toastr.error(res.msg);
               } else {
                  $('[name="inputKodePaymentChannel"]').val("");
                  $('[name="inputNamaPaymentChannel"]').val("");
                  $('[name="inputLogoPaymentChannel"]').val("");
                  $('#modalTambahPaymentChannel').modal('hide');

                  Swal.fire({
                     title: "Payment Channel",
                     text: "Payment channel berhasil ditambahkan",
                     icon: 'success'
                  });
                  getPaymentChannelData();
               }
            }
         });
         return false;
      }
   });

   $(document).on('click', '.btnDeletePaymentChannel', function(e) {
      e.preventDefault();
      const kode = $(this).data('kode');
      Swal.fire({
         title: 'Are you sure?',
         text: "Payment channel ini akan dihapus",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Hapus'
      }).then((result) => {
         if (result.value) {
            $.ajax({
               type: "POST",
               url: "deletePaymentChannel",
               data: { kode : kode },
               success: function () {
                  Swal.fire({
                     title: "Payment Channel",
                     text: "Payment channel berhasil dihapus",
                     icon: 'success'
                  });
                  getPaymentChannelData();
               }
            });
            return false;
         }
      })
   });

   $(document).on('click', '.btnEditPaymentChannel', function() {
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');

      $('#modalEditPaymentChannel').modal('show');
      $('#editKodePaymentChannel').val(kode);
      $('#editNamaPaymentChannel').val(nama);
   })

   $('#formEditPaymentChannel').submit(function() {
      var kode = $('#editKodePaymentChannel').val();
      var nama = $('#editNamaPaymentChannel').val();

      if (kode === "" || nama === "") {
         e.preventDefault();
         toastr.error('Harap isi seluruh kolom');
      } else {
         $.ajax({
            url: 'editPaymentChannel',
            method: 'post',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(res) {
               if (res.status === false) {
                  toastr.error(res.msg);
               } else {
                  $('[name="editKodePaymentChannel"]').val("");
                  $('[name="editNamaPaymentChannel"]').val("");
                  $('[name="editLogoPaymentChannel"]').val("");
                  $('#modalEditPaymentChannel').modal('hide');

                  Swal.fire({
                     title: "Payment Channel",
                     text: "Payment channel berhasil diperbaharui",
                     icon: 'success'
                  });
                  getPaymentChannelData();
               }
            }
         });
         return false;
      }
   })
});