<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (env('BYPASS_AUTH') === true) {
            return null;
        }

        $authHeader = $this->getAuthorizationHeader($request);

        if ($authHeader === '') {
            return $this->unauthorized('Akses Ditolak. Token tidak ditemukan pada request!');
        }

        $token = '';
        if (preg_match('/Bearer\s+(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (! $this->isValidToken($token)) {
            return $this->unauthorized('Sesi Token tidak valid atau kedaluwarsa!');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    private function getAuthorizationHeader(RequestInterface $request): string
    {
        $authHeader = trim((string) $request->getHeaderLine('Authorization'));

        if ($authHeader !== '') {
            return $authHeader;
        }

        foreach (['HTTP_AUTHORIZATION', 'REDIRECT_HTTP_AUTHORIZATION'] as $key) {
            $serverValue = trim((string) $request->getServer($key));
            if ($serverValue !== '') {
                return $serverValue;
            }
        }

        return '';
    }

    private function isValidToken(string $token): bool
    {
        if ($token === '') {
            return false;
        }

        $decoded = base64_decode($token, true);

        return is_string($decoded) && str_starts_with($decoded, 'TOKEN-SECRET-');
    }

    private function unauthorized(string $message)
    {
        $response = Services::response();
        $response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->setStatusCode(401);

        return $response->setJSON([
            'status'   => 401,
            'error'    => 401,
            'messages' => $message,
        ]);
    }
}
