<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Novel, cerpen, dan karya fiksi lainnya.', 'status' => 'active'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku berdasarkan fakta dan kejadian nyata.', 'status' => 'active'],
            ['name' => 'Sains & Teknologi', 'description' => 'Buku ilmu pengetahuan alam dan teknologi terkini.', 'status' => 'active'],
            ['name' => 'Sejarah', 'description' => 'Buku tentang sejarah Indonesia dan dunia.', 'status' => 'active'],
            ['name' => 'Filsafat', 'description' => 'Pemikiran, logika, dan filsafat barat maupun timur.', 'status' => 'active'],
            ['name' => 'Pendidikan', 'description' => 'Buku teks, panduan belajar, dan referensi pendidikan.', 'status' => 'active'],
            ['name' => 'Ekonomi & Bisnis', 'description' => 'Keuangan, manajemen, dan kewirausahaan.', 'status' => 'active'],
            ['name' => 'Agama & Spiritualitas', 'description' => 'Buku keagamaan dan pengembangan spiritual.', 'status' => 'active'],
            ['name' => 'Psikologi', 'description' => 'Ilmu perilaku manusia dan kesehatan mental.', 'status' => 'active'],
            ['name' => 'Seni & Budaya', 'description' => 'Seni rupa, musik, sastra, dan budaya nusantara.', 'status' => 'active'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
