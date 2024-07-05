@extends('layouts.template')

@section('content')
@if (Session::get('succes'))
    <div class="alert alert-succes">
        {{Session::get('succes')}}
    </div>
@endif
@if (Session::get('deleted'))
    <div class="alert alert-warning">
        {{Session::get('deleted')}}
    </div>
@endif
<p></p>
<a href="{{  route('user.create')  }}" class="btn btn-secondary" style="float: right; margin-bottom: 10px;">Tambah Pengguna</a>
    <table class="table mt-5 table-striped table-bordered table-hovered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($user as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['email'] }}</td>
                    <td>{{ $item['role'] }}</td>
                    <td  class="d-flex">
                        <a href="{{ route('user.edit', $item['id']) }}" class="btn btn-success">Edit</a>
                        {{-- method::DELETE tidak bisa di gunakan pada a href, harus melalui form action --}}
                        <form action="{{ route('user.delete', $item['id']) }}"  method="POST" class="ms-3">
                            @csrf
                            {{-- menimpa/mengubah method="post" agar menjadi method="delete" sesuai dengan method route (::delete) --}}
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach 
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        @if ($user->count())
            {{ $user->links() }}
        @endif
    </div>
@endsection