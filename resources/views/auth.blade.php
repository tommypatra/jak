@extends('template_dashboard')

@section('head')
<title>Login</title>
<style>
    #logo-web-container {
        text-align: center;
        /* Mengatur agar kontainer gambar menjadi pusat */
    }

    #logo-web {
        display: inline-block;
        /* Agar gambar bisa diatur dengan margin */
        margin: auto;
        /* Mengatur margin secara otomatis untuk membuat gambar menjadi pusat */
    }
</style>
@endsection

@section('container')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div id="logo-web-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="50" height="50" id="logo-web">
                </div>
                <h3 class="card-title text-center">Login</h3>
                <h4 class="card-title text-center mb-4">Administrator Website</h4>
                <form id="myform">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control  w-100" id="email" name="email" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control  w-100" id="password" name="password" required minlength="8">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/myapp.js') }}"></script>
{{-- <script src="{{ asset('js/token.js') }}"></script> --}}
<script>
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var goUrl = `{{ url('/dashboard') }}`;

    var akses_grup = localStorage.getItem('akses_grup');
    if (akses_grup) {
        toastr.success('sesi login anda masih aktif!', 'login berhasil', {
            timeOut: 1000
        });
        window.location.replace(goUrl);
    }

    $("#myform").validate({
        submitHandler: function(form) {
            $.ajax({
                url: base_url + '/' + 'api/auth-cek',
                type: 'post',
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    toastr.success('login berhasil, proses set session!', 'login berhasil', {
                        timeOut: 1000
                    });
                    localStorage.setItem('access_token', response.access_token);
                    // localStorage.setItem('daftar_akses', response.daftar_akses);
                    localStorage.setItem('daftar_akses', JSON.stringify(response.daftar_akses));
                    localStorage.setItem('akses_grup', response.akses_grup);
                    // setSession(form);
                    window.location.replace(goUrl);
                },
                error: function() {
                    alert('login gagal, user atau password anda salah!');
                }
            });
        }
    });

    function setSession(form) {
        $.ajax({
            url: base_url + '/' + 'set-session',
            type: 'POST',
            data: $(form).serialize(),
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                toastr.success('set session berhasil, akan diarahkan ke halaman dashboard!', 'login berhasil', {
                    timeOut: 1000
                });
                var goUrl = `{{ url('/dashboard') }}`;
                window.location.replace(goUrl);
            },
            error: function(error) {
                toastr.danger('set session gagal, hubungi admin!', 'login gagal');
                console.error(error);
            }
        });
    }
</script>
@endsection