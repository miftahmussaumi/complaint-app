@extends('template')
@section('content')
<!-- {{Auth::guard('pengawas')->user()->id}} -->
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pengguna</h4>
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Pelapor</a>
                    </li>
                    <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Teknisis IT</a>
                    </li>
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
                                                <!-- <th>Jabatan</th>
                                                <th>Divisi</th> -->
                                                <th>Email</th>
                                                <!-- <th>Telepon</th> -->
                                                <th>Penanggung Jawab</th>
                                                <th>Total Pengaduan</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 0; ?>
                                            @foreach($pelapor as $dtp)
                                            <tr>
                                                <?php $no++ ?>
                                                <td>{{$no}}</td>
                                                <td>{{$dtp->nama}}</td>
                                                <td>{{$dtp->nipp}}</td>
                                                <!-- <td>{{$dtp->jabatan}}</td>
                                                <td>{{$dtp->divisi}}</td> -->
                                                <td>{{$dtp->email}}</td>
                                                <!-- <td>{{$dtp->telepon}}</td> -->
                                                <td>
                                                    @if ($dtp->id_admin_tj == null)
                                                    <span class="badge badge-warning">Pengajuan Akun</span>
                                                    @else
                                                    {{$dtp->nama_admin}}
                                                    @endif
                                                </td>
                                                <td>{{$dtp->jumlah_laporan}}</td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#exampleModalDetail{{$dtp->id}}" data-whatever="@getbootstrap"><button class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                    </table>
                                    @foreach ($pelapor as $dtmp)
                                    <div class="modal fade" id="exampleModalDetail{{$dtmp->id}}">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{route('pilih-pjuser',$dtmp->id)}}" method="post">
                                                {{csrf_field()}}
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Detail Data Pelapor</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table style="color: #2D3134;">
                                                            <tr>
                                                                <td><b>Data Pelapor</b></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nama</td>
                                                                <td style="width: 15px;">:</td>
                                                                <td>{{$dtmp->nama}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jabatan</td>
                                                                <td>:</td>
                                                                <td>{{$dtmp->jabatan}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Divisi</td>
                                                                <td>:</td>
                                                                <td>{{$dtmp->divisi}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>:</td>
                                                                <td>{{$dtmp->email}}</td>
                                                            </tr>
                                                            <tr style="height: 40px;" valign="top">
                                                                <td>Telepon</td>
                                                                <td>:</td>
                                                                <td>{{$dtmp->telepon}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Penanggung Jawab</b></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            @if ($dtmp->id_admin_tj != null)
                                                            <tr>
                                                                <td>Nama</td>
                                                                <td>:</td>
                                                                <td>{{$dtmp->nama_admin}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Nip</td>
                                                                <td>:</td>
                                                                <td>{{$dtmp->nipp_admin}}</td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <td>Nama</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <select name="id_admin_tj" class="form-control">
                                                                        <option value="" selected disabled>Pilih Penanggung Jawab</option>
                                                                        @foreach ($it as $dtit)
                                                                        <option value="{{$dtit->id}}">{{$dtit->nama}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        @if($dtmp->id_admin_tj == null)
                                                        <button type="submit" class="btn btn-success">Kirim</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
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
                                                <?php $no = 1; ?>
                                                <td>{{$no++}}</td>
                                                <td>{{$dtit->nama}}</td>
                                                <td>{{$dtit->nipp}}</td>
                                                <td>{{$dtit->jabatan}}</td>
                                                <td>{{$dtit->email}}</td>
                                                <td>{{$dtit->jumlah_laporan}}</td>
                                            </tr>
                                            @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== END HALAMAN TAB AKUN ADMIN ===== -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection