<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

class Post extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';

    private const IMAGE_DIR = 'gambar';

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->setCorsHeaders();
    }

    



    public function options($id = null)
    {
        $this->setCorsHeaders();

        return $this->response->setStatusCode(204);
    }

    private function setCorsHeaders(): void
    {
        $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->setHeader('Access-Control-Max-Age', '86400');
    }

    



    public function index()
    {
        $model = new ArtikelModel();
        $artikel = $model->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy('artikel.id', 'DESC')
            ->findAll();

        return $this->respond([
            'status' => 200,
            'error'  => null,
            'artikel' => $artikel,
        ]);
    }

    



    public function show($id = null)
    {
        $id = $this->normalizeId($id);
        if ($id === null) {
            return $this->failValidationErrors(['id' => 'ID artikel wajib berupa angka.']);
        }

        $model = new ArtikelModel();
        $artikel = $model->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->where('artikel.id', $id)
            ->first();

        if (! $artikel) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        return $this->respond($artikel);
    }

    



    public function create()
    {
        $input = $this->requestInput();
        $validationErrors = $this->validateArticleInput($input, true);

        if (! empty($validationErrors)) {
            return $this->failValidationErrors($validationErrors);
        }

        $payload = $this->buildPayload($input, true);

        $model = new ArtikelModel();
        $id = $model->insert($payload, true);

        return $this->respondCreated([
            'status' => 201,
            'error'  => null,
            'messages' => [
                'success' => 'Data artikel berhasil ditambahkan.',
            ],
            'id' => $id,
        ]);
    }

    



    public function update($id = null)
    {
        $input = $this->requestInput();
        $id = $this->normalizeId($id ?? ($input['id'] ?? null));

        if ($id === null) {
            return $this->failValidationErrors(['id' => 'ID artikel wajib berupa angka.']);
        }

        $model = new ArtikelModel();
        $existing = $model->find($id);

        if (! $existing) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        $validationErrors = $this->validateArticleInput($input, false);
        if (! empty($validationErrors)) {
            return $this->failValidationErrors($validationErrors);
        }

        $payload = $this->buildPayload($input, false);
        if (empty($payload)) {
            return $this->failValidationErrors(['payload' => 'Tidak ada data yang dikirim untuk diubah.']);
        }

        $model->update($id, $payload);

        return $this->respond([
            'status' => 200,
            'error'  => null,
            'messages' => [
                'success' => 'Data artikel berhasil diubah.',
            ],
        ]);
    }

    



    public function delete($id = null)
    {
        $id = $this->normalizeId($id);
        if ($id === null) {
            return $this->failValidationErrors(['id' => 'ID artikel wajib berupa angka.']);
        }

        $model = new ArtikelModel();
        $existing = $model->find($id);

        if (! $existing) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        $this->hapusGambarLama($existing['gambar'] ?? null);
        $model->delete($id);

        return $this->respondDeleted([
            'status' => 200,
            'error'  => null,
            'messages' => [
                'success' => 'Data artikel berhasil dihapus.',
            ],
        ]);
    }

    private function requestInput(): array
    {
         
         
         
        $contentType = strtolower((string) $this->request->getHeaderLine('Content-Type'));

        if (str_contains($contentType, 'application/json')) {
            try {
                $json = $this->request->getJSON(true);
                if (is_array($json)) {
                    return $json;
                }
            } catch (\Throwable $e) {
                return [];
            }
        }

        $rawInput = $this->request->getRawInput();
        if (! empty($rawInput)) {
            return $rawInput;
        }

        $post = $this->request->getPost();
        if (! empty($post)) {
            return $post;
        }

        $vars = $this->request->getVar();
        return is_array($vars) ? $vars : [];
    }

    private function normalizeId($id): ?int
    {
        if ($id === null || $id === '') {
            return null;
        }

        if (! is_numeric($id)) {
            return null;
        }

        $id = (int) $id;
        return $id > 0 ? $id : null;
    }

    private function validateArticleInput(array $input, bool $isCreate): array
    {
        $errors = [];

        if ($isCreate && trim((string) ($input['judul'] ?? '')) === '') {
            $errors['judul'] = 'Judul artikel wajib diisi.';
        }

        if ($isCreate && trim((string) ($input['isi'] ?? '')) === '') {
            $errors['isi'] = 'Isi artikel wajib diisi.';
        }

        if (array_key_exists('judul', $input) && trim((string) $input['judul']) !== '' && strlen(trim((string) $input['judul'])) < 3) {
            $errors['judul'] = 'Judul artikel minimal 3 karakter.';
        }

        if (array_key_exists('isi', $input) && trim((string) $input['isi']) !== '' && strlen(trim((string) $input['isi'])) < 10) {
            $errors['isi'] = 'Isi artikel minimal 10 karakter.';
        }

        if (array_key_exists('id_kategori', $input) && $input['id_kategori'] !== '' && ! is_numeric($input['id_kategori'])) {
            $errors['id_kategori'] = 'ID kategori harus berupa angka.';
        }

        if (array_key_exists('status', $input) && $input['status'] !== '' && ! in_array((string) $input['status'], ['0', '1'], true)) {
            $errors['status'] = 'Status hanya boleh 0 atau 1.';
        }

        return $errors;
    }

    private function buildPayload(array $input, bool $isCreate): array
    {
        $payload = [];

        if (array_key_exists('judul', $input)) {
            $judul = trim((string) $input['judul']);
            if ($judul !== '') {
                $payload['judul'] = $judul;
                $payload['slug'] = url_title($judul, '-', true);
            }
        }

        if (array_key_exists('isi', $input)) {
            $isi = trim((string) $input['isi']);
            if ($isi !== '') {
                $payload['isi'] = $isi;
            }
        }

        if (array_key_exists('id_kategori', $input) && $input['id_kategori'] !== '') {
            $payload['id_kategori'] = (int) $input['id_kategori'];
        }

        if (array_key_exists('status', $input) && $input['status'] !== '') {
            $payload['status'] = (int) $input['status'];
        } elseif ($isCreate) {
            $payload['status'] = 1;
        }

        return $payload;
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
