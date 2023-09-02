<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();
        return view('home.index')->with('produk', $produk);
    }

    public function checkout(Request $request)
    {
        // Ambil harga produk dari database berdasarkan product_id (ganti dengan kolom yang sesuai di database Anda)
        $produk = Produk::find($request->produk_id);
        $gambar = Produk::all();
        if (!$produk) {
            // Handle jika produk tidak ditemukan
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }
        // Hitung total harga berdasarkan kuantitas (qty) dan harga produk
        $total_harga = $produk->harga * $request->qty;

        $request->request->add(['total_harga' => $total_harga, 'status' => 'unpaid']);
        $order = Order::create($request->all());

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id,
                'gross_amount' => $order->total_harga,
            ),
            'customer_details' => array(
                'first_name' => $request->nama,
                'address' => $request->alamat,
                'phone' => $request->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        // return view('checkout.index', compact('produk', 'snapToken', 'order', 'gambar', 'request'));
        return view('checkout.index')
            ->with('produk', $produk)
            ->with('snapToken', $snapToken)
            ->with('order', $order)
            ->with('gambar', $gambar)
            ->with('request', $request);
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture') {
                $order = Order::find($request->order_id);
                $order->update(['status' => 'Paid']);
            } elseif ($request->transaction_status == 'pending') {
                $order = Order::find($request->order_id);
                $order->update(['status' => 'Pending']);
            } elseif ($request->transaction_status == 'settlement') {
                $order = Order::find($request->order_id);
                $order->update(['status' => 'Paid']);
            }
        }
    }

    public function invoice($id)
    {
        $order = Order::find($id);
        return view('invoice.index')->with('order', $order);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
