@extends('template_dashboard')

@section('head')
<title>Pegawai/Akun Website</title>
<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endsection

@section('container')

<h1>Pegawai/Akun</h1>
<p>digunakan untuk mengelola akun yang bisa login untuk mengelola website ini </p>

<div class="accordion mb-3" id="frmAcr">
    <div class="accordion-item">
        <h2 class="accordion-header" id="frm-acr-header">
            <button class="accordion-button collapsed" id="tambahForm" type="button" data-bs-toggle="collapse" data-bs-target="#bodyAcr" aria-expanded="false" aria-controls="aria-acr-controls">
                <h3>Formulir Pegawai/Akun</h3>
            </button>
        </h2>
        <div id="bodyAcr" class="accordion-collapse collapse" aria-labelledby="frm-acr-header" data-bs-parent="#frmAcr">
            <div class="accordion-body">
                <form id="form">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-warning batal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

            <!-- Kiri: Search dan Filter -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <select class="form-select form-select-sm" id="filter-data" style="width: 150px;">
                    <option value="semua">Semua</option>
                    <option value="is_dosen">Dosen</option>
                    <option value="is_administrasi">Administrasi</option>
                </select>

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
                <th scope="col">Nama/Email</th>
                <th scope="col">Alamat/ HP</th>
                <th scope="col">Jabatan/ Unit Kerja</th>
                <th scope="col">Hak Akses</th>
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
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Akses Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAkses">
                    <input type="hidden" name="user_id" class="user_id">
                    <div id="checkboxContainer"></div>
                    <hr>
                    <button type="submit" class="btn btn-sm btn-primary" id="simpanAkses">Perbaharui Akses</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJabatan" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formJabatan">
                    <input type="hidden" name="id" class="id">
                    <input type="hidden" name="user_id" class="user_id">
                    <div id="checkboxContainer"></div>
                    <hr>
                    <button type="submit" class="btn btn-sm btn-primary" >Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Start -->
