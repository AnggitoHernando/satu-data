<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SeksisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seksi')->insert([
            [
                'nama_seksi' => 'Sub Bagian Tata Usaha',
                'slug' => 'sub-bagian-tata-usaha',
                'icon_seksi' => 'resources/assets/icon/subbag1.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Madrasah',
                'slug' => 'pendidikan-madrasah',
                'icon_seksi' => 'resources/assets/icon/pendma1.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Penyelenggara Zakat dan Wakaf',
                'slug' => 'penyelenggara-zakat-dan-wakaf',
                'icon_seksi' => 'resources/assets/icon/zawa.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Bimbingan Masyarakat Islam',
                'slug' => 'bimbingan-masyarakat-islam',
                'icon_seksi' => 'resources/assets/icon/bimas.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Agama Islam',
                'slug' => 'pendidikan-agama-islam',
                'icon_seksi' => 'resources/assets/icon/pais.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Diniyah dan Pondok Pesantren',
                'slug' => 'pendidikan-diniyah-dan-pondok-pesantren',
                'icon_seksi' => 'resources/assets/icon/pdpontren.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Penyelenggara Haji dan Umroh',
                'slug' => 'penyelenggara-haji-dan-umroh',
                'icon_seksi' => 'resources/assets/icon/haji1.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
