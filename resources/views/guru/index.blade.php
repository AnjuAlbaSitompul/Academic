@extends('layouts.pages.app')
@section('content')
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Kelas</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal" name="formGuru">
                    <div class=" row mb-4">
                        <div class="form-row">
                            <div class="form-group col-md-9 mb-0">
                                <div class="form-group">
                                    <label class="form-label">Nama Guru</label>
                                    <input type="text" class="form-control" id="namaGuru" placeholder="Nama Guru">
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
                                <button class="btn btn-primary" id="simpanGuru">Simpan</button>
                                <button class="btn btn-secondary" style="display:none" id="updateGuru">Update</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Guru</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="dataTableGuru">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">No</th>
                                <th class="wd-15p border-bottom-0">Nama Guru</th>
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
            let table = $('#dataTableGuru').DataTable({
                processing: true,
                ajax: {
                    url: '/guru/data' + (id ? '/' + id : ''),
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
                        data: 'nama_guru',
                        name: 'Nama Guru'
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
                            return `<button class="btn btn-sm btn-primary" id="editGuru" data-id="${data.id}"><i class="fe fe-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="deleteGuru" data-id="${data.id}"><i class="fe fe-trash"></i></button>`;
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

            // on Edit Guru
            $('#dataTableGuru').on('click', '#editGuru', function() {
                let GuruId = $(this).data('id');
                let nama = table.row($(this).parents('tr')).data().nama_guru
                let idKelas = table.row($(this).parents('tr')).data().kelas_id
                $('#namaGuru').val(nama);
                $('#updateGuru').data('id', GuruId);
                $('#updateGuru').fadeIn();
                $('#simpanGuru').fadeOut();
                $('#kelasSelect').val(idKelas).trigger('change');
            });

            // on Update Guru
            $('#updateGuru').on('click', function(event) {
                event.preventDefault();
                let guruId = $(this).data('id');
                let namaGuru = $('#namaGuru').val();
                let kelasId = $('#kelasSelect').val();

                $.ajax({
                    url: '/guru/' + guruId,
                    method: 'PUT',
                    data: {
                        nama_guru: namaGuru,
                        kelas_id: kelasId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#namaGuru').val('');
                        $('#kelasSelect').val(null).trigger('change');
                        $('#updateGuru').fadeOut();
                        $('#simpanGuru').fadeIn();
                        showModal(true, 'Guru berhasil diperbarui.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat memperbarui Guru.');
                    }
                });
            });

            // on Delete Guru
            $('#dataTableGuru').on('click', '#deleteGuru', function() {
                let GuruId = $(this).data('id');
                $.ajax({
                    url: '/guru/' + GuruId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        showModal(true, 'Guru berhasil dihapus.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat menghapus Guru.');
                    }
                });
            });

            // on Save Kelas
            $('#simpanGuru').on('click', function(event) {
                event.preventDefault();
                let namaGuru = $('#namaGuru').val();
                let kelasId = $('#kelasSelect').val();

                $.ajax({
                    url: '/guru',
                    method: 'POST',
                    data: {
                        nama_guru: namaGuru,
                        kelas_id: kelasId
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#namaGuru').val('');
                        $('#kelasSelect').val(null).trigger('change');
                        showModal(true, 'Guru berhasil disimpan.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat menyimpan guru.');
                    }
                });
            });
        });
    </script>
@endsection
