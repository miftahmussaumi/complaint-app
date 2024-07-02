@extends('template')
@section('style')
<style>
    .table1 {
        border-collapse: collapse;
        border-spacing: 10px;
    }

    .table1 th,
    .table1 td {
        border: 1px solid black;
        padding: 2px;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    @if(Session::has('success'))
    <div class="toastr-trigger" data-type="success" data-message="Akun Sudah Disetujui" data-position-class="Berhasil!"></div>
    @endif

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Setting Kop Surat Laporan</h4>
                <table style="width: 100%; color: black; font-size: 15px;" class="table1">
                    <tr>
                        <td rowspan="4" style="width: 25%; margin-top: 5px;" valign="center" align="center">
                            <img src="{{asset('storage/img/kop_surat/kop_kai.png')}}" width="150" height="70" />
                            <!-- <i style="color: #3167D5; cursor: pointer;" valign="top" type="button" data-toggle="modal" data-target="#exampleModalP" data-whatever="@getbootstrap" class="fa fa-pencil-square-o"></i> -->
                        </td>
                        <td rowspan="2" valign="center">
                            PT Kereta Api Indonesia <br> Sistem Informasi
                        </td>
                        <td style="width: 10%;">Nomor</td>
                        <td style="width: 25%;">{{$kop->nomor}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>{{$tanggal_f}}</td>
                    </tr>
                    <tr>
                        <td rowspan="2">
                            BERITA ACARA INSTALASI DAN <br> TROUBLESHOOTING LAYANAN IT
                        </td>
                        <td>Versi</td>
                        <td>{{$kop->versi}}</td>
                    </tr>
                    <tr>
                        <td>Halaman</td>
                        <td>{{$kop->halaman}}</td>
                    </tr>
                </table><br><br>
                <div class="basic-form">
                    <form action="{{route('update-kop-surat',$kop->id)}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nomor</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nomor" value="{{$kop->nomor}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="tanggal" value="{{$kop->tanggal}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Versi</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="versi" value="{{$kop->versi}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Halaman</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="halaman" value="{{$kop->halaman}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection