<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        table, tr, th, td{
            border: solid 1px;
            border-collapse: collapse;
            padding-left: 0.4rem;
            padding-right: 0.4rem;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <h3>Terapkan Filter</h3>
    <form action="{{ route('filter_traffic') }}" method="get">
        <div>
            <select name="id_ruas" id="">
                <option value="">Ruas Jalan</option>
                @foreach($jalan as $j)
                    <option value="{{ $j->id }}">{{ $j->ruas }} </option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="id_jenis" id="">
                <option value="">Jenis Kendaraan</option>
                @foreach($kendaraan as $j)
                    <option value="{{ $j->id }}">{{ $j->jenis }} </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tanggal1">Dari Tanggal</label>
            <input type="datetime-local" name="tanggal1" id="tanggal1">
        </div>
        <div>
            <label for="tanggal2">Sampai Tanggal</label>
            <input type="datetime-local" name="tanggal2" id="tanggal2">
        </div>
        <div>
            <label for="kecepatan">Kecepatan</label>
            <select name="logic_speed" id="">
                <option value="=">=</option>
                <option value="kurang"><</option>
                <option value="lebih">></option>
            </select>
            <input type="number" name="kecepatan" id="kecepatan">
            <label for="">Km/h</label>
        </div>
        <input type="submit" value="Terapkan">
    </form>
    <br>
    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis Kendaraan</th>
            <th>Kecepatan</th>
            <th>Ruas Jalan</th>
        </tr>
        @php $no = $traffic->firstItem(); @endphp
        @foreach($traffic as $t)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $t->tanggal }}</td>
            <td>{{ $t->kendaraan->jenis }}</td>
            <td>{{ $t->kecepatan }}</td>
            <td>{{ $t->jalan->ruas }}</td>
        </tr>
        @endforeach
    </table>
    <br>
    {{ $traffic->links('pagination.simple-html') }}
</body>
</html>