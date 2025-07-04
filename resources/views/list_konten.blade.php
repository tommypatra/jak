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
				<div class="col-lg-9">
          <div id="list-konten"></div>
					
          <!-- Pagination -->
          <nav aria-label="Page navigation">
              <ul class="pagination justify-content-center" id="pagination"></ul>
          </nav>
					<!-- /pagination -->
				</div>
				<!-- /col -->

				<aside class="col-lg-3">
					<div class="widget">
							<div class="form-group">
								<input type="text" name="search" id="search" class="form-control" placeholder="Search...">
							</div>
							<button id="cari-konten" class="btn_1 rounded"> Search</button>
					</div>
					<!-- /widget -->
					<div class="widget">
						<div class="widget-title">
							<h4><span class="blog-nama">Konten</span> Populer</h4>
						</div>
						<ul class="comments-list" id="list-konten-populer">
						</ul>
					</div>
					<!-- /widget -->
					<div class="widget">
						<div class="widget-title">
							<h4>Kategori Publikasi</h4>
						</div>
						<ul class="cats" id="jumlah-jenis-konten">
						</ul>
					</div>
					<!-- /widget -->
				</aside>
				<!-- /aside -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
@endsection

@section('script')
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
  const jenis="{{ $slug_kategori }}";
  'use strict';

  async function konten(page=1,search=""){ 
    try {
      const response = await fetch(`${base_url}/api/list-konten?web=1&jenis=${jenis}&search=${search}&page=${page}&limit=10&publikasi=1`);
      const dataResponse = await response.json();
      const row = $("#list-konten");
      const pagination = $('#pagination');
      row.empty(); 
      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(function (konten) {
          const tanggal = new Date(konten.waktu);
          const tgl = tanggal.getDate();
          const bln = tanggal.toLocaleString('default', { month: 'short' });
          const tglLengkap = tanggal.toLocaleDateString('id-ID');
          const url=`${base_url}/read/${konten.jenis_konten_slug}/${konten.slug}`;
          const html = `
            <article class="blog wow fadeIn">
              <div class="row g-0">
                <div class="col-lg-7">
                  <figure>
                    <a href="${url}"><img src="${konten.thumbnail ?? base_url+'/images/logo.png'}" alt="">
                      <div class="preview"><span>Read more</span></div>
                    </a>
                  </figure>
                </div>
                <div class="col-lg-5">
                  <div class="post_info box_list">
                    <small>${tglLengkap}</small>                      
                    <h3><a href="${url}">${konten.judul}</a></h3>
                    <button type="button" class="btn btn-sm btn-outline-primary wish_bt tambah-like" id="${konten.id}"><i class="icon_like"></i> <span class="jumlah-like">${konten.likedislike_count}</span></button>
                    <p>${konten.pembuka}</p>
                    <ul>
                      <li>
                        <div class="thumb"><img src="${base_url}/images/thumb_blog.jpg" alt=""></div> ${konten.user_name ?? 'Admin'}
                      </li>
                      <li><i class="bi bi-eye"></i> ${konten.jumlah_akses}</li>
                      <li><i class="icon_comment_alt"></i> ${konten.komentar_count}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </article>`;
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
      const response = await fetch(`${base_url}/api/list-konten?web=1&jenis=${jenis}&urut=populer&limit=5&publikasi=1`);
      const dataResponse = await response.json();
      const row = $("#list-konten-populer");
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
              <div class="alignleft">
                <a href="${url}"><img src="${konten.thumbnail ?? base_url+'/images/logo.png'}" alt=""></a>
              </div>
              <small>${tglLengkap}</small>
              <h3><a href="${url}" title="${konten.judul}">${konten.judul}</a></h3>
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
      const response = await fetch(`${base_url}/api/list-jenis-konten?web=1&slug=${jenis}`);
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
      const response = await fetch(`${base_url}/api/jumlah-jenis-publikasi/artikel`);
      const dataResponse = await response.json();
      const row = $("#jumlah-jenis-konten");
      row.empty();

      if (dataResponse.length > 0) {
        dataResponse.forEach(kategori => {
          const url = `${base_url}/artikel/${kategori.slug}`; // buat URL target
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

    $('#cari-konten').click(function(){
      const cari=$('#search').val();
      konten(1, cari);
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
        data: { konten_id: id },
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
