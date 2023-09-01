@extends('layout.index')
@section('title', 'Edit Data Produk') <!-- Ubah judul sesuai dengan konteks -->
@section('content')
    <div class="col-md-6">
        <form method="POST" action="{{ route('produk.update', $data['id']) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_produk">Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                        placeholder="Masukkan Nama Produk" value="{{ $data['nama_produk'] }}">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi" cols="30" rows="10">{{ $data['deskripsi'] }}</textarea>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga"placeholder="Masukkan Harga"
                        value="{{ $data['harga'] }}">
                </div>
                <div class="form-group">
                    <label for="gambar">Image</label>
                    <input type="file" name="gambar" id="gambar" class="form-control">
                </div>
                <!-- Tampilkan gambar produk yang sudah ada -->
                <div class="form-group">
                    <label for="gambar">Current Image</label>
                    @if ($data['gambar'])
                        <img src="{{ asset('image') }}/{{ $data['gambar'] }}" alt="gambar"
                            style="max-height: 250px; max-width: 250px;" class="img-thumbnail">
                    @else
                        <p>Tidak ada gambar yang tersedia.</p>
                    @endif
                </div>
                <div>
                    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
