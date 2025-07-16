<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\File;
use App\Models\Grup;
use App\Models\Menu;
use App\Models\User;
use App\Models\Konten;
use App\Models\Jabatan;
use App\Models\AturGrup;
use App\Models\HtmlCode;
use App\Models\Komentar;
use App\Models\Publikasi;
use App\Models\SlideShow;
use App\Models\UnitKerja;
use App\Models\KotakSaran;
use App\Models\JenisKonten;
use App\Models\LikeDislike;
use App\Models\PengaturanWeb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// use Faker\Provider\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $factory = Factory::create();
        //untuk admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@app.com', //email login
            'password' => Hash::make('00000000'), // password default login 
        ]);

        //untuk pengguna
        for ($i = 1; $i <= 9; $i++) {
            User::create([
                'name' => 'Pengguna ' . $i,
                'email' => 'pengguna' . $i . '@app.com', //email login
                'password' => Hash::make('00000000'), // password default login 
            ]);
        }

        //untuk jenis konten
        $dtdef = [
            ['user_id' => 1, 'nama' => 'Profil', 'slug' => 'profil', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Profil website'],
            ['user_id' => 1, 'nama' => 'Pojok Rektor', 'slug' => 'pojok-rektor', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Tulisan rektor kita'],
            ['user_id' => 1, 'nama' => 'Berita', 'slug' => 'berita', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Daftar berita website'],
            ['user_id' => 1, 'nama' => 'Program Studi', 'slug' => 'program-studi', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Program studi IIQ JA Kendari'],
            ['user_id' => 1, 'nama' => 'Pengumuman', 'slug' => 'pengumuman', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Daftar pengumuman website'],
            ['user_id' => 1, 'nama' => 'Testimoni', 'slug' => 'testimoni', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Kata mereka tentang kami'],
            ['user_id' => 1, 'nama' => 'Penerimaan Mahasiswa Baru', 'slug' => 'pmb', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Semua tentang penerimaan mahasiswa baru IIQ JA Kendari'],
            ['user_id' => 1, 'nama' => 'Biaya Pendidikan', 'slug' => 'biaya-pendidikan', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Biaya pendidikan setiap angkatan'],
            ['user_id' => 1, 'nama' => 'Kalender Akademik', 'slug' => 'kalender-akademik', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Data kalender akademik aktivitas kami'],

            ['user_id' => 1, 'nama' => 'Download', 'slug' => 'download', 'kategori' => 'FILE', 'deskripsi' => 'Daftar download pada website'],
            ['user_id' => 1, 'nama' => 'Peraturan', 'slug' => 'peraturan', 'kategori' => 'FILE', 'deskripsi' => 'Daftar peraturan pada website'],
            ['user_id' => 1, 'nama' => 'Akreditasi', 'slug' => 'akreditasi', 'kategori' => 'FILE', 'deskripsi' => 'Akreditasi institut dan program studi IIQ JA Kendari'],
            ['user_id' => 1, 'nama' => 'Struktur Organisasi', 'slug' => 'struktur-organisasi', 'kategori' => 'FILE', 'deskripsi' => 'Dokumen struktur organisasi IIQ JA Kendari'],
        ];

        foreach ($dtdef as $dt) {
            JenisKonten::create([
                'user_id' => $dt['user_id'],
                'nama' => $dt['nama'],
                'slug' => $dt['slug'],
                'kategori' => $dt['kategori'],
                'deskripsi' => $dt['deskripsi'],
            ]);
        }

        //untuk menu
        $dtdef = [
            ['user_id' => 1, 'urut' => 1, 'nama' => 'Halaman Depan', 'url' => '/'],
            ['user_id' => 1, 'urut' => 2, 'nama' => 'Profil'],
            ['user_id' => 1, 'urut' => 3, 'nama' => 'Publikasi'],
            ['user_id' => 1, 'urut' => 4, 'nama' => 'Kotak Saran', 'url' => '/kotak-saran-web'],
            ['user_id' => 1, 'urut' => 5, 'nama' => 'Login', 'url' => '/login'],

            ['user_id' => 1, 'urut' => 1, 'nama' => 'Visi Misi', 'url' => 'konten-read/visi-misi', 'endpoint' => 'api/konten-read/visi-misi', 'menu_id' => 2],
            ['user_id' => 1, 'urut' => 2, 'nama' => 'Sejarah', 'url' => 'konten-read/sejarah', 'endpoint' => 'api/konten-read/sejarah', 'menu_id' => 2],
            ['user_id' => 1, 'urut' => 3, 'nama' => 'Struktur Organisasi', 'url' => 'konten-read/struktur-organisasi', 'endpoint' => 'api/konten-read/struktur-organisasi', 'menu_id' => 2],

            ['user_id' => 1, 'urut' => 1, 'nama' => 'Berita', 'url' => 'konten-web/berita', 'endpoint' => 'api/list-konten?jenis=berita&is_web=true&publikasi=1', 'menu_id' => 3],
            ['user_id' => 1, 'urut' => 2, 'nama' => 'Galeri', 'url' => 'galeri-web', 'menu_id' => 3],
            ['user_id' => 1, 'urut' => 2, 'nama' => 'Pengumuman', 'url' => 'konten-web/pengumuman', 'endpoint' => 'api/list-konten?jenis=pengumuman&is_web=true&publikasi=1',  'menu_id' => 3],
            ['user_id' => 1, 'urut' => 3, 'nama' => 'Download', 'url' => 'file-web/download', 'endpoint' => 'api/list-file?jenis=download&is_web=true&publikasi=1', 'menu_id' => 3],
            ['user_id' => 1, 'urut' => 4, 'nama' => 'Peraturan', 'url' => 'file-web/peraturan', 'endpoint' => 'api/list-file?jenis=peraturan&is_web=true&publikasi=1', 'menu_id' => 3],
        ];

        foreach ($dtdef as $dt) {
            $import = [
                'user_id' => $dt['user_id'],
                'nama' => $dt['nama'],
                'urut' => $dt['urut'],
            ];
            $import['url'] = isset($dt['url']) ? $dt['url'] : null;
            $import['endpoint'] = isset($dt['endpoint']) ? $dt['endpoint'] : null;
            $import['menu_id'] = isset($dt['menu_id']) ? $dt['menu_id'] : null;

            Menu::create($import);
        }

        //untuk grup
        $dtdef = [
            ['user_id' => 1, 'nama' => 'Administrator'],
            ['user_id' => 1, 'nama' => 'Editor'],
            ['user_id' => 1, 'nama' => 'Pengguna'],
        ];

        foreach ($dtdef as $dt) {
            Grup::create([
                'user_id' => $dt['user_id'],
                'nama' => $dt['nama'],
            ]);
        }

        //untuk pengaturan web
        PengaturanWeb::create([
            'user_id' => 1,
            'nama' => 'IIQ | Institut Ilmu Al Quran JA Kendari',
            'deskripsi' => 'IIQ | Institut Ilmu Al Quran JA Kendari memiliki website resmi yang digunakan untuk mempublikasikan konten atau kegiatan pada institusi kami',
            'alamat' => 'Jl. Wayong By Pass, Lepo-lepo, Betao Riase, Kec. Pitu Riawa, Kabupaten Sidenreng Rappang, Sulawesi Selatan 91683',
            'helpdesk' => '+6281217081329',
            'longitude' => 122.506486,
            'latitude' => -4.022702,
            'keywords' => 'IIQ, Institut Ilmu Al Quran JA Kendari, Jannatu adnin',
            'email' => 'info@iiq-jakendari.ac.id',
        ]);

        //untuk atur grup
        $dtdef = [
            ['user_id' => 1, 'grup_id' => 1],
            ['user_id' => 1, 'grup_id' => 2],
            ['user_id' => 1, 'grup_id' => 3],
            ['user_id' => 2, 'grup_id' => 2],
            ['user_id' => 2, 'grup_id' => 3],
            ['user_id' => 3, 'grup_id' => 3],
            ['user_id' => 4, 'grup_id' => 3],
            ['user_id' => 5, 'grup_id' => 3],
        ];

        foreach ($dtdef as $dt) {
            AturGrup::create([
                'user_id' => $dt['user_id'],
                'grup_id' => $dt['grup_id'],
            ]);
        }

        //untuk konten
        $dtdef = [
            ['user_id' => 1, 'jenis_konten_id' => 2, 'slug' => 'sambutan-rektor', 'judul' => 'Sambutan Rektor', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'yayasan', 'judul' => 'Tentang Yayasan', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'sejarah', 'judul' => 'Sejarah', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'visi-misi', 'judul' => 'Visi Misi Kantor', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'lambang-atribut', 'judul' => 'Lambang & Atribut', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'mars-lembaga', 'judul' => 'Mars Lembaga', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'unsur-pimpinan', 'judul' => 'Unsur Pimpinan', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'dosen-pengajar', 'judul' => 'Dosen Pengajar', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'tenaga-kependidikan', 'judul' => 'Tenaga Kependidikan', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'tentang-kami', 'judul' => 'Tentang Kami', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'kontak-kami', 'judul' => 'Kontak Kami', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],

            ['user_id' => 1, 'jenis_konten_id' => 4, 'slug' => 'as', 'judul' => 'Program Studi Ahwal Al-Syakhshiyyah', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 4, 'slug' => 'iqt', 'judul' => 'Program Studi Ilmu Al-Qur`an dan Tafsir', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],
            ['user_id' => 1, 'jenis_konten_id' => 4, 'slug' => 'pba', 'judul' => 'Program Studi Pendidikan Bahasa Arab', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p></p>'],

        ];

        foreach ($dtdef as $dt) {
            Konten::create([
                'user_id' => $dt['user_id'],
                'jenis_konten_id' => $dt['jenis_konten_id'],
                'judul' => $dt['judul'],
                'waktu' => $dt['waktu'],
                'isi' => $dt['isi'],
                'slug' => $dt['slug'],
            ]);
        }

        //publikasi profil dan berita
        for ($i = 1; $i <= 14; $i++)
            Publikasi::create([
                'user_id' => 1,
                'konten_id' => $i,
                'is_publikasi' => 1,
            ]);

        //visitor counter
        HtmlCode::create([
            'user_id' => 1,
            'judul' => 'Visitor Counter',
            'slug' => 'visitor',
            'code' => ' <div class="mb-3">
                            <h5>Visitor Counter</h5>
                            <hr>
                            <div style="text-align:center;">
                                <a href="https://info.flagcounter.com/8BOw"><img src="https://s11.flagcounter.com/count2/8BOw/bg_FFFFFF/txt_000000/border_CCCCCC/columns_2/maxflags_10/viewers_0/labels_0/pageviews_0/flags_0/percent_0/" alt="Flag Counter" border="0"></a>
                            </div>
                        </div>',
        ]);

        //peta google
        HtmlCode::create([
            'user_id' => 1,
            'judul' => 'Peta Lokasi',
            'slug' => 'peta-lokasi',
            'code' => ' <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d822.1925400501424!2d122.50629952459582!3d-4.0225434298362615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sid!2sid!4v1749463373009!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
        ]);

        //plink terkait
        HtmlCode::create([
            'user_id' => 1,
            'judul' => 'Link Terkait',
            'slug' => 'link-terkait',
            'code' => ' <div class="mb-3">
                            <h5>Link Terkait</h5>
                            <hr>
                            <ul>
                                <li><a href="https://iainkendari.ac.id" target="_blank">IAIN Kendari</li>
                                <li><a href="https://sia.iainkendari.ac.id" target="_blank">Sistem Informasi Akademik</li>
                                <li><a href="https://simpeg.iainkendari.ac.id" target="_blank">SIMPEG</li>
                            </ul>
                        </div>',
        ]);


        //untuk atur jabatan
        $dtdef = [
            ['nama' => 'Rektor', 'urut' => 1, 'is_pimpinan_utama' => 1],
            ['nama' => 'Wakil Rektor 1', 'urut' => 1, 'is_pimpinan_utama' => 1],
            ['nama' => 'Wakil Rektor 2', 'urut' => 1, 'is_pimpinan_utama' => 1],
            ['nama' => 'Wakil Rektor 3', 'urut' => 1, 'is_pimpinan_utama' => 1],
            ['nama' => 'Dekan', 'urut' => 1, 'is_pimpinan_utama' => 1],
            ['nama' => 'Ketua Prodi', 'urut' => 1, 'is_pimpinan_utama' => 0],
        ];

        foreach ($dtdef as $dt) {
            Jabatan::create([
                'nama' => $dt['nama'],
                'urut' => $dt['urut'],
                'is_pimpinan_utama' => $dt['is_pimpinan_utama'],
            ]);
        }


        //untuk atur jabatan
        $dtdef = [
            ['nama' => 'Rektorat', 'urut' => 1, 'unit_kerja_id' => null],
            ['nama' => 'Fakultas Tarbiyah dan Ilmu Keguruan', 'urut' => 2, 'unit_kerja_id' => null],
            ['nama' => 'Fakultas Syariah', 'urut' => 3, 'unit_kerja_id' => null],
            ['nama' => 'Fakultas Ushuludin Adab dan Dakwah', 'urut' => 4, 'unit_kerja_id' => null],
            ['nama' => 'Program Studi Ahwal Al-Syakhshiyyah', 'urut' => 5, 'unit_kerja_id' => 3],
            ['nama' => 'Program Studi Ilmu Al-Quran dan Tafsir', 'urut' => 6, 'unit_kerja_id' => 4],
            ['nama' => 'Program Studi Pendidikan Bahasa Arab', 'urut' => 7, 'unit_kerja_id' => 2],
        ];

        foreach ($dtdef as $dt) {
            UnitKerja::create([
                'nama' => $dt['nama'],
                'urut' => $dt['urut'],
                'unit_kerja_id' => $dt['unit_kerja_id'],
            ]);
        }
    }
}
