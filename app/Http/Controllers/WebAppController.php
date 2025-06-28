<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WebAppController extends Controller
{

    public function setSession(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi email dan password tidak valid',
            ], 404);
        }
        $user = Auth::user();
        Auth::login($user);
        $respon_data = [
            'message' => 'Proses login selesai dilakukan',
        ];
        return response()->json($respon_data, 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function session()
    {
        dd(auth()->user());
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function web()
    {
        return view('website');
    }

    public function jenisKonten()
    {
        return view('admin.jenis_konten');
    }

    public function grup()
    {
        return view('admin.grup');
    }

    public function pengaturanWeb()
    {
        return view('admin.pengaturan_web');
    }

    public function akun()
    {
        return view('admin.akun');
    }

    public function menu()
    {
        return view('admin.menu');
    }

    public function kontenDashboard()
    {
        return view('admin.konten_dashboard');
    }

    public function galeriDashboard()
    {
        return view('admin.galeri_dashboard');
    }



    public function masukan()
    {
        return view('masukan');
    }

    public function galeri()
    {
        return view('galeri');
    }

    public function fileDashboard()
    {
        return view('admin.file_dashboard');
    }

    public function verifikasiKonten()
    {
        return view('admin.verifikasi_konten');
    }

    public function verifikasiGaleri()
    {
        return view('admin.verifikasi_galeri');
    }

    public function verifikasiFile()
    {
        return view('admin.verifikasi_file');
    }

    public function verifikasiKomentar()
    {
        return view('admin.verifikasi_komentar');
    }

    public function htmlCode()
    {
        return view('admin.html_code');
    }

    public function slideShow()
    {
        return view('admin.slide_show');
    }

    public function kotakSaran()
    {
        return view('admin.kotak_saran_dashboard');
    }

    public function shortLink()
    {
        return view('admin.short_link');
    }
    public function login()
    {
        return view('auth');
    }

    public function webold()
    {
        return view('website');
    }

    public function kotakSaranWeb()
    {
        return view('kotak_saran_web');
    }

    public function kontenWeb($kategori)
    {
        return view('konten_web', ['kategori' => $kategori]);
    }

    public function galeriWeb()
    {
        return view('galeri_web');
    }

    public function fileWeb($kategori)
    {
        return view('file_web', ['kategori' => $kategori]);
    }

    public function kontenReadSlug($slug_kategori, $slug_judul)
    {
        return view('konten_read', ['slug_kategori' => $slug_kategori, 'slug_judul' => $slug_judul]);
    }

    public function downloadDokumenSlug($slug_kategori, $slug_judul)
    {
        return view('download_dokumen', ['slug_kategori' => $slug_kategori, 'slug_judul' => $slug_judul]);
    }

    public function fileRead($slug)
    {
        return view('file_read', ['slug' => $slug]);
    }

    public function listKontenSlug($slug_kategori)
    {
        return view('list_konten', ['slug_kategori' => $slug_kategori]);
    }

    public function listDokumenSlug($slug_kategori)
    {
        return view('list_dokumen', ['slug_kategori' => $slug_kategori]);
    }
}
