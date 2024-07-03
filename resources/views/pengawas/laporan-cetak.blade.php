@extends('template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <h3>Data Permintaan Layanan</h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Pelapor</th>
                                    <th>No Referensi</th>
                                    <th>No Inventaris</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Teknisi</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0; ?>
                                @foreach ($lap as $data)
                                <?php $no++ ?>
                                <tr>
                                    <td>{{ $no }}</td>
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
                                        <a href="{{url('detail-laporan',$data->id)}}"><button class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></button></a>
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal{{$data->id}}" data-whatever="@getbootstrap"><i class="fa fa-pencil-square-o"></i></button>
                                        <a href="{{url('cetak-laporan',$data->id)}}"><button class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i></button></a>

                                        <!-- @if($data->ttd != null)
                                        <a href="{{url('cetak-laporan',$data->id)}}"><button class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i></button></a>
                                        @else
                                        <a href="{{url('profile-pengawas')}}"><button class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Masukkan TTD dahulu"><i class="fa fa-file-pdf-o"></i></button></a>
                                        @endif -->
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                        @foreach ($lap as $data2)
                        <!-- ========= MODAL ========= -->
                        <div class="modal fade" id="exampleModal{{$data2->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{route('laporan-selesai-it',$data2->id)}}" method="POST">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Alihkan Penanggung Jawab Laporan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="message-text" class="col-form-label">Alihkan ke :</label>
                                                <div class="form-group">
                                                    @foreach($pengawas as $dt)
                                                    <select class="form-control" required name="">
                                                        <option>1</option>
                                                    </select>
                                                    @endforeach
                                                </div>
                                                <div id="error-message" class="error"></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                        <!-- ========= END MODAL ========= -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection