<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

        .table2 {
            border-collapse: collapse;
            border-spacing: 10px;
        }

        .table2 th,
        .table2 td {
            border: 1px solid black;
            padding: 2px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="font-family: Arial, sans-serif; font-size: 10;">
            <table style="width: 100%;" class="table1">
                <tr>
                    <td rowspan="4" style="width: 25%; margin-top: 5px;" valign="center" align="center">
                        <img src="{{storage_path('app/public/img/kai.png')}}" width="100" height="60" />
                    </td>
                    <td rowspan="2" valign="center">
                        PT. Kereta Api Indonesia <br> Sistem Informasi
                    </td>
                    <td style="width: 10%;">Nomor</td>
                    <td style="width: 25%;">{{$dt->nomor}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{$dt->tanggal}}</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        BERITA ACARA INSTALASI DAN <br>TROUBLESHOOTING LAYANAN IT
                    </td>
                    <td>Versi</td>
                    <td>{{$dt->versi}}</td>
                </tr>
                <tr>
                    <td>Halaman</td>
                    <td>{{$dt->halaman}}</td>
                </tr>
            </table>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 2px; width: 15%;">No. Ref</td>
                    <td style="padding: 2px; width: 2%;">:</td>
                    <td style="padding: 2px; width: 63%;">{{$dt->no_ref}}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{$dt->tanggal}}</td>
                </tr>
                <tr>
                    <td>Business Area</td>
                    <td>:</td>
                    <td>{{$dt->bisnis_area}}</td>
                </tr>
            </table>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td colspan="3" style="width: 100%;">Permintaan Layanan dari :</td>
                </tr>
                <tr>
                    <td style="width: 15%;">Nama</td>
                    <td style="width: 2%;">:</td>
                    <td style="width: 63%;">{{$dt->nama_pelapor}}</td>
                </tr>
                <tr>
                    <td>Divisi</td>
                    <td>:</td>
                    <td>{{$dt->divisi}}</td>
                </tr>
                <tr>
                    <td>Telepon / Email</td>
                    <td>:</td>
                    <td>{{$dt->telepon}} / {{$dt->email}}</td>
                </tr>
                <tr>
                    <td>Waktu Pengerjaan</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width: 100%;" colspan="3">Tanggal : {{$dt->tgl_awal_pengerjaan}}. Pukul : {{$dt->waktu_awal_pengerjaan}} s.d Tanggal : {{$dt->tgl_akhir_pengerjaan}} Pukul : {{$dt->waktu_akhir_pengerjaan}}</td>
                </tr>
            </table>
            <br>
            <table style="width: 100%;" class="table2">
                <tr>
                    <td style="width: 19%;" colspan="2">Nomor Inventaris Aset</td>
                    <td style="width: 70%;" colspan="6">: {{$dt->no_inv_aset}}</td>
                </tr>
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
                    @if($dt->kat_layanan == 'Throubleshooting' && $dt->jenis_layanan == 'Aplikasi')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td align="center" valign="center"><img src="{{storage_path('app/public/img/check.png')}}" width="15" height="15" /></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>1.2</td>
                    <td>Jaringan</td>
                    @if($dt->kat_layanan == 'Throubleshooting' && $dt->jenis_layanan == 'Jaringan')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>1.3</td>
                    <td>PC/Laptop</td>
                    @if($dt->kat_layanan == 'Throubleshooting' && $dt->jenis_layanan == 'PC/Laptop')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>1.4</td>
                    <td>Printer</td>
                    @if($dt->kat_layanan == 'Throubleshooting' && $dt->jenis_layanan == 'Printer')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>1.5</td>
                    @if($dt->kat_layanan == 'Throubleshooting' && $dt->jenis_layanan != 'Aplikasi' && $dt->jenis_layanan != 'Jaringan' && $dt->jenis_layanan != 'PC/Laptop' && $dt->jenis_layanan != 'Printer')
                    <td>Lainnya : <br>{{$dt->jenis_layanan}}</td>
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
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
                    @if($dt->kat_layanan == 'Instalasi' && $dt->jenis_layanan == 'Aplikasi')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>2.2</td>
                    <td>Sistem Operasi</td>
                    @if($dt->kat_layanan == 'Instalasi' && $dt->jenis_layanan == 'Sistem Operasi')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>2.3</td>
                    <td>Jaringan</td>
                    @if($dt->kat_layanan == 'Instalasi' && $dt->jenis_layanan == 'Jaringan')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>2.4</td>
                    <td>PC/Laptop</td>
                    @if($dt->kat_layanan == 'Instalasi' && $dt->jenis_layanan == 'PC/Laptop')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>2.5</td>
                    <td>Printer</td>
                    @if($dt->kat_layanan == 'Instalasi' && $dt->jenis_layanan == 'Printer')
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
                <tr>
                    <td>2.6</td>
                    @if($dt->kat_layanan == 'Instalasi' && $dt->jenis_layanan != 'Aplikasi' && $dt->jenis_layanan != 'Jaringan' && $dt->jenis_layanan != 'PC/Laptop' && $dt->jenis_layanan != 'Printer' && $dt->jenis_layanan != 'Sistem Operasi')
                    <td>Lainnya : <br>{{$dt->jenis_layanan}}</td>
                    <td>{{$dt->det_pekerjaan}}</td>
                    <td><i class="fa fa-check" aria-hidden="true"></i></td>
                    <td></td>
                    <td>{{$dt->ket_pekerjaan}}</td>
                    @else
                    <td>Lainnya : <br>.......</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @endif
                </tr>
            </table>
            <p>*v: selesai; x : gagal <br>
                Menyatakan bahwa, Penanganan instalasi dan atau troubleshooting telah diperiksa dan dilakukan oleh pihak sistem
                informasi dan pihak {{$dt->nama_pelapor}} dengan hasil seperti dijelaskan diatas.</p>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 5px; width: 33%;" align="center">
                        Staf IT <br>
                        <img src="{{storage_path('app/public/img/admin/'.$dt->ttd_admin)}}" width="130px" height="100px">
                        <br>
                        <u>{{$dt->nama_admin}}</u><br>
                        NIPP.{{$dt->nipp_admin}}
                    </td>
                    <td style="padding: 5px; width: 33%;"></td>
                    <td style="padding: 5px; width: 33%;" align="center">
                        User <br>
                        <img src="{{storage_path('app/public/img/pelapor/'.$dt->ttd_pelapor)}}" width="130px" height="100px">
                        <br>
                        <u>{{$dt->nama_pelapor}}</u><br>
                        NIPP.{{$dt->nipp_pelapor}}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center">
                        Mengetahui, <br> Manager <br>
                        <img src="{{storage_path('app/public/img/pengawas/'.$dt->ttd_pengawas)}}" width="130px" height="100px">
                        <br>
                        <u>{{$dt->nama_pengawas}}</u><br>
                        NIPP.{{$dt->nipp_pengawas}}
                    </td>
                    <td> </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>