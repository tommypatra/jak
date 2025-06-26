@extends('template_dashboard')

@section('head')
<title>Slide Show Web</title>
@endsection

@section('container')

<h1>Slide Show</h1>
<p>digunakan untuk mengelola slide yang akan tayang pada website ini </p>

<div class="accordion mb-3" id="frmAcr">
    <div class="accordion-item">
        <h2 class="accordion-header" id="frm-acr-header">
            <button class="accordion-button collapsed" id="tambahForm" type="button" data-bs-toggle="collapse" data-bs-target="#bodyAcr" aria-expanded="false" aria-controls="aria-acr-controls">
                <h3>Form Slide</h3>
            </button>
        </h2>
        <div id="bodyAcr" class="accordion-collapse collapse" aria-labelledby="frm-acr-header" data-bs-parent="#frmAcr">
            <div class="accordion-body">
                <form id="form">
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-md-7 mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control w-100" id="judul" name="judul" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="path" class="form-label">File Gambar</label>
                            <input type="file" class="form-control w-100" id="path" name="file" accept="image/*" required>
                            <div class="font-12">ukuran gambar lebarxtinggi 970x450 (pixel)</div>
        
                            <div class="col-sm-3">
                                <div id="preview-img"></div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea rows="4" class="form-control w-100" id="deskripsi" name="deskripsi"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="is_publikasi" class="form-label">Aktif</label>
                            <select class="form-control w-100" id="is_publikasi" name="is_publikasi" required>
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-warning batal">Batal</button>
                        </div>
                    </div>
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
                <th scope="col">Judul/ Deskripsi</th>
                <th scope="col">Gambar</th>
                <th scope="col">Aktif</th>
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
    var vApiUrl = base_url + '/' + 'api/slide-show';
    var vDataGrup = [];
    $(document).ready(function() {

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
                        var pathImg = base_url + dt.path;
                        var publikasi = (dt.is_publikasi) ? `<span class="badge text-bg-success">Aktif</span>` : `<span class="badge text-bg-danger">Tidak Aktif</span>`;

                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${++index}</td> 
                            <td>
                                <i class="bi bi-person"></i> ${dt.user_name}
                                <div class="font-12">${dt.updated_at}</div>
                                <div>${dt.judul}</div>
                                <div>${myLabel(dt.deskripsi)}</div>
                            </td> 
                            <td><img src="${pathImg}" width="350px"></td> 
                            <td>${publikasi}</td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-primary gantiData" data-id="${dt.id}">Ganti</button>
                                    <button type="button" class="btn btn-danger hapusData" data-id="${dt.id}">Hapus</button>
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
            $('#preview-img').html('');
        }

        $('#path').on('change', function(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var dataURL = reader.result;
                $('#preview-img').html('<img src="' + dataURL + '" width="200%" alt="Preview Image">');
            };
            reader.readAsDataURL(input.files[0]);
        });


        $(document).on('click', '#tambah', function() {
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
            rules: {
                file: {
                    required: function(element) {
                        return $('#id').val().trim() === '';
                    }
                }
            },
            submitHandler: function(form) {
                var selectedPage = $('.page-item.active .page-link').data('page');
                var formData = new FormData($(form)[0]);
                var vUrl = vApiUrl;

                if ($('#id').val() !== '') {
                    vUrl = vApiUrl + '/' + $('#id').val();
                    formData.append("_method", "PUT");
                }

                $.ajax({
                    url: vUrl,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
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
                    $('#id').val(response.data.id);
                    $('#judul').val(response.data.judul);
                    $('#deskripsi').val(response.data.deskripsi);
                    $('#is_publikasi').val(response.data.is_publikasi);

                    if (response.data.path)
                        $('#preview-img').html('<img src="' + response.data.path + '" width="200%" alt="Preview Image">');

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
    });
</script>
@endsection