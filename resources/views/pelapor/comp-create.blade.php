@extends('template')

@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/comp">Layanan</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i>Permintaan Layanan</i></a></li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="card-title">Form Permintaan Layanan</h4> -->
                    <div class="basic-form">
                        <form action="{{route('save-laporan')}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">No Inventaris Aset</label>
                                <div class="col-sm-10">
                                    <input type="text" name="no_inv_aset" class="form-control" placeholder="Nomor Inventaris Aset">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kategori Layanan</label>
                                <div class="col-sm-10">
                                    <select name="kat_layanan" id="kat_layanan" onchange="showJenisLayanan()" class="form-control">
                                        <option value="">Pilih satu</option>
                                        <option value="Throubleshooting">Throubleshooting</option>
                                        <option value="Instalasi">Instalasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kategori Layanan</label>
                                <div class="col-sm-10">
                                    <div class="basic-form">
                                        <div class="form-row">
                                            <div class="col">
                                                <select name="jenis_layanan" id="jenis_layanan" onchange="showLainnya()" class="form-control">
                                                    <!-- Opsi dinamis akan dimasukkan di sini menggunakan JavaScript -->
                                                </select>
                                            </div>
                                            <div class="col" id="LainnyaInput" style="display: none;">
                                                <input type="text" name="layanan_lain" id="LainnyaInput" class="form-control" placeholder="Jenis Layanan Lainnya">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Periode Pengerjaan :</label>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Awal</label>
                                <div class="col-sm-10">
                                    <div class="basic-form">
                                        <div class="form-row">
                                            <div class="col">
                                                <div class="input-group">
                                                    <input type="text" name="tgl_awal" class="form-control mydatepicker" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                                    <input type="text" name="waktu_awal" class="form-control" value="13:14"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Awal</label>
                                <div class="col-sm-10">
                                    <div class="basic-form">
                                        <div class="form-row">
                                            <div class="col">
                                                <div class="input-group">
                                                    <input type="text" name="tgl_akhir" class="form-control" id="datepicker-autoclose" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                                    <input type="text" name="waktu_akhir" class="form-control" value="13:14"> <span class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Permasalahan</label>
                                <div class="col-sm-10">
                                    <textarea style="height: 120px;" type="text" name="det_layanan" class="form-control" placeholder="Masukkan detail permasalahan"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function showJenisLayanan() {
        var firstOption = document.getElementById('kat_layanan').value;
        var secondDropdown = document.getElementById('secondDropdown');
        var LainnyaInput = document.getElementById('LainnyaInput');

        // Hapus opsi sebelumnya
        var secondOptionDropdown = document.getElementById('jenis_layanan');
        secondOptionDropdown.innerHTML = "";

        if (firstOption === 'Throubleshooting') {
            var options = ["Aplikasi", "Jaringan", "PC/Laptop", "Printer", "Lainnya"];
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option;
                opt.innerHTML = option;
                secondOptionDropdown.appendChild(opt);
            });
            secondDropdown.style.display = 'block';
            LainnyaInput.style.display = 'none';
        } else if (firstOption === 'Instalasi') {
            var options = ["Aplikasi", "Sistem Operasi", "Jaringan", "PC/Laptop", "Printer", "Lainnya"];
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option;
                opt.innerHTML = option;
                secondOptionDropdown.appendChild(opt);
            });
            secondDropdown.style.display = 'block';
            LainnyaInput.style.display = 'none';
        } else {
            secondDropdown.style.display = 'none';
            LainnyaInput.style.display = 'none';
        }
    }

    function showLainnya() {
        var secondOption = document.getElementById('jenis_layanan').value;
        var LainnyaInput = document.getElementById('LainnyaInput');

        if (secondOption === 'Lainnya') {
            LainnyaInput.style.display = 'block';
        } else {
            LainnyaInput.style.display = 'none';
        }
    }
</script>

@endsection