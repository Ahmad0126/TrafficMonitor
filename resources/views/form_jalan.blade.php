<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ruas Jalan</title>
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
    <form action="{{ route('tambah_jalan') }}" method="post">
        <input type="text" name="ruas" id="" placeholder="Tambah Ruas Jalan">
        <input type="submit" value="Tambah">
    </form>
</body>
</html>