<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table            = 'artikel';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['judul', 'isi', 'status', 'slug', 'gambar', 'id_kategori'];

    public function getArtikelDenganKategori(string $slugKategori = ''): array
    {
        $builder = $this->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        if ($slugKategori !== '') {
            $builder->where('kategori.slug_kategori', $slugKategori);
        }

        return $builder->orderBy('artikel.id', 'DESC')->findAll();
    }

    public function getArtikelBySlugDenganKategori(string $slug): ?array
    {
        return $this->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->where('artikel.slug', $slug)
            ->first();
    }
}
