<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials_head')
  @yield('head')
  <script>
    var base_url = "{{ url('/') }}";
  </script>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('/dashboard') }}">Dashboard Website</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      </button>
      <ul class="navbar-nav menu-akun" style="display:none;">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Halaman Depan Web</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ url('dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Konten
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="{{ route('konten-dashboard') }}">Artikel</a></li>
            <li><a class="dropdown-item" href="{{ route('file-dashboard') }}">File</a></li>
            <li><a class="dropdown-item" href="{{ route('galeri-dashboard') }}">Galeri</a></li>
            <li><a class="dropdown-item" href="{{ route('html-code') }}">Html Code</a></li>
            <li><a class="dropdown-item" href="{{ route('slide-show') }}">Slide Show</a></li>
            <li><a class="dropdown-item" href="{{ route('short-link') }}">Short Link</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown menu-editor" style="display:none;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarEditor" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Editor
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarEditor">
            <li><a class="dropdown-item" href="{{ route('verifikasi-konten') }}">Verifikasi Konten</a></li>
            <li><a class="dropdown-item" href="{{ route('verifikasi-file') }}">Verifikasi File</a></li>
            <li><a class="dropdown-item" href="{{ route('verifikasi-galeri') }}">Verifikasi Galeri</a></li>
            <li><a class="dropdown-item" href="{{ route('verifikasi-komentar') }}">Verifikasi Komentar</a></li>
            <li><a class="dropdown-item" href="{{ route('kotak-saran') }}">Kotak Saran</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown menu-admin" style="display:none;">
          <a class="nav-link dropdown-toggle" href="#" id="navbarAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Administrator
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarAdmin">
            <li><a class="dropdown-item" href="{{ route('menu') }}">Menu</a></li>
            <li><a class="dropdown-item" href="{{ route('unit-kerja') }}">Unit Kerja</a></li>
            <li><a class="dropdown-item" href="{{ route('jabatan') }}">Jabatan</a></li>
            <li><a class="dropdown-item" href="{{ route('pengaturan-web') }}">Pengaturan Web</a></li>
            <li><a class="dropdown-item" href="{{ route('jenis-konten') }}">Jenis Konten</a></li>
            <hr class="dropdown-divider">
            <li><a class="dropdown-item" href="{{ route('grup') }}">Grup</a></li>
            <li><a class="dropdown-item" href="{{ route('akun') }}">Pegawai/Akun</a></li>
          </ul>
        </li>
        <li class="nav-item menu-ganti-akses" style="display:none;">
          <a class="nav-link" href="javascript:;" onclick="gantiAkses()">Ganti Akses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="javascript:;" onclick="forceLogout()">Keluar</a>
        </li>
      </ul>
    </div>
    </div>
  </nav>

  <!-- Main content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-10 mx-auto"> <!-- Menggunakan mx-auto untuk membuat konten berada di tengah -->
          @yield('container')
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="aksesModal" tabindex="-1" aria-labelledby="aksesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <ul class="nav-item dropdown daftar-akses">
            </li>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  @include('partials_footer')
  <script src="{{ asset('js/token.js?v=5') }}"></script>
  <script src="{{ asset('js/myapp.js?v=5') }}"></script>
  <script>
    var akses_grup = localStorage.getItem('akses_grup');
    var daftar_akses = JSON.parse(localStorage.getItem('daftar_akses'));

    $(document).ajaxStart(function() {
      // Disable semua tombol submit
      $('button[type="submit"], .btn-submit').prop('disabled', true);
    });

    $(document).ajaxStop(function() {
      // Enable kembali setelah semua ajax selesai
      $('button[type="submit"], .btn-submit').prop('disabled', false);
    });

    if (akses_grup) {
      $('.menu-akun').show();

      if (akses_grup == 1) {
        $('.menu-admin').show();
        $('.menu-editor').show();
      } else if (akses_grup == 2) {
        $('.menu-editor').show();
      }
      // console.log(daftar_akses);
      if (daftar_akses.length > 1) {
        $('.menu-ganti-akses').show();
        $.each(daftar_akses, function(index, item) {
          var listItem = `<li><a href='#' onclick="setAkses(${item.grup.id})">${item.grup.nama}</a></li>`;
          $('.daftar-akses').append(listItem);
        });
      }
    }

    function setAkses(id) {
      localStorage.setItem('akses_grup', id);
      toastr.success('set akses berhasil, akan diarahkan ke halaman dashboard!', 'berhasil', {
        timeOut: 1000
      });
      var goUrl = `{{ url('/dashboard') }}`;
      window.location.replace(goUrl);
    }

    function gantiAkses() {
      var myModalAkses = new bootstrap.Modal(document.getElementById('aksesModal'), {
        keyboard: false
      });
      myModalAkses.show();

    };

    function forceLogout(pesan) {
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url: base_url + '/web-logout',
        type: 'post',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
          console.log(respose);
        },
      });
      localStorage.clear();
      if (pesan)
        alert(pesan);
      window.location.replace(base_url + '/login');
    }
    // tokenCek();

    var akses_grup = localStorage.getItem('akses_grup');
    if (akses_grup) {
        tokenCek();
    }

    sesuaikanPengaturan();
  </script>
  @yield('script')

</body>

</html>