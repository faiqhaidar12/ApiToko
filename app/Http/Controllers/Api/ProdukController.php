<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Produk::orderBy('title', 'asc')->get();
        return response()->json(
            [
                'status' => true,
                'message' => 'Produk ditemukan!',
                'data' => $data
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dataProduk = new Produk;

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal Menambahkan Produk!',
                'data' => $validator->errors(),
            ]);
        }

        $dataProduk->title = $request->title;
        $dataProduk->description = $request->description;
        $dataProduk->price = $request->price;


        $foto_file = $request->file('image');
        $foto_ekstensi = $foto_file->extension();
        $foto_nama = date('ymdhis') . "." . $foto_ekstensi;
        $foto_file->move(public_path('image'), $foto_nama);
        $dataProduk->image = $foto_nama;

        $simpan = $dataProduk->save();

        return response([
            'status' => true,
            'message' => 'Berhasil Tambah Produk!',
            'data' => $dataProduk,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Produk::find($id);
        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Produk ditemukan!',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Produk Tidak ditemukan!',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dataProduk = Produk::find($id);

        if (!$dataProduk) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.',
            ]);
        }

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui produk!',
                'data' => $validator->errors(),
            ]);
        }

        $dataProduk->title = $request->title;
        $dataProduk->description = $request->description;
        $dataProduk->price = $request->price;

        if ($request->hasFile('image')) {
            $foto_file = $request->file('image');
            $foto_ekstensi = $foto_file->extension();
            $foto_nama = date('ymdhis') . "." . $foto_ekstensi;
            $foto_file->move(public_path('image'), $foto_nama);
            $dataProduk->image = $foto_nama;
        }

        $update = $dataProduk->save();

        if ($update) {
            return response([
                'status' => true,
                'message' => 'Produk berhasil diperbarui!',
                'data' => $dataProduk,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui produk!',
            ]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dataProduk = Produk::find($id);
        if (empty($dataProduk)) {
            return response()->json([
                'status' => false,
                'message' => 'Produk Tidak Ada!'
            ], 404);
        }

        $simpan = $dataProduk->delete();
        return response([
            'status' => true,
            'message' => 'Berhasil Hapus Produk!',
            'data' => $dataProduk,
        ]);
    }
}
