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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Madrasah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Penyelenggara Zakat dan Wakaf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Bimbingan Masyarakat Islam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Agama Islam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Pendidikan Diniyah dan Pondok Pesantren',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_seksi' => 'Penyelenggara Haji dan Umroh',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
