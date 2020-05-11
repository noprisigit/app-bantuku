$(document).ready(function() {
    getDashboardData();
    showCartCustomers();
    
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
            }
        });
    }

    function showCartCustomers() {
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
    
    setInterval(function(){
        getDashboardData();
        showCartCustomers();
    }, 300000);
    
});