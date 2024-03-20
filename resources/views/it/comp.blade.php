@extends('template')
@section('content')
<div class>
    <div class="page-title">
        <div class="title_left">
            <h3>Welcome <small>in Page Complaints</small></h3>
        </div>
        <div class="title_right">
            <!-- <button type="button" class="btn btn-round btn-success">Success</button> -->

            <div class="form-group pull-right">
                <button type="button" class="btn btn-success">Success</button>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>List Complaints Users</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal Permintaan</th>
                                        <th>No Inventaris</th>
                                        <th>Kategori Layanan</th>
                                        <th>Jenis Layanan</th>
                                        <th>Tgl Awal Pengerjaan</th>
                                        <th>Tgl Selesai Pengerjaan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataIT as $data)
                                    <tr>
                                        <td>{{ $data->tgl_masuk }}</td>
                                        <td>{{ $data->no_inv_aset }}</td>
                                        <td>{{ $data->kat_layanan }}</td>
                                        <td>{{ $data->jenis_layanan }}</td>
                                        <td>
                                            @if($data->tgl_awal_pengerjaan == null)
                                            <em>menunggu diproses</em>
                                            @else
                                            {{ $data->tgl_awal_pengerjaan }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->tgl_akhir_pengerjaan == null)
                                            <em>menunggu diproses</em>
                                            @else
                                            {{ $data->tgl_akhir_pengerjaan }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->status_terbaru == 'Pengajuan')
                                            <span class="badge badge-dark">Pengajuan</span>
                                            @elseif($data->status_terbaru == 'Diproses')
                                            <span class="badge badge-info">Diproses</span>
                                            @elseif($data->status_terbaru == 'CheckedU')
                                            <span class="badge badge-warning">Pengecekan User</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->status_terbaru == 'Pengajuan')
                                            <a href="" data-toggle="modal" data-target=".bs-example-modal-lg-{{$data->idhist}}"><i class="fa fa-edit"></i></a>
                                            @elseif($data->status_terbaru == 'Diproses')
                                            <a href="" data-toggle="modal" data-target=".bs-example-modal-lg-{{$data->idhist}}"><i class="fa fa-check-square-o"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @foreach($dataIT as $datam)
                        <div class="modal fade bs-example-modal-lg-{{$datam->idhist}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form action="{{route('update-laporan',$datam->idhist)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Detail Permintaan Layanan</h4>
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Permintaan Layanan dari :</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table>
                                                        <tr>
                                                            <td>Nama</td>
                                                            <td>:</td>
                                                            <td>{{$datam->nama}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Divisi</td>
                                                            <td>:</td>
                                                            <td>{{$datam->divisi}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Telepon</td>
                                                            <td>:</td>
                                                            <td>{{$datam->telepon}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email</td>
                                                            <td>:</td>
                                                            <td>{{$datam->email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>No Inventaris Aset</td>
                                                            <td>:</td>
                                                            <td>{{$datam->no_inv_aset}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Kategori Layanan</td>
                                                            <td>:</td>
                                                            <td>{{$datam->kat_layanan}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jenis Layanan</td>
                                                            <td>:</td>
                                                            <td>{{$datam->jenis_layanan}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Detail</td>
                                                            <td>:</td>
                                                            <td>{{$datam->det_layanan}}</td>
                                                        </tr>
                                                        @if($datam->tgl_awal_pengerjaan != null && $datam->tgl_akhir_pengerjaan != null)
                                                        <tr>
                                                            <td>Waktu Pengerjaan</td>
                                                            <td>:</td>
                                                            <td>
                                                                {{ $datam->tgl_awal_pengerjaan }} - {{ $datam->tgl_akhir_pengerjaan }}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <fieldset>
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                <input type="text" name="status_laporan" value="{{$datam->status_terbaru}}" hidden>
                                                                <input type="text" name="id_laporan" value="{{$datam->idlap}}" hidden>
                                                                @if($datam->tgl_awal_pengerjaan == null && $datam->tgl_akhir_pengerjaan == null)
                                                                Proses Pengerjaan :
                                                                <div class="input-prepend input-group">
                                                                    <!-- <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span> -->
                                                                    <!-- <input type="text" name="tgl_pengerjaan" id="reservation-time" class="form-control" value="01/01/2016 - 01/25/2016" /> -->
                                                                    <input type="time" name="waktu_awal">
                                                                    <input type="date" name="tgl_awal">
                                                                    -
                                                                    <input type="time" name="waktu_akhir">
                                                                    <input type="date" name="tgl_akhir">
                                                                </div>
                                                                @endif
                                                            </div>
                                                            @if($datam->status_terbaru == 'Diproses')
                                                            Keterangan (Opsional)
                                                            <textarea class="form-control" name="keterangan" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="100"></textarea>
                                                            @endif
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            @if($datam->status_terbaru == 'Pengajuan')
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            @elseif($datam->status_terbaru == 'Diproses')
                                            <button type="submit" class="btn btn-primary">Pelayanan Selesai</button>
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
    </div>
</div>
@endsection