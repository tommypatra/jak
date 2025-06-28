@extends('template_dashboard')

@section('head')
<title>Verifikasi Konten</title>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

@section('container')

<h1>Verifikasi Konten</h1>
<p>digunakan untuk verifikasi artikel pada website</p>


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
                <th scope="col">Artikel Web/ Penulis</th>
                <th scope="col">Kategori/ Status Publikasi</th>
                <th scope="col">Statistik</th>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Konten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formKonten">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="slug" id="slug">
                        <input type="hidden" name="jenis_konten_id" id="jenis_konten_id">
                        <input type="hidden" name="waktu" id="waktu">
                        <div class="col-sm-12 mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control w-100" id="judul" name="judul" required>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <label for="editor" class="form-label">Isi Artikel</label>
                            <div id="editor-container">
                                <textarea id="editor" name="isi" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-9">
                                <label for="thumbnail" class="form-label">Thumbnail Artikel</label>
                            </div>
                            <div class="col-sm-3">
                                <div id="preview-img"></div>
                            </div>
                        </div>
                    </div>
                </form>

                <form id="formVerifikasi">
                    <div class="row">
                        <input type="hidden" name="id" id="formVerifikasi_id">
                        <input type="hidden" name="konten_id" id="konten_id">
                        <div class="col-sm-12 mb-3">
                            <hr>
                            <h3>Verifikasi</h3>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="is_publikasi" class="form-label">Status Publikasi</label>
                            <select class="form-control w-100" id="is_publikasi" name="is_publikasi" required>
                                <option value="1">Terima</option>
                                <option value="0">Tolak</option>
                            </select>
                        </div>
                        <div class="col-sm-8 mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control w-100" rows="4" id="catatan" name="catatan"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-submit" id="btnSimpan">Simpan</button>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')

<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    var vPublikasi = 2;
    var vApiUrl = 'api/konten';
    var vDataGrup = [];

    var myModalForm = new bootstrap.Modal(document.getElementById('modalForm'), {
        keyboard: false
    });


    $(document).ready(function() {

        loadData();

        function resetForm() {
            $('#formKonten input').val('');
            $('#formVerifikasi input').val('');
            $('#editor').summernote('code', '');
            $('#formKonten')[0].reset();
            $('#formVerifikasi')[0].reset();
            $('#preview-img').html('');
        }

        $('.item-tab').click(function() {
            $('.item-tab').removeClass('active');
            $(this).addClass('active');
            vPublikasi = $(this).data('publikasi');
            loadData();
        });

        $('#editor').summernote({
            height: 500,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    sendFile(files[0], $(this));
                }
            }
        });

        function sendFile(file, editor) {
            var data = new FormData();
            data.append("file", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "/api/upload-image-editor",
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    editor.summernote('insertImage', response.image);
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


        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })


        function loadData(page = 1) {
            let search=$("#cari-data").val();

            $.ajax({
                url: vApiUrl + '?page=' + page + '&search=' + search + '&paging=' + vPaging + '&publikasi=' + vPublikasi,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    dataList.empty();

                    $.each(response.data, function(index, dt) {
                        var hakakses = '';
                        var thumbnail = (dt.thumbnail) ? `<img src="${dt.thumbnail}" width="125px" style="float:left;margin:5px;">` : "";

                        // console.log(dt.aturgrup);
                        var publikasi = '<span class="badge text-bg-warning">Belum diperiksa</span>';
                        if (dt.publikasi) {
                            // $waktu=myFormatDate(date)
                            publikasi = (dt.publikasi.is_publikasi) ? `<span class="badge text-bg-success">Terpublikasi</span>` : `<span class="badge text-bg-danger">Ditolak</span>`;
                            publikasi += `<div class="font-12">${myLabel(dt.publikasi.catatan)}</div><div class="font-12"><i class="bi bi-calendar-event"></i> ${dt.publikasi.created_at}</div>`;
                            publikasi += `<div><i>${dt.publikasi.user_name}</i></div>`;
                        }

                        dataList.append(`
                        <tr data-id="${dt.id}"> 
                            <td>${++index}</td> 
                            <td>
                                <h5>${dt.judul}</h5>
                                <div class='font-12'><i class="bi bi-calendar-event"></i> ${dt.waktu}</div>
                                <div class='font-12'>${dt.slug}</div>
                                <div class='font-12'>${dt.user_name}</div>
                                ${thumbnail} ${dt.pembuka}
                            </td> 
                            <td>
                                <div>${dt.jenis_konten_nama}</div>
                                ${publikasi}
                            </td> 
                            <td>
                                <span class="badge text-bg-info">
                                    <i class="bi bi-view-list"></i> ${dt.jumlah_akses}  
                                    <i class="bi bi-hand-thumbs-up"></i> ${dt.likedislike_count}  
                                    <i class="bi bi-chat-right-text"></i> ${dt.komentar_count}
                                </span>
                            </td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary btnVerifikasi" data-id="${dt.id}" >Verifikasi</button>
                                    <button type="button" class="btn btn-danger btnHapus" data-id="${dt.id}" >Hapus</button>
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
            if ($("#formKonten").valid() && $("#formVerifikasi").valid()) { // Validasi form konten berita
                if (confirm('apakah anda yakin?')) {
                    $.ajax({
                        url: vApiUrl + '/' + $('#id').val(),
                        type: 'PUT',
                        data: $("#formKonten").serialize(),
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

        $(document).on('click', '.btnHapus', function() {
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


        function verifikasiKonten(selectedPage) {
            let isUpdate = $('#formVerifikasi_id').val() !== '';
            let vUrl = 'api/publikasi';
            let formData = $("#formVerifikasi").serialize();

            if (isUpdate) {
                vUrl += '/' + $('#formVerifikasi_id').val();
                formData += '&_method=PUT';
            }

            $.ajax({
                url: vUrl,
                type: 'POST', // <--- tetap pakai POST
                data: formData,
                dataType: 'json',
                success: function(response) {
                    myModalForm.hide();
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
            var id = $(this).data('id');
            var selectedPage = $('.page-item.active .page-link').data('page');
            resetForm();
            $.ajax({
                url: vApiUrl + '/' + id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#id').val(response.data.id);
                    $('#konten_id').val(response.data.id);
                    $('#judul').val(response.data.judul);
                    $('#slug').val(response.data.slug);
                    $('#editor').summernote('code', response.data.isi);
                    $('#waktu').val(response.data.waktu);
                    $('#jenis_konten_id').val(response.data.jenis_konten_id);

                    if (response.data.publikasi) {
                        $('#formVerifikasi_id').val(response.data.publikasi.id);
                        $('#catatan').val(response.data.publikasi.catatan);
                        $('#is_publikasi').val(response.data.publikasi.is_publikasi);
                    }

                    if (response.data.thumbnail)
                        $('#preview-img').html('<img src="' + response.data.thumbnail + '" width="100%" alt="Preview Image">');
                    myModalForm.show();
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