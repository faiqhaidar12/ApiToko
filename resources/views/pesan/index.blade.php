@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <ul>
            @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Session::get('success'))
    <div class="alert alert-success alert-dismissible">
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ Session::get('success') }}
    </div>
@endif

@if (Session::get('update'))
    <div class="alert alert-warning alert-dismissible">
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ Session::get('update') }}
    </div>
@endif
