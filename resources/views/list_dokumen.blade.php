@extends('template_web')

@section('head')
  <title>IIQ | Institut Ilmu Al Quran JA Kendari</title>
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
					<h1 class="fadeInUp blog-nama"></h1>
					<small id="blog-deskripsi"></small>
				</div>
			</div>
		</section>
		<!--/hero_in-->

		<div class="container margin_60_35">
			<div class="row">
				<aside class="col-lg-3" id="sidebar-menu">
					<div id="filters_col"> <a data-bs-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">Dokumen Web</a>
						<div class="collapse show" id="collapseFilters">
              <div class="filter_type">
                  <input type="text" name="search" id="search" class="form-control mt-2" placeholder="Search...">
                  <button id="cari-konten" class="btn_1 rounded mt-2"> Search</button>
              </div>

							<div class="filter_type">
								<h6>Jenis</h6>
								<ul id="jumlah-jenis-konten"></ul>
							</div>
							<div class="filter_type">
								<h6><span class="blog-nama">Dokumen</span> Populer</h6>
								<ul id="list-file-populer"></ul>
							</div>
						</div>
						<!--/collapse -->
					</div>
					<!--/filters col-->
				</aside>
				<!-- /aside -->

				<div class="col-lg-9" id="list_sidebar">
          <div id="list-file"></div>
				
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
		{{-- <div class="bg_color_1">
			<div class="container margin_60_35">
				<div class="row">
					<div class="col-md-4">
						<a href="#0" class="boxed_list">
							<i class="pe-7s-help2"></i>
							<h4>Need Help? Contact us</h4>
							<p>Cum appareat maiestatis interpretaris et, et sit.</p>
						</a>
					</div>
					<div class="col-md-4">
						<a href="#0" class="boxed_list">
							<i class="pe-7s-wallet"></i>
							<h4>Payments and Refunds</h4>
							<p>Qui ea nemore eruditi, magna prima possit eu mei.</p>
						</a>
					</div>
					<div class="col-md-4">
						<a href="#0" class="boxed_list">
							<i class="pe-7s-note2"></i>
							<h4>Quality Standards</h4>
							<p>Hinc vituperata sed ut, pro laudem nonumes ex.</p>
						</a>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div> --}}
		<!-- /bg_color_1 -->

		<!-- /container -->
@endsection

