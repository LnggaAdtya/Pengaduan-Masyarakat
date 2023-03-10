<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function show(Response $response)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function edit( $report_id)
    {
        // ambil data response yang bakal dimunculin data yang diambil data response yang report_id nya sama kayak $report_id dari path dinamis {report_id}
        // kalau ada, dataya diambil satu / first()
        // kenapa ga pke firstOrFail() karena nnti bakal munculin not found view, kalau pake first() viewnya ttp bkal ditampilin
        $report = Response::where('report_id', $report_id)->first();
        // karena mau kirim data {report_id} buat di route updatenya, jadi bir bisa dipake di blade kita simpen data path dinamis $report_id nya ke variable baru yg bakal di compat dan panggil di baldenya
        $reportId = $report_id;
        return view('response', compact('report', 'reportId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $report_id)
    {
        $request->validate([
            'status' => 'required',
            'pesan' => 'required',
        ]);
        // updateOrCreate() fungsinya untuk melakukan update data klo emng di db responnya uda ada data yg punya report_id sama dengan $report_id dari path dinamis, kalau gada data itu maka di create
        // array oertama,acuan cari datanya
        // array ke dua, data yang dikirim
        //kenapa pake updateOrCreate ? karena response ini kalo tadinya gada mau di tmbhain tpi klo ada mau diupdate aj

        Response:: updateOrCreate (
            [
                'report_id' => $report_id,
            ],
            [
                'status' => $request->status,
                'pesan' => $request->pesan,
            ]
            );
            // setlh berhasil, arahkan ke route yg namenya data.petugas dengan pesan alret
            return redirect()->route('data.petugas')->with('responseSeccess', 'Berhasil mengubah response!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function destroy(Response $response)
    {
        //
    }
}
