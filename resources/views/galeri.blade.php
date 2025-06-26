@extends('template_web')

@section('head')
    <title>IIQ | Institut Ilmu Al Quran JA Kendari</title>    
    <link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/blog.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

@endsection

@section('container')

		<section id="hero_in" class="general">
			<div class="wrapper">
				<div class="container">
					<h1 class="fadeInUp">Galeri</h1>
					<small>Foto kegiatan kampus</small>
				</div>
			</div>
		</section>

		<div class="container margin_60_35">
			<div class="main_title_2">
				<span><em></em></span>
				<h2>Galeri Kampus</h2>
				<p>Dokumentasi kegiatan tridharma perguruan tinggi</p>
			</div>
			<div class="grid">
				<ul class="magnific-gallery" id="list-galeri"></ul>
			</div>
			<!-- /grid gallery -->

      <!-- Pagination -->
      <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center" id="pagination"></ul>
      </nav>
      <!-- /pagination -->
		</div>
		<!-- /container -->

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
  'use strict';

  async function konten(page=1,search=""){ 
    try {
      const response = await fetch(`${base_url}/api/list-galeri?search=${search}&page=${page}&limit=20&publikasi=1&web=1`);
      const dataResponse = await response.json();
      const row = $("#list-galeri");
      const pagination = $('#pagination');
      row.empty(); 
      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(function (konten) {
          const tanggal = new Date(konten.waktu);
          const tgl = tanggal.getDate();
          const bln = tanggal.toLocaleString('default', { month: 'short' });
          const tglLengkap = tanggal.toLocaleDateString('id-ID');
          const url=`${base_url}${konten.path}`;
          const html = `
                      <li>
                        <figure>
                          <img src="${url}" alt="">
                          <figcaption>
                            <div class="caption-content">
                              <a href="${url}" title="${konten.judul}" data-effect="mfp-zoom-in">
                                <i class="pe-7s-albums"></i>
                                <p>${konten.judul}</p>
                              </a>
                            </div>
                          </figcaption>
                        </figure>
                      </li>`;
          row.append(html);
        });
        renderPagination(dataResponse.meta, pagination);

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
      var search = $('#search-input').val();
      konten(page, search);
    });

    $('#cari-konten').click(function(){
      const cari=$('#search').val();
      konten(1, cari);
    });

    
  });	
</script>
@endsection
