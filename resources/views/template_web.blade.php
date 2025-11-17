<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>IIQ JA Kendari</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Website resmi Institut Ilmu Al Quran Jannatu Adnin Kendari">
	<meta name="keywords" content="Institut Ilmu Al Quran Jannatu Adnin Kendari, IIQ, Kendari">
	<meta name="author" content="Admin IIQ Kendari">

	<!-- Untuk gambar preview (WhatsApp, FB) -->
    <meta property="og:title" content="IIQ JA Kendari" />
    <meta property="og:description" content="Website resmi Institut Ilmu Al Quran Jannatu Adnin Kendari" />
    <meta property="og:image" content="{{ url('images/logo.png') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
	

	<!-- Favicons-->
	<link rel="shortcut icon" href="{{ url('images/logo.png') }}" type="image/x-icon">
	<link rel="apple-touch-icon" type="image/x-icon" href="{{ url('images/logo.png') }}">

	<!-- GOOGLE WEB FONT -->
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<!-- BASE CSS -->
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/bootstrap.min.css" rel="stylesheet">
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/style.css" rel="stylesheet">
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/vendors.css" rel="stylesheet">
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/icon_fonts/css/all_icons.min.css" rel="stylesheet">

	<!-- SPECIFIC CSS -->
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/layerslider/css/layerslider.css" rel="stylesheet">	
	<link href="{{url('template/udema_v_3.3/html_menu_1')}}/css/custom.css" rel="stylesheet">
	<style>
		.loading-progress {
			position: fixed;
			top: 10px;
			right: 10px;
			background: rgba(51, 51, 51, 0.9);
			color: #fff;
			padding: 6px;
			border-radius: 6px;
			font-weight: bold;
			font-size: 14px;
			z-index: 99999;
			display: none; 
			box-shadow: 0 2px 6px rgba(0,0,0,0.3);
			transition: opacity 0.3s ease;
		}
		.loading-progress img {
			height: 30px;
		}
	</style>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@yield('head')
	<script>
		const base_url="{{ url('/') }}";
	</script>
</head>

<body>
	<div class="loading-progress">Loading <img src="{{ url('images/loading-2.gif') }}"></div>
	<div id="page">

		<header class="header menu_2">
			<div id="preloader">
				<div data-loader="circle-side"></div>
			</div><!-- /Preload -->
			<div id="logo">
				<a href="{{ url('/') }}"><img src="{{ url('images/logo-web.png') }}" width="160" height="42" alt=""></a>
			</div>
			<ul id="top_menu">
				<li><a href="{{ route('login') }}" class="login">Login</a></li>
				<li><a href="#0" class="search-overlay-menu-btn">Search</a></li>
				<li class="hidden_tablet"><a href="https://iiq-jakendari.ac.id" class="btn_1 rounded">Admisi</a></li>
			</ul>
			<!-- /top_menu -->
			<a href="#menu" class="btn_mobile">
				<div class="hamburger hamburger--spin" id="hamburger">
					<div class="hamburger-box">
						<div class="hamburger-inner"></div>
					</div>
				</div>
			</a>
			<nav id="menu" class="main-menu">
				<ul>
					<li><span><a href="#0">Profil</a></span>
						<ul>
							<li><a href="{{ url('read/pojok-rektor/sambutan-rektor') }}">Sambutan Rektor</a></li>
							<li><a href="{{ url('read/profil/yayasan') }}">Yayasan IIQ JA Kendari</a></li>
							<li><a href="{{ url('read/profil/sejarah') }}">Sejarah</a></li>
							<li><a href="{{ url('read/profil/visi-misi') }}">Visi, Misi dan Tujuan</a></li>
							<li><a href="{{ url('read/profil/lambang-atribut') }}">Lambang & Atribut</a></li>
							<li><a href="{{ url('read/profil/mars-lembaga') }}">Mars Lembaga</a></li>
							<li><a href="{{ url('dokumen/struktur-organisasi') }}">Struktur Organisasi</a></li>
							<li><a href="{{ url('sdm/pimpinan') }}">Unsur Pimpinan</a></li>
							<li><a href="{{ url('sdm/dosen') }}">Dosen Pengajar</a></li>
							<li><a href="{{ url('sdm/administrasi') }}">Tenaga Kependidikan</a></li>
						</ul>
					</li>
					<li><span><a href="#0">Pendidikan</a></span>
						<ul>
							<li><a href="{{ url('dokumen/akreditasi') }}">Akreditasi</a></li>
							<li><span><a href="#0">Program Studi</a></span>
								<ul>
									<li><a href="{{ url('read/program-studi/as') }}">Ahwal Al-Syakhshiyyah</a></li>
									<li><a href="{{ url('read/program-studi/iqt') }}">Ilmu Al-Qur`an dan Tafsir</a></li>
									<li><a href="{{ url('read/program-studi/pba') }}">Pendidikan Bahasa Arab</a></li>
								</ul>
							</li>
							<li><a href="{{ url('artikel/pmb') }}">Penerimaan Mahasiswa Baru</a></li>
							<li><a href="{{ url('artikel/biaya-pendidikan') }}">Biaya Pendidkan</a></li>
							<li><a href="{{ url('artikel/kalender-akademik') }}">Kalender Akademik</a></li>
						</ul>
					</li>

					

					<li><span><a href="#0">Tulisan & Pengabdian</a></span>
						<ul>
							<li><a href="{{ url('artikel/pojok-rektor') }}">Pojok Rektor</a></li>
							<li><a href="{{ url('artikel/pojok-dosen') }}">Pojok Dosen</a></li>
							<li><a href="https://rumahjurnal.iiq-jakendari.ac.id">Open Journal System (OJS)</a></li>
							<li><a href="https://repository.iiq-jakendari.ac.id">Repository</a></li>
						</ul>
					</li>
					<li><span><a href="#0">Publikasi</a></span>
						<ul>
							<li><a href="{{ url('artikel/berita') }}">Berita</a></li>
							<li><a href="{{ url('artikel/pengumuman') }}">Pengumuman</a></li>
							<li><a href="{{ url('galeri') }}">Galeri</a></li>
							<li><span><a href="#0">Download</a></span>
								<ul>
									<li><a href="{{ url('dokumen/akreditasi') }}">Akreditasi</a></li>
									<li><a href="{{ url('dokumen/download') }}">Arsip Dokumen Web</a></li>
									<li><a href="{{ url('dokumen/peraturan') }}">Peraturan</a></li>
									<li><a href="{{ url('dokumen/struktur-organisasi') }}">Struktur Organisasi</a></li>
								</ul>
							</li>

						</ul>
					</li>
					<li><span><a href="{{ url('feedback') }}">Feedback</a></span></li>
				</ul>
			</nav>
			<!-- Search Menu -->
			<div class="search-overlay-menu">
				<span class="search-overlay-close"><span class="closebt"><i class="ti-close"></i></span></span>
				<form role="search" id="searchform" method="get">
					<input value="" name="q" type="search" placeholder="Search..." />
					<button type="submit"><i class="icon_search"></i>
					</button>
				</form>
			</div><!-- End Search Menu -->
		</header>
		<!-- /header -->

		<main>
			@yield('container')			
		</main>
		<!-- /main -->

		<footer>
			<div class="container margin_120_95">
				<div class="row justify-content-between">
					<div class="col-lg-5 col-md-12">
						<p><img src="{{ url('images/logo-web.png') }}" width="160" height="42" alt=""></p>
						<p id="web-alamat">Kendari Sulawesi Tenggara</p>
						<div class="follow_us">
							<ul>
								<li>Follow us</li>
								<li><a href="#" id="web-fb" target="_blank"><i class="bi bi-facebook"></i></a></li>
								<li><a href="#" id="web-x" target="_blank"><i class="bi bi-twitter-x"></i></a></li>
								<li><a href="#" id="web-ig" target="_blank"><i class="bi bi-instagram"></i></a></li>
								<li><a href="#" id="web-tiktok" target="_blank"><i class="bi bi-tiktok"></i></a></li>
								<li><a href="#" id="web-youtube" target="_blank"><i class="bi bi-youtube"></i></a></li>
								<li><a href="#" id="web-wa" target="_blank"><i class="bi bi-whatsapp"></i></a></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-3 col-md-6 ml-lg-auto">
						<h5>Useful links</h5>
						<div class="custom-code-html" data-slug="link-terkait"></div>
					</div>
					<div class="col-lg-3 col-md-6">
						<h5>Contact with Us</h5>
						<ul class="contacts">
							<li><a href="https://web.whatsapp.com/send?phone=+6281217081329" target="_blank" class="hp"><i class="ti-mobile"></i> +6281217081329</a></li>
							<li><a href="mailto:info@iiq-jakendari.ac.id" class="email"><i class="ti-email"></i> info@iiq-jakendari.ac.id</a></li>
						</ul>
						{{-- <div id="newsletter">
							<h6>Newsletter</h6>
							<div id="message-newsletter"></div>
							<form method="post" action="assets/newsletter.php" name="newsletter_form" id="newsletter_form">
								<div class="form-group">
									<input type="email" name="email_newsletter" id="email_newsletter" class="form-control" placeholder="Your email">
									<input type="submit" value="Submit" id="submit-newsletter">
								</div>
							</form>
						</div> --}}
					</div>
				</div>
				<!--/row-->
				<hr>
				<div class="row">
					<div class="col-md-8">
						<ul id="additional_links">
							<li><a href="#0">Terms and conditions</a></li>
							<li><a href="#0">Privacy</a></li>
						</ul>
					</div>
					<div class="col-md-4">
						<div id="copy">© Udema</div>
					</div>
				</div>
			</div>
		</footer>
		<!--/footer-->
	</div>
	<!-- page -->
	<script>
		var pengaturan_web=localStorage.getItem('pengaturan_web');
	</script>
	<!-- COMMON SCRIPTS -->
	<script src="{{url('template/udema_v_3.3/html_menu_1')}}/js/jquery-3.7.1.min.js"></script>
	<script src="{{url('template/udema_v_3.3/html_menu_1')}}/js/common_scripts.js"></script>
	<script src="{{url('template/udema_v_3.3/html_menu_1')}}/js/main.js"></script>
	<script src="{{url('template/udema_v_3.3/html_menu_1')}}/assets/validate.js"></script>

	<script src="{{ asset('js/myapp.js?v=7') }}"></script>
	<script src="{{url('/js/iiq_web.js?v=7')}}"></script>
	<script>
	$(document)
		.ajaxStart(function () {
			$(".loading-progress").fadeIn(200);
			$('button[type="submit"], input[type="submit"]').prop('disabled', true);
		})
		.ajaxStop(function () {
			$(".loading-progress").fadeOut(200);
			$('button[type="submit"], input[type="submit"]').prop('disabled', false);
		})
		.ajaxError(function () {
			$(".loading-progress").fadeOut(200);
			$('button[type="submit"], input[type="submit"]').prop('disabled', false);
		});

	</script>
	@yield('script')
</body>

</html>