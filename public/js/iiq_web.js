'use strict';

function renderPengaturan(){
    const pengaturanWeb = JSON.parse(localStorage.getItem('pengaturanWeb'));    
    const logo = pengaturanWeb.logo!==null ? pengaturanWeb.logo : 'images/logo.png';
    const icon = pengaturanWeb.icon!==null ? pengaturanWeb.icon : 'images/icon.png';
    var judul = pengaturanWeb.nama.trim();
    var url;
    if (!document.title.includes(judul)) {
        judul += ' - ' + document.title.trim();
    }
    document.title = judul.trim();
    var url_wa=(pengaturanWeb.helpdesk)?`https://web.whatsapp.com/send?phone=${pengaturanWeb.helpdesk}`:"#";
    $('meta[name="description"]').attr('content', pengaturanWeb.deskripsi);
    $('meta[name="keywords"]').attr('content', pengaturanWeb.keywords);
    $('link[rel="shortcut icon"]').attr('href', base_url+icon);
    $('link[rel="apple-touch-icon"]').attr('href', base_url+icon);
    $('#web-alamat').text((pengaturanWeb.alamat)?pengaturanWeb.alamat:"Kendari, Sulawesi Tenggara");    
    $('#web-fb').attr('src', (pengaturanWeb.fb)?pengaturanWeb.fb:"#");    
    $('#web-ig').attr('src', (pengaturanWeb.ig)?pengaturanWeb.ig:"#");    
    $('#web-x').attr('src', (pengaturanWeb.twitter)?pengaturanWeb.twitter:"#");    
    $('#web-tiktok').attr('src', (pengaturanWeb.tiktok)?pengaturanWeb.tiktok:"#");    
    $('#web-youtube').attr('src', (pengaturanWeb.youtube)?pengaturanWeb.youtube:"#");    
    $('#web-wa').attr('href', url_wa);    
    $('#logo-web').attr('src', base_url+logo);    
    $('#nama-web').html(pengaturanWeb.nama);    
    $('.hp').html(`<i class="ti-mobile"></i> ${pengaturanWeb.helpdesk}`).attr('href',url_wa);    
    $('.email').html(`<i class="ti-email"></i> ${pengaturanWeb.email}`).attr('href',`mailto:${pengaturanWeb.email}`);    
}

async function init() {
    renderPengaturan();
}

$(document).ready(function() {
    init();  
});