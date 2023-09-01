@extends('layout.index')
@section('title', 'Home')
@section('content')
    <div class="row mb-5">
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card text-center">
                <div class="card-header">MANTAB</div>
                <div class="card-body">
                    <h5 class="card-title">Asus Rog</h5>
                    <img src="{{ asset('image/rog.png') }}" alt="gambar" class="img-thumbnail">
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
                                    <td>:{{ $order->total_harga }}</td>
                                </tr>
                            </table>
                            <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* You may add your own implementation here */
                    alert("payment success!");
                    console.log(result);
                },
                onPending: function(result) {
                    /* You may add your own implementation here */
                    alert("wating your payment!");
                    console.log(result);
                },
                onError: function(result) {
                    /* You may add your own implementation here */
                    alert("payment failed!");
                    console.log(result);
                },
                onClose: function() {
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
            })
        })
    })
</script>
