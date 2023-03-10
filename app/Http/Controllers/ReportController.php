<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;
use App\Exports\ReportsExport;


class ReportController extends Controller
{
    public function printPDF($id)
    {
        //ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jgan gunakan pagination
        //jgan lupa kovert data jdi array dgan toArray()
        $data = Report::with('response')->where('id', $id)->get()->toArray();
        //kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial
        view()->share('reports', $data);
        // panggil view blade yg akan dicetak PDF serta data yg akan digunakan 
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        //download PDF dengan file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function exportPDF()
    {
        //ambil data yg akan ditampilkan pada pdf, bisa juga dengan where atau eloquent lainnya dan jgan gunakan pagination
        //jgan lupa kovert data jdi array dgan toArray()
        $data = Report::with('response')->get()->toArray();
        //kirim data yg diambil kepada view yg akan ditampilkan, kirim dengan inisial
        view()->share('reports', $data);
        // panggil view blade yg akan dicetak PDF serta data yg akan digunakan 
        $pdf = PDF::loadView('print', $data)->setPaper('a4', 'landscape');
        //download PDF dengan file dengan nama tertentu
        return $pdf->download('data_pengaduan_keseluruhan.pdf');
    }

    public function exportExcel()
    {
        //nama file yg akan terdownload
        $file_name = 'data_keseluruhan_pengaduan.xlsx';
        //memanggil file ReportsExport dan mendownloadnya dengan naama seperti $file_name
        return Excel::download(new ReportsExport, $file_name);
    }

    public function index()
    {
        //ASC : asceding -> terkecil terbesar 1-100 / a-z
        // DESC : desceding -> terbesar terkecil 100-1 / z-a
        $reports = Report::orderBy('created_at', 'DESC')->simplePaginate(2); // orderBy adalah untuk mengurutkan Data
        return view('index', compact('reports')); //fungsi COMPACT adalaha untuk mengirim data ke index
    }

    //Request $request ditambahkan karena ada pada halaman data ada fitur searchnya

    public function data(Request $request)
    {
            //dan akan mengambil teks yg diinput search
        $search = $request->search;
        // ambil data yang diinput ke input yg name nya search
        // where akan mencari data berdasarkan column nama
        //data yang di ambil merupakan data yang 'LIKE' (terdapat) teks yg dimasukin ke input search
        //contoh : ngisi input search dengan 'fem'
        //bakal nyari ke db yg coloumn namanya ada isi 'fem' nya
        $reports = Report::with('response')->where ('nama' , 'LIKE' , '%' . $search . '%')->orderBy ('created_at', 'DESC')->get();
        return view('data', compact('reports'));
    }

    public function dataPetugas(Request $request)
    {
        $search = $request->search;
        // with : ambil relasi (nama fungsi hasOne/hasMany/belongsTo di modelnya), ambil dari rrlaasi itu 
        $reports = Report::with ('response')->where ('nama' , 'LIKE' , '%' . $search . '%')->orderBy ('created_at', 'DESC')->get();
        return view('data_petugas', compact('reports'));
    }
     public function auth(Request $request)
     {
        //validasi
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);
        // ambil data dan simpan di variable
        // dd($request->all());
        $user = $request->only('email', 'password');
        //simpen data ke auth dengan Auth:attempt
        //cek proses penyimpnanan ke auth berhasil atau tdk lewat if else
        if (Auth::attempt($user)) {
            // nesting if, if bersarang, if didalam if
            //kalau data login uda mask ke fitur Auth, dicek lagi pake if-else
            //kalau data Auth tersebut role admin maka masuk ke route data
            //kalau data Auth rolenya petugas maka masuk ke route data.petugas 
            if (Auth::user()->role == 'admin') {
                return redirect()->route('data');
            }elseif(Auth::user()->role == 'petugas') {
                return redirect()->route('data.petugas');
            }
        }else {
            return redirect()->back()->with('gagal', 'Gagagl login, coba lagi!');
        }
     }

     public function logout()
     {
        Auth::logout();
        return redirect()->route('login');
     }




    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //kegunannya untuk validasi
        $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|max:13',
            'pengaduan' => 'required|min:5',
            'foto' => 'required|image|mimes:jpg,jpeg,png,svg',
        ]);

        //untuk pindah foto ke folder public
        $path = public_path('assets/image/');
        $image = $request->file('foto');
        $imgName = rand () . '.' . $image->extension(); //foto.jpg : 12345.jpg
        $image->move($path, $imgName);

        //tambah data ke db
        Report::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'no_telp' =>$request->no_telp,
            'pengaduan' =>$request->pengaduan,
            'foto' =>  $imgName,
        ]);
        return redirect()->back()->with('success', "Berhasil Menambah Data Pengaduan");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cari yang akan di delete
       $data = Report::where('id', $id)->firstOrFail();

       //data isinya ->nik sampe foto dari pengaduan
       //bikin variable yg isinya ngarah ke file foto terkait
       //public_path nyari file di folder public yg namanya sama kaya $data bagian foto
       $image = public_path('assets/image/'.$data['foto']);

       //uda menu posisi fotonya, tinggal dihps fotonya pas unlink
       unlink($image);

       //hapus $data yg isinya data nik-foto-tadi, hapusnya di database
       $data->delete();

       Response::where('report_id', $id)->delete();

       //setelahnya di kembalikan di halaman ini
       return redirect()->back();
    }
}
