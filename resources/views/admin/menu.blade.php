@extends('template_dashboard')

@section('head')
<title>Menu Web</title>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('plugins/select2-to-tree/src/select2totree.css') }}" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
    }
</style>
@endsection

@section('container')

<h1>Menu Web</h1>
<p>digunakan untuk mengatur menu pada tampilan utama website ini </p>
<hr>

<form id="form" class="row">
    <input type="hidden" name="id" id="id">
    <input type="hidden" name="urut" id="urut">
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-4 mb-2">
                <input type="text" name="nama" id="nama" class="form-control w-100" placeholder="nama menu" aria-label="menu" required>
            </div>
            <div class="col-sm-5 mb-2">
                <input type="text" name="url" id="url" class="form-control w-100" placeholder="url tujuan" aria-label="url">
            </div>
            <!-- <div class="col-sm-6 mb-2">
                <input type="text" name="endpoint" id="endpoint" class="form-control w-100" placeholder="endpoint api" aria-label="endpoint">
            </div> -->
            <div class="col-sm-3 mb-2">
                <select name="menu_id" id="menu_id" class="form-control" required>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="input-group justify-content-end">
            <button type="submit" class="btn btn-sm btn-outline-secondary simpan">Simpan</button>
            <button type="button" class="btn btn-sm btn-outline-secondary resetForm">Batal</button>
            <button type="button" class="btn btn-sm btn-outline-secondary refreshForm">Refresh</button>
        </div>
    </div>
</form>

<div class="font-12">double click pada data yang akan ingin diubah</div>

<div id="data-list"></div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('plugins/select2-to-tree/src/select2totree.js') }}"></script>
<script src="{{ asset('js/token.js') }}"></script>

<script>
    var vApiUrl = base_url + '/' + 'api/menu';

    $(document).ready(function() {

        loadTreeMenu();
        loadData();

        function resetForm() {
            $('#form input').val('');
            $('#form')[0].reset();
            $('#menu_id').val(0).trigger('change');
        }

        $(document).on('click', '.resetForm', function() {
            resetForm();
        });

        function buildMenuTree(data, parentId) {
            var result = "<ul>";
            for (var i = 0; i < data.length; i++) {
                if (data[i].menu_id == parentId) {
                    result += `<li class="gantiData" data-id="${data[i].id}" data-urut="${data[i].urut}">${data[i].nama} <span class="font-12">${myLabel(data[i].url)}</span> <button type="button" class="btn btn-vsm btn-danger hapusData" data-id="${data[i].id}">x</button>`;
                    result += buildMenuTree(data, data[i].id);
                    result += "</li>";
                }
            }
            result += "</ul>";
            return result;
        }

        function loadTreeMenu() {
            $.ajax({
                url: base_url + '/' + 'api/load-menu-tree',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#menu_id').empty();
                    $("#menu_id").select2ToTree({
                        treeData: {
                            dataArr: response
                        }
                    });
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

        function loadData(search = '') {
            $.ajax({
                url: vApiUrl + '?showall=true&search=' + search,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    dataList.empty();
                    var menuTree = buildMenuTree(response, null);
                    $(dataList).html(menuTree);
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

        function refresh() {
            loadTreeMenu();
            loadData();
            resetForm();
        };

        $(document).on('click', '.refreshForm', function(event) {
            loadData();
        });

        $(document).on('dblclick', '.gantiData', function(event) {
            event.stopPropagation();
            var id = $(this).data('id');
            var menu_id = 0;
            $.ajax({
                url: vApiUrl + '/' + id,
                method: 'GET',
                success: function(response) {
                    menu_id = (response.menu_id > 0) ? response.menu_id : 0;
                    $('#id').val(response.id);
                    $('#urut').val(response.urut);
                    $('#nama').val(response.nama);
                    // $('#endpoint').val(response.endpoint);
                    $('#url').val(response.url);
                    $('#menu_id').val(menu_id).trigger('change');
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
                        loadData(); // Reload list after submission
                        loadTreeMenu();
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

        // function hapusData(id){
        $(document).on('click', '.hapusData', function(event) {
            event.stopPropagation();
            var id = $(this).data('id');

            var selectedPage = $('.page-item.active .page-link').data('page');
            if (confirm('apakah anda yakin?'))
                $.ajax({
                    url: vApiUrl + '/' + id,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function(response) {
                        toastr.success('operasi berhasil dilakukan!', 'berhasil');
                        loadData(); // Reload list after submission
                        loadTreeMenu();
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