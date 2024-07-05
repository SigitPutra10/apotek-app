@extends('layouts.template')

@section('content')
    <form action="{{ route('order.store')}}" class="card p-4 mt-5" method="POST">
        @csrf
        @if ($errors->any(''))
            <ul class="alert alert-danger"
            @foreach ($errors->all() as $error)
                <li>
                    {{$error}}
                </li>
            </ul>
            @endforeach
        @endif
        <div class="mb-3 d-flex">
            <label for="nama_customer" class="form-label" style="width: 14%">Penanggung Jawab: </label>
            <p style="width: 88%">{{ Auth::user()->name}}</b></p>
        </div>
        <div class="mb-3 d-flex align-item-center">
            <label for="name_customer" class="form-label" style="width: 12%">Nama Pembeli: </label>
            <input type="text" name="name_customer" id="name_customer" class="form-control" style="width: 88%">
        </div>
        <div class="mb-3">
            <div class="d-flex align-item-center">
                <label for="medicines" class="form-label" style="width: 12%">Obat: </label>
                <select name="medicines[]" id="medicines" class="form-control" style="width: 88%">
                    <option selected hidden disabled>Pesanan 1</option>
                    @foreach ($medicines as $medicine)
                    <option value="{{ $medicine['id'] }}">{{$medicine['name']}}</option>
                    @endforeach
                </select>
                </div>
                <div id="warp-select"></div>
                <p class="text-primary" style="margin-left: 13%; cursor:pointer;"onclick="addSelect()">+ Tambah Pesanan</p>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
        @endsection
        @push('script')
        <script>
             let no = 2;
             function addSelect(){
                let el =  `<div class="d-flex align-item-center">
                <label for="medicines" class="form-label" style="width: 12%"></label>
                <select name="medicines[]" id="medicines" class="form-control" style="width: 88%">
                    <option selected hidden disabled>Pesanan ${no}</option>
                    @foreach ($medicines as $medicine)
                    <option value="{{ $medicine['id'] }}">{{$medicine['name']}}</option>
                    @endforeach
                </select>
                </div>`;

                $("#warp-select").append(el);
                
                no++;
             }
        </script>
            
        @endpush