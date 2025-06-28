@extends('template_dashboard')

@section('head')
<title>HTML Code</title>
@endsection

@section('container')

<h1>HTML Code</h1>
<p>digunakan untuk mengelola html code yang akan digunakan pada website ini </p>

<div class="accordion mb-3" id="frmAcr">
    <div class="accordion-item">
        <h2 class="accordion-header" id="frm-acr-header">
            <button class="accordion-button collapsed" id="tambahForm" type="button" data-bs-toggle="collapse" data-bs-target="#bodyAcr" aria-expanded="false" aria-controls="aria-acr-controls">
                <h3>Form HTML Code</h3>
            </button>
        </h2>
        <div id="bodyAcr" class="accordion-collapse collapse" aria-labelledby="frm-acr-header" data-bs-parent="#frmAcr">
            <div class="accordion-body">
                <form id="form" class="row">
                    <input type="hidden" name="id" id="id">
                    <div class="col-md-4 mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control w-100" id="judul" name="judul" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control w-100" id="slug" name="slug" required>
                    </div>
                    <div class="col-md-10 mb-3">
                        <label for="code" class="form-label">HTML Code</label>
                        <textarea rows="4" class="form-control w-100" id="code" name="code" required></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-warning batal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

            <!-- Kiri: Search dan Filter -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <input type="text" class="form-control form-control-sm" id="cari-data" placeholder="Pencarian..." style="width: 200px;">            
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-cari">Cari</button>
            </div>

            <!-- Kanan: Tombol-tombol -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary btnTambah" id="tambah">Tambah</button>
                <button type="button" class="btn btn-sm btn-outline-secondary btnRefresh" id="refresh">Refresh</button>
                
                <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">
                        Paging
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging">
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Akun/ Judul</th>
                <th scope="col">Slug</th>
                <th scope="col">Code HTML/ Kode Elemen Pada Web</th>
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

@endsection

@section('script')
<script src="{{ asset('js/myapp.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>

<script>
    var vApiUrl = base_url + '/' + 'api/html-code';
    var vDataGrup = [];

    $(document).ready(function() {
        loadData();

        function loadData(page = 1) {
            let search=$("#cari-data").val();

            $.ajax({
                url: vApiUrl + '?page=' + page + '&search=' + search + '&paging=' + vPaging,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    dataList.empty();

                    $.each(response.data, function(index, dt) {
                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${++index}</td> 
                            <td>
                                <i class="bi bi-person"></i> ${dt.user.name}
                                ${dt.judul}
                            </td> 
                            <td>${dt.slug}</td> 
                            <td><div><code>&lt;div class="custom-code-html" data-slug="${dt.slug}"&gt;&lt;/div&gt;</code></div>
                                ${dt.code}
                                
                            </td> 
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
            $('#judul').focus();
        });

        function resetForm() {
            $('#form input').val('');
            $('#form')[0].reset();
        }

        $(document).on('click', '#tambah', function(event) {
            event.preventDefault();
            $('html, body').scrollTop($('#tambahForm').offset().top);
            $('#bodyAcr').collapse('show'); // Menampilkan accordion
            resetForm();
            $('#judul').focus();
        });

        $(document).on('click', '.batal', function(event) {
            event.preventDefault();
            resetForm();
            $('#bodyAcr').collapse('hide');
        });

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
                    $('#judul').val(response.judul);
                    $('#slug').val(response.slug);
                    $('#code').val(response.code);
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

        $("#btn-cari").click(function(){
            loadData();
        })

    });
</script>
@endsection