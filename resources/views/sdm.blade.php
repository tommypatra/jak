@extends('template_web')

@section('head')
  <title>SDM - IIQ | Institut Ilmu Al Quran JA Kendari</title>
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/blog.css" rel="stylesheet">
  <style>
    figure {
      margin: 0 0 20px 0; /* top right bottom left */
      padding: 5px;
    }
  </style>
@endsection

@section('container')

		<section id="hero_in" class="general">
			<div class="wrapper">
				<div class="container">
					<h1 class="fadeInUp blog-nama"><?=strtoupper($kategori)?></h1>
					<small id="blog-deskripsi"></small>
				</div>
			</div>
		</section>
		<!--/hero_in-->

		<div class="container margin_60_35">
			<div class="row">

        <div class="row mb-4">
          <div class="col-lg-12 d-flex justify-content-center">
              <input type="text" id="search-input" class="form-control w-50 me-2" placeholder="Cari nama">
              <button type="button" class="btn btn-primary" id="btn-cari">
                <i class="bi bi-search"></i> Cari
              </button>
          </div>
        </div>

				<div class="col-lg-12" id="list_sidebar">
          <div class="row" id="list-file"></div>
				
          <!-- Pagination -->
          <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center" id="pagination"></ul>
          </nav>
					<!-- /pagination -->
				</div>
				<!-- /col -->
			</div>
			<!-- /row -->
		</div>


		<!-- /container -->
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
  const kategori="{{ $kategori }}";
  'use strict';

  function maskHp(nomor) {
    $limit=3;
    if (!nomor || nomor.length < $limit) return nomor;
    return nomor.slice(0, -$limit) + 'XXX';
  }


  async function konten(page=1){ 
    try {
      const search=$('#search-input').val();
      const response = await fetch(`${base_url}/api/list-sdm?web=1&kategori=${kategori}&search=${search}&page=${page}&limit=2&publikasi=1`);
      const dataResponse = await response.json();
      const row = $("#list-file");
      const pagination = $('#pagination');
      row.empty(); 
      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(function (dt) {
          const profil = dt.profil || {}; // fallback biar aman

          const foto = profil.foto ? `/storage/${profil.foto}` : "/images/user-avatar.png";
          const jenis_kelamin = profil.jenis_kelamin === 'L'
            ? '<span class="badge bg-primary">Laki-laki</span>'
            : profil.jenis_kelamin === 'P'
              ? '<span class="badge bg-danger">Perempuan</span>'
              : '';

          const gelar_depan = profil.gelar_depan?profil.gelar_depan:"";
          const gelar_belakang = profil.gelar_belakang?profil.gelar_belakang:"";


          const nomor_pegawai = profil.nomor_pegawai
            ? `<p class="mb-1"><i class="bi bi-person-badge me-2"></i>Nomor Pegawai: ${profil.nomor_pegawai}</p>`
            : '';

          const nidn = profil.nidn
            ? `<p class="mb-1"><i class="bi bi-person-bounding-box me-2"></i>NIDN: ${profil.nidn}</p>`
            : '';

          const jabatan = profil.jabatan
            ? `<p class="mb-1"><i class="bi bi-briefcase me-2"></i>${profil.jabatan.nama}</p>`
            : '';
          const unit_kerja = profil.unit_kerja
            ? `<p class="mb-1"><i class="bi bi-building me-2"></i>${profil.unit_kerja.nama}</p>`
            : '';


          // badge dosen/admin
          const badgeDosen = profil.is_dosen
            ? `<span class="badge bg-success me-1">Dosen</span>` : '';
          const badgeAdmin = profil.is_administrasi
            ? `<span class="badge bg-warning text-dark">Administrasi</span>` : '';

          const html = `
            <div class="col-md-6">
              <div class="card shadow-sm mb-4 border-0 rounded-4">
                <div class="card-body">
                  <div class="d-flex align-items-start flex-column flex-md-row gap-4">
                    <!-- FOTO -->
                    <div class="text-center">
                      <img src="${base_url}${foto}" alt="Foto Profil" class="img-thumbnail rounded-circle shadow-sm" style="width: 130px; height: 130px; object-fit: cover;">
                    </div>

                    <!-- DATA PROFIL -->
                    <div>
                      ${jenis_kelamin}
                      <h5 class="fw-semibold text-primary mb-2">${gelar_depan} ${dt.name} ${gelar_belakang}</h5>
                      <p class="mb-1"><i class="bi bi-envelope me-2"></i>${dt.email}</p>
                      <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>${profil.tempat_lahir ?? ''} / ${profil.tanggal_lahir ?? ''}</p>
                      <p class="mb-1"><i class="bi bi-house-door me-2"></i>${profil.alamat ?? ''} - ${maskHp(profil.hp)}</p>
                      ${nomor_pegawai}
                      ${nidn}
                      ${jabatan}
                      ${unit_kerja}
                      ${badgeDosen} ${badgeAdmin}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
          row.append(html);
        });
        renderPagination(dataResponse, pagination);
      }
    } catch (error) {
      console.error(error);
    }
  }  

  $(document).ready(function() {
    init();			
    async function init(){
      await konten();
    }

    // Handle page change
    $(document).on('click', '.page-link', function() {
      var page = $(this).data('page');
      konten(page);
    });

    $("#btn-cari").click(function(){
        konten(1);
    });
  });	
</script>
@endsection
