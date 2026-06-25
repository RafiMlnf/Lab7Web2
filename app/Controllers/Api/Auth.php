<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->setCorsHeaders();
    }

    public function options()
    {
        $this->setCorsHeaders();

        return $this->response->setStatusCode(204);
    }

    public function login()
    {
        $input = $this->requestInput();
        $username = trim((string) ($input['username'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        if ($username === '' || $password === '') {
            return $this->failValidationErrors([
                'username' => 'Username, ID, atau email wajib diisi.',
                'password' => 'Password wajib diisi.',
            ]);
        }

        $model = new UserModel();
        $user = null;

        if (ctype_digit($username)) {
            $user = $model->find((int) $username);
        }

        if (! $user) {
            $user = $model->where('username', $username)
                ->orWhere('useremail', $username)
                ->first();
        }

        if ($user && $this->passwordMatches($password, (string) ($user['userpassword'] ?? ''))) {
            $tokenPayload = 'TOKEN-SECRET-' . ($user['username'] ?? 'user') . '-' . ($user['id'] ?? '0');

            return $this->respond([
                'status'   => 200,
                'error'    => null,
                'messages' => 'Login Berhasil',
                'data'     => [
                    'id'       => $user['id'] ?? null,
                    'username' => $user['username'] ?? null,
                    'email'    => $user['useremail'] ?? null,
                    'token'    => base64_encode($tokenPayload),
                ],
            ], 200);
        }

        return $this->failUnauthorized('Username atau Password yang Anda masukkan salah.');
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

        $post = $this->request->getPost();
        if (! empty($post)) {
            return $post;
        }

        $vars = $this->request->getVar();
        return is_array($vars) ? $vars : [];
    }

    private function passwordMatches(string $password, string $storedPassword): bool
    {
        if ($storedPassword === '') {
            return false;
        }

        return hash_equals($storedPassword, $password) || password_verify($password, $storedPassword);
    }

    private function setCorsHeaders(): void
    {
        $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->setHeader('Access-Control-Max-Age', '86400');
    }
}
