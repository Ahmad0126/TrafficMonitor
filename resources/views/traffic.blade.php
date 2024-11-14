<x-template>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Traffic</h3>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_filter" class="btn btn-label-info btn-round me-2">Filter</a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#buat_grafik" class="btn btn-primary btn-round">Buat Grafik</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Lalu Lintas</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Kecepatan</th>
                                    <th>Ruas Jalan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = $traffic->firstItem(); @endphp
                                @foreach($traffic as $t)
                                <tr>
                                    <th scope="row">{{ $no++ }}</th>
                                    <td>{{ $t->tanggal }}</td>
                                    <td>{{ $t->kendaraan->jenis }}</td>
                                    <td>{{ $t->kecepatan }} Km/h</td>
                                    <td>{{ $t->jalan->ruas }}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $traffic->links('pagination.template') }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_filter" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Filter</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('filter_traffic') }}" method="get">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label for="">Urutkan</label>
                                <select class="form-control form-select" name="order" id="">
                                    <option @isset($old['order']) {{ $old['order'] == "terbaru" ? 'selected' : ''}} @endisset value="terbaru">Terbaru</option>
                                    <option @isset($old['order']) {{ $old['order'] == "terlama" ? 'selected' : ''}} @endisset value="terlama">Terlama</option>
                                    <option @isset($old['order']) {{ $old['order'] == "tercepat" ? 'selected' : ''}} @endisset value="tercepat">Kendaraan Tercepat</option>
                                    <option @isset($old['order']) {{ $old['order'] == "terlambat" ? 'selected' : ''}} @endisset value="terlambat">Kendaraan Terlambat</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-6">
                                <label for="">Ruas Jalan</label>
                                <select class="form-control form-select" name="id_ruas" id="">
                                    <option value="">-</option>
                                    @foreach($jalan as $j)
                                    <option value="{{ $j->id }}" @isset($old['id_ruas']) {{ $old['id_ruas'] == $j->id ? 'selected' : '' }} @endisset>{{ $j->ruas }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-6">
                                <label for="">Jenis Kendaraan</label>
                                <select class="form-control form-select" name="id_jenis" id="">
                                    <option value="">-</option>
                                    @foreach($kendaraan as $j)
                                    <option value="{{ $j->id }}" @isset($old['id_jenis']) {{ $old['id_jenis'] == $j->id ? 'selected' : '' }} @endisset>{{ $j->jenis }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-3 col-md-6">
                                <label for="tanggal1">Dari Tanggal</label>
                                <input type="datetime-local" class="form-control" name="tanggal1" id="tanggal1" @isset($old['tanggal1']) value="{{ $old['tanggal1'] }}" @endisset>
                            </div>
                            <div class="form-group col-lg-3 col-md-6">
                                <label for="tanggal2">Sampai Tanggal</label>
                                <input type="datetime-local" class="form-control" name="tanggal2" id="tanggal2" @isset($old['tanggal2']) value="{{ $old['tanggal2'] }}" @endisset>
                            </div>
                            <div class="form-group col-lg-6">
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
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="buat_grafik">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Buat Grafik</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('graph_traffic') }}" method="get">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <label for="">Perhitungan</label>
                                <select name="period" id="" class="form-control form-select">
                                    <option value="year">per bulan selama 1 Tahun</option>
                                    <option value="month">per hari selama 30 Hari</option>
                                    <option value="week">per hari selama 7 Hari</option>
                                    <option value="today">per jam selama 1 Hari</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="">Tanggal Akhir</label>
                                <input type="date" name="end_date" id="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-template>
