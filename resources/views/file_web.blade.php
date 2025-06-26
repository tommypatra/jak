@extends('template_website')

@section('head')
<title>File {{ $kategori }}</title>
@endsection

@section('container')

<h1>File {{ $kategori }}</h1>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">File Dokumen Web</th>
                <th scope="col">Akun</th>
                <th scope="col">Statistik</th>
                <th scope="col">Waktu</th>
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

<script>
    var vApiUrl = base_url;
    var vDataGrup = [];
    var vKategori = '{{ "file-web/".$kategori }}';
    var endPointList = base_url + '{{"/api/list-file?jenis=".$kategori}}';

    $(document).ready(function() {
        loadData();
        // getMenu();

        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })

        // function getMenu() {
        //     $.ajax({
        //         url: base_url + '/api/get-menu?search=' + vKategori + '&showall=true',
        //         method: 'GET',
        //         dataType: 'json',
        //         success: function(response) {
        //             vApiUrl = vApiUrl + '/' + response[0].endpoint;
        //             loadData();
        //             // console.log(response);
        //         },
        //         error: function() {
        //             alert(jenis + ' tidak ditemukan!');
        //         }
        //     });
        // }

        function loadData(page = 1, search = '') {
            $.ajax({
                url: endPointList + '&is_web=true&publikasi=1&page=' + page + '&search=' + search,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    dataList.empty();

                    $.each(response.data, function(index, dt) {
                        var linkFile = base_url + '/' + dt.path;
                        var link = base_url + '/file-read/' + dt.slug;

                        var hakakses = '';

                        dataList.append(`
                            <tr data-id="${dt.id}"> 
                                <td>${dt.nomor}</td> 
                                <td>
                                    <h5><a href="${link}">${dt.judul}</a></h5>
                                    <div class='font-12'><i class="bi bi-calendar-event"></i> ${dt.waktu}</div>
                                    <div class='font-12'>${dt.slug}</div>
                                    <a href='${linkFile}' target='_blank'><i class="bi bi-box-arrow-down"></i></a>
                                </td> 
                                <td>${dt.user.name}</td> 
                                <td>
                                    <span class="badge text-bg-info">
                                        <i class="bi bi-view-list"></i> ${dt.jumlah_akses}  
                                        <i class="bi bi-hand-thumbs-up"></i> ${dt.likedislike_count}  
                                        <i class="bi bi-chat-right-text"></i> ${dt.komentar_count}
                                    </span>
                                </td> 
                                <td>${dt.waktu}</td> 
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
        });

    });
</script>
@endsection