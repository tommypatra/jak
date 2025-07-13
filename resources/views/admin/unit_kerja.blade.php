@extends('template_dashboard')

@section('head')
<title>Unit Kerja Website</title>
@endsection

@section('container')

<h1>Unit Kerja</h1>
<p>digunakan untuk mengelola unit kerja</p>

<div class="accordion mb-3" id="frmAcr">
    <div class="accordion-item">
        <h2 class="accordion-header" id="frm-acr-header">
            <button class="accordion-button collapsed" id="tambahForm" type="button" data-bs-toggle="collapse" data-bs-target="#bodyAcr" aria-expanded="false" aria-controls="aria-acr-controls">
                <h3>Formulir Unit Kerja</h3>
            </button>
        </h2>
        <div id="bodyAcr" class="accordion-collapse collapse" aria-labelledby="frm-acr-header" data-bs-parent="#frmAcr">
            <div class="accordion-body">
                <form id="form">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Unit Kerja</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit_kerja_id" class="form-label">Unit Kerja Induk</label>
                        <select class="form-control" id="unit_kerja_id" name="unit_kerja_id" >
                            <option value="">pilih</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="urut" class="form-label">Nomor Urut</label>
                        <input type="number" class="form-control" id="urut" name="urut" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-warning batal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="input-group justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="tambah">Tambah</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="refresh">Refresh</button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">
                Paging
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging">
            </ul>

        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Unit Kerja</th>
                <th scope="col">Induk</th>
                <th scope="col">Nomor Urut</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody id="data-list">
            <!-- Data pesan akan dimuat di sini -->
        </tbody>
    </table>
</div>
<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination">
    </ul>
</nav>

<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Akses Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAkses">
                    <input type="hidden" name="user_id" id="user_id">
                    <div id="checkboxContainer"></div>
                    <hr>
                    <button type="submit" class="btn btn-sm btn-primary" id="simpanAkses">Perbaharui Akses</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/pagination.js') }}"></script>

