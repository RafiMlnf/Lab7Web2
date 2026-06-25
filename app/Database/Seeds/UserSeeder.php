<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $model = model('UserModel');
        $existing = $model->where('useremail', 'admin@email.com')->first();

        $data = [
            'username'     => 'admin',
            'useremail'    => 'admin@email.com',
             
            'userpassword' => password_hash('1', PASSWORD_DEFAULT),
        ];

        if ($existing) {
            $model->update($existing['id'], $data);
            return;
        }

        $model->insert($data);
    }
}