<!-- Modal Profil Pegawai -->
<div class="modal fade" id="modalProfil" tabindex="-1" aria-labelledby="modalProfilLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProfilLabel">Profil Pegawai / Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formProfil">
          <input type="hidden" name="id" class="id">
          <input type="hidden" name="user_id" class="user_id">

          <h5 class="mb-3" id="nama-pegawai">Profil Pegawai</h5>

          <div class="row mb-3">
            <div class="col-md-3">
              <label class="form-label">Gelar Depan</label>
              <input type="text" name="gelar_depan" id="gelar_depan" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama-lengkap" id="nama-lengkap" class="form-control" disabled>
            </div>
            <div class="col-md-3">
              <label class="form-label">Gelar Belakang</label>
              <input type="text" name="gelar_belakang" id="gelar_belakang" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Jenis Kelamin</label>
              <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                <option value="">-- Pilih --</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Tempat Lahir</label>
              <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Tanggal Lahir</label>
              <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control" placeholder="yyyy-mm-dd" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nomor Pegawai</label>
              <input type="text" name="nomor_pegawai" id="nomor_pegawai" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">NIDN</label>
              <input type="text" name="nidn" id="nidn" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-8">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label">Nomor HP</label>
              <input type="text" name="hp" id="hp" class="form-control" required>
            </div>
          </div>

           <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Jabatan</label>
              <select name="jabatan_id" id="jabatan_id" class="form-control"></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Unit Kerja</label>
              <select name="unit_kerja_id" id="unit_kerja_id" class="form-control"></select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_administrasi" id="is_administrasi" value="1">
                <label class="form-check-label" for="is_administrasi">Administrasi</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_dosen" id="is_dosen" value="1">
                <label class="form-check-label" for="is_dosen">Dosen</label>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Foto</label>
              <input type="file" name="foto" class="form-control" id="foto" accept="image/*" >
              <div class="mt-2">
                <img id="previewFoto" src="{{ url('images/user-avatar.png') }}" alt="Preview Foto" class="img-thumbnail" style="max-width: 150px;">
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            <a href="javascript:;" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</a>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/pagination.js') }}"></script>
<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    var vApiUrl = base_url + '/' + 'api/akun';
    var vDataGrup = [];

    $(document).ready(function() {

        loadGrup();
        loadJabatan();
        loadUnitKerja();
        loadData();

        $("#tanggal_lahir").datepicker({
            dateFormat: "yy-mm-dd",  // format tahun-bulan-tanggal
            changeMonth: true,
            changeYear: true,
        });

        function loadGrup() {
            $.ajax({
                url: base_url + '/' + 'api/grup?limit=100',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    vDataGrup = response;
                    // console.log(response);
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
        
        function loadUnitKerja() {
            const select=$('#unit_kerja_id');
            select.empty();
            select.append('<option value="">- pilih -</option>');
            $.ajax({
                url: base_url + '/' + 'api/get-unit-kerja?limit=500',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.data.length > 0) {
                        $.each(response.data, function(index, dt) {
                            select.append('<option value="' + dt.id + '">' + dt.nama + '</option>');
                        });
                    }
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

        function loadJabatan() {
            const select=$('#jabatan_id');
            select.empty();
            select.append('<option value="">- pilih -</option>');

            $.ajax({
                url: base_url + '/' + 'api/get-jabatan?limit=500',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.data.length > 0) {
                        $.each(response.data, function(index, dt) {
                            select.append('<option value="' + dt.id + '">' + dt.nama + '</option>');
                        });
                    }
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


        //load data profil pegawai
        function loadPegawai(user_id) {
            $.ajax({
                url: base_url + '/' + 'api/get-pegawai?user_id='+user_id,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.data.length>0){

                        const data = response.data[0];
                        const profil = data.profil;
                        const foto_pegawai=(profil)?'/storage/'+profil.foto:'/images/user-avatar.png';

                        $('#nama-pegawai').text(data.name);
                        $('#nama-lengkap').val(data.name);
                        $('#is_administrasi').prop('checked', false);
                        $('#is_dosen').prop('checked', false);

                        $('#formProfil').find('[name="user_id"]').val(data.id);
                        if(profil){
                            $('#formProfil').find('[name="id"]').val(profil.id);
                            $('#gelar_depan').val(profil.gelar_depan);
                            $('#gelar_belakang').val(profil.gelar_belakang);
                            $('#jenis_kelamin').val(profil.jenis_kelamin);
                            $('#alamat').val(profil.alamat);
                            $('#tempat_lahir').val(profil.tempat_lahir);
                            $('#tanggal_lahir').val(profil.tanggal_lahir);
                            $('#nidn').val(profil.nidn);
                            $('#nomor_pegawai').val(profil.nomor_pegawai);
                            $('#hp').val(profil.hp);
                            $('#is_administrasi').prop('checked', profil.is_administrasi == 1);
                            $('#is_dosen').prop('checked', profil.is_dosen == 1);

                            if(profil.jabatan_id)
                                $('#jabatan_id').val(profil.jabatan_id);
                            if(profil.unit_kerja_id)
                                $('#unit_kerja_id').val(profil.unit_kerja_id);
                            
                        }
                        $('#previewFoto').attr('src',base_url+foto_pegawai);
                        // console.log(data);
                    }
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
        
        $('#btn-cari').click(function(){
            loadData(1);
        });

        function loadData(page = 1) {
            const search=$('#cari-data').val();
            $.ajax({
                url: vApiUrl + '?page=' + page + '&search=' + search + '&paging=' + vPaging,
                method: 'GET',
                success: function(response) {
                    var dataList = $('#data-list');
                    var pagination = $('#pagination');
                    dataList.empty();

                    $.each(response.data, function(index, dt) {
                        var hakakses = '';
                        // console.log(dt.atur_grup);
                        let foto_pegawai='/images/user-avatar.png';
                        let alamat='';
                        let nama_lengkap=dt.name;
                        let nip='';
                        let nidn='';
                        let status_pegawai='';
                        let jabatan='';
                        let unit_kerja =''
                        if(dt.profil){
                            if(dt.profil.is_administrasi==1){
                                status_pegawai+='<span class="badge text-bg-success ms-1">Administrasi</span>';
                            }
                            if(dt.profil.is_dosen==1){
                                status_pegawai+='<span class="badge text-bg-primary ms-1">Dosen</span>';
                            }
                            unit_kerja=dt.profil.unit_kerja?dt.profil.unit_kerja.nama:"";
                            jabatan=dt.profil.jabatan?dt.profil.jabatan.nama:"";
                            nama_lengkap=myLabel(dt.profil.gelar_depan)+' '+dt.name+' '+myLabel(dt.profil.gelar_belakang);
                            foto_pegawai='/storage/'+dt.profil.foto;
                            alamat=myLabel(dt.profil.alamat)+' '+myLabel(dt.profil.hp);
                            nip=(dt.profil.nomor_pegawai)?`<div class="text-muted small">NOPEG : ${dt.profil.nomor_pegawai}</div>`:'';
                            nidn=(dt.profil.nidn)?`<div class="text-muted small">NIDN : ${dt.profil.nidn}</div>`:'';
                        }

                        // console.log(dt.profil);  
                        if (dt.atur_grup.length > 0) {
                            hakakses = '<ul>';
                            $.each(dt.atur_grup, function(index, dp) {
                                hakakses = hakakses + `<li>${dp.grup.nama} <button type="button" class="btn btn-danger btn-vsm hapusAkses" data-id="${dp.id}" >X</button></li>`;
                            });
                            hakakses = hakakses + '</ul>';
                        }
                        dataList.append(`<tr data-id="${dt.id}"> 
                            <td>${index+1}</td> 
                            <td>
                                <div class="d-flex align-items-start">
                                    <img src="${base_url}${foto_pegawai}" alt="Foto Pegawai" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <div class="fw-semibold">${nama_lengkap}</div>
                                        <div class="text-muted small">${dt.email}</div>
                                        ${nip}
                                        ${nidn}
                                        ${status_pegawai}
                                    </div>
                                </div>                            
                            </td>

                            <td>${alamat}</td>
                            <td>${jabatan} ${unit_kerja}</td> 
                            <td>${hakakses}</td> 
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" id="btn-paging">Aksi </button>
                                <ul class="dropdown-menu dropdown-menu-end" id="list-select-paging">
                                    <li><a class="dropdown-item gantiData" data-id="${dt.id}" href="javascript:;">Ganti</a></li>
                                    <li><a class="dropdown-item profilAkun" data-id="${dt.id}" href="javascript:;">Profil</a></li>
                                    <li><a class="dropdown-item setAkses" data-id="${dt.id}" href="javascript:;">Akses</a></li>
                                    <li><a class="dropdown-item hapusData" data-id="${dt.id}" href="javascript:;" >Hapus</a></li>
                                </ul>
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
            $('#name').focus();
        });

        function resetForm() {
            $('#form input').val('');
            $('#form')[0].reset();
        }

       $("#formProfil").validate({
            submitHandler: function(form) {
                const $form = $(form);
                const id = $form.find('[name="id"]').val();
                const vType = (id === '') ? 'POST' : 'POST'; // Kalau pakai FormData, sebaiknya POST, nanti method override via `_method`
                let vUrl = `${base_url}/api/profil-pegawai`;
                if (id !== '') vUrl += '/' + id;

                const formData = new FormData(form);
                if (id !== '') {
                formData.append('_method', 'PUT'); // Laravel-style override
                }

                $.ajax({
                    url: vUrl,
                    type: 'POST', // Tetap pakai POST agar bisa kirim FormData
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        var modal = bootstrap.Modal.getInstance(document.getElementById('modalProfil'));
                        modal.hide();
                        loadData();
                        toastr.success('berhasil dilakukan!', 'Berhasil');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                            forceLogout('Akses ditolak! Login kembali');
                        } else {
                            alert('gagal dilakukan!');
                        }
                    }
                });
            }
        });


        $("#tambah").on("click", function() {
            $('html, body').scrollTop($('#tambahForm').offset().top);
            $('#bodyAcr').collapse('show'); // Menampilkan accordion
            resetForm();
            $('#name').focus();
        });

        $(document).on('click', '.batal', function(event) {
            event.preventDefault();
            resetForm();
            $('#bodyAcr').collapse('hide');
        });

        // function batal(event) {
        //     event.preventDefault();
        //     resetForm();
        //     $('#name').focus();
        //     $('#bodyAcr').collapse('hide');
        // }

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
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: function(element) {
                        return $('#id').val().trim() === '';
                    },
                    minlength: 8
                }
            },
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

        //ganti
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
                    $('#id').val(response.id);
                    $('#name').val(response.name);
                    $('#email').val(response.email);
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

        //hapus
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

        // function hapusAkses(id){
        $(document).on('click', '.hapusAkses', function() {
            var id = $(this).data('id');
            var selectedPage = $('.page-item.active .page-link').data('page');
            if (confirm('apakah anda yakin?'))
                $.ajax({
                    url: base_url + '/' + 'api/atur-grup/' + id,
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

        // function profiAkun(id){
        $(document).on('click', '.profilAkun', function() {
            var id = $(this).data('id');
            $('#formProfil')[0].reset();
            $('#formProfil').find('[name="user_id"]').val("");
            $('#formProfil').find('[name="id"]').val("");
            $('#formProfil select').val(null).trigger('change');
            $('#formProfil input:checkbox').prop('checked', false);
            loadPegawai(id);

            var fModalForm = new bootstrap.Modal(document.getElementById('modalProfil'), {
                keyboard: false
            });
            fModalForm.show();
        });

        $(document).on('click', '.setAkses', function() {
            var id = $(this).data('id');
            // alert(id);
            $('#checkboxContainer').html('');
            $('.user_id').val(id);
            $.ajax({
                url: vApiUrl + '/' + id,
                method: 'get',
                dataType: 'json',
                success: function(response) {
                    var aksesGrup = response.atur_grup;
                    var showModal=false;
                    $.each(vDataGrup.data, function(index, dt) {
                        var idFound = false;
                        $.each(aksesGrup, function(index, grup) {
                            if (grup.grup_id === dt.id) {
                                idFound = true;
                                return;
                            }
                        });

                        if (!idFound) {
                            var checkbox = $('<input type="checkbox">').attr({
                                id: 'grup_id' + dt.id,
                                value: dt.id,
                                name: 'grup_id[]'
                            });
                            var label = $('<label>').attr('for', 'grup_id' + dt.id).text(dt.nama);
                            label.css('margin-left', '5px');
                            $('#checkboxContainer').append(checkbox, label, '<br>');
                            showModal = true;
                        }
                    });

                    if(showModal){
                    var fModalForm = new bootstrap.Modal(document.getElementById('modalForm'), {
                        keyboard: false
                    });
                    fModalForm.show();
                    }else{
                        alert('semua hak akses sudah ada')
                    }
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

        $('#formAkses').submit(function(event) {
            event.preventDefault();
            var selectedPage = $('.page-item.active .page-link').data('page');
            $.ajax({
                url: base_url + '/' + 'api/atur-grup',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    loadData(selectedPage);
                    toastr.success('set akses akun berhasil dilakukan!', 'berhasil');
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


        $('#foto').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewFoto').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });


    });
</script>
@endsection