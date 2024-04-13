<table style="width: 100%;" class="table2">
    <tr align="center">
        <td style="width: 4%;" rowspan="2">No</td>
        <td style="width: 15%;" rowspan="2">Kategori Layanan</td>
        <td style="width: 15%;" rowspan="2" colspan="2">Jenis Layanan</td>
        <td style="width: 30%;" rowspan="2">Detail Pekerjaan</td>
        <td style="width: 6%;" colspan="2">Status</td>
        <td style="width: 30%;" rowspan="2">Keterangan</td>
    </tr>
    <tr align="center">
        <td style="width: 3%;">V</td>
        <td style="width: 3%;">X</td>
    </tr>
    <tr>
        <td rowspan="5" align="center">1</tdstyle=>
        <td rowspan="5">Troubleshooting </td>
        <td>1.1</td;>
        <td>Aplikasi</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>1.2</td>
        <td>Jaringan</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>1.3</td>
        <td>PC/Laptop</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>1.4</td>
        <td>Printer</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>1.5</td>
        <td>Lainnya : <br>.......</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @endif
    </tr>
    <tr>
        <td rowspan="6" align="center">2</td>
        <td rowspan="6">Instalasi </td>
        <td>2.1</td>
        <td>Aplikasi</td>
        <td>V</td>
        <td></td>
        <td>Menginstalasi Aplikasi Trouble</td>
        <td>Pekerjaan selesai dengan baik</td>
        @endif
    </tr>
    <tr>
        <td>2.2</td>
        <td>Sistem Operasi</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>2.3</td>
        <td>Jaringan</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>2.4</td>
        <td>PC/Laptop</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>2.5</td>
        <td>Printer</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>2.6</td>
        <td>Lainnya : <br> Lainnya </td>
        <td>V</td>
        <td></td>
        <td>Berhasil Instalasi</td>
        <td>Ye berhasil</td>
    </tr>
</table>

@extends('template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>Tanggal Permintaan</th>
                                    <th>Pelapor</th>
                                    <th>No Referensi</th>
                                    <th>No Inventaris</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Teknisi</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtLap as $data)
                                <tr>
                                    <td>{{ $data->tgl_masuk }}</td>
                                    <td>{{ $data->nama_pelapor }}</td>
                                    <td>{{ $data->lap_no_ref }}</td>
                                    <td>{{ $data->no_inv_aset }}</td>
                                    <td>{{ $data->tgl_selesai }}</td>
                                    <td>
                                        @if($data->id_teknisi != null)
                                        {{$data->nama_teknisi}}
                                        @else
                                        <i>Teknisi belum dipilih</i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{url('detail-laporan-admin',$data->id)}}"><button class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></button></a>
                                        @if($data->id_teknisi == null)
                                        <a data-toggle="modal" data-target="#exampleModalDetail{{$data->id}}" data-whatever="@getbootstrap"><button class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button></a>
                                        @else
                                        <a data-toggle="modal" data-target="#exampleModalDetail{{$data->id}}" data-whatever="@getbootstrap"><button disabled class="btn btn-primary btn-sm"><i class="fa fa-bars"></i></button></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                        @foreach ($dtLap as $detail)
                        <div class="modal fade" id="exampleModalDetail{{$detail->id}}">
                            <div class="modal-dialog" role="document">
                                <form action="{{route('pilih-teknisi',$detail->id)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Proses Laporan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <select name="id_teknisi" class="form-control">
                                                <option value="" selected disabled>Pilih Teknisi</option>
                                                @foreach ($teknisi as $dtt)
                                                <option value="{{$dtt->id}}">{{$dtt->nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Kirim</button>
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