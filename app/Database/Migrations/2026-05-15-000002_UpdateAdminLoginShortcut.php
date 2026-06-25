<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAdminLoginShortcut extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('user')) {
            return;
        }

        $password = password_hash('1', PASSWORD_DEFAULT);
        $builder = $this->db->table('user');
        $existing = $builder->where('useremail', 'admin@email.com')->get()->getRowArray();

        $data = [
            'username'     => 'admin',
            'useremail'    => 'admin@email.com',
            'userpassword' => $password,
        ];

        if ($existing) {
            $builder->where('id', $existing['id'])->update($data);
            return;
        }

        $builder->insert($data);
    }

    public function down()
    {
         
    }
}
