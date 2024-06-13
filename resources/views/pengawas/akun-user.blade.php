@extends('template')
@section('content')
<!-- {{Auth::guard('pengawas')->user()->id}} -->
<div class="container-fluid">
    @if(Session::has('success'))
    <div class="toastr-trigger" data-type="success" data-message="Pelapor Dapat Login" data-position-class="Akun Berhasil Disetujui"></div>
    @endif
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pengguna</h4>
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Pelapor</a></li>
                    @if($cacc != 0)
                    <li class="nav-item"><a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="false">Acc Akun Pelapor <div class="badge badge-pill badge-primary">{{$cacc}}</div></a></li>
                    @endif
                    <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Teknisi IT </a></li>
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
                                                <th>Email</th>
                                                <th>Telepon</th>
                                                <!-- <th>Total Pengaduan</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($pelapor as $dtp)
                                            <?php $no++ ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$dtp->nama}}</td>
                                                <td>{{$dtp->nipp}}</td>
                                                <td>{{$dtp->jabatan}}</td>
                                                <td>{{$dtp->divisi}}</td>
                                                <td>{{$dtp->email}}</td>
                                                <td>{{$dtp->telepon}}</td>
                                                <!-- <td>{{$dtp->jumlah_laporan}}</td> -->
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== HALAMAN TAB AKUN ADMIN ===== -->
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
                                                <th>Total Penyelesaian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($it as $dtit)
                                            <?php $no++ ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$dtit->nama}}</td>
                                                <td>{{$dtit->nipp}}</td>
                                                <td>{{$dtit->jabatan}}</td>
                                                <td>{{$dtit->email}}</td>
                                                <td>{{$dtit->jumlah_laporan}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== END HALAMAN TAB AKUN ADMIN ===== -->

                    <!-- ===== HALAMAN TAB ACC AKUN PELAPOR ===== -->
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
                                                <th>Unit</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($acc as $dtp)
                                            <?php $no++ ?>
                                            <tr>
                                                <td>{{$no}}</td>
                                                <td>{{$dtp->nama}}</td>
                                                <td>{{$dtp->nipp}}</td>
                                                <td>{{$dtp->jabatan}}</td>
                                                <td>{{$dtp->divisi}}</td>
                                                <td>
                                                    <!-- <a href="{{url('detail-laporan',$dtp->id)}}"><button class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></button></a>
                                                    <a href="{{url('detail-laporan',$dtp->id)}}"><button class="btn btn-success btn-sm"><i class="fa fa-check"></i></button></a> -->
                                                    <!-- <a href="{{url('cetak-laporan',$dtp->id)}}"><button class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button></a> -->
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal{{$dtp->id}}" data-whatever="@getbootstrap"><i class="fa fa-eye"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @foreach($acc as $dtp2)
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
                                                        <!-- <button class="btn btn-danger" type="submit" name="action" value="tolak">Tolak</button> -->
                                                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
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
                    <!-- ===== END HALAMAN TAB ACC AKUN PELAPOR ===== -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection