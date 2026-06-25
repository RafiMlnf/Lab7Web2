<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $model = model('KategoriModel');
        $data = [
            ['nama_kategori' => 'Berita Teknologi', 'slug_kategori' => 'berita-teknologi'],
            ['nama_kategori' => 'Tutorial Pemrograman', 'slug_kategori' => 'tutorial-pemrograman'],
            ['nama_kategori' => 'Review Gadget', 'slug_kategori' => 'review-gadget'],
        ];

        foreach ($data as $row) {
            $exists = $model->where('slug_kategori', $row['slug_kategori'])->first();
            if (! $exists) {
                $model->insert($row);
            }
        }
    }
}
