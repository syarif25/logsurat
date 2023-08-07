<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
<script>
   // Mendapatkan data grafik dari controller
   var chartData = <?php echo $chartData; ?>;

// Membuat konfigurasi grafik
var options = {
    series: [],
    chart: {
        type: 'bar',
        height: 350
    },
    xaxis: {
        categories: []
    },
    yaxis: {
        title: {
            text: 'Jumlah'
        }
    }
};

// Menyiapkan data dan kategori untuk grafik
for (var jenisSurat in chartData) {
    options.xaxis.categories.push(jenisSurat);

    var seriesData = [];
    var bulanData = chartData[jenisSurat];

    for (var i = 0; i < bulanData.length; i++) {
        seriesData.push(bulanData[i].jml);
    }

    options.series.push({
        name: jenisSurat,
        data: seriesData
    });
}

// Membuat grafik menggunakan konfigurasi
var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
</script>