@section('script')
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
  const jenis="{{ $slug_kategori }}";
  'use strict';

  async function konten(page=1,search=""){ 
    try {
      const response = await fetch(`${base_url}/api/list-file?web=1&jenis=${jenis}&search=${search}&page=${page}&limit=10&publikasi=1`);
      const dataResponse = await response.json();
      const row = $("#list-file");
      const pagination = $('#pagination');
      row.empty(); 
      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(function (konten) {
          const tanggal = new Date(konten.waktu);
          const tgl = tanggal.getDate();
          const bln = tanggal.toLocaleString('default', { month: 'short' });
          const tglLengkap = tanggal.toLocaleDateString('id-ID');
          const url=`${base_url}/download/${konten.jenis_konten_slug}/${konten.slug}`;
          const cover=(konten.cover)?konten.cover:"/images/dokumen.png";
          const html = `
            <div class="box_list wow">
              <div class="row g-0">
                <div class="col-lg-4">
                  <figure class="block-reveal">
                    <div class="block-horizzontal"></div>
                    <a href="${url}"><img src="${base_url}${cover}" alt=""></a>
                    <div class="preview"><span>dokumen download</span></div>
                  </figure>
                </div>
                <div class="col-lg-8">
                  <div class="wrapper">
                    <button type="button" class="btn btn-sm btn-outline-primary wish_bt tambah-like" id="${konten.id}"><i class="icon_like"></i> <span class="jumlah-like">${konten.likedislike_count}</span></button>
                    <div class="mt-4">
                      <a href="${base_url}/dokumen/${konten.jenis_konten_slug}">
                        <small>${konten.jenis_konten_nama}</small>
                      </a>
                    </div>
                    <a href="${url}">
                      <h4>${konten.judul}</h4>
                    </a>
                    <p>${konten.deskripsi}</p>
                  </div>
                  <ul>
                    <li><i class="icon_clock_alt"></i> ${tglLengkap}</li>
                    <li><i class="icon_comment"></i> ${konten.komentar_count}</li>
                    <li><i class="bi bi-eye"></i> ${konten.jumlah_akses}</li>
                    <li><a href="${url}">Selengkapnya</a></li>
                  </ul>
                </div>
              </div>
            </div>`;
          row.append(html);
        });
        renderPagination(dataResponse.meta, pagination);

      }
    } catch (error) {
      console.error(error);
    }
  }  		

  async function kontenPopuler(){ 
    try {
      const response = await fetch(`${base_url}/api/list-file?web=1&jenis=${jenis}&urut=populer&limit=5&publikasi=1`);
      const dataResponse = await response.json();
      const row = $("#list-file-populer");
      row.empty(); 
      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(function (konten) {
          const tanggal = new Date(konten.waktu);
          const tgl = tanggal.getDate();
          const bln = tanggal.toLocaleString('default', { month: 'short' });
          const tglLengkap = tanggal.toLocaleDateString('id-ID');
          const url=`${base_url}/read/${konten.jenis_konten_slug}/${konten.slug}`;
          const html = `
            <li>
              <small>${tglLengkap}</small>
              <div></div><a href="${url}" title="${konten.judul}">${konten.judul}</a>
            </li>
          `;          
          row.append(html);
        });
      }
    } catch (error) {
      console.error(error);
    }
  }  		

  async function getGrup(){ 
    try {
      const response = await fetch(`${base_url}/api/list-jenis-konten?slug=${jenis}`);
      const dataResponse = await response.json();
      if (dataResponse.data.length > 0) {
        $('.blog-nama').text(dataResponse.data[0].nama);
        $('#blog-deskripsi').text(dataResponse.data[0].deskripsi);

        //untuk judul
        const pengaturanWeb = JSON.parse(localStorage.getItem('pengaturanWeb'));    
        const judul = pengaturanWeb.nama.trim();
        document.title = `${dataResponse.data[0].nama} - ${judul}`;        

      }
    } catch (error) {
      console.error(error);
    }
  }  		  

  async function jumlahJenisKonten() { 
    try {
      const response = await fetch(`${base_url}/api/jumlah-jenis-publikasi/file`);
      const dataResponse = await response.json();
      const row = $("#jumlah-jenis-konten");
      row.empty();

      if (dataResponse.length > 0) {
        dataResponse.forEach(kategori => {
          const url = `${base_url}/dokumen/${kategori.slug}`; // buat URL target
          const html = `
            <li>
              <a href="${url}">${kategori.nama} <span>(${kategori.jumlah_terbit})</span></a>
            </li>
          `;
          row.append(html);
        });
      } else {
        row.append('<li><em>Tidak ada kategori ditemukan</em></li>');
      }
    } catch (error) {
      console.error('Gagal memuat kategori:', error);
    }
  }


  $(document).ready(function() {
    init();			
    async function init(){
      await getGrup();
      await konten();
      await kontenPopuler()
      await jumlahJenisKonten();
    }

    // Handle page change
    $(document).on('click', '.page-link', function() {
      var page = $(this).data('page');
      var search = $('#search-input').val();
      konten(page, search);
    });

    $(document).on('click', '.tambah-like', function(e) {
      e.preventDefault();
      const btn = $(this);
      const id = btn.attr('id');
      const box = btn.closest('.box_list');
      const jumlah_like_element = box.find('.jumlah-like');

      btn.prop('disabled', true); // Optional, biar gak spam klik    

      $.ajax({
        url: `${base_url}/api/like`,
        type: 'POST',
        data: { file_id: id },
        success: function(response) {
          if (response.status) {
            alert("terima kasih like nya");
            jumlah_like_element.text(response.jumlah_like);
          } else {
            alert("Gagal menambah like");
          }
        },
        error: function() {
          alert("Terjadi kesalahan saat menambah like");
        },
        complete: function() {
          btn.prop('disabled', false);
        }
      });

    });

  });	
</script>
@endsection
