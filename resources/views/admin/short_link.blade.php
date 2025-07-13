@extends('template_dashboard')

@section('head')
<title>Short Link</title>
@endsection

@section('container')

<h1>Short Link</h1>
<p>digunakan untuk membuat short link</p>
<hr>
<div class="font-12">double click pada data yang akan ingin diubah</div>

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
                <th scope="col">Judul</th>
                <th scope="col">Url Tujuan</th>
                <th scope="col">Slug</th>
                <th scope="col">Url Short</th>
                <th scope="col">Counter</th>
                <th scope="col">Akun</th>
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

<script>
    var vApiUrl = base_url + '/' + 'api/short-link';

    $(document).ready(function() {

        $(document).on('click', '#tambah', function() {
            $('#data-list').prepend(`
            <tr data-id="">
                <td></td>
                <td><input type="text" class="form-control" name="nama[]"></td>
                <td><input type="text" class="form-control" name="url_src[]"></td>
                <td><input type="text" class="form-control" name="slug[]"></td>
                <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-success simpan-baris">Simpan</button>
                        <button type="button" class="btn btn-warning batal-baris">Batal</button>
                    </div>
                </td>
                <td></td>
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
                url_src: baris.find("input[name='url_src[]']").val(),
                slug: baris.find("input[name='slug[]']").val(),
            };

            $.ajax({
                url: vApiUrl,
                type: 'post',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    toastr.success('operasi berhasil dilakukan!', 'berhasil');
                    baris.attr("data-id", response.data.id);
                    baris.find("td:eq(1)").text(response.data.nama);
                    baris.find("td:eq(2)").text(response.data.url_src);
                    baris.find("td:eq(3)").text(response.data.slug);
                    baris.find("td:eq(4)").html(shortUrl(response.data.slug));
                    baris.find("td:eq(5)").text("0");
                    baris.find("td:eq(6)").text("");
                    baris.find("td:eq(7)").html(`<div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-danger hapusData" data-id="${response.data.id}">Hapus</button>
                                            </div>`);

                    //---------------- sembunyikan inputan -------------------
                    baris.find("input .simpan-baris, .batal-baris").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('operasi gagal dilakukan!');
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
                        case 2:
                            $cell.html('<input type="text" class="form-control" value="' + content + '">');
                            break;
                        case 3:
                            $cell.html('<input type="text" class="form-control" value="' + content + '">');
                            break;
                        case 4:
                            $cell.html('<textarea class="form-control">' + content + '"</textarea>');
                            break;
                        default:
                            break;
                    }
                    $cell.find('input').first().focus();
                }
            });
        });

        function shortUrl(slug) {
            var url = base_url + '/g/' + slug;
            return `<a href="${url}" target="_blank">${url}</a>`;
        }

        $('#data-list').on('focusout', 'input', function() {
            var tr = $(this).closest('tr');
            var id = $(tr).attr('data-id');
            var newValue = $(this).val();
            // console.log('id '+id);
            if (id !== '') {
                $(this).closest('td').html(newValue);
                var nama = $(tr).find('td:nth-child(2)').text();
                var url_src = $(tr).find('td:nth-child(3)').text();
                var slug = $(tr).find('td:nth-child(4)').text();

                // console.log('old : '+oldValue+' ,new : '+newValue);
                if (oldValue !== newValue)
                    $.ajax({
                        url: vApiUrl + '/' + id,
                        type: 'PUT',
                        data: {
                            nama: nama,
                            url_src: url_src,
                            slug: slug,
                        },
                        dataType: 'json',
                        success: function(response) {
                            $(tr).find('td:nth-child(5)').html(shortUrl(slug));
                            toastr.success('operasi berhasil dilakukan!', 'berhasil');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('operasi gagal dilakukan!');
                        }
                    });
            }
        });

        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })

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
                            <td>${dt.nama}</td> 
                            <td>${dt.url_src}</td> 
                            <td>
                                ${dt.slug}
                            </td> 
                            <td>
                                ${shortUrl(dt.slug)}
                            </td> 
                            <td>
                                ${dt.jumlah_akses}
                            </td> 
                            <td>${dt.user.name}</td> 
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-danger hapusData" data-id="${dt.id}">Hapus</button>
                                </div>                                        
                            </td>
                        </tr>`);
                    });

                    renderPagination(response, pagination);
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
                        alert('operasi gagal dilakukan!');
                    }
                });
        });

        $("#btn-cari").click(function(){
            loadData();
        })

    });
</script>
@endsection