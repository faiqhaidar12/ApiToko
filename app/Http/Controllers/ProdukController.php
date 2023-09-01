<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://127.0.0.1:8000/api/produk/');
        $data = $response->json('data');
        // Inisialisasi $data dengan array kosong jika $data adalah null
        if (!$data) {
            $data = [];
        }
        return view('product.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { // Flash data setelah validasi dan pengiriman API
        Session::flash('nama_produk', $request->nama_produk);
        Session::flash('deskripsi', $request->deskripsi);
        Session::flash('harga', $request->harga);

        $nama_produk = $request->nama_produk;
        $deskripsi = $request->deskripsi;
        $harga = $request->harga;

        $foto_file = $request->file('gambar');
        $foto_nama = null; // Inisialisasi dengan null jika tidak ada gambar diunggah

        // Validasi input
        $this->validate($request, [
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan aturan validasi gambar dengan kebutuhan Anda.
        ]);

        if ($foto_file) {
            $foto_ekstensi = $foto_file->getClientOriginalExtension();
            $foto_nama = date('ymdhis') . "." . $foto_ekstensi;

            // Simpan gambar ke direktori penyimpanan
            $foto_file->storeAs('public/image', $foto_nama);
        }

        $client = new Client();
        $url = "http://127.0.0.1:8000/api/produk";

        // Buat array 'multipart' awal dengan bidang yang tetap ada
        $multipart = [
            [
                'name' => 'nama_produk',
                'contents' => $nama_produk
            ],
            [
                'name' => 'deskripsi',
                'contents' => $deskripsi
            ],
            [
                'name' => 'harga',
                'contents' => $harga
            ],
        ];

        // Tambahkan bagian 'gambar' hanya jika $foto_nama tidak null (gambar diunggah)
        if ($foto_nama) {
            $multipart[] = [
                'name' => 'gambar',
                'contents' => fopen(storage_path("app/public/image/$foto_nama"), 'r')
            ];
        }

        $response = $client->request('POST', $url, [
            'multipart' => $multipart,
        ]);

        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect('produk/create')->withErrors($error); // Kembalikan input yang sudah dimasukkan
        }

        return redirect('produk')->with('success', 'Berhasil Tambah Produk!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = Http::get("http://127.0.0.1:8000/api/produk/{$id}");
        $data = $response->json('data');

        return view('product.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
        ], [
            'nama_produk.required' => "Nama Pelanggan Wajib diisi!!",
            'deskripsi.required' => "Deskripsi Pelanggan Wajib diisi!!",
            'email.required' => "E-Mail Pelanggan Wajib diisi!!",
            'email.unique' => "E-Mail Pelanggan Sudah Dipakai!!",
            'harga.required' => "Harga Pelanggan Wajib diisi!!",
            'harga.numeric' => "Harga Wajib diisi Dengan Angka!!",
        ]);

        $data = [
            'nama_produk' => $request->input('nama_produk'),
            'deskripsi' => $request->input('deskripsi'),
            'harga' => $request->input('harga'),
        ];

        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar' => 'mimes:png,jpg,jpeg|max:2040',
            ], [
                'gambar.mimes' => "Jenis Gambar Yang Anda Masukan Bukan Png,Jpg,Jpeg!!",
            ]);

            $foto_file = $request->file('gambar');
            $foto_ekstensi = $foto_file->extension();
            $foto_nama = date('ymdhis') . "." . $foto_ekstensi;
            $foto_file->move(public_path('image'), $foto_nama);
            //sudah tersimpan di direktori

            $data_foto = Produk::where('id', $id)->first();
            File::delete(public_path('image') . '/' . $data_foto->gambar);

            $data['gambar'] = $foto_nama;
        }


        Produk::where('id', $id)->update($data);
        return redirect('/produk')->with('update', 'Berhasil Update Data!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = new Client();
        $url = "http://127.0.0.1:8000/api/produk/$id";
        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect('produk')->withErrors($error)->withInput();
        } else {
            // Hapus gambar di sini berdasarkan informasi yang Anda terima dari API.
            // Misalnya, jika API memberikan nama file gambar yang harus dihapus,
            // Anda bisa menggunakan File::delete() untuk menghapusnya.
            $gambarToDelete = $contentArray['status'];
            File::delete(public_path('image') . '/' . $gambarToDelete);
            return redirect('produk')->with('success', 'Berhasil hapus data');
        }
    }
}
