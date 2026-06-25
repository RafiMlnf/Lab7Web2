<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        return redirect()->to('/user/login');
    }

    public function login()
    {
        helper(['form']);

        if (session()->get('logged_in')) {
            return redirect()->to('/admin/artikel');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'login'    => 'required',
                'password' => 'required',
            ];

            $data = [
                'login'    => trim((string) $this->request->getPost('login')),
                'password' => (string) $this->request->getPost('password'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('user/login', [
                    'title'      => 'Login Admin',
                    'validation' => $this->validator,
                ]);
            }

            $login = $this->findUserByLogin($data['login']);

            if (! $login || ! password_verify($data['password'], $login['userpassword'])) {
                session()->setFlashdata('error', 'ID, username, email, atau password salah. Silakan coba lagi.');
                return redirect()->to('/user/login')->withInput();
            }

            session()->set([
                'user_id'    => $login['id'],
                'user_name'  => $login['username'],
                'user_email' => $login['useremail'],
                'logged_in'  => true,
            ]);

            session()->setFlashdata('success', 'Login berhasil. Selamat datang kembali, ' . $login['username'] . '.');
            return redirect()->to('/admin/artikel');
        }

        return view('user/login', ['title' => 'Login Admin']);
    }

    public function register()
    {
        helper(['form']);

        if (session()->get('logged_in')) {
            return redirect()->to('/admin/artikel');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username'         => 'required|max_length[50]',
                'email'            => 'required|valid_email|is_unique[user.useremail]',
                'password'         => 'required',
                'confirm_password' => 'required|matches[password]',
            ];

            $data = [
                'username'         => trim((string) $this->request->getPost('username')),
                'email'            => trim((string) $this->request->getPost('email')),
                'password'         => (string) $this->request->getPost('password'),
                'confirm_password' => (string) $this->request->getPost('confirm_password'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('user/register', [
                    'title'      => 'Buat Akun',
                    'validation' => $this->validator,
                ]);
            }

            $model = new UserModel();
            $model->insert([
                'username'     => $data['username'],
                'useremail'    => $data['email'],
                'userpassword' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            session()->setFlashdata('success', 'Akun berhasil dibuat. Silakan login menggunakan ID, username, atau email.');
            return redirect()->to('/user/login');
        }

        return view('user/register', ['title' => 'Buat Akun']);
    }

    public function forgotPassword()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'login'            => 'required',
                'new_password'     => 'required',
                'confirm_password' => 'required|matches[new_password]',
            ];

            $data = [
                'login'            => trim((string) $this->request->getPost('login')),
                'new_password'     => (string) $this->request->getPost('new_password'),
                'confirm_password' => (string) $this->request->getPost('confirm_password'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('user/forgot_password', [
                    'title'      => 'Ubah Password',
                    'validation' => $this->validator,
                ]);
            }

            $model = new UserModel();
            $user = $this->findUserByLogin($data['login']);

            if (! $user) {
                session()->setFlashdata('error', 'Akun tidak ditemukan. Masukkan ID, username, atau email yang benar.');
                return redirect()->to('/user/forgot-password')->withInput();
            }

            $model->update($user['id'], [
                'userpassword' => password_hash($data['new_password'], PASSWORD_DEFAULT),
            ]);

            session()->setFlashdata('success', 'Password berhasil diperbarui. Silakan login dengan password baru Anda.');
            return redirect()->to('/user/login');
        }

        return view('user/forgot_password', ['title' => 'Ubah Password']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/user/login');
    }

    private function findUserByLogin(string $login): ?array
    {
        $model = new UserModel();
        $login = trim($login);

        if ($login === '') {
            return null;
        }

        if (ctype_digit($login)) {
            $user = $model->find((int) $login);
            if ($user) {
                return $user;
            }
        }

        if (str_contains($login, '@')) {
            return $model->where('useremail', $login)->first();
        }

        return $model->where('username', $login)->first();
    }
}