<script>
    var vApiUrl = base_url + '/' + 'api/unit-kerja';
    var vDataGrup = [];

    $(document).ready(function() {
        loadGrup();
        function loadGrup() {
            $('#unit_kerja_id').empty(); 
            $('#unit_kerja_id').append('<option value="">pilih</option>'); 
            $.ajax({
                url: base_url + '/' + 'api/unit-kerja?limit=100',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.data.length > 0) {
                        $.each(response.data, function(index, dt) {
                            $('#unit_kerja_id').append('<option value="' + dt.id + '">' + dt.nama + '</option>');
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                        forceLogout('Akses ditolak! login kembali');
                    } else {
                        alert('Operasi gagal dilakukan!');
                    }
                }
            });
        }

        loadData();

        function loadData(page = 1, search = '') {
            $.ajax({
                url: vApiUrl + '?page=' + page + '&search=' + search + '&paging=' + vPaging,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    dataList.empty();

                    $.each(response.data, function(index, dt) {
                        var hakakses = '';
                        // console.log(dt.aturgrup);
                        var pimpinan_utama=(dt.is_pimpinan_utama)?"Ya":"Tidak";
                        var unit_kerja=(dt.unit_kerja_parent)?dt.unit_kerja_parent.nama:"";
                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${index+1}</td> 
                            <td>${dt.nama}</td>
                            <td>${unit_kerja}</td> 
                            <td>${dt.urut}</td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary gantiData" data-id="${dt.id}" >Ganti</button>
                                    <button type="button" class="btn btn-danger hapusData" data-id="${dt.id}" >Hapus</button>
                                </div>                                        
                            </td>
                        </tr>`);
                    });

                    renderPagination(response, pagination);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                        forceLogout('Akses ditolak! login kembali');
                    } else {
                        alert('operasi gagal dilakukan!');
                    }
                }
            });
        }

        $('#bodyAcr').on('shown.bs.collapse', function() {
            $('#nama').focus();
        });

        function resetForm() {
            $('#form input').val('');
            $('#form')[0].reset();
        }

        $("#tambah").on("click", function() {
            $('html, body').scrollTop($('#tambahForm').offset().top);
            $('#bodyAcr').collapse('show'); // Menampilkan accordion
            resetForm();
            $('#nama').focus();
        });

        $(document).on('click', '.batal', function(event) {
            event.preventDefault();
            resetForm();
            $('#bodyAcr').collapse('hide');
        });

        // function batal(event) {
        //     event.preventDefault();
        //     resetForm();
        //     $('#name').focus();
        //     $('#bodyAcr').collapse('hide');
        // }

        // Handle page change
        $(document).on('click', '.page-link', function() {
            var page = $(this).data('page');
            var search = $('#search-input').val();
            loadData(page, search);
        });

        $('#refresh').on('click', function(e) {
            loadData();
        });

        // Handle search form submission
        $('.cari-data').click(function() {
            var search = $("#search-input").val();
            if (search.length > 3) {
                loadData(1, search);
            } else if (search.length === 0) {
                loadData(1, '');
            }
        })

        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })

        $("#form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: function(element) {
                        return $('#id').val().trim() === '';
                    },
                    minlength: 8
                }
            },
            submitHandler: function(form) {
                var selectedPage = $('.page-item.active .page-link').data('page');
                let vType = ($('#id').val() === '') ? 'POST' : 'PUT';
                let vUrl = vApiUrl;
                if (vType === 'PUT')
                    vUrl = vApiUrl + '/' + $('#id').val();

                $.ajax({
                    url: vUrl,
                    type: vType,
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        toastr.success('operasi berhasil dilakukan!', 'berhasil');
                        loadData(selectedPage); // Reload pesan list after submission
                        resetForm();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                            forceLogout('Akses ditolak! login kembali');
                        } else {
                            alert('operasi gagal dilakukan!');
                        }
                    }
                });
            }
        });

        //ganti
        $(document).on('click', '.gantiData', function() {
            var id = $(this).data('id');
            var selectedPage = $('.page-item.active .page-link').data('page');
            $.ajax({
                url: vApiUrl + '/' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('html, body').scrollTop($('#tambahForm').offset().top);
                    $('#bodyAcr').collapse('show'); // Menampilkan accordion
                    $('#id').val(response.id);
                    $('#nama').val(response.nama);
                    $('#urut').val(response.urut);
                    $('#unit_kerja_id').val(response.unit_kerja_id);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                        forceLogout('Akses ditolak! login kembali');
                    } else {
                        alert('operasi gagal dilakukan!');
                    }
                }
            });
        });

        //hapus
        $(document).on('click', '.hapusData', function() {
            var id = $(this).data('id');
            var selectedPage = $('.page-item.active .page-link').data('page');
            if (confirm('apakah anda yakin?'))
                $.ajax({
                    url: vApiUrl + '/' + id,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        loadData(selectedPage);
                        toastr.success('operasi berhasil dilakukan!', 'berhasil');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                            forceLogout('Akses ditolak! login kembali');
                        } else {
                            alert('operasi gagal dilakukan!');
                        }
                    }
                });
        });

        // function hapusAkses(id){
        $(document).on('click', '.hapusAkses', function() {
            var id = $(this).data('id');
            var selectedPage = $('.page-item.active .page-link').data('page');
            if (confirm('apakah anda yakin?'))
                $.ajax({
                    url: base_url + '/' + 'api/atur-grup/' + id,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        loadData(selectedPage);
                        toastr.success('operasi berhasil dilakukan!', 'berhasil');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                            forceLogout('Akses ditolak! login kembali');
                        } else {
                            alert('operasi gagal dilakukan!');
                        }
                    }
                });
        });

        $('#formAkses').submit(function(event) {
            event.preventDefault();
            var selectedPage = $('.page-item.active .page-link').data('page');
            $.ajax({
                url: base_url + '/' + 'api/atur-grup',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    loadData(selectedPage);
                    toastr.success('set akses akun berhasil dilakukan!', 'berhasil');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                        forceLogout('Akses ditolak! login kembali');
                    } else {
                        alert('operasi gagal dilakukan!');
                    }
                }
            });
        });

    });
</script>
@endsection