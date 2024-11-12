<x-template>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col">
            <div class="card">
                <form action="{{ route('filter_traffic') }}" method="get">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title">Filter</div>
                        <button class="btn btn-secondary" type="submit">Terapkan</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <select class="form-control form-select" name="id_ruas" id="">
                                    <option value="">Ruas Jalan</option>
                                    @foreach($jalan as $j)
                                    <option value="{{ $j->id }}" @selected($old['id_ruas'] == $j->id)>{{ $j->ruas }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <select class="form-control form-select" name="id_jenis" id="">
                                    <option value="">Jenis Kendaraan</option>
                                    @foreach($kendaraan as $j)
                                    <option value="{{ $j->id }}" @selected($old['id_jenis'] == $j->id)>{{ $j->jenis }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6">
                                <label for="tanggal1">Dari Tanggal</label>
                                <input type="datetime-local" class="form-control" name="tanggal1" id="tanggal1" value="{{ $old['tanggal1'] }}">
                            </div>
                            <div class="form-group col-md-3 col-sm-6">
                                <label for="tanggal2">Sampai Tanggal</label>
                                <input type="datetime-local" class="form-control" name="tanggal2" id="tanggal2" value="{{ $old['tanggal2'] }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="kecepatan">Kecepatan</label>
                                <div class="input-group">
                                    <select class="form-control form-select" name="logic_speed" id="">
                                        <option @selected($old['logic_speed'] == '=') value="=">=</option>
                                        <option @selected($old['logic_speed'] == 'kurang') value="kurang"><</option>
                                        <option @selected($old['logic_speed'] == 'lebih') value="lebih">></option>
                                    </select>
                                    <input class="form-control w-50" type="number" name="kecepatan" id="kecepatan" value="{{ $old['kecepatan'] }}">
                                    <span class="input-group-text" for="">Km/h</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                                    <td>{{ $t->kecepatan }}</td>
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
</x-template>
