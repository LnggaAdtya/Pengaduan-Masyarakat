<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <form action="{{route('response.update', $reportId)}}" method="POST" 
    style="width: 500px; margin: 50px auto; display:block;">
        @csrf   
        @method('PATCH')
        <div class="input-card">
            @if ($report)
            <label for="status">Status :</label>
            {{-- cek apakah data $report yg dari compact itu adaan, mksdnya adaan tuh where di db responses nya ada data yg punya
                report_id sama kata yg dikirim ke path {report_id} --}}
            <select name="status" id="status">
                {{-- kalau ada --}}
                <option value="ditolak" {{ $report['status'] == 'ditolak' ? 'selected' : '' }}> Ditolak</option>
                <option value="proses" {{ $report['status'] == 'proses' ? 'selected' : '' }}> Proses</option>
                <option value="diterima" {{ $report['status'] == 'diterima' ? 'selected' : '' }}> Diterima</option>
            </select>
            @else
            <select name="status" id="status">
                <option selected hidden disabled>Pilih status</option>
                <option value="ditolak"> Ditolak</option>
                <option value="proses"> Proses</option>
                <option value="diterima"> Diterima</option>
            </select>
            @endif
        </div>
        <div class="input-card">
            <label for="pesan">Pesan :</label>
            <textarea name="pesan" id="pesan" rows="3">{{ $report ? $report ['pesan'] : ''}}</textarea>
        </div>
        <button type="submit">Kirim Response</button>
    </form>   
</body>
</html>