@extends('template_web')

@section('head')
  <title>IIQ | Institut Ilmu Al Quran JA Kendari</title>
@endsection

@section('container')

  <!-- Slider -->
  <div id="full-slider-wrapper">
    <div id="layerslider" style="width:100%; height:750px;"></div>
  </div>
  <!-- End layerslider -->

  <div class="features clearfix">
    <div class="container">
      <ul>
        <li><i class="pe-7s-cup"></i>
          <h4>Kuliah Hemat, Kualitas Hebat!</h4><span>Kampus swasta agama termurah, mutu terjamin.</span>
        </li>
        <li><i class="pe-7s-study"></i>
          <h4>Belajar dari Ahlinya!</h4><span>Dosen profesional, siap membimbing masa depanmu.</span>
        </li>
        <li><i class="pe-7s-target"></i>
          <h4>Strategis, Dinamis, Islami!</h4><span>Kampus di pusat kota, nuansa religius nyaman.</span>
        </li>
      </ul>
    </div>
  </div>
  <!-- /features -->

  <div class="container margin_120_0">
    <div class="main_title_2">
      <span><em></em></span>
      <h2>Program Studi</h2>
      <p>Daftar Program Studi IIQ JA Kendari</p>
    </div>
    <div class="row" id="list-prodi">
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->

  <div class="container-fluid margin_30_95">
    <div class="main_title_2">
      <span><em></em></span>
      <h2>Testimoni</h2>
      <p>Ini kata mereka tentang kami</p>
    </div>
    <div id="reccomended" class="owl-carousel owl-theme">
    </div>

    <!-- /carousel -->
    <div class="container">
      <p class="btn_home_align"><a href="{{ url('artikel/testimoni') }}" class="btn_1 rounded">Semua Testimoni</a></p>
    </div>
    <!-- /container -->
    <hr>
  </div>
  <!-- /container -->

  <div class="bg_color_1">
    <div class="container margin_120_95">
      <div class="main_title_2">
        <span><em></em></span>
        <h2>Berita</h2>
        <p>daftar berita IIQ JA Kendari terbaru</p>
      </div>
      <div class="row" id="list-berita">
        
      </div>
      <!-- /row -->
      <p class="btn_home_align"><a href="{{ url('artikel/berita') }}" class="btn_1 rounded">Berita lainnya</a></p>
    </div>
    <!-- /container -->
  </div>
  <!-- /bg_color_1 -->

  <div class="call_section">
    <div class="container clearfix">
      <div class="col-lg-5 col-md-6 float-right wow position-relative" data-wow-offset="250">
        <div class="block-reveal">
          <h2>Pojok Rektor</h2>
          <div class="block-vertical"></div>
          <div class="box_1">
            <h3 id="pojok-rektor-judul"></h3>
            <p id="pojok-rektor-konten"></p>
            <a href="javascript:;" class="btn_1 rounded" id="pojok-rektor-link">Read more</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/call_section-->

    <div class="container margin_120_95">
      <div class="main_title_2">
        <span><em></em></span>
        <h2>Galeri</h2>
        <p>Dokumentasi kegiatan terkini</p>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="grid">
            <ul class="magnific-gallery" id="list-galeri"></ul>
          </div>

          <div class="container">
            <p class="btn_home_align"><a href="{{ url('galeri') }}" class="btn_1 rounded">Semua Galeri</a></p>
          </div>

        </div>
      </div>
  </div>
    <!-- /container -->

  <div class="bg_color_1">
    <div class="container margin_120_95">
      <div class="main_title_2">
        <span><em></em></span>
        <h2>Peta Lokasi</h2>
        <p>Kunjungi kampus kami</p>
      </div>
      <div class="row">
        <div class="col-lg-12 custom-code-html" data-slug="peta-lokasi" style="height: 450px !important;min-height: 450px;"></div>
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>

@endsection

@section('script')
<!-- SPECIFIC SCRIPTS -->
<script src="{{url('template/udema_v_3.3/html_menu_1')}}/layerslider/js/greensock.js"></script>
<script src="{{url('template/udema_v_3.3/html_menu_1')}}/layerslider/js/layerslider.transitions.js"></script>
<script src="{{url('template/udema_v_3.3/html_menu_1')}}/layerslider/js/layerslider.kreaturamedia.jquery.js"></script>
<script type="text/javascript">
  'use strict';

  async function htmlCode(slug,element) {
      $(element).html(slug + ' tidak ditemukan');
      try {
          const response = await fetch(`${base_url}/api/get-html-code?slug=${slug}`);
          const result = await response.json();
          if (result.data.length > 0) {
              $(element).html(result.data[0].code);
          }
      } catch (error) {
          console.error(error);
      }
  }  

  async function renderCustomHtml() {
    const elements = document.querySelectorAll('.custom-code-html');
    for (const el of elements) {
      const slug = el.dataset.slug;
      await htmlCode(slug, el);
    }
  }

  async function loadLayerSlider() {
    try {
      const response = await fetch(`${base_url}/api/get-slide-show?is_web=true&showall=true`);
      const result = await response.json();
      const data = result.data || [];

      const slider = $('#layerslider');
      slider.empty();

      if (data.length > 0) {
        data.forEach((slide, i) => {
          const html = `
          <div class="ls-slide" data-ls="slidedelay:3500; transition2d:75;">
            <img src="${base_url}${slide.path}" class="ls-bg" alt="${slide.judul}">
            <h3 class="ls-l slide_typo slide-style" style="top: 47%;" data-ls="durationin:1500;">
              ${slide.judul}
            </h3>
            ${slide.deskripsi ? `<p class="ls-l slide_typo_2 slide-style" style="top: 55%;" data-ls="durationin:1500;">${slide.deskripsi}</p>` : ''}
          </div>`;
          // ${slide.link ? `<a class="ls-l btn_1 rounded" href="${slide.link}" style="top: 65%; left: 50%;" data-ls="durationin:1500;">Selengkapnya</a>` : ''}

          slider.append(html);
        });
      } else {
        insertDefaultSlide();
      }

      // Inisialisasi ulang LayerSlider
      slider.layerSlider({
        autoStart: true,
        navButtons: false,
        navStartStop: false,
        showCircleTimer: false,
        responsive: true,
        responsiveUnder: 1280,
        layersContainer: 1200,
        skinsPath: 'layerslider/skins/'
      });
    } catch (error) {
      console.error('Gagal memuat slide:', error);
      insertDefaultSlide();

      $('#layerslider').layerSlider({
        autoStart: true,
        navButtons: false,
        navStartStop: false,
        showCircleTimer: false,
        responsive: true,
        responsiveUnder: 1280,
        layersContainer: 1200,
        skinsPath: 'layerslider/skins/'
      });
    }
  }

  function insertDefaultSlide() {
    $('#layerslider').html(`
      <div class="ls-slide" data-ls="slidedelay:5000; transition2d:75;">
        <img src="img/default-slide.jpg" class="ls-bg" alt="Slide Default">
        <h3 class="ls-l slide_typo" style="top: 50%; left: 50%;" data-ls="durationin:1500;">
          Selamat Datang di Website Kami
        </h3>
      </div>
    `);
  }

  async function berita(){ 
    try {
      const response = await fetch(`${base_url}/api/list-konten?jenis=berita&is_web=true&limit=6&publikasi=1`);
      const dataResponse = await response.json();
      if (dataResponse.data.length > 0) {
        const row = $("#list-berita");
        row.empty(); // kosongkan konten default

        dataResponse.data.forEach(function (konten) {

          const tanggal = new Date(konten.waktu);
          const tgl = tanggal.getDate();
          const bln = tanggal.toLocaleString('default', { month: 'short' });
          const tglLengkap = tanggal.toLocaleDateString('id-ID');

          const html = `
            <div class="col-lg-6">
              <a class="box_news" href="${base_url}/read/${konten.jenis_konten_slug}/${konten.slug}">
                <figure>
                  <img src="${konten.thumbnail ?? 'img/default.jpg'}" alt="">
                  <figcaption><strong>${tgl}</strong>${bln}</figcaption>
                </figure>
                <ul>
                  <li>${konten.user_name ?? 'Admin'}</li>
                  <li>${tglLengkap}</li>
                </ul>
                <h4>${konten.judul}</h4>
                <p>${konten.pembuka}...</p>
              </a>
            </div>`;
          row.append(html);
        });
      }
    } catch (error) {
      console.error(error);
    }
  }  		

  async function pojokRektor(){ 
    try {
      const response = await fetch(`${base_url}/api/list-konten?jenis=pojok-rektor&is_web=true&limit=1&publikasi=1`);
      const dataResponse = await response.json();
      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(function (konten) {
          $('#pojok-rektor-judul').html(konten.judul);
          $('#pojok-rektor-konten').html(konten.pembuka);
          $('#pojok-rektor-link').attr('href',`${base_url}/read/${konten.jenis_konten_slug}/${konten.slug}`);

        });
      }
    } catch (error) {
      console.error(error);
    }
  }  		

  async function testimoni() {
    try {
      const response = await fetch(`${base_url}/api/list-konten?jenis=testimoni&is_web=true&limit=6&publikasi=1`);
      const dataResponse = await response.json();

      const container = document.getElementById("reccomended");
      container.innerHTML = ""; // Kosongkan dulu

      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach(konten => {
          const html = `
            <div class="item">
              <div class="box_grid">
                <figure>
                  <a href="${base_url}/read/${konten.jenis_konten_slug}/${konten.slug}">
                    <img src="${base_url}${konten.thumbnail}" class="img-fluid" alt="${konten.judul}">
                  </a>
                </figure>
                <div class="wrapper">
                  <h3>${konten.judul}</h3>
                  <p>${konten.isi}</p>
                </div>
                <ul>
                  <li><i class="icon_clock_alt"></i> ${konten.waktu}</li>
                  <li><i class="icon_like"></i> ${konten.likedislike_count}</li>
                </ul>
              </div>
            </div>
          `;
          container.insertAdjacentHTML('beforeend', html);
        });

        // Reinitialize owlCarousel (jika perlu setelah manipulasi DOM)
        if ($(container).hasClass('owl-carousel')) {
          $(container).trigger('destroy.owl.carousel');
          $(container).owlCarousel({
            center: true,
            items: 2,
            loop: true,
            margin: 0,
            responsive: {
              0: {
                items: 1
              },
              767: {
                items: 2
              },
              1000: {
                items: 3
              },
              1400: {
                items: 4
              }
            }						
          });
        }
      }
    } catch (error) {
      console.error("Gagal memuat testimoni:", error);
    }
  }

  async function programStudi() {
    try {
      const response = await fetch(`${base_url}/api/list-konten?jenis=program-studi&is_web=true&limit=9&publikasi=1`);
      const dataResponse = await response.json();

      const container = document.getElementById("list-prodi");
      container.innerHTML = ""; // Kosongkan sebelum menambahkan

      if (dataResponse.data.length > 0) {
        dataResponse.data.forEach((konten, i) => {
          const card = `
            <div class="col-lg-4 col-md-6 wow animated" data-wow-offset="150">
              <a href="${base_url}/read/${konten.jenis_konten_slug}/${konten.slug}" class="grid_item">
                <figure class="block-reveal">
                  <div class="block-horizzontal"></div>
                  <img src="${base_url}${konten.thumbnail}" class="img-fluid" alt="${konten.judul}">
                  <div class="info">
                    <small><i class="ti-layers"></i> Program Studi</small>
                    <h3>${konten.judul}</h3>
                    <p style="font-size: 14px;">${konten.pembuka}</p>
                    <span class="btn btn-sm btn-outline-primary mt-2">Selengkapnya</span>
                  </div>
                </figure>
              </a>
            </div>
          `;
          container.insertAdjacentHTML('beforeend', card);
        });
      }
    } catch (error) {
      console.error('Gagal memuat program studi:', error);
    }
  }

  async function galeri(){ 
    try {
      const response = await fetch(`${base_url}/api/list-galeri?limit=8&publikasi=1&web=1`);
      const dataResponse = await response.json();
      const row = $("#list-galeri");
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
      }
    } catch (error) {
      console.error(error);
    }
  }  		  
  
  $(document).ready(function() {
    init();			
    async function init(){
      await loadLayerSlider();
      await programStudi();
      await testimoni();
      await berita();
      await pojokRektor();
      await galeri();
      await renderCustomHtml();
    }
    sesuaikanPengaturan();

  });	
</script>
@endsection
