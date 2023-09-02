@extends('layout.index')
@section('title', 'Invoice')
@section('content')
    <div class="row mb-5">
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card text-center">
                <div class="card-header">Invoice</div>
                <div class="card-body">
                    <div class="card-body">
                        <h4>Detail Pesanan</h4>
                        <div class="text-center">
                            <table>
                                <tr>
                                    <td>Nama</td>
                                    <td>: {{ $order->nama }}</td>
                                </tr>
                                <tr>
                                    <td>No Hp</td>
                                    <td>:{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:{{ $order->alamat }}</td>
                                </tr>
                                <tr>
                                    <td>Qty</td>
                                    <td>:{{ $order->qty }}</td>
                                </tr>
                                <tr>
                                    <td>Total Harga</td>
                                    <td>Rp {{ number_format($order->total_harga, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:{{ $order->status }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
