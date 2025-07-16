var authToken=localStorage.getItem('access_token');
$.ajaxSetup({
    headers: {
        'Authorization': 'Bearer ' + authToken
    }
});

function tokenCek() {
    var akses_grup = localStorage.getItem('akses_grup');
    // alert(akses_grup);
    $.ajax({
        url: base_url + '/' + 'api/token-cek/'+akses_grup,
        method: 'get',
        async: false,
        success: function(response) {
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401) {
                forceLogout('Silahkan login kembali');
            } else {
                alert('gagal dilakukan!');
            }
        }
    });
}