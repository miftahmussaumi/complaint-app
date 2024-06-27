@extends('template')
@section('content')
<div class="container-fluid">
    @if(Session::has('success'))
    <div class="toastr-trigger" data-type="success" data-message="Data berhasil diubah" data-position-class="Perubahan disimpan!"></div>
    @endif

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Persetujuan Akun Pengguna</h4>
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Pelapor</a></li>
                    <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Teknisi IT</a></li>
                    <li class="nav-item"><a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="false">Pengawas</a></li>
                </ul>
                <div class="tab-content br-n pn">

                    <div id="navpills-1" class="tab-pane active">
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIPP</th>
                                                <th>Jabatan</th>
                                                <th>Unit</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($pelapor as $dtpelapor)
                                            <?php $no++ ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$dtpelapor->nama}}</td>
                                                <td>{{$dtpelapor->nipp}}</td>
                                                <td>{{$dtpelapor->jabatan}}</td>
                                                <td>{{$dtpelapor->divisi}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal{{$dtpelapor->id}}" data-whatever="@getbootstrap"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                    @foreach($pelapor as $dtp2)
                                    <div class="modal fade" id="exampleModal{{$dtp2->id}}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{route('acc-akun',$dtp2->id)}}" method="post">
                                                    {{csrf_field()}}
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Pelapor</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table style="color: #2D3134;">
                                                            <tr>
                                                                <td style="width: 150px;">Nama</td>
                                                                <td style="width: 15px;">:</td>
                                                                <td>{{ $dtp2->nama }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>NIPP</td>
                                                                <td>:</td>
                                                                <td>{{ $dtp2->nipp }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jabatan</td>
                                                                <td>:</td>
                                                                <td>{{ $dtp2->jabatan }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Unit</td>
                                                                <td>:</td>
                                                                <td>{{ $dtp2->divisi }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>:</td>
                                                                <td>{{ $dtp2->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>No. Telepon</td>
                                                                <td>:</td>
                                                                <td>{{ $dtp2->telepon }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" type="submit" name="action" value="accept">Setujui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== HALAMAN TAB AKUN TEKNISI ===== -->
                    <div id="navpills-2" class="tab-pane">
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIPP</th>
                                                <th>Jabatan</th>
                                                <th>Email</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($teknisi as $dtteknisi)
                                            <?php $no++ ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$dtteknisi->nama}}</td>
                                                <td>{{$dtteknisi->nipp}}</td>
                                                <td>{{$dtteknisi->jabatan}}</td>
                                                <td>{{$dtteknisi->email}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal{{$dtteknisi->id}}" data-whatever="@getbootstrap"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== END HALAMAN TAB AKUN TEKNISI ===== -->

                    <!-- ===== HALAMAN TAB AKUN PENGAWAS ===== -->
                    <div id="navpills-3" class="tab-pane">
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIPP</th>
                                                <th>Jabatan</th>
                                                <th>Email</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($pengawas as $dtpengawas)
                                            <?php $no++ ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$dtpengawas->nama}}</td>
                                                <td>{{$dtpengawas->nipp}}</td>
                                                <td>{{$dtpengawas->jabatan}}</td>
                                                <td>{{$dtpengawas->email}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal{{$dtpengawas->id}}" data-whatever="@getbootstrap"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== END HALAMAN TAB AKUN PENGAWAS ===== -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection