<x-template>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            <h6 class="op-7 mb-2">Total Traffic di Semua Ruas Jalan</h6>
        </div>
        <div class="row ms-md-auto w-100 py-2 py-md-0">
            <div class="col-sm-6">
                <div class="card card-stats card-round mb-md-2 mb-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-truck-moving"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total kendaraan</p>
                                    <h4 class="card-title">{{ number_format(array_sum(array_values($volume))) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mt-sm-0 mt-4">
                <div class="card card-stats card-round mb-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Rata2 Kecepatan</p>
                                    <h4 class="card-title">{{ number_format($rata2, 2) }} Km/h</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Volume Kendaraan</div>
                        <div class="card-tools">
                            <form action="{{ route('base') }}" method="get">
                                <select name="period" id="" class="form-select form-control" onchange="this.form.submit()">
                                    <option @selected($period == 'year') value="year">1 Tahun Terakhir</option>
                                    <option @selected($period == 'month') value="month">30 Hari Terakhir</option>
                                    <option @selected($period == 'week') value="week">7 Hari Terakhir</option>
                                    <option @selected($period == 'today') value="today">Hari ini</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Jenis Kendaraan</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pieChart" style="width: 50%; height: 50%" ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Kecepatan (Km/h)</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var ctx = document.getElementById('statisticsChart').getContext('2d'),
            barChart = document.getElementById("barChart").getContext("2d"),
            pieChart = document.getElementById("pieChart").getContext("2d");

        var statisticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @php echo json_encode(array_keys($volume)); @endphp,
                datasets: [ {
                    label: "Volume",
                    borderColor: '#177dff',
                    pointBackgroundColor: 'rgba(23, 125, 255, 0.6)',
                    pointRadius: 0,
                    backgroundColor: 'rgba(23, 125, 255, 0.4)',
                    legendColor: '#177dff',
                    fill: true,
                    borderWidth: 2,
                    data: @php echo json_encode(array_values($volume)); @endphp
                }]
            },
            options : {
                responsive: true, 
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    bodySpacing: 4,
                    mode:"nearest",
                    intersect: 0,
                    position:"nearest",
                    xPadding:10,
                    yPadding:10,
                    caretPadding:10
                },
                layout:{
                    padding:{left:5,right:5,top:15,bottom:15}
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            fontStyle: "500",
                            beginAtZero: true,
                            maxTicksLimit: 5,
                            padding: 10
                        },
                        gridLines: {
                            drawTicks: false,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 10,
                            fontStyle: "500"
                        }
                    }]
                }, 
                legendCallback: function(chart) { 
                    var text = []; 
                    text.push('<ul class="' + chart.id + '-legend html-legend">'); 
                    for (var i = 0; i < chart.data.datasets.length; i++) { 
                        text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>'); 
                        if (chart.data.datasets[i].label) { 
                            text.push(chart.data.datasets[i].label); 
                        } 
                        text.push('</li>'); 
                    } 
                    text.push('</ul>'); 
                    return text.join(''); 
                }  
            }
        });

        var myBarChart = new Chart(barChart, {
            type: "bar",
            data: {
                labels: @php echo json_encode(array_keys($kecepatan)); @endphp,
                datasets: [{
                    label: "Jumlah",
                    backgroundColor: "rgb(23, 125, 255)",
                    borderColor: "rgb(23, 125, 255)",
                    data: @php echo json_encode(array_values($kecepatan)); @endphp,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                    }],
                },
            },
        });

        var myPieChart = new Chart(pieChart, {
            type: "pie",
            data: {
                datasets: [{
                    data: @php echo json_encode(array_values($kendaraan)); @endphp,
                    backgroundColor: ["#1d7af3", "#f3545d", "#fdaf4b", "#1a2035", "#6861ce", "#48abf7", "#31ce36", "#ffad46", "#f25961", "#1572e8"],
                    borderWidth: 0,
                }],
                labels: @php echo json_encode(array_keys($kendaraan)); @endphp,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: "bottom",
                    labels: {
                        fontColor: "rgb(154, 154, 154)",
                        fontSize: 11,
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                pieceLabel: {
                    render: "percentage",
                    fontColor: "white",
                    fontSize: 14,
                },
                tooltips: false,
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20,
                    },
                },
            },
        });


        var myLegendContainer = document.getElementById("myChartLegend");

        // generate HTML legend
        myLegendContainer.innerHTML = statisticsChart.generateLegend();

        // bind onClick event to all LI-tags of the legend
        var legendItems = myLegendContainer.getElementsByTagName('li');
        for (var i = 0; i < legendItems.length; i += 1) {
            legendItems[i].addEventListener("click", legendClickCallback, false);
        }
    </script>
</x-template>