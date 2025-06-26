@extends('template_website')

@section('head')
<title>Galeri Web</title>
<link rel="stylesheet" href="{{ asset('plugins/viewbox-master/viewbox.css') }}">
@endsection

@section('container')

<h1>Galeri Web</h1>
<hr>
<!-- Data pesan akan dimuat di sini -->
<div id="data-list"></div>
<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center" id="pagination">
    </ul>
</nav>

@endsection

@section('script')

<script src="{{ asset('plugins/viewbox-master/jquery.viewbox.min.js') }}"></script>
<script src="{{ asset('js/myapp.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script>
    var vApiUrl = base_url + '/api/list-galeri?is_web=true&publikasi=1';

    $(document).ready(function() {
        loadData();


        $('.item-paging').on('click', function() {
            vPaging = $(this).data('nilai');
            loadData();
        })

        function loadData(page = 1, search = '') {
            $.ajax({
                url: vApiUrl + '&page=' + page + '&search=' + search,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    var row = $('<div class="row"></div>');
                    dataList.empty();
                    $.each(response.data, function(index, dt) {
                        var linkFile = base_url + '/' + dt.path;
                        var col = $('<div class="col-md-4 gallery-item"></div>');
                        var content = `
                            <h5>${dt.judul}</h5>
                            <div class='font-12'><i class="bi bi-calendar-event"></i> ${dt.waktu}</div>
                            <a href='${linkFile}' class='image-link' target='_blank'><img src='${linkFile}' class="img-fluid"></a>
                        `;
                        col.append(content);
                        row.append(col);
                    });
                    dataList.append(row);
                    $('.image-link').viewbox();
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