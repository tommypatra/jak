function myFormatDate(dateString){
    var date = new Date(dateString);

    // Periksa apakah objek Date valid
    if (isNaN(date.getTime())) {
        return 'Invalid Date';
    }    
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function getPengaturanWeb() {
    var pengaturanWeb = localStorage.getItem('pengaturanWeb');
    return pengaturanWeb ? JSON.parse(pengaturanWeb) : null;
}

function myLabel(text=null) {
    return text ? text : "";
}

function sesuaikanPengaturan(){
    const pengaturanWeb = JSON.parse(localStorage.getItem('pengaturanWeb'));    
 
    const logo = pengaturanWeb.logo!==null ? pengaturanWeb.logo : 'images/logo.png';
    const icon = pengaturanWeb.icon!==null ? pengaturanWeb.icon : 'images/icon.png';
    var judul = pengaturanWeb.nama.trim();
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



async function getInfo() {
    try {
        const response = await fetch(`${base_url}/api/info-web`);
        const responseJson = await response.json();

        if (responseJson.data.length > 0) {
            localStorage.setItem('pengaturanWeb', JSON.stringify(responseJson.data[0]));
            sesuaikanPengaturan();
        }
    } catch (error) {
        console.error(error);
    }
}

async function htmlCode(slug,element) {
    $(element).html(slug + ' tidak ditemukan');
    try {
        const response = await fetch(`${base_url}/api/get-html-code?web=1&slug=${slug}`);
        const result = await response.json();
        if (result.data.length > 0) {
            $(element).html(result.data[0].code);
        }
    } catch (error) {
        console.error(error);
    }
}  

async function renderCustomHtml() {
    const elements = document.querySelectorAll('.custom-code-html');
    for (const el of elements) {
        const slug = el.dataset.slug;
        await htmlCode(slug, el);
    }
}

$(document).ready(function() {
    init();			
    async function init(){
        await getInfo();
        await renderCustomHtml();
    }
});