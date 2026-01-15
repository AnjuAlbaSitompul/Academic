@extends('layouts.pages.app')
@section('content')
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Kelas</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal" name="formSiswa">
                    <div class=" row mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-9 mb-0">
                                <div class="form-group">
                                    <label class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control" id="namaSiswa" placeholder="Nama Siswa">
                                </div>
                            </div>
                            <div class="form-group col-md-3 mb-0">
                                <div class="form-group">
                                    <label class="form-label">Kelas</label>
                                    <select class="form-control" id="kelasSelect">
                                        <option class="form-control form-select select2" data-bs-placeholder="Pilih Kelas"
                                            value="" disabled selected>Pilih Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0 mt-4 row">
                            <div class="col-md-9">
                                <button class="btn btn-primary" id="simpanSiswa">Simpan</button>
                                <button class="btn btn-secondary" style="display:none" id="updateSiswa">Update</button>

                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Siswa</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="dataTableSiswa">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">No</th>
                                <th class="wd-15p border-bottom-0">Nama Siswa</th>
                                <th class="wd-15p border-bottom-0">Kelas</th>
                                <th class="wd-15p border-bottom-0"><i class="fe fe-settings"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4 pb-5">
                    <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="icon fs-70  lh-1 my-5 d-inline-block" id="modalIcon"></i>
                    <h4 class=" tx-semibold" id="modalText"></h4><button aria-label="Close" class="btn btn-success pd-x-25"
                        id="modalButton">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA TABLE JS-->

    <script>
        $(document).ready(function() {
            // Table Data Kelas
            let id = null;
            let table = $('#dataTableSiswa').DataTable({
                ajax: {
                    url: '/siswa/data' + (id ? '/' + id : ''),
                    type: 'GET',
                    datasrc: function(json) {
                        let data = [];
                        if (json.status === 'success') {
                            data = json.data;
                        }
                        return data;
                    }
                },
                columns: [{
                        data: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        name: 'no'
                    },
                    {
                        data: 'nama_siswa',
                        name: 'Nama Siswa'
                    },
                    {
                        data: function(data) {
                            return data.kelas ? data.kelas.nama_kelas : 'N/A';
                        },
                        name: 'Nama Kelas'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-primary" id="editSiswa" data-id="${data.id}"><i class="fe fe-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="deleteSiswa" data-id="${data.id}"><i class="fe fe-trash"></i></button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function showModal(isSuccess, message, showButton = false) {
                const modalIcon = $('#modalIcon');
                modalIcon.removeClass(isSuccess ? 'text-danger  icon-close' : 'text-success icon-check');
                modalIcon.addClass(isSuccess ? 'text-success icon-check' : 'text-danger icon-close');
                showButton ? $('#modalButton').show() : $('#modalButton').hide();
                $('#modalText').text(message);
                $('#modaldemo4').modal('show');
            }


            // on Delete Siswa
            $('#dataTableSiswa').on('click', '#deleteSiswa', function() {
                let siswaId = $(this).data('id');
                $.ajax({
                    url: '/siswa/' + siswaId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        showModal(true, 'Siswa berhasil dihapus.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat menghapus siswa.');
                    }
                });
            });

            // on Edit Siswa
            $('#dataTableSiswa').on('click', '#editSiswa', function() {
                let siswaId = $(this).data('id');
                let nama = table.row($(this).parents('tr')).data().nama_siswa
                let idKelas = table.row($(this).parents('tr')).data().kelas_id
                $('#namaSiswa').val(nama);
                $('#updateSiswa').data('id', siswaId);
                $('#updateSiswa').fadeIn();
                $('#simpanSiswa').fadeOut();
                $('#kelasSelect').val(idKelas).trigger('change');
            });

            // on Update Siswa
            $('#updateSiswa').on('click', function(event) {
                event.preventDefault();
                let siswaId = $(this).data('id');
                let namaSiswa = $('#namaSiswa').val();
                let kelasId = $('#kelasSelect').val();

                $.ajax({
                    url: '/siswa/' + siswaId,
                    method: 'PUT',
                    data: {
                        nama_siswa: namaSiswa,
                        kelas_id: kelasId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#namaSiswa').val('');
                        $('#kelasSelect').val(null).trigger('change');
                        $('#updateSiswa').fadeOut();
                        $('#simpanSiswa').fadeIn();
                        showModal(true, 'Siswa berhasil diperbarui.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat memperbarui siswa.');
                    }
                });
            });

            // on Save Kelas
            $('#simpanSiswa').on('click', function(event) {
                event.preventDefault();
                let namaSiswa = $('#namaSiswa').val();
                let kelasId = $('#kelasSelect').val();

                $.ajax({
                    url: '/siswa',
                    method: 'POST',
                    data: {
                        nama_siswa: namaSiswa,
                        kelas_id: kelasId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#namaSiswa').val('');
                        $('#kelasSelect').val(null).trigger('change');
                        showModal(true, 'Siswa berhasil disimpan.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat menyimpan siswa.');
                    }
                });
            });
        });
    </script>
@endsection
