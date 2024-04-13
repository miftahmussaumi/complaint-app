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
                                <label class="col-sm-2 col-form-label"><b>Periode Pelaporan :</b></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Awal</label>
                                <div class="col-sm-10">
                                    <div class="basic-form">
                                        <div class="form-row">
                                            <div class="col">
                                                <div class="input-group">
                                                    <input type="text" id="tgl_awal" name="tgl_awal" class="form-control datepicker" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
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
                                <label class="col-sm-2 col-form-label">Akhir</label>
                                <div class="col-sm-10">
                                    <div class="basic-form">
                                        <div class="form-row">
                                            <div class="col">
                                                <div class="input-group">
                                                    <input type="text" id="tgl_akhir" name="tgl_akhir" class="form-control datepicker" id="datepicker-autoclose" placeholder="mm/dd/yyyy"> <span class="input-group-append"><span class="input-group-text"><i class="mdi mdi-calendar-check"></i></span></span>
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
                                <label class="col-sm-2 col-form-label"><b>Isi Pelaporan</b></label>
                            </div>
                            <!-- FORM MULTIINPUT -->
                            <div id="laporanInputs">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 17%;">
                                            <label>Kategori/Jenis</label>
                                        </td>
                                        <td colspan="2" style="height: 95px;">
                                            <div class="form-row">
                                                <div class="col">
                                                    <div class="input-group">
                                                        <select name="kat_layanan[]" class="form-control kat-layanan">
                                                            <option value="">Pilih satu</option>
                                                            <option value="Throubleshooting">Throubleshooting</option>
                                                            <option value="Instalasi">Instalasi</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group clockpicker" data-placement="bottom" data-align="top" data-autoclose="true">
                                                        <div class="col">
                                                            <select name="jenis_layanan[]" class="form-control jenis-layanan">
                                                            </select>
                                                        </div>
                                                        <div class="col lainnya-input" style="display: none;">
                                                            <input type="text" name="layanan_lain[]" class="form-control" placeholder="Jenis Layanan Lainnya">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top">
                                            <label>Permasalahan</label>
                                        </td>
                                        <td>
                                            <textarea style="height: 120px;" name="det_layanan[]" class="form-control det-layanan" placeholder="Masukkan detail permasalahan"></textarea>
                                        </td>
                                        <td align="center">
                                            <!-- Tombol hapus disini dihapus -->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- END FORM MULTIINPUT -->
                            <button type="submit" class="btn btn-success">SUBMIT</button>
                            <button type="button" id="addLaporan" class="btn btn-secondary" data-toggle="tooltip" data-placement="right" title="Tambah Laporan"><i class="fa fa-plus" aria-hidden="true"></i></button>
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
    $(function() {
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });
        $("#tgl_awal").on('changeDate', function(selected) {
            var startDate = new Date(selected.date.valueOf());
            $("#tgl_akhir").datepicker('setStartDate', startDate);
            if ($("#tgl_awal").val() > $("#tgl_akhir").val()) {
                $("#tgl_akhir").val($("#tgl_awal").val());
            }
        });
    });

    function showJenisLayanan(selectElement) {
        var selectedOption = selectElement.value;
        var jenisDropdown = selectElement.closest('.form-row').querySelector('.jenis-layanan');
        var lainnyaInput = selectElement.closest('.form-row').querySelector('.lainnya-input');

        // Hapus opsi sebelumnya
        jenisDropdown.innerHTML = "";

        if (selectedOption === 'Throubleshooting') {
            var options = ["Aplikasi", "Jaringan", "PC/Laptop", "Printer", "Lainnya"];
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option;
                opt.innerHTML = option;
                jenisDropdown.appendChild(opt);
            });
            jenisDropdown.style.display = 'block';
            lainnyaInput.style.display = 'none';
        } else if (selectedOption === 'Instalasi') {
            var options = ["Aplikasi", "Sistem Operasi", "Jaringan", "PC/Laptop", "Printer", "Lainnya"];
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option;
                opt.innerHTML = option;
                jenisDropdown.appendChild(opt);
            });
            jenisDropdown.style.display = 'block';
            lainnyaInput.style.display = 'none';
        } else {
            jenisDropdown.style.display = 'none';
            lainnyaInput.style.display = 'none';
        }
    }

    function showLainnya(selectElement) {
        var selectedOption = selectElement.value;
        var lainnyaInput = selectElement.closest('.form-row').querySelector('.lainnya-input');

        if (selectedOption === 'Lainnya') {
            lainnyaInput.style.display = 'block';
        } else {
            lainnyaInput.style.display = 'none';
        }
    }

    document.getElementById('addLaporan').addEventListener('click', function() {
        var laporanInputs = document.getElementById('laporanInputs');
        var clonedInput = laporanInputs.firstElementChild.cloneNode(true);

        // Kosongkan nilai input pada form yang baru ditambahkan
        clonedInput.querySelectorAll('input, textarea').forEach(function(inputElement) {
            inputElement.value = '';
        });

        laporanInputs.appendChild(clonedInput);

        // Mendaftarkan event listener untuk dropdown baru yang ditambahkan
        clonedInput.querySelectorAll('.kat-layanan').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                showJenisLayanan(this);
            });
        });

        clonedInput.querySelectorAll('.jenis-layanan').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                showLainnya(this);
            });
        });

        var deleteButton = document.createElement('button');
        deleteButton.setAttribute('type', 'button');
        deleteButton.setAttribute('class', 'btn btn-danger remove-laporan');
        deleteButton.setAttribute('data-toggle', 'tooltip');
        deleteButton.setAttribute('data-placement', 'right');
        deleteButton.setAttribute('title', 'Hapus Laporan');
        deleteButton.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i>';
        deleteButton.addEventListener('click', function() {
            this.closest('table').remove();
        });

        clonedInput.querySelector('tr:nth-child(2) td:last-child').appendChild(deleteButton);
    });

    document.querySelectorAll('.kat-layanan').forEach(function(selectElement) {
        selectElement.addEventListener('change', function() {
            showJenisLayanan(this);
        });
    });

    document.querySelectorAll('.jenis-layanan').forEach(function(selectElement) {
        selectElement.addEventListener('change', function() {
            showLainnya(this);
        });
    });
</script>

@endsection