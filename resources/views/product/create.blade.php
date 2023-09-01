@extends('layout.index')
@section('title', 'Tambah Data Produk')
@section('content')
    <div class="col-md-6">
        <form method="POST" action="/produk" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_produk">Title</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                        value="{{ Session::get('nama_produk') }}" placeholder="Masukan Nama Pelanggan">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Desc</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi" cols="30" rows="10">{{ Session::get('deskripsi') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="harga">Price</label>
                    <input type="number" class="form-control" id="harga" name="harga"
                        value="{{ Session::get('harga') }}" placeholder="Masukan Harga">
                </div>
                <div class="form-group">
                    <label for="gambar">Image</label>
                    <input type="file" name="gambar" id="gambar" class="form-control">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
