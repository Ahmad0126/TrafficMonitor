<x-template>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tambah Jenis</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('tambah_kendaraan') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="jenis" id="" class="form-control" placeholder="Nama Jenis Kendaraan">
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
                    <div class="card-title">Daftar Jenis Kendaraan</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kendaraan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($kendaraan as $j)
                                    <tr>
                                        <th scope="row">{{ $no++ }}</th>
                                        <td>{{ $j->jenis }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-id="{{ $j->id }}"
                                                data-bs-target="#edit_kendaraan" data-jenis="{{ $j->jenis }}">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_kendaraan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Kendaraan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('edit_kendaraan') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <input type="text" name="jenis" class="form-control" placeholder="Nama Jenis Kendaraan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $('#edit_kendaraan').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);
            var jenis = button.data('jenis');
            var id = button.data('id');
            var modal = $(this);
            modal.find('input[name="id"]').val(id);
            modal.find('input[name="jenis"]').val(jenis);
        });
    </script>
</x-template>