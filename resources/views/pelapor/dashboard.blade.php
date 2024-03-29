@extends('template')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-1">
                <div class="card-body">
                    <h3 class="card-title text-white">Total Pengaduan</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">{{$all}}</h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-archive"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-2">
                <div class="card-body">
                    <h3 class="card-title text-white">Diproses</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">{{$proses}}</h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-repeat"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-3">
                <div class="card-body">
                    <h3 class="card-title text-white">Pengecekan</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">{{$check}}</h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-pencil"></i></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card gradient-4">
                <div class="card-body">
                    <h3 class="card-title text-white">Selesai</h3>
                    <div class="d-inline-block">
                        <h2 class="text-white">{{$selesai}}</h2>
                        <!-- <p class="text-white mb-0">Jan - March 2019</p> -->
                    </div>
                    <span class="float-right display-5 opacity-5"><i class="fa fa-check-square"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection