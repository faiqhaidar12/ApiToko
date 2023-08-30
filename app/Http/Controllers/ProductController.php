<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = new Client();
        $url = "http://127.0.0.1:8001/api/produk";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        $data = $contentArray['data'];
        return view('product.index', ['data' => $data]);
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
        Session::flash('title', $request->title);
        Session::flash('description', $request->description);
        Session::flash('price', $request->price);

        $title = $request->title;
        $description = $request->description;
        $price = $request->price;

        $foto_file = $request->file('image');
        $foto_nama = null; // Inisialisasi dengan null

        // Validasi input
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan aturan validasi gambar dengan kebutuhan Anda.
        ]);

        if ($foto_file) {
            $foto_ekstensi = $foto_file->getClientOriginalExtension();
            $foto_nama = date('ymdhis') . "." . $foto_ekstensi;

            $client = new Client();
            $url = "http://127.0.0.1:8001/api/produk";
            $response = $client->request('POST', $url, [
                'multipart' => [
                    [
                        'name' => 'title',
                        'contents' => $title
                    ],
                    [
                        'name' => 'description',
                        'contents' => $description
                    ],
                    [
                        'name' => 'price',
                        'contents' => $price
                    ],
                    [
                        'name' => 'image',
                        'contents' => fopen($foto_file->getRealPath(), 'r')
                    ]
                ]
            ]);

            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);
            if ($contentArray['status'] != true) {
                $error = $contentArray['data'];
                return redirect('produk/create')->withErrors($error); // Kembalikan input yang sudah dimasukkan
            }

            // Jika API response berhasil, simpan gambar ke folder
            $foto_file->move(public_path('image'), $foto_nama);
        }

        return redirect('produk')->with('success', 'Berhasil Tambah Produk!');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $client = new Client();
        // $url = "http://127.0.0.1:8001/api/produk/$id";
        // $response = $client->request('GET', $url);
        // $content = $response->getBody()->getContents();
        // $contentArray = json_decode($content, true);
        // if ($contentArray['status'] != true) {
        //     $error = $contentArray['message'];
        //     return redirect('produk')->withErrors($error);
        // } else {
        //     return view('product.edit');
        // }

        return redirect('produk');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = new Client();
        $url = "http://127.0.0.1:8001/api/produk/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        if ($contentArray['status'] != true) {
            $error = $contentArray['message'];
            return redirect('produk')->withErrors($error);
        } else {
            $data = $contentArray['data'];
            return view('product.edit', ['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $title = $request->title;
        $description = $request->description;
        $price = $request->price;

        // Validasi input
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        $client = new Client();
        $url = "http://127.0.0.1:8001/api/produk/$id";

        // Mengecek apakah ada gambar yang diunggah
        if ($request->hasFile('image')) {
            $foto_file = $request->file('image');
            $foto_ekstensi = $foto_file->getClientOriginalExtension();
            $foto_nama = date('ymdhis') . "." . $foto_ekstensi;
            $foto_file->move(public_path('image'), $foto_nama);

            // Jika ada gambar yang diunggah, kirim permintaan dengan gambar baru
            $response = $client->request('PUT', $url, [
                'multipart' => [
                    [
                        'name' => 'title',
                        'contents' => $title
                    ],
                    [
                        'name' => 'description',
                        'contents' => $description
                    ],
                    [
                        'name' => 'price',
                        'contents' => $price
                    ],
                    [
                        'name' => 'image',
                        'contents' => fopen(public_path('image') . '/' . $foto_nama, 'r')
                    ]
                ]
            ]);
        } else {
            // Jika tidak ada gambar yang diunggah, kirim permintaan tanpa bidang gambar
            $response = $client->request('PUT', $url, [
                'multipart' => [
                    [
                        'name' => 'title',
                        'contents' => $title
                    ],
                    [
                        'name' => 'description',
                        'contents' => $description
                    ],
                    [
                        'name' => 'price',
                        'contents' => $price
                    ]
                ]
            ]);
        }

        // Lanjutkan dengan penanganan respons seperti yang Anda lakukan sebelumnya
        $content = $response->getBody()->getContents();
        $contentArray = json_decode($content, true);
        dd($contentArray);

        if ($contentArray['status'] != true) {
            $error = $contentArray['data'];
            return redirect('produk/edit')->withErrors($error); // Kembalikan input yang sudah dimasukkan
        }

        return redirect('produk')->with('success', 'Berhasil Update Produk!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
