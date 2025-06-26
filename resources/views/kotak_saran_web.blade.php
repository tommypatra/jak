@extends('template_website')

@section('head')
    <title>Kotak Saran Pengunjung</title>
@endsection

@section('container')

    <h1 id="konten-judul">Kotak Saran Pengunjung</h1>

    <div class="row">
        <form id="form-kotak-saran">
            <div class="col-sm-7 mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control w-100" id="nama" name="nama" required>
            </div>
            <div class="col-sm-12 mb-3">
                <label for="komentar" class="form-label">Komentar Anda</label>
                <textarea class="form-control w-100" id="komentar" name="komentar" rows="4" required></textarea>
            </div>
            <div class="col-sm-4 mb-3">
                <button type="submit" class="btn btn-primary">Kirim Saran</button>
                <button type="button" class="btn btn-warning btnBatal">Batal</button>
            </div>
        </form>                    
    </div>

@endsection

@section('script')

<script>
$(document).ready(function() {

    function resetForm(){
        $('#form-kotak-saran input').val('');
        $('#form-kotak-saran')[0].reset();
    }

    $('.btnBatal').on('click', function(e) {
        resetForm();
    });    

    $("#form-kotak-saran").validate({
        submitHandler: function(form) {
            $.ajax({
                url: base_url+'/api/simpan-kotak-saran',
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    toastr.success('saran anda  berhasil terkirim, terima kasih atas partisipasinya!', 'berhasil');
                    resetForm();
                },
                error: function() {
                    alert('gagal dilakukan!');
                }
            });
        }
    });

});

</script>
@endsection
