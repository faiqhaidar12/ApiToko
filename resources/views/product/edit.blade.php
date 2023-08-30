@extends('layout.index')
@section('title', 'Edit Data Produk') <!-- Ubah judul sesuai dengan konteks -->
@section('content')
    <div class="col-md-6">
        <form method="POST" action="{{ route('produk.update', $data['id']) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ old('title', $data['title']) }}" placeholder="Masukkan Nama Produk">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" id="description" cols="30" rows="10">{{ old('description', $data['description']) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price"
                        value="{{ old('price', $data['price']) }}" placeholder="Masukkan Harga">
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>

                <!-- Tampilkan gambar produk yang sudah ada -->
                <div class="form-group">
                    <label for="current_image">Current Image</label>
                    @if ($data['image'])
                        <img src="{{ asset('image') }}/{{ $data['image'] }}" alt="Current Image"
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
