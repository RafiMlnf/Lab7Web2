<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class AjaxController extends BaseController
{
    private const IMAGE_DIR = 'gambar';
    private const MAX_IMAGE_SIZE = 2097152;  
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function index(): string
    {
        $kategoriModel = new KategoriModel();

        return view('ajax/index', [
            'title'    => 'Dashboard Pengelolaan Artikel',
            'kategori' => $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function getData()
    {
        $model = new ArtikelModel();
        $data = $model->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy('artikel.id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'OK',
            'data'   => $data,
        ]);
    }

    public function create()
    {
        $payload = $this->readPayload();
        $errors = $this->validateArtikelPayload($payload);

        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Validasi gagal.',
                'errors'  => $errors,
            ]);
        }

        try {
            $gambar = $this->uploadGambar($this->request->getFile('gambar'));
        } catch (RuntimeException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }

        $data = [
            'judul'       => $payload['judul'],
            'isi'         => $payload['isi'],
            'slug'        => url_title($payload['judul'], '-', true),
            'status'      => (int) $payload['status'],
            'id_kategori' => (int) $payload['id_kategori'],
        ];

        if ($gambar !== null) {
            $data['gambar'] = $gambar;
        }

        $model = new ArtikelModel();
        $model->insert($data);

        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil ditambahkan.',
        ]);
    }

    public function update($id)
    {
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if (! $artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $payload = $this->readPayload();
        $errors = $this->validateArtikelPayload($payload);

        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Validasi gagal.',
                'errors'  => $errors,
            ]);
        }

        try {
            $gambar = $this->uploadGambar($this->request->getFile('gambar'));
        } catch (RuntimeException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'ERROR',
                'message' => $e->getMessage(),
            ]);
        }

        $data = [
            'judul'       => $payload['judul'],
            'isi'         => $payload['isi'],
            'slug'        => url_title($payload['judul'], '-', true),
            'status'      => (int) $payload['status'],
            'id_kategori' => (int) $payload['id_kategori'],
        ];

        if ($gambar !== null) {
            $data['gambar'] = $gambar;
            $this->hapusGambarLama($artikel['gambar'] ?? null);
        }

        $model->update($id, $data);

        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil diperbarui.',
        ]);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if (! $artikel) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Artikel tidak ditemukan.',
            ]);
        }

        $this->hapusGambarLama($artikel['gambar'] ?? null);
        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil dihapus.',
        ]);
    }

    private function readPayload(): array
    {
         
         
         
        $contentType = strtolower((string) $this->request->getHeaderLine('Content-Type'));
        $input = [];

        if (str_contains($contentType, 'application/json')) {
            try {
                $json = $this->request->getJSON(true);
                if (is_array($json)) {
                    $input = $json;
                }
            } catch (\Throwable $e) {
                $input = [];
            }
        }

        if ($input === []) {
            $input = $this->request->getPost();
        }

        return [
            'judul'       => trim((string) ($input['judul'] ?? '')),
            'isi'         => trim((string) ($input['isi'] ?? '')),
            'status'      => (string) ($input['status'] ?? '0'),
            'id_kategori' => (string) ($input['id_kategori'] ?? ''),
        ];
    }

    private function validateArtikelPayload(array $payload): array
    {
        $errors = [];

        if ($payload['judul'] === '' || mb_strlen($payload['judul']) < 3) {
            $errors['judul'] = 'Judul wajib diisi minimal 3 karakter.';
        }

        if ($payload['isi'] === '' || mb_strlen($payload['isi']) < 10) {
            $errors['isi'] = 'Isi artikel wajib diisi minimal 10 karakter.';
        }

        if ($payload['id_kategori'] === '' || ! ctype_digit($payload['id_kategori'])) {
            $errors['id_kategori'] = 'Kategori wajib dipilih.';
        } else {
            $kategoriModel = new KategoriModel();
            if (! $kategoriModel->find((int) $payload['id_kategori'])) {
                $errors['id_kategori'] = 'Kategori tidak ditemukan di database.';
            }
        }

        if (! in_array($payload['status'], ['0', '1'], true)) {
            $errors['status'] = 'Status tidak valid.';
        }

        return $errors;
    }

    private function uploadGambar(?UploadedFile $file): ?string
    {
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $file->isValid()) {
            throw new RuntimeException('Upload gambar gagal. Silakan pilih file gambar yang valid.');
        }

        $extension = strtolower($file->getClientExtension());
        if (! in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            throw new RuntimeException('File gambar harus berformat JPG, JPEG, PNG, GIF, atau WEBP.');
        }

        if ($file->getSize() > self::MAX_IMAGE_SIZE) {
            throw new RuntimeException('Ukuran gambar maksimal 2 MB.');
        }

        $targetDir = FCPATH . self::IMAGE_DIR;
        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        return $newName;
    }

    private function hapusGambarLama(?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        $path = FCPATH . self::IMAGE_DIR . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
