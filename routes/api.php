<?php

use App\Models\SlideShow;
use App\Models\LikeDislike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontenController;
use App\Http\Controllers\WebAppController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\AturGrupController;
use App\Http\Controllers\HtmlCodeController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\SlideShowController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\KotakSaranController;
use App\Http\Controllers\ImageEditorController;
use App\Http\Controllers\JenisKontenController;
use App\Http\Controllers\LikeDislikeController;
use App\Http\Controllers\PengaturanWebController;
use App\Http\Controllers\ProfilController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth-cek', [AuthController::class, 'index']);
Route::post('/simpan-komentar', [KomentarController::class, 'store']);

Route::post('/simpan-kotak-saran', [KotakSaranController::class, 'store']);

Route::get('/info-web', [PengaturanWebController::class, 'index']);
Route::get('/load-menu-tree', [MenuController::class, 'getMenu']);
Route::get('/daftar', [KontenController::class, 'index']);
Route::get('/get-jenis-konten', [JenisKontenController::class, 'index']);
Route::get('/get-menu', [MenuController::class, 'index']);
Route::get('/get-komentar', [KomentarController::class, 'index']);
Route::get('/get-pengaturan-web', [PengaturanWebController::class, 'index']);

Route::get('/list-konten', [KontenController::class, 'index']);
Route::get('/list-file', [FileController::class, 'index']);
Route::get('/list-galeri', [GaleriController::class, 'index']);
Route::get('/list-jenis-konten', [JenisKontenController::class, 'index']);
Route::get('/jumlah-jenis-publikasi/{kategori}', [JenisKontenController::class, 'jumlahJenisPublikasi']);

Route::post('/kirim-komentar', [KomentarController::class, 'store']);


Route::get('/update-jumlah-akses-konten/{id}', [KontenController::class, 'updateJumlahAkses']);
Route::get('/update-jumlah-akses-file/{id}', [FileController::class, 'updateJumlahAkses']);

Route::get('/get-html-code', [HtmlCodeController::class, 'index']);
Route::get('/get-slide-show', [SlideShowController::class, 'index']);

Route::get('/like', [LikeDislikeController::class, 'index']);
Route::post('/like', [LikeDislikeController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/token-cek/{grup_id}', [AuthController::class, 'tokenCek']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/get-pegawai', [AkunController::class, 'index']);

    Route::resource('/profil-pegawai', ProfilController::class);

    Route::post('/upload-image-editor', [ImageEditorController::class, 'upload']);
    Route::resource('konten', KontenController::class);
    Route::resource('file', FileController::class);
    Route::resource('galeri', GaleriController::class);
    Route::resource('komentar', KomentarController::class);
    Route::resource('short-link', ShortLinkController::class);
    Route::resource('html-code', HtmlCodeController::class);
    Route::resource('slide-show', SlideShowController::class);
    Route::resource('kotak-saran', KotakSaranController::class);

    Route::middleware(['is.admin'])->group(function () {
        Route::resource('menu', MenuController::class);
        Route::resource('publikasi', PublikasiController::class);
        Route::resource('unit-kerja', UnitKerjaController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::resource('jenis-konten', JenisKontenController::class);
        Route::resource('grup', GrupController::class);
        Route::resource('pengaturan-web', PengaturanWebController::class);
        Route::resource('akun', AkunController::class);
        Route::resource('atur-grup', AturGrupController::class);
    });
});
