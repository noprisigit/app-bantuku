$(document).ready(function() {
   $('.btn-cari-invoice').click(function(e) {
      $('#invoiceDisplay').hide();
      $('#invoiceErrorDisplay').hide();
      var invoiceNumber = $('#inputInvoice').val();
      $('#printInvoice').attr('href', 'invoice/print/' + invoiceNumber);
      if (invoiceNumber === "") {
         e.preventDefault();
         toastr.error('Harap isi nomor invoice');
      } else {
         $.ajax({
            url: 'invoice/cariInvoice',
            type: 'post',
            data: { invoiceNumber: invoiceNumber },
            dataType: 'json',
            beforeSend: function() {
               $('#loader').show();
            },
            success: function(res) {
               if (res.length < 1) {
                  $('#invoiceErrorDisplay').show();
               } else {
                  $('#invoiceDisplay').show();
                  $('#invoiceNumber').html(res[0].Invoice);
                  $('#customerName').html(res[0].CustomerName);
                  $('#customerAddress').html(res[0].CustomerAddress1);
                  $('#customerPhone').html('Phone: ' + res[0].CustomerPhone);
                  $('#customerEmail').html('Email: ' + res[0].CustomerEmail);
   
                  var tglPesan = res[0].OrderDate;
                  var parseTgl = tglPesan.split(' ');
                  var hasilParse = parseTgl[0].split('-');
   
                  $('#tglPesan').html('Tanggal: ' + hasilParse[2] + '/' + hasilParse[1] + '/' + hasilParse[0]);
                  $('#detailOrder').html("");
                  var billTotal = 0;
                  var tax = 0.1;
                  for (let i = 0; i < res.length; i++) {
                     $('#detailOrder').append(`
                        <tr>
                           <td>${res[i].ProductName}</td>
                           <td class="text-center">${res[i].OrderProductQuantity}</td>
                           <td class="text-center">${res[i].ProductWeight} gr</td>
                           <td class="text-center">`+ formatRupiah(res[i].ProductPrice, "Rp. ") +`</td>
                           <td class="text-center">`+ formatRupiah(res[i].OrderTotalPrice, "Rp. ") +`</td>
                        </tr>
                     `);
                     billTotal += parseInt(res[i].OrderTotalPrice);
                  }
                  var pajak = billTotal * tax;
                  var grandTotal = parseInt(billTotal) + parseInt(pajak);
                  $('#subTotal').html(formatRupiah(billTotal.toString(), "Rp. "));
                  $('#tax').html(formatRupiah(pajak.toString(), "Rp. "));
                  $('#grandTotal').html(formatRupiah(grandTotal.toString(), "Rp ."));

               }
            },
            complete: function() {
               $('#loader').hide();
               
            },
            error: function(err) {
               console.log(err.responseText);
            }
         });
      }
   });

   $('#inputInvoice').keyup(function() {
      var query = $(this).val();
      if (query != "") {
         $.ajax({
            url: "invoice/searchInvoice",
            type: "post",
            data: { query: query },
            beforeSend: function() {
               $('#invoiceList').html('<p class="p-2" style="background-color: #eee">Loading...</p>');
            },
            success: function(data) {
               $('#invoiceList').fadeIn();
               $('#invoiceList').html(data);
            }
         });
         return false;
      }
      $('#invoiceList').fadeOut();
   })

   $(document).on('click', '.listInvoice', function() {
      $('#inputInvoice').val($(this).text());
      $('#invoiceList').fadeOut();
   })

   function formatRupiah(angka, prefix){
      var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split   		= number_string.split(','),
      sisa     		= split[0].length % 3,
      rupiah     		= split[0].substr(0, sisa),
      ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if(ribuan){
         separator = sisa ? '.' : '';
         rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
   }
});