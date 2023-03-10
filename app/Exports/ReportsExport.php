<?php

namespace App\Exports;

use App\Models\Report;
//mengambil data dari databse
use Maatwebsite\Excel\Concerns\FromCollection;
//untuk mengatur nama nama coloum header di excelnya
use Maatwebsite\Excel\Concerns\WithHeadings;
//mngatur data yang dimunculkan tiap colum di excel
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{

    //mengambil data daridatabse
    public function collection()
    {
        //dalam sini boleh menyertakan perintah eloquent lain seperti where, all, dll
        return Report::with('response')->orderBy('created_at', 'DESC')->get();
    }
    //mengatur nama-nama coloum headers
    public function headings(): array
    {
        return [
            'ID',
            'Nik Pelapor',
            'Nama Pelapor',
            'No Telp Pelapor',
            'Tanggal pelaporan',
            'Pengaduan',
            'Status Response',
            'Pesan Response',

        ];
    }
    //mengatur data yang ditampilkan per coloumn di excelnya
    //fungsi seperti foreach, $item merupakan bagian as pada foreach
    public function map($item): array
    {
        return [
            $item->id,
            $item->nik,
            $item->nama,
            $item->no_telp,
            \Carbon\Carbon::parse($item->created_at)->format('j F, Y'),
            $item->pengaduan,
            $item->response ? $item->response['status'] : '-',
            $item->response ? $item->response['pesan'] : '-',
        ];
    }
}
