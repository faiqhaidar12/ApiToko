<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $data = Produk::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('nama_produk', 'like', '%' . $query . '%')
                ->orWhere('deskripsi', 'like', '%' . $query . '%')
                ->orWhere('harga', 'like', '%' . $query . '%');
        })
            ->orderBy('created_at', 'asc')
            ->get();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Pencarian Produk Tidak Ada!!'
            ], 404);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Data Produk ditemukan!!',
                'data' => $data
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new Produk();

        $rules = [
            'nama_produk' => "required",
            'deskripsi' => "required",
            'harga' => "required|numeric",
            'gambar' => "image|mimes:jpeg,png,jpg,gif|max:2048", // Misalnya, batas ukuran 2MB dan jenis file gambar yang diizinkan.
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Gagal Menambah Produk!!",
                'data' => $validator->errors(),
            ], 500);
        }

        $data->nama_produk = $request->nama_produk;
        $data->deskripsi = $request->deskripsi;
        $data->harga = $request->harga;

        // Proses gambar jika ada dalam permintaan.
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaGambar = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('image'), $namaGambar);
            $data->gambar = $namaGambar;
        }

        $simpan = $data->save();
        if ($simpan) {
            return response()->json([
                'status' => true,
                'message' => "Berhasil Tambah Produk!!",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Gagal Tambah Produk!!",
            ], 500);
        }
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
                'message' => 'Data Produk ditemukan!!',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak ditemukan!!',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Produk::find($id);

        if (empty($data)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak ditemukan!!',
            ], 404);
        }

        $rules = [
            'nama_produk' => "required",
            'deskripsi' => "required",
            'harga' => "required|numeric",
        ];
        // Tambahkan aturan validasi gambar hanya jika ada gambar yang diunggah.
        if ($request->hasFile('gambar')) {
            $rules['gambar'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Gagal Menambah Produk!!",
                'data' => $validator->errors(),
            ], 500);
        }

        $data->nama_produk = $request->nama_produk;
        $data->deskripsi = $request->deskripsi;
        $data->harga = $request->harga;

        // Proses gambar jika ada dalam permintaan.
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaGambar = time() . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('image'), $namaGambar);

            // Hapus gambar lama jika ada.
            if (!empty($data->gambar)) {
                $gambarLamaPath = public_path('image') . '/' . $data->gambar;
                if (file_exists($gambarLamaPath)) {
                    unlink($gambarLamaPath);
                }
            }
            $data->gambar = $namaGambar;
        }
        $simpan = $data->save();
        if ($simpan) {
            return response()->json([
                'status' => true,
                'message' => "Berhasil Update Produk!!",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Gagal Update Produk!!",
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Produk::find($id);

        if (empty($data)) {
            return response()->json([
                'status' => false,
                'message' => 'Data Tidak ditemukan!!',
            ], 404);
        }

        if (!empty($data->gambar)) {
            $gambarPath = public_path('image') . '/' . $data->gambar;
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

        $simpan = $data->delete();

        if ($simpan) {
            return response()->json([
                'status' => true,
                'message' => "Berhasil Hapus Produk!!",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Gagal Hapus Produk!!",
            ], 500);
        }
    }

    public function checkout(Request $request)
    {

        $request->request->add(['total_harga' => $request->qty * 1000000, 'status' => 'unpaid']);
        $order = Order::create($request->all());

        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_harga,
            ],
            'customer_details' => [
                'nama' => $request->nama,
                'phone' => $request->phone,
            ],
            // Tambahkan informasi pembayaran lainnya jika diperlukan
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Mengembalikan respons JSON dengan snapToken dan informasi pesanan
        return response()->json([
            'snapToken' => $snapToken,
            'order' => $order,
        ], 200);
    }
}
