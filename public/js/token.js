var authToken=localStorage.getItem('access_token');
$.ajaxSetup({
    headers: {
        'Authorization': 'Bearer ' + authToken
    }
});

function tokenCek() {
    var akses_grup = localStorage.getItem('akses_grup');
    var currentPath = window.location.pathname;

    // Jika akses_grup tidak ada
    if (!akses_grup) {
        if (currentPath === '/login') {
            return;
        }
        forceLogout('Sesi tidak ditemukan. Silakan login kembali.');
        return;
    }

    $.ajax({
        url: base_url + '/' + 'api/token-cek/'+akses_grup,
        method: 'get',
        async: false,
        success: function(response) {
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            forceLogout('Silahkan login kembali');
        }
    });
}
