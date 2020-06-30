$(document).ready(function() {
    getDashboardData();
    showChartCustomers();
    showChartPendapatanThisYear();

    function getDashboardData()
    {
        $.ajax({
            url: 'dashboard/getDashboardData',
            type: 'get',
            dataType: 'json',
            success: function(res) {
                $('#countMerchant').html(res.countPartners.toString() + ' Mitra');
                $('#countCustomer').html(res.countCustomers.toString() + ' Orang');
                $('#countCategory').html(res.countCategories.toString() + ' Kategori');
                $('#countAccount').html(res.countAccount.toString() + ' Akun');
                $('#countOrdersThisMonth').html(res.countOrdersThisMonth.jumlah.toString() + ' Pesanan');

                $('.info-box-number').map(function() {
                    let idNumber = $(this).attr('id');
                    $('#'+idNumber).prop('Counter', 0).animate({
                        Counter: $('#'+idNumber).text()
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $('#'+idNumber).text(Math.ceil(now));
                        }
                    });
                })
                

                if (res.jumlahPendapatan.total_bayar == null) {
                    $('#jumlahPendapatan').html("Rp 0");
                } else {
                    $('#jumlahPendapatan').html(formatRupiah(res.jumlahPendapatan.total_bayar.toString(), "Rp. "));
                }
                var disukai;
                for(let i = 0; i < res.produkYangDisukai.length; i++) {
                    disukai += `<tr>
                        <td class="text-center">${i+1}</td>
                        <td>${res.produkYangDisukai[i].ProductName}</td>
                        <td>${res.produkYangDisukai[i].CompanyName}</td>
                        <td class="text-center">${res.produkYangDisukai[i].jumlah} Orang</td>
                    </tr>`;
                }
                $('#produk-disukai').html(disukai);
                var tokoDisukai;
                for (let j = 0; j < res.tokoYangDisukai.length; j++) {
                    tokoDisukai += `<tr>
                        <td class="text-center">${j+1}</td>
                        <td>${res.tokoYangDisukai[j].CompanyName}</td>
                        <td>${res.tokoYangDisukai[j].PartnerName}</td>
                        <td class="text-center">${res.tokoYangDisukai[j].jumlah} Orang</td>
                    </tr>`;
                } 
                $('#toko-disukai').html(tokoDisukai);
            }
        });
    }

    // $('.info-box-number').each(function () {
    //     console.log(this);
    //     $(this).prop('Counter', 1).animate({
    //         Counter: $(this).text()
    //     }, {
    //         duration: 4000,
    //         easing: 'swing',
    //         step: function (now) {
    //             $(this).text(Math.ceil(now));
    //         }
    //     });
    // });

    function showChartCustomers() {
        $.ajax({
            url: 'dashboard/countingCustomersByCurrentYear',
            type: 'get',
            dataType: 'json',
            success: function(res) {
                // var obj = JSON.parse(res);
                var data_jumlah = [];

                for (i = 0; i < res.length; i++) {
                    data_jumlah.push(parseInt(res[i][0].jumlah));
                }
                
                var barChartCanvas = $('#barChart').get(0).getContext('2d')
                var myChart = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [{
                            label: 'Jumlah Customer Tahun Ini',
                            data: data_jumlah,
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius          : false,
                            pointColor          : '#3b8bba',
                            pointStrokeColor    : 'rgba(60,141,188,1)',
                            pointHighlightFill  : '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                        }]
                    },
                    options: {
                        responsive              : true,
                        maintainAspectRatio     : false,
                        datasetFill             : false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });
    }

    function showChartPendapatanThisYear()
    {
        $.ajax({
            url: 'dashboard/countingPendapatanThisYear',
            type: 'get',
            dataType: 'json',
            success: function(res) {
                var data_jumlah = [];

                for (i = 0; i < res.length; i++) {
                    data_jumlah.push(parseInt(res[i][0].pendapatan));
                }
                
                var pendapatanChartCanvas = $('#pendapatanChart').get(0).getContext('2d')
                var pendapatanChart = new Chart(pendapatanChartCanvas, {
                    type: 'bar',
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        datasets: [{
                            label: 'Jumlah Pendapatan',
                            data: data_jumlah,
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius          : false,
                            pointColor          : '#3b8bba',
                            pointStrokeColor    : 'rgba(60,141,188,1)',
                            pointHighlightFill  : '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                        }]
                    },
                    options: {
                        responsive              : true,
                        maintainAspectRatio     : false,
                        datasetFill             : false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value, index, values) {
                                        return addCommas(value); //! panggil function addComas tadi disini
                                    }
                                }
                            }]
                        }
                    }
                });
            }
        });
    }

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

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        let rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return "Rp " + x1 + x2;
    }
    
    setInterval(function(){
        getDashboardData();
        showChartCustomers();
        showChartPendapatanThisYear();
    }, 300000);
    
});