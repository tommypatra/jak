@extends('template_dashboard')

@section('head')
<title>Verifikasi Komentar</title>
@endsection

@section('container')

<h1>Verifikasi Komentar</h1>
<p>digunakan untuk verifikasi komentar pada website</p>


<div class="row">
    <div class="col-sm-12">
        <div class="input-group justify-content-end">
            <button type="button" class="btn btn-sm btn-outline-secondary btnRefresh" id="refresh">Refresh</button>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">
                Paging
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging">
            </ul>

        </div>
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link item-tab active" aria-current="page" href="javascript:;" data-publikasi="2">Diajukan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link item-tab" href="javascript:;" data-publikasi="1">Diterima</a>
    </li>
    <li class="nav-item">
        <a class="nav-link item-tab" href="javascript:;" data-publikasi="0">Ditolak</a>
    </li>
</ul>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama/ Komentar</th>
                <th scope="col">Konten/ File</th>
                <th scope="col">Kategori/ Status Publikasi</th>
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

<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>

<script>
    var vPublikasi = 2;
    var vApiUrl = 'api/komentar';
    var vDataGrup = [];


    $(document).ready(function() {

        loadData();

        function resetForm() {
            $('#formFile input').val('');
            $('#formVerifikasi input').val('');
            $('#formFile')[0].reset();
            $('#formVerifikasi')[0].reset();
        }

        $('.item-tab').click(function() {
            $('.item-tab').removeClass('active');
            $(this).addClass('active');
            vPublikasi = $(this).data('publikasi');
            loadData();
        });

        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })


        function loadData(page = 1, search = '') {
            $.ajax({
                url: vApiUrl + '?page=' + page + '&search=' + search + '&publikasi=' + vPublikasi,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    dataList.empty();

                    $.each(response.data, function(index, dt) {
                        var hakakses = '';
                        var konten = '';
                        var publikasi = '<span class="badge text-bg-warning">Belum diperiksa</span>';
                        if (dt.konten_id) {
                            konten = `<div class='font-12'>Artikel</div>${dt.konten.judul}`;
                        } else if (dt.file_id) {
                            konten = `<div class='font-12'>File</div>${dt.file.judul}`;
                        }


                        if (dt.is_publikasi !== null) {
                            publikasi = (dt.is_publikasi) ? `<span class="badge text-bg-success">Terpublikasi</span>` : `<span class="badge text-bg-danger">Ditolak</span>`;
                            if (dt.user)
                                publikasi += `<div><i>${dt.user.name}</i></div>`;
                        }

                        dataList.append(`
                        <tr data-id="${dt.id}"> 
                            <td>${++index}</td> 
                            <td>${dt.nama}/ ${dt.komentar}</td> 
                            <td>${konten}</td> 
                            <td>${publikasi}</td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-success btnVerifikasi" data-is_publikasi="1" data-id="${dt.id}" >Terima</button>
                                    <button type="button" class="btn btn-danger btnVerifikasi" data-is_publikasi="0" data-id="${dt.id}" >Tolak</button>
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
        });

        $('#btnSimpan').on('click', function(event) {
            var selectedPage = $('.page-item.active .page-link').data('page');
            simpanKonten(selectedPage);
        });

        function simpanKonten(selectedPage) {
            if ($("#formFile").valid() && $("#formVerifikasi").valid()) { // Validasi form konten berita
                if (confirm('apakah anda yakin?')) {
                    $.ajax({
                        url: vApiUrl + '/' + $('#id').val(),
                        type: 'PUT',
                        data: $("#formFile").serialize(),
                        dataType: 'json',
                        success: function(response) {
                            toastr.success('operasi berhasil dilakukan!', 'berhasil');
                            verifikasiKonten(selectedPage);
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
            }
        }

        function verifikasiKonten(selectedPage) {
            let vType = ($('#formVerifikasi_id').val() === '') ? 'POST' : 'PUT';
            let vUrl = 'api/publikasi';
            if (vType === 'PUT')
                vUrl = vUrl + '/' + $('#formVerifikasi_id').val();

            $.ajax({
                url: vUrl,
                type: vType,
                data: $("#formVerifikasi").serialize(),
                dataType: 'json',
                success: function(response) {

                    loadData(selectedPage);
                    toastr.success('publikasi berhasil dilakukan!', 'berhasil');
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

        $(document).on('click', '.btnVerifikasi', function() {
            var id = $(this).attr('data-id');
            var is_publikasi = $(this).attr('data-is_publikasi');
            var selectedPage = $('.page-item.active .page-link').data('page');
            if (confirm('apakah anda yakin?'))
                $.ajax({
                    url: vApiUrl + '/' + id,
                    method: 'PUT',
                    data: {
                        is_publikasi: is_publikasi
                    },
                    dataType: 'json',
                    success: function(response) {
                        loadData(selectedPage);
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