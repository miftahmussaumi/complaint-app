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
                                    <th>No Inventaris</th>
                                    <th>Batas Akhir Pengerjaan</th>
                                    <th>Teknisi</th>
                                    <th>Status</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtLap as $data)
                                <tr>
                                    <td>{{ $data->tgl_masuk }}</td>
                                    <td>{{ $data->no_inv_aset }}</td>
                                    <td>
                                        @if($data->waktu_tambahan != null)
                                        <?php
                                        $tanggalDeadline        = $data->deadline;
                                        $waktu_tambahan         = $data->waktu_tambahan;
                                        $tanggalBaruTimestamp   = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                        $tanggalBaru_format     = date('d M Y', $tanggalBaruTimestamp) . ', ' . date('H:i', $tanggalBaruTimestamp) . ' WIB';
                                        $tanggalBaru            = date('d-m-Y', $tanggalBaruTimestamp);
                                        ?>
                                        <!-- MENAMPILKAN TANGGAL BARU SETELAH PENAMBAHAN WAKTU -->
                                        <span style="color: #3167D5;">{{ $tanggalBaru_format }}</span>
                                        @else
                                        {{ $data->tgl_akhir_pengerjaan }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->id_teknisi != null)
                                        {{$data->nama_teknisi}}
                                        @else
                                        <i>Teknisi belum dipilih</i>
                                        @endif
                                    </td>

                                    <td>
                                        @if($data->status_terakhir == 'Pengajuan')
                                        <span class="badge badge-primary">Open</span>
                                        @elseif($data->status_terakhir == 'Diproses')
                                        <span class="badge badge-info">Diproses</span>
                                        @elseif($data->status_terakhir == 'CheckedU')
                                        <span class="badge badge-warning">User Check</span>
                                        @elseif($data->status_terakhir == 'ReqHapus')
                                        <span class="badge badge-warning">Request <i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                        @elseif($data->status_terakhir == 'reqAddTime')
                                        <span class="badge badge-warning">Request <i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                        @elseif($data->status_terakhir == 'Selesai')
                                        <span class="badge badge-success">Closed</span>
                                        @elseif($data->status_terakhir == 'Manager')
                                        <span class="badge badge-success">Manager</span>
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