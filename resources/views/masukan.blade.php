@extends('template_web')

@section('head')
    <title>Masukan - IIQ | Institut Ilmu Al Quran JA Kendari</title>    
    <link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/blog.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
      .list-ketentuan {
          padding-left: 1.2rem;
      }
      .list-ketentuan li {
          list-style-type: disc; /* pakai bullet bulat, atau bisa 'decimal' untuk angka */
      }
    </style>
@endsection

@section('container')
		<section id="hero_in" class="general">
			<div class="wrapper">
				<div class="container">
					<h1 class="fadeInUp blog-nama">Masukan</h1>
					<small id="blog-deskripsi">saran dan masukan yang positif masukan disini</small>
				</div>
			</div>
		</section>

		<div class="bg_color_1">
			<div class="container margin_60_35">
				<div class="row">
					<div class="col-md-12">
            <h5>Masukan Anda</h5>
            <form id="form-feedback">
              <div class="form-group">
                <input type="text" name="nama" id="nama" class="form-control" placeholder="nama" required>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="komentar" id="komentar" rows="6" placeholder="saran masukan anda" required></textarea>
              </div>

              <div class="p-3 mb-3">
                <h6 class="fw-bold">Ketentuan Pengiriman Saran dan Masukan:</h6>
                <ol class="list-ketentuan mt-2">
                    <li>Saran dan masukan diharapkan bersifat positif, konstruktif, dan membangun demi kemajuan kampus.</li>
                    <li>Saran dan masukan tidak akan dipublikasikan, hanya digunakan sebagai bahan pertimbangan oleh pimpinan kampus.</li>
                    <li>Semua masukan akan menjadi bahan evaluasi untuk meningkatkan layanan dan pengembangan kampus.</li>
                    <li>Dilarang mengirimkan saran yang mengandung provokasi, fitnah, atau melanggar etika dan peraturan kampus.</li>
                </ol>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="setuju" name="setuju" required>
                    <label class="form-check-label" for="setuju">
                        Saya setuju dengan ketentuan di atas.
                    </label>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" id="btn-kirim" class="btn_1 rounded add_bottom_30"> Kirim Sekarang</button>
              </div>
            </form>
          
          </div>
				</div>
			</div>
		</div>
		<!-- /bg_color_1 -->

		<!-- /container -->
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
  'use strict';

  $(document).ready(function() {

    $("#form-feedback").validate({
      submitHandler: function (form) {
        const btn = $("#btn-kirim");
        btn.prop("disabled", true).text("Mengirim...");
        const formData = new FormData(form);
        $.ajax({
          url: `${base_url}/api/simpan-kotak-saran`,
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (result) {
            if (result.status) {
              alert("Feedback anda berhasil dikirim, semoga masukannya bisa membuat kampus kita semakin maju. Terima kasih");
              form.reset();
            } else {
              alert("Gagal mengirim feedback");
            }
          },
          error: function (xhr) {
            alert("Terjadi kesalahan saat mengirim feedback, hubungi admin");
          },
          complete: function () {
            btn.prop("disabled", false).text("Kirim sekarang");
          }
        });
      }
    });

    
  });	
</script>
@endsection
