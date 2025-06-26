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
				<aside class="col-lg-3" id="sidebar">
					<div id="filters_col"> <a data-bs-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt">Dokumen Web</a>
						<div class="collapse show" id="collapseFilters">
              <div class="filter_type">
                  <input type="text" name="search-konten" id="search-konten" class="form-control mt-2" placeholder="Search...">
              </div>

							<div class="filter_type">
								<h6>Jenis</h6>
								<ul id="jumlah-jenis-konten"></ul>
							</div>
						</div>
					</div>
				</aside>
				<!-- /aside -->

				<div class="col-lg-9" id="list_sidebar">
          <div id="list-file"></div>				
				</div>
			</div>
		</div>
		<!-- /container -->

		<div class="bg_color_1">
			<div class="container margin_60_35">
				<div class="row">
					<div class="col-md-12">
            <h5>Kirim Komentar Anda</h5>
            <form id="form-komentar">
              <input type="hidden" name="file_id" id="file_id">
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
				</div>
			</div>
		</div>
		<!-- /bg_color_1 -->

		<!-- /container -->
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('js/pagination.js') }}"></script>
<script type="text/javascript">
  const jenis="{{ $slug_kategori }}";
  const judul="{{ $slug_judul }}";
  'use strict';

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

  async function read(){ 
    try {
      const response = await fetch(`${base_url}/api/list-file?jenis=${jenis}&slug=${judul}`);
      const dataResponse = await response.json();
      const row = $("#list-file");
      row.empty(); 
      if (dataResponse.data.length > 0) {
            const konten=dataResponse.data[0];

            const tanggal = new Date(konten.waktu);
            const tgl = tanggal.getDate();
            const bln = tanggal.toLocaleString('default', { month: 'short' });
            const tglLengkap = tanggal.toLocaleDateString('id-ID');
            const url=`${base_url}${konten.path}`;
            const cover=(konten.cover)?konten.cover:"/images/dokumen.png";
            
            const html = `
              <div class="box_list wow">
                <div class="row g-0">
                  <div class="col-lg-5">
                    <figure class="block-reveal">
                      <div class="block-horizzontal"></div>
                      <a href="${url}" target="_blank"><img src="${base_url}${cover}" alt=""></a>
                      <div class="preview"><span>dokumen download</span></div>
                    </figure>
                  </div>
                  <div class="col-lg-7">
                    <div class="wrapper">
                      <a href="#0" class="wish_bt"></a>
                      <div class="mt-4">
                        <a href="${base_url}/dokumen/${konten.jenis_konten_slug}">
                          <small>${konten.jenis_konten_nama}</small>
                        </a>
                      </div>
                      <a href="${url}" target="_blank">
                        <h4>${konten.judul}</h4>
                      </a>
                      <p>${konten.deskripsi}</p>
                    </div>
                    <ul>
                      <li><i class="icon_clock_alt"></i> ${tglLengkap}</li>
                      <li><i class="icon_like"></i> ${konten.likedislike_count}</li>
                      <li><i class="icon_comment"></i> ${konten.komentar_count}</li>
                      <li><i class="bi bi-eye"></i> ${konten.jumlah_akses+1}</li>
                      <li><a href="${url}" target="_blank">DOWNLOAD</a></li>
                    </ul>
                  </div>
                </div>
              </div>`;
            $('#file_id').val(konten.id);
            row.append(html);
      }
    } catch (error) {
      console.error(error);
    }
  }  		

  async function updateJumlahAksesKonten(){ 
    try {
      const file_id = $('#file_id').val();
      const response = await fetch(`${base_url}/api/update-jumlah-akses-file/${file_id}`);
      const dataResponse = await response.json();
    } catch (error) {
      console.error(error);
    }
  }


  async function getKomentar() {
    try {
      const page = parseInt($('#komentar-berikutnya').attr('data-page'));
      const file_id=$('#file_id').val();       
      const response = await fetch(`${base_url}/api/get-komentar?page=${(page+1)}&publikasi=1&file_id=${file_id}&limit=10`);
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
      await jumlahJenisKonten();
      await updateJumlahAksesKonten();
    }

    $("#search-konten").autocomplete({
      source: function(request, response) {
        $.ajax({
          url: `${base_url}/api/list-file`,
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
              image: item.thumbnail ?? 'images/default.png',
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
