@extends('template_website')

@section('head')
    <title>Dokumen Download</title>
@endsection

@section('container')

    <h1 id="konten-judul">Dokumen Download</h1>
    <div id="konten-terbit"></div>
    <div class="mb-3" id="konten-statistik"></div>
    {{-- <div class="row">
        <div class="col-sm-12">
            <div class="input-group justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-secondary btnRefresh" id="refresh">Refresh</button>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-md-9 mb-3" >
            <div class="mb-3" id="konten-isi"></div>
        </div>
        <div class="col-md-3 mb-3">
            <h4 class="card-title" id="judul-lain">Dokumen Lain</h4>
            <div class="card" style="width: 18rem;">
                <ul class="list-group" id="konten-lain">
                </ul>
            </div>            
        </div>
    </div>    
    <div class="row">
        <div class="col-md-9 mb-3" >
            <i data-bs-toggle="tooltip" data-bs-title="klik jempol jika bermanfaat">apakah bermanfaat untuk anda ?  <i class="bi bi-hand-thumbs-up" id="berikan-like"></i></i>     
            <div class="accordion mb-3" id="frmAcr">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="frm-acr-header">
                        <button class="accordion-button collapsed" id="acrKomentar" type="button" data-bs-toggle="collapse" data-bs-target="#bodyAcr" aria-expanded="false" aria-controls="aria-acr-controls">
                            <h4>Komentar Pengunjung</h4>
                        </button>
                    </h2>
                    <div id="bodyAcr" class="accordion-collapse collapse" aria-labelledby="frm-acr-header" data-bs-parent="#frmAcr" style="">
                        <div class="accordion-body">
                            <form id="form-komentar">
                                <input type="hidden" name="file_id" id="file_id">
                                <div class="col-sm-7 mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control w-100" id="nama" name="nama" required>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <label for="komentar" class="form-label">Komentar Anda</label>
                                    <textarea class="form-control w-100" id="komentar" name="komentar" rows="4" required></textarea>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <i>semua komentar yang tampil akan diverifikasi terlebih dahulu oleh admin dan kami ucapkan terima kasih atas komentar anda</i>
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                                    <button type="button" class="btn btn-warning btnBatal">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
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

        </div>
    </div>    

@endsection

@section('script')
<script src="{{ asset('js/myapp.js') }}"></script>
<script src="{{ asset('js/pagination.js') }}"></script>

<script>
var slug='{{ $slug }}';
var file_id;
var jumlah_akses;
$(document).ready(function() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    
    readSlug();

    function readSlug() {
        $.ajax({
            url: base_url+'/api/list-file?showall=1&is_web=1&slug='+ slug,
            method: 'GET',
            success: function(response) {
                if(response.length>0){
                    var dt=response[0];
                    var statistik=`<span class="badge text-bg-info">
                                        <i class="bi bi-view-list"></i> <span id="jumlah-akses">${dt.jumlah_akses}</span>  
                                        <i class="bi bi-hand-thumbs-up"></i> <span id="jumlah-like">${dt.likedislike_count}</span>   
                                        <i class="bi bi-chat-right-text"></i> <span id="jumlah-komentar">${dt.komentar_count}</span> 
                                    </span>`;
                    file_id=dt.id;
                    jumlah_akses=dt.jumlah_akses;
                    $('#konten-terbit').html(`
                        <div>${dt.user.name} - ${dt.jeniskonten.nama}</div>
                        <div class="font-12">${dt.waktu}</div>
                    `);
                    $('#konten-judul').html(dt.judul);
                    $('#konten-statistik').html(statistik);

                    var linkfile='{{ url("/") }}'+'/'+dt.path;
                    $('#konten-isi').html(`
                        ${myLabel(dt.deskripsi)}
                        <div><a href="${linkfile}" target="_blank"><i class="bi bi-box-arrow-down"></i> download selengkpnya</a></div>
                    `);
                    // console.log(base_url+'/'+thumbnail);
                    $('#judul-lain').html(dt.jeniskonten.nama+' Lainnya');
                    resetForm();
                    readKontenLain(dt.jeniskonten.slug);
                    loadKomentar();
                    updateJumlahAkses();
                }else{
                    window.location.replace(base_url);
                }
            }
        });
    }

    function updateJumlahAkses(){
        $.ajax({
            url: base_url+'/api/update-jumlah-akses-file/'+ file_id,
            method: 'GET',
            success: function(response) {
                $('#jumlah-akses').html(response.jumlah_akses);
                // console.log(response);
            }
        });        
    }

    $('#berikan-like').on('click', function(e) {
        berikanLike(file_id);
    });

    function berikanLike(vfile_id){
        if(confirm('apakah anda yakin?'))
            $.ajax({
                url: base_url+'/api/like',
                method: 'POST',
                data: {file_id:vfile_id},
                dataType: 'json',            
                success: function(response) {
                    jumlahLike(vfile_id);
                    toastr.success('terima kasih atas like nya!', 'berhasil');

                    // console.log(response);
                }
            });        
    }

    function jumlahLike(vfile_id){
        $.ajax({
            url: base_url+'/api/like?file_id='+vfile_id,
            method: 'GET',
            dataType: 'json',            
            success: function(response) {
                $('#jumlah-like').html(response.count);
            }
        });        
    }    

    function readKontenLain(jenis) {
        $('#konten-lain').html('');
        $.ajax({
            url: base_url+'/api/list-file?publikasi=1&is_web=true&limit=10&jenis='+ jenis,
            method: 'GET',
            success: function(response) {
                // console.log(response);
                if(response.length>0){
                    response.forEach(function(konten, index) {
                    var link=base_url+'/file-read/'+konten.slug; 
                        $('#konten-lain').append(`<li class="list-group-item"><a href="${link}">${konten.judul}</a></li>`);
                    });  
                }else{
                    // window.location.replace(base_url);
                }
            }
        });
    }    
    function resetForm(){
        $('#form-komentar input').val('');
        $('#form-komentar')[0].reset();
        $('#file_id').val(file_id);
        // console.log(file_id);
    }

    $('.btnBatal').on('click', function(e) {
        resetForm();
        $('#bodyAcr').collapse('hide'); 
    });    

    $("#form-komentar").validate({
        submitHandler: function(form) {
            $.ajax({
                url: base_url+'/api/simpan-komentar',
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    toastr.success('komentar baru berhasil terkirim, komentar anda akan diverifikasi oleh admin!', 'berhasil');
                    // readSlug();
                    resetForm();
                },
                error: function() {
                    alert('operasi gagal dilakukan!');
                }
            });
        }
    });

    function loadKomentar(page = 1) {
        $.ajax({
            url: base_url+'/'+'api/get-komentar?file_id='+file_id+'&page=' + page + '&publikasi=1',
            method: 'GET',
            success: function(response) {
                var dataList = $('#data-list');
                var pagination = $('#pagination');
                
                dataList.empty();
                $.each(response.data, function(index, dt) {
                    dataList.append(`
                        <tr> 
                            <td>
                                <h5><i class="bi bi-person"></i> ${dt.nama}</h5>
                                <div class='font-12'>${dt.updated_at_format}</div>
                                <p>${dt.komentar}</p>
                            </td> 
                        </tr>`);                
                });
                renderPagination(response, pagination);
            }
        });
    }

    $(document).on('click', '.page-link', function() {
        var page = $(this).data('page');
        loadKomentar(page);
    });

});

</script>
@endsection
