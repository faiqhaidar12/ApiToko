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
                    <p class="card-text">Ini Deskripsi.</p>
                    <form action="/checkout" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama"
                                    name="nama"placeholder="Masukan Nama">
                            </div>
                            <div class="form-group">
                                <label for="qty">Jumlah</label>
                                <input type="number" class="form-control" id="qty"
                                    name="qty"placeholder="Masukan qty">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea type="text" class="form-control" id="alamat" name="alamat"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" class="form-control" id="phone"
                                    name="phone"placeholder="Masukan Phone">
                            </div>
                        </div>
                        <button class="btn btn-primary">Beli</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
