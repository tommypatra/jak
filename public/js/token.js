var authToken=localStorage.getItem('access_token');
$.ajaxSetup({
    headers: {
        'Authorization': 'Bearer ' + authToken
    }
});

function tokenCek() {
    var akses_grup = localStorage.getItem('akses_grup');
    $.ajax({
        url: base_url + '/' + 'api/token-cek/'+akses_grup,
        method: 'get',
        success: function(response) {
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                forceLogout('Akses ditolak! login kembali');
            } else {
                alert('operasi gagal dilakukan!');
            }
        }
    });
}
