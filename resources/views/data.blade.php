<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2 class="title-table">Laporan Keluhan</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="/logout" style="text-align: center">Logout</a> 
    <div style="margin: 0 10px"> | </div>
    <a href="/" style="text-align: center">Home</a>
</div>
<div style="display: flex; justify-content: flex-end; align-item: center; ">
    {{-- Menggunakan Method GET karena route untuk masuk ke halaman data ini menggunakan ::get --}}
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="Cari berdasarkan nama...">
        <button type="submit" class="btn-login" style="margin-top: -1px;">Cari</button>
    </form>
    {{-- refrsh balik lg ke route data karena nnti pas di klik refresh itu bersihin lagi
        riwayat pemcarian sebelumnnya dan balikan lagi ke halaman data lagi--}}
    <a href="{{route('data')}}" style="margin-left: 10px; margin-top: -10px;">Refresh</a>
    <a href="{{route('export-pdf')}}"  style="margin-left: 10px; margin-top: -10px;" >Cetak PDF</a>
    <a href="{{route('export.excel')}}"  style="margin-left: 10px; margin-top: -10px;" >Cetak Excel</a>
</div>

<div style="padding: 0 30px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Respone</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>

            @php
            $no = 1;
            @endphp

            @foreach ($reports as $report)
            <tr>
                {{-- memunculkan angka 1 dari $no tiap barisnya --}}
                <td>{{$no++}}</td>
                <td>{{$report['nik']}}</td>
                <td>{{$report['nama']}}</td>

                {{-- mengganti format no yg 08 jadi 628 --}}
                @php
                //substr_replace : mengubah karakter string
                //punya 3 argumen. Argumen ke-1 : data yg mau dimasukkan ke string
                     //argumen ke-2 : mulai dari dr index mana ubahnya
                    //argumen ke-3 : sampai  index mana diubahnya
                    $telp = substr_replace($report->no_telp, "62", 0, 1);
                @endphp
                {{-- yg ditampilkan tag a dengan $telp (data no_telp yg uda diubah jadi format 628) --}}
                {{-- %20 fungsinya buat ngasi space --}}
                {{-- trget="_blank"  untuk membuta web baru--}}

                @php
                // kalau uda di response data reportnya, cht wa nya data dari response di tampilin
                if ($report->response) {
                $pesanWA = 'Hello ' . $report-> nama . '! pengaduan anda di ' . $report->response['status'] . '.Berikut pesan untuk anda : ' . $report->response['pesan'];
                }
                // klo blom di response pengaduan, chat wa nya kyak gini
                else {
                    $pesanWA = 'Belum ada data response';
                }
                @endphp
                <td><a href="https://wa.me/{{ $telp}}?text={{$pesanWA}}" target="_blank">{{$telp}}</a></td>
                <td>{{$report['pengaduan']}}</td>
                <td>
                    {{-- menamplkan gambar full layar di tab baru --}}
                    <a href="../assets/image/{{$report->foto}}" target="_blank">
                        <img src="{{asset('assets/image/'.$report->foto)}}" width="120">
                    </a>
                </td>
                <td>
                    @if ($report->response)
                    {{ $report->response['status'] }}
                    @else 
                    -
                    @endif
                </td>
                <td>
                    @if ($report->response)
                    {{ $report->response['pesan'] }}
                    @else 
                    -
                    @endif
                </td>
                <td>
                    <form action="{{route('delete', $report->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete" type="submit">Hapus</button>
                    </form>

                    <form action="{{route('print-pdf', $report->id)}}" method="GET">
                        <button class="btn-deete" type="submit">Print</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>
</body>
</html>