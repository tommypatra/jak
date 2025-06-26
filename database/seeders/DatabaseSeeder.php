<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\File;
use App\Models\Grup;
use App\Models\Menu;
use App\Models\User;
use App\Models\Konten;
use App\Models\AturGrup;
use App\Models\HtmlCode;
use App\Models\Komentar;
use App\Models\Publikasi;
use App\Models\JenisKonten;
use App\Models\KotakSaran;
use App\Models\LikeDislike;
use App\Models\PengaturanWeb;
use App\Models\SlideShow;
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
            ['user_id' => 1, 'nama' => 'Berita', 'slug' => 'berita', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Daftar berita website'],
            ['user_id' => 1, 'nama' => 'Pengumuman', 'slug' => 'pengumuman', 'kategori' => 'ARTIKEL', 'deskripsi' => 'Daftar pengumuman website'],
            ['user_id' => 1, 'nama' => 'Download', 'slug' => 'download', 'kategori' => 'FILE', 'deskripsi' => 'Daftar download pada website'],
            ['user_id' => 1, 'nama' => 'Peraturan', 'slug' => 'peraturan', 'kategori' => 'FILE', 'deskripsi' => 'Daftar peraturan pada website'],
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
            'nama' => 'Website Institut',
            'deskripsi' => 'Institut kami memiliki website resmi yang digunakan untuk mempublikasikan konten atau kegiatan pada institusi kami',
            'alamat' => 'Jalan Sultan Diponegoro No. 17 Kendari, Sulawesi Tenggara',
            'helpdesk' => '(wa call only) 0852235361763, (wa chat only) 085435263544',
            'keywords' => 'Institut Website Resmi',
            'email' => 'institut@mail.com',
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
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'visi-misi', 'judul' => 'Visi Misi Kantor', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p>Visi misi instansi ini adalah</p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'sejarah', 'judul' => 'Sejarah', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p>sejarah instansi ini dimulai dari</p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'struktur-organisasi', 'judul' => 'Struktur Organisasi', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p>Struktur Organisasi berdasarkan peraturan yang berlaku sebagai berikut</p>'],
            ['user_id' => 1, 'jenis_konten_id' => 1, 'slug' => 'tentang-kami', 'judul' => 'Tentang Kami', 'waktu' => date('Y-m-d H:i:s'), 'isi' => '<p>Kami adalah organisasi yang profesional di bidangnya dan terus berkembang setiap hari</p>'],
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

        //berita sampai 4 sd 13
        for ($i = 1; $i <= 10; $i++) {
            $judul = $factory->sentence;
            Konten::create([
                'user_id' => 1,
                'jenis_konten_id' => 2,
                'judul' => $judul,
                'waktu' => $dt['waktu'],
                'isi' => $factory->paragraph(5),
                'slug' => generateSlug($judul, $dt['waktu']),
            ]);
        }

        //pengumuman 13 sd 18
        for ($i = 1; $i <= 5; $i++) {
            $judul = $factory->sentence;
            Konten::create([
                'user_id' => 1,
                'jenis_konten_id' => 3,
                'judul' => $judul,
                'waktu' => $dt['waktu'],
                'isi' => $factory->paragraph(5),
                'slug' => generateSlug($judul, $dt['waktu']),
            ]);
        }

        //publikasi profil dan berita
        for ($i = 1; $i <= 10; $i++)
            Publikasi::create([
                'user_id' => 1,
                'konten_id' => $i,
                'is_publikasi' => 1,
            ]);

        //publikasi untuk pengumuman
        for ($i = 13; $i <= 17; $i++)
            Publikasi::create([
                'user_id' => 1,
                'konten_id' => $i,
                'is_publikasi' => 1,
            ]);

        //tolak untuk berita
        for ($i = 11; $i <= 11; $i++)
            Publikasi::create([
                'user_id' => 1,
                'konten_id' => $i,
                'is_publikasi' => 2,
                'catatan' => 'cek lagi kontennya',
            ]);


        for ($i = 1; $i <= 5; $i++)
            Komentar::create([
                'user_id' => 1,
                'is_publikasi' => rand(1, 0),
                'nama' => 'pengunjung-' . $i,
                'komentar' => 'bagus, saya suka konten beritanya, terima kasih',
                'konten_id' => rand(1, 3),
            ]);

        Komentar::create([
            'user_id' => 1,
            'nama' => 'pengunjung-' . $i,
            'komentar' => 'bagus, saya suka konten beritanya, terima kasih',
            'konten_id' => rand(1, 4),
        ]);


        for ($i = 1; $i < 15; $i++)
            LikeDislike::create([
                // 'kategori' => 'KONTEN',
                'konten_id' => rand(1, 3),
            ]);

        //untuk file
        $dtdef = [
            ['user_id' => 1, 'jenis_konten_id' => 4, 'judul' => 'Formulir Pendaftaran', 'waktu' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'jenis_konten_id' => 4, 'judul' => 'Buku Pedoman Pegawai', 'waktu' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'jenis_konten_id' => 4, 'judul' => 'SBU Tahun 2024', 'waktu' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'jenis_konten_id' => 5, 'judul' => 'SOP Penerimaan Pegawai', 'waktu' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'jenis_konten_id' => 5, 'judul' => 'SK Pendirian Kantor', 'waktu' => date('Y-m-d H:i:s')],
            ['user_id' => 1, 'jenis_konten_id' => 5, 'judul' => 'SK Pedoman Kenaikan Pangkat', 'waktu' => date('Y-m-d H:i:s')],
        ];

        foreach ($dtdef as $i => $dt) {
            File::create([
                'user_id' => $dt['user_id'],
                'jenis_konten_id' => $dt['jenis_konten_id'],
                'judul' => $dt['judul'],
                'waktu' => $dt['waktu'],
                'path' => 'uploads/file/sampel-' . $i . ".pdf",
                'slug' => generateSlug($dt['judul'], $dt['waktu']),
            ]);
        }

        for ($i = 1; $i <= 2; $i++)
            Publikasi::create([
                'user_id' => 1,
                'file_id' => $i,
                'is_publikasi' => 1,
            ]);

        for ($i = 3; $i <= 4; $i++)
            Publikasi::create([
                'user_id' => 1,
                'file_id' => $i,
                'is_publikasi' => 0,
                'catatan' => 'ditolak, ini catatan publikasi file ke-' . $i,
            ]);

        Publikasi::create([
            'user_id' => 1,
            'file_id' => 6,
            'is_publikasi' => 1,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Komentar::create([
                'user_id' => 1,
                'is_publikasi' => rand(1, 0),
                'nama' => 'pengunjung file ke-' . $i,
                'komentar' => 'file ke ' . $i . ' nya  bagus, terima kasih',
                'file_id' => rand(1, 4),
            ]);
        }

        Komentar::create([
            'user_id' => 1,
            'nama' => 'pengunjung-' . $i,
            'komentar' => 'bagus file downloadnya, terima kasih',
            'file_id' => rand(1, 4),
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
            'code' => ' <div class="mb-3">
                            <h5>Lokasi Kami</h5>
                            <hr>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7959.793924170087!2d122.475!3d-4.041445!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d988c472863d8db%3A0x80e257d51fbd380d!2sInstitut%20Agama%20Islam%20Negeri%20(IAIN)%20Kendari!5e0!3m2!1sid!2sid!4v1713571268614!5m2!1sid!2sid" width="600" height="400" style="border:1;width:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>                        
                        </div>',
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

        //untuk file
        $dtdef = [
            ['user_id' => 1,  'judul' => 'Pelaksanaan Sosialisasi', 'path' => 'slideshows/1.jpg'],
            ['user_id' => 1,  'judul' => 'Selamat Hari Raya Idul Fitri', 'path' => 'slideshows/2.jpg'],
            ['user_id' => 1,  'judul' => 'Selamat Berpuasa di Bulan Ramadhan', 'path' => 'slideshows/3.jpg'],
        ];

        foreach ($dtdef as $i => $dt) {
            SlideShow::create([
                'user_id' => $dt['user_id'],
                'judul' => $dt['judul'],
                'path' => $dt['path'],
                'is_publikasi' => 1,
            ]);
        }

        KotakSaran::create([
            'nama' => "Al Fath",
            'komentar' => "kalau bisa beritanya kasi menarik ",
        ]);
    }
}
