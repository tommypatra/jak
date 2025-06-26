@extends('template_dashboard')

@section('head')
<title>Grup Pengguna</title>
@endsection

@section('container')

<h1>Grup Pengguna</h1>
<p>digunakan untuk mengatur jenis grup akun yang dapat login pada website ini </p>
<hr>
<div class="font-12">double click pada data yang akan ingin diubah</div>

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
                <th scope="col">Nama Grup</th>
                <th scope="col">User</th>
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

@endsection

@section('script')
<script src="{{ asset('js/pagination.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>

<script>
    var vApiUrl = base_url + '/' + 'api/grup';

    $(document).ready(function() {

        $(document).on('click', '#tambah', function() {
            $('#data-list').prepend(`
            <tr data-id="">
                <td></td>
                <td><input type="text" class="form-control" name="nama[]"></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success simpan-baris">Simpan</button>
                        <button type="button" class="btn btn-warning batal-baris">Batal</button>
                    </div>
                </td>
                <td></td>
                <td></td>
            </tr>
        `);
            resetNomorUrut();
        });

        function resetNomorUrut() {
            var nomor = 1;
            $('#data-list tr').each(function(index) {
                $(this).find('td:first').text(nomor);
                nomor++;
            });
        }

        // function batalBaris(button) {
        $(document).on('click', '.batal-baris', function() {
            var id = $(this).data('id');

            $(this).closest('tr').remove();
            resetNomorUrut();
        });

        // function simpanBaris(button) {
        $(document).on('click', '.simpan-baris', function() {
            // var button = $(this);
            var baris = $(this).closest('tr');
            var postData = {
                nama: baris.find("input[name='nama[]']").val(),
            };

            $.ajax({
                url: vApiUrl,
                type: 'post',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    toastr.success('operasi berhasil dilakukan!', 'berhasil');
                    baris.attr("data-id", response.id);
                    baris.find("td:eq(1)").text(response.nama);
                    baris.find("td:eq(2)").text(response.user.name);
                    baris.find("td:eq(3)").text(response.updated_at_format);
                    baris.find("td:eq(4)").html(`<div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-danger hapusData" data-id="${response.id}">Hapus</button>
                                            </div>`);

                    //---------------- sembunyikan inputan -------------------
                    baris.find("input[name='nama[]'] .simpan-baris, .batal-baris").hide();
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

        var oldValue = "";
        $('#data-list').on('dblclick', 'td', function() {
            var $td = $(this);
            var $tr = $td.closest('tr');
            var index = $td.index();
            var content = $td.text().trim();
            oldValue = content;

            $tr.find('td').each(function(i, cell) {
                var $cell = $(cell);
                if (i === index) {
                    switch (index) {
                        case 1:
                            $cell.html('<input type="text" class="form-control" value="' + content + '">');
                            break;
                        default:
                            break;
                    }
                    $cell.find('input').first().focus();
                }
            });
        });

        $('#data-list').on('focusout', 'input', function() {
            var tr = $(this).closest('tr');
            var id = $(tr).attr('data-id');
            var newValue = $(this).val();
            // console.log('id '+id);
            if (id !== '') {
                $(this).closest('td').html(newValue);
                var nama = $(tr).find('td:nth-child(2)').text();

                // console.log('old : '+oldValue+' ,new : '+newValue);
                if (oldValue !== newValue)
                    $.ajax({
                        url: vApiUrl + '/' + id,
                        type: 'PUT',
                        data: {
                            nama: nama,
                        },
                        dataType: 'json',
                        success: function(response) {
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
            }
        });

        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })

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

                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${dt.nomor}</td> 
                            <td>${dt.nama}</td> 
                            <td>${dt.user.name}</td> 
                            <td>${dt.updated_at_format}</td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
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

        // function hapusData(id){
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