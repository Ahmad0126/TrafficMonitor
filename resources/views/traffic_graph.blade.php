<x-template>
    <x-slot:title>Data Traffic Tanggal {{ $title }}</x-slot:title>

    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-1">Data Traffic</h3>
            <h6 class="op-7 mb-0">{{ $title }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_filter" class="btn btn-label-info btn-round me-2">Filter</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card card-stats card-round">
                <form action="{{ route('graph_traffic') }}" method="get">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 pb-2">
                                <label for="">Perhitungan</label>
                                <select name="period" id="" class="form-control form-select">
                                    <option @selected($old['period'] == "year") value="year">per bulan selama 1 Tahun</option>
                                    <option @selected($old['period'] == "month") value="month">per hari selama 30 Hari</option>
                                    <option @selected($old['period'] == "week") value="week">per hari selama 7 Hari</option>
                                    <option @selected($old['period'] == "today") value="today">per jam selama 1 Hari</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Tanggal Akhir</label>
                                <div class="input-group">
                                    <input type="date" name="end_date" id="" class="form-control" value="{{ $old['end_date'] }}">
                                    <button type="submit" class="btn btn-primary">Ubah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_filter" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Filter</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="">Ruas Jalan</label>
                                            <select class="form-control form-select" name="id_ruas" id="">
                                                <option value="">-</option>
                                                @foreach($jalan as $j)
                                                <option value="{{ $j->id }}" @isset($old['id_ruas']) {{ $old['id_ruas'] == $j->id ? 'selected' : '' }} @endisset>{{ $j->ruas }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Jenis Kendaraan</label>
                                            <select class="form-control form-select" name="id_jenis" id="">
                                                <option value="">-</option>
                                                @foreach($jenis as $j)
                                                <option value="{{ $j->id }}" @isset($old['id_jenis']) {{ $old['id_jenis'] == $j->id ? 'selected' : '' }} @endisset>{{ $j->jenis }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            <label for="kecepatan">Kecepatan</label>
                                            <div class="input-group">
                                                <select class="form-control form-select" name="logic_speed" id="">
                                                    <option @isset($old['logic_speed']) {{ $old['logic_speed'] == '=' ? 'selected' : '' }} @endisset value="=">=</option>
                                                    <option @isset($old['logic_speed']) {{ $old['logic_speed'] == 'kurang' ? 'selected' : '' }} @endisset value="kurang"><</option>
                                                    <option @isset($old['logic_speed']) {{ $old['logic_speed'] == 'lebih' ? 'selected' : '' }} @endisset value="lebih">></option>
                                                </select>
                                                <input class="form-control w-50" type="number" name="kecepatan" id="kecepatan" @isset($old['kecepatan']) value="{{ $old['kecepatan'] }}" @endisset>
                                                <span class="input-group-text" for="">Km/h</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats card-round">
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
        <div class="col-sm-6 col-xl-3">
            <div class="card card-stats card-round">
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
    <div class="row">
        <div class="col">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Volume Kendaraan</div>
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