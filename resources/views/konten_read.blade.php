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
					<h1 class="fadeInUp blog-nama"></h1>
					<small id="blog-deskripsi"></small>
				</div>
			</div>
		</section>

		<div class="container margin_60_35">
			<div class="row">
				<div class="col-lg-9">
					<div class="bloglist singlepost">
						<p><img alt="" class="img-fluid" id="konten-gambar" src="img/blog-single.jpg"></p>
						<h1 id="konten-judul">Judul</h1>
						<div class="postmeta">
							<ul>
								<li><a href="#"><i class="icon_folder-alt"></i> <span id="konten-jenis">Jenis</span></a></li>
								<li><a href="#"><i class="icon_clock_alt"></i> <span id="konten-tanggal">{{ date('d-m-Y') }}</span></a></li>
								<li><a href="#"><i class="icon_pencil-edit"></i> <span id="konten-penulis">Admin</span></a></li>
								<li><a href="#"><i class="icon_comment_alt"></i> (<span id="konten-komentar">0</span>) Komentar</a></li>
								<li><a href="#"><i class="bi bi-eye"></i> <span id="konten-jumlah-akses">0</span>x Dibaca</a></li>
							</ul>
						</div>
						<!-- /post meta -->
						<div class="post-content">
							<div class="dropcaps">
								<p id="konten-isi"></p>
							</div>
						</div>
						<!-- /post -->
					</div>
					<!-- /single-post -->

					<h5>Kirim Komentar Anda</h5>
					<form id="form-komentar">
            <input type="hidden" name="konten_id" id="konten_id">
						<div class="form-group">
							<input type="text" name="nama" id="nama" class="form-control" placeholder="nama" required>
						</div>
						<div class="form-group">
							<textarea class="form-control" name="komentar" id="komentar" rows="6" placeholder="komentar anda" required></textarea>
						</div>
						<div class="form-group">
							<button type="submit" id="btn-kirim" class="btn_1 rounded add_bottom_30"> Kirim Sekarang</button>
						</div>
					</form>
					<div id="comments">
						<h5>Komentar Pengunjung</h5>
						<ul id="list-komentar">
						</ul>
            <div class="btn btn-primary mt-5" style="display: none" id="komentar-berikutnya" data-page="0">Komentar berikutnya</div>
					</div>
				</div>
				<!-- /col -->

				<aside class="col-lg-3">
					<div class="widget">
                <div class="form-group">
                    <input type="text" name="search-konten" id="search-konten" class="form-control" placeholder="Search...">
                </div>
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

				</aside>
				<!-- /aside -->
			</div>
			<!-- /row -->
		</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
  const jenis="{{ $slug_kategori }}";
  const judul="{{ $slug_judul }}";
  'use strict';

  async function read(){ 
    try {
      const response = await fetch(`${base_url}/api/list-konten?jenis=${jenis}&slug=${judul}`);
      const dataResponse = await response.json();
      const row = $("#list-konten");
      row.empty(); 
      if (dataResponse.data.length > 0) {
            const konten=dataResponse.data[0];

            const tanggal = new Date(konten.waktu);
            const tgl = tanggal.getDate();
            const bln = tanggal.toLocaleString('default', { month: 'short' });
            const tglLengkap = tanggal.toLocaleDateString('id-ID');
            const url=`${base_url}/artikel/${konten.jenis_konten_slug}`;


            $('#konten-gambar').attr('src',`${konten.thumbnail ?? base_url+'/images/logo.png'}`);
            $('#konten-judul').text(konten.judul);
            
            $('#konten-jenis').text(konten.jenis_konten_nama);
            $('#konten-jumlah-akses').text(konten.jumlah_akses+1);
            $('#konten-jenis').parent('a').attr('href', url);

            $('#konten-tanggal').text(tglLengkap);
            $('#konten-penulis').text(konten.user_name);
            $('#konten-komentar').text(konten.komentar_count);
            $('#konten-isi').html(konten.isi);

            $('#konten_id').val(konten.id);
      }
    } catch (error) {
      console.error(error);
    }
  }  		

  async function kontenPopuler(){ 
    try { 
      const response = await fetch(`${base_url}/api/list-konten?jenis=${jenis}&urut=populer&limit=5&publikasi=1`);
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
      const response = await fetch(`${base_url}/api/list-jenis-konten?slug=${jenis}`);
      const dataResponse = await response.json();
      if (dataResponse.data.length > 0) {
        $('.blog-nama').text(dataResponse.data[0].nama);
        $('#blog-deskripsi').text(dataResponse.data[0].deskripsi);
      }
    } catch (error) {
      console.error(error);
    }
  }

  async function updateJumlahAksesKonten(){ 
    try {
      const konten_id = $('#konten_id').val();
      const response = await fetch(`${base_url}/api/update-jumlah-akses-konten/${konten_id}`);
      const dataResponse = await response.json();
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


  async function getKomentar() {
    try {
      const page = parseInt($('#komentar-berikutnya').attr('data-page'));
      const konten_id=$('#konten_id').val();       
      const response = await fetch(`${base_url}/api/get-komentar?page=${(page+1)}&publikasi=1&konten_id=${konten_id}&limit=1`);
      const dataResponse = await response.json();
      const row = $("#list-komentar");

      if((page+1)<=1)
        row.empty();

      console.log(dataResponse);
      if (dataResponse.data.length > 0) {

        if(dataResponse.current_page<dataResponse.last_page)
          $('#komentar-berikutnya').show();
        else
          $('#komentar-berikutnya').hide();

        $('#komentar-berikutnya').attr('data-page',dataResponse.current_page);
        dataResponse.data.forEach(function (item) {
          console.log(item);
          const tanggal = new Date(item.created_at).toLocaleDateString('id-ID');
          const html = `
            <li>
              <div class="avatar">
                <a href="#"><img src="${item.avatar ?? base_url+'/images/user-avatar.png'}" alt=""></a>
              </div>
              <div class="comment_right clearfix">
                <div class="comment_info">
                  By <a href="#">${item.nama}</a><span>|</span>${tanggal}
                </div>
                <p>${item.komentar}</p>
              </div>
            </li>
          `;
          row.append(html);
        });
      } else {
        $('#komentar-berikutnya').hide();          
        row.append('<li><em>Belum ada komentar.</em></li>');
      }
    } catch (error) {
      console.error("Gagal memuat komentar:", error);
    }
  }



  $(document).ready(function() {
    init();			
    async function init(){
      await getGrup();
      await read();
      await getKomentar();
      await kontenPopuler();
      await jumlahJenisKonten();
      await updateJumlahAksesKonten();
    }

    $('#cari-konten').click(function(){
      const cari=$('#search').val();
      konten(1, cari);
    });

    $("#search-konten").autocomplete({
      source: function(request, response) {
        $.ajax({
          url: `${base_url}/api/list-konten`,
          data: {
            jenis: jenis,
            limit: 7,
            publikasi: 1,
            search: request.term
          },
          success: function(dataResponse) {
            const hasil = dataResponse.data.map(item => ({
              label: item.judul,
              value: item.slug,
              jenis: item.jenis_konten_nama,
              image: item.thumbnail ?? base_url+'/images/thumb_blog.jpg',
              tanggal: new Date(item.waktu).toLocaleDateString('id-ID'),
              url: `${base_url}/read/${item.jenis_konten_slug}/${item.slug}`
            }));
            response(hasil);
          },
          error: function(err) {
            console.error("AJAX error:", err);
          }
        });
      },
      minLength: 3,
      select: function(event, ui) {
        window.location.href = ui.item.url;
      }
    }).autocomplete("instance")._renderItem = function(ul, item) {
      return $("<li>")
        .append(`
          <div style="display: flex; align-items: center; gap: 10px; padding: 5px 0;">
            <img src="${item.image}" width="70" height="60" style="object-fit: cover; border-radius: 4px;">
            <div>
              ${item.jenis}
              <div><strong>${item.label}</strong></div>
              <small>${item.tanggal}</small>
            </div>
          </div>
        `)
        .appendTo(ul);
    };

    $("#form-komentar").validate({
      submitHandler: function (form) {
        const btn = $("#btn-kirim");
        btn.prop("disabled", true).text("Mengirim...");
        const formData = new FormData(form);
        $.ajax({
          url: `${base_url}/api/kirim-komentar`,
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (result) {
            if (result.status) {
              alert("Komentar berhasil dikirim, komentar anda akan diverifikasi terlebih dahulu oleh admin sebelum ditayangkan. Terima kasih");
              form.reset();
            } else {
              alert("Gagal mengirim komentar");
            }
          },
          error: function (xhr) {
            alert("Terjadi kesalahan saat mengirim komentar, hubungi admin");
          },
          complete: function () {
            btn.prop("disabled", false).text("Kirim sekarang");
          }
        });
      }
    });

    $('#komentar-berikutnya').click(function(){
      getKomentar();
    });
    
  });	
</script>
@endsection
