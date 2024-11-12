<x-template>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tambah Ruas</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('tambah_jalan') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="ruas" id="" class="form-control" placeholder="Nama Ruas Jalan">
                                <button class="btn btn-secondary" type="submit">Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Ruas Jalan</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Jalan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($jalan as $j)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                        <td>{{ $j->ruas }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-template>