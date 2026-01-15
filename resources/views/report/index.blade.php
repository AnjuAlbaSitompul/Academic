@extends('layouts.pages.app')
@section('content')
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Data Laporan Siswa</h4>
                <button class="btn btn-sm btn-primary" id="generateReport"
                    onclick="window.location.href='{{ route('siswa.export') }}'">
                    Download Excel
                </button>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="dataTableSiswa">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">No</th>
                                <th class="wd-15p border-bottom-0">Nama Siswa</th>
                                <th class="wd-15p border-bottom-0">Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Data Laporan Nilai Siswa Lulusan</h4>
                <button class="btn btn-sm btn-primary" onclick="window.location.href='{{ route('nilai.export') }}'">
                    Download Excel
                </button>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="dataLaporanNilai">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>UAS</th>
                                <th>UTS</th>
                                <th>UN</th>
                                <th>Kehadiran</th>
                                <th>Keterlambatan</th>
                                <th>Prestasi</th>
                                <th class="wd-15p border-bottom-0"><i class="fe fe-settings"></i></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let table = $('#dataTableSiswa').DataTable({
                ajax: '/siswa/data',
                datasrc: function(json) {
                    let data = [];
                    if (json.status === 'success') {
                        data = json.data;
                    }
                    return data;
                },
                columns: [{
                        data: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        name: 'no'
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa'
                    },
                    {
                        data: 'kelas.nama_kelas',
                        name: 'kelas.nama_kelas'
                    },
                ],
            });

            let tableNilai = $('#dataLaporanNilai').DataTable({
                ajax: {
                    url: '/nilai-siswa',
                    dataSrc: 'data'
                },
                columns: [{
                        data: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        name: 'no'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'nilai_uas'
                    },
                    {
                        data: 'nilai_uts'
                    },
                    {
                        data: 'nilai_un'
                    },
                    {
                        data: 'kehadiran'
                    },
                    {
                        data: 'keterlambatan'
                    },
                    {
                        data: 'prestasi'
                    },
                ],
            })
            tableNilai.DataTable({
                buttons: ['Download PDF'],
            })


        });
    </script>
@endsection
