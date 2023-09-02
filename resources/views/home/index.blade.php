@extends('layout.index')
@section('title', 'Home')
@section('content')
    <div class="row mb-5">
        @foreach ($produk as $item)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center d-flex align-items-stretch">
                    <div class="card-header">MANTAB</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->nama_produk }}</h5>
                        @if ($item->gambar)
                            <img src="{{ asset('image/' . $item->gambar) }}" width="100" height="100">
                        @else
                            <span>No Image</span>
                        @endif
                        <!-- Isi card lainnya -->
                        <p class="card-text">{{ $item->deskripsi }}</p>
                        <h5 class="card-text">Rp {{ number_format($item->harga, 2) }}</h5>
                        <form action="/checkout" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama"
                                    placeholder="Masukkan Nama">
                            </div>
                            <input type="hidden" name="produk_id" value="{{ $item->id }}">
                            <div class="form-group">
                                <label for="qty">Jumlah</label>
                                <input type="number" class="form-control" name="qty" id="qty"
                                    placeholder="Masukkan qty">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea type="text" class="form-control" name="alamat" id="alamat" name="alamat"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" class="form-control" name="phone" id="phone"
                                    placeholder="Masukkan Phone">
                            </div>
                            <button class="btn btn-primary mt-2">Beli</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
