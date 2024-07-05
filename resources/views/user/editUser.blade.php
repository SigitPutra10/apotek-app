@extends('layouts.template')

@section('content')
<form action="{{ route('user.update', $user['id'])}}" method="post" class="card p-5 mt-5 bg-light">
    {{--sebagai token akses database--}}
    @csrf
    @method('PATCH')
    {{--jika terjadi error validasi, akan ditampilkan bagian errornya : --}}
    @if ($errors->any())
        <ul class="alert alert-danger p-5">
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif

    <div class="mb-3 row">
      <label for="name" class="col-sm-2 col-form-label">Nama :</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="name" name="name" value="{{ $user['name'] }}">
      </div>
    </div>
    <div class="mb-3 row">
        <label for="price" class="col-sm-2 col-form-label"> Email: </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" name="email" value="{{ $user['email'] }}">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="role" class="col-sm-2 col-form-label">Tipe Pengguna :</label>
        <div class="col-sm-10">
            <select id="role" class="form-control" name="role">
                <option disabled hidden selected> Pilih </option>
                <option value="kasir" {{ $user['role'] == 'kasir' ? 'selected' : '' }} > kasir </option>
                <option value="admin" {{ $user['role'] == 'admin' ? 'selected' : '' }} > Admin </option>
            </select>
        </div>
    </div>
    <div class="mb-3 row">
        <label for="price" class="col-sm-2 col-form-label">Password :</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="password" name="password" value="{{ $user['password']}}">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Kirim</button>
</form>
@endsection