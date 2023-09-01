@extends('layout.index')
@section('title', 'produk')
@section('content')
    <a href="/produk/create" class="btn btn-primary mb-2">Tambah Produk</a>
    <div class="card">

        <h5 class="card-header">Kategori</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; ?>
                        @foreach ($data as $item)
                            <?php $no++; ?>
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $item['nama_produk'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 2) }}</td>
                                <td>{{ $item['deskripsi'] }}</td>
                                <td>
                                    @if ($item['gambar'])
                                        <img src="{{ asset('image/' . $item['gambar']) }}" alt="{{ $item['nama_produk'] }}"
                                            width="100">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td style="width: 150px">
                                    <a class="btn btn-sm" href="{{ url('/produk/' . $item['id'] . '/edit') }}"><i
                                            class="bx bx-edit-alt me-1"></i> Edit</a> |
                                    <form onsubmit="return confirm('Apakah Anda Yakin Ingin Hapus Data?')"
                                        action="{{ '/produk/' . $item['id'] }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm" type="submit"><i class="bx bx-trash me-1"></i>
                                            Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- {{ $data->links() }} --}}
        {{-- {{ $pagination['links'] }} --}}
    </div>
@endsection
