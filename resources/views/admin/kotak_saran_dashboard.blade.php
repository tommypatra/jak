@extends('template_dashboard')

@section('head')
<title>Kotak Saran</title>
@endsection

@section('container')

<h1>Saran Penungjung</h1>
<p>digunakan untuk mengecek kotak saran website ini </p>

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

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Kotak Suara</th>
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
    var vApiUrl = base_url + '/' + 'api/kotak-saran';
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
                        const tanggal = new Date(dt.created_at).toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${++index}</td> 
                            <td><div class="card">
                                    <div class="card-header">
                                        ${tanggal}
                                    </div>
                                    <div class="card-body">
                                        <blockquote class="blockquote mb-0">
                                        <p>${dt.komentar}</p>
                                        <footer class="blockquote-footer">${dt.nama}</footer>
                                        </blockquote>
                                    </div>
                                </div>
                            </td> 
                            <td>
                                <button type="button" class="btn btn-danger hapusData" data-id="${dt.id}" >Hapus</button>
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

        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
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

        $("#btn-cari").click(function(){
            loadData();
        })

    });
</script>
@endsection