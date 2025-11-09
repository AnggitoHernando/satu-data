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
                'deskripsi_seksi' => 'SUBBAG TU',
                'slug' => 'sub-bagian-tata-usaha',
                'icon_seksi' => 'subbag1.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Madrasah',
                'deskripsi_seksi' => 'PENDMA',
                'slug' => 'pendidikan-madrasah',
                'icon_seksi' => 'pendma1.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Penyelenggara Zakat dan Wakaf',
                'deskripsi_seksi' => 'PENZAWA',
                'slug' => 'penyelenggara-zakat-dan-wakaf',
                'icon_seksi' => 'zawa.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Bimbingan Masyarakat Islam',
                'deskripsi_seksi' => 'BIMAS',
                'slug' => 'bimbingan-masyarakat-islam',
                'icon_seksi' => 'resources/assets/icon/bimas.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Agama Islam',
                'deskripsi_seksi' => 'PAIS',
                'slug' => 'pendidikan-agama-islam',
                'icon_seksi' => 'pais.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Diniyah dan Pondok Pesantren',
                'deskripsi_seksi' => 'PD PONTREN',
                'slug' => 'pendidikan-diniyah-dan-pondok-pesantren',
                'icon_seksi' => 'pdpontren.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Penyelenggara Haji dan Umroh',
                'deskripsi_seksi' => 'PHU',
                'slug' => 'penyelenggara-haji-dan-umroh',
                'icon_seksi' => 'haji1.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
