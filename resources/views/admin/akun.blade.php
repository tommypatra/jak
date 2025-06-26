@extends('template_dashboard')

@section('head')
<title>Akun Website</title>
@endsection

@section('container')

<h1>Akun</h1>
<p>digunakan untuk mengelola akun yang bisa login untuk mengelola website ini </p>

<div class="accordion mb-3" id="frmAcr">
    <div class="accordion-item">
        <h2 class="accordion-header" id="frm-acr-header">
            <button class="accordion-button collapsed" id="tambahForm" type="button" data-bs-toggle="collapse" data-bs-target="#bodyAcr" aria-expanded="false" aria-controls="aria-acr-controls">
                <h3>Formulir Akun</h3>
            </button>
        </h2>
        <div id="bodyAcr" class="accordion-collapse collapse" aria-labelledby="frm-acr-header" data-bs-parent="#frmAcr">
            <div class="accordion-body">
                <form id="form">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-warning" onclick="batal(event)">Batal</button>
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
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">Akses Grup</th>
                <th scope="col">Waktu</th>
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
<script src="{{ asset('js/token.js') }}"></script>

<script>
    var vApiUrl = base_url + '/' + 'api/akun';
    var vDataGrup = [];

    $(document).ready(function() {

        loadGrup();
        loadData();

        function loadGrup() {
            $.ajax({
                url: base_url + '/' + 'api/grup?showall=true',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    vDataGrup = response;
                    // console.log(response);
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
                        if (dt.aturgrup.length > 0) {
                            hakakses = '<ul>';
                            $.each(dt.aturgrup, function(index, dp) {
                                hakakses = hakakses + `<li>${dp.grup.nama} <button type="button" class="btn btn-danger btn-vsm hapusAkses" data-id="${dp.id}" >X</button></li>`;
                            });
                            hakakses = hakakses + '</ul>';
                        }
                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${dt.nomor}</td> 
                            <td>${dt.name}</td> 
                            <td>${dt.email}</td> 
                            <td>${hakakses}</td> 
                            <td>${dt.updated_at_format}</td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary gantiData" data-id="${dt.id}" >Ganti</button>
                                    <button type="button" class="btn btn-warning setAkses" data-id="${dt.id}" >Akses</button>
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
            $('#name').focus();
        });

        function resetForm() {
            $('#form input').val('');
            $('#form')[0].reset();
        }

        $("#tambah").on("click", function() {
            $('html, body').scrollTop($('#tambahForm').offset().top);
            $('#bodyAcr').collapse('show'); // Menampilkan accordion
            resetForm();
            $('#name').focus();
        });

        function batal(event) {
            event.preventDefault();
            resetForm();
            $('#name').focus();
            $('#bodyAcr').collapse('hide');
        }

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
                    $('#name').val(response.name);
                    $('#email').val(response.email);
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

        // function setAkses(id){
        $(document).on('click', '.setAkses', function() {
            var id = $(this).data('id');
            $('#checkboxContainer').html('');
            $('#formAkses #user_id').val(id);
            $.ajax({
                url: vApiUrl + '/' + id,
                method: 'get',
                dataType: 'json',
                success: function(response) {
                    var aksesGrup = response.aturgrup;
                    $.each(vDataGrup, function(index, dt) {
                        var idFound = false;
                        $.each(aksesGrup, function(index, grup) {
                            if (grup.grup_id === dt.id) {
                                idFound = true;
                                return;
                            }
                        });

                        if (!idFound) {
                            var checkbox = $('<input type="checkbox">').attr({
                                id: 'grup_id' + dt.id,
                                value: dt.id,
                                name: 'grup_id[]'
                            });
                            var label = $('<label>').attr('for', 'grup_id' + dt.id).text(dt.nama);
                            label.css('margin-left', '5px');
                            $('#checkboxContainer').append(checkbox, label, '<br>');
                            showModal = true;
                        }
                    });

                    var fModalForm = new bootstrap.Modal(document.getElementById('modalForm'), {
                        keyboard: false
                    });
                    fModalForm.show();
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