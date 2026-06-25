<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateArtikelKategoriGambar extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('kategori')) {
            $this->forge->addField([
                'id_kategori' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'nama_kategori' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'slug_kategori' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
            ]);
            $this->forge->addKey('id_kategori', true);
            $this->forge->createTable('kategori', true);
        }

        if (! $this->db->tableExists('artikel')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'judul' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => false,
                ],
                'isi' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'gambar' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => true,
                ],
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => true,
                ],
                'id_kategori' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('artikel', true);
        } else {
            $fields = $this->db->getFieldNames('artikel');
            $newFields = [];

            if (! in_array('gambar', $fields, true)) {
                $newFields['gambar'] = ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true];
            }
            if (! in_array('status', $fields, true)) {
                $newFields['status'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }
            if (! in_array('slug', $fields, true)) {
                $newFields['slug'] = ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true];
            }
            if (! in_array('id_kategori', $fields, true)) {
                $newFields['id_kategori'] = ['type' => 'INT', 'constraint' => 11, 'null' => true];
            }
            if (! in_array('created_at', $fields, true)) {
                $newFields['created_at'] = ['type' => 'DATETIME', 'null' => true];
            }
            if (! in_array('updated_at', $fields, true)) {
                $newFields['updated_at'] = ['type' => 'DATETIME', 'null' => true];
            }

            if ($newFields !== []) {
                $this->forge->addColumn('artikel', $newFields);
            }
        }

        if ((int) $this->db->table('kategori')->countAllResults() === 0) {
            $this->db->table('kategori')->insertBatch([
                ['nama_kategori' => 'Berita Teknologi', 'slug_kategori' => 'berita-teknologi'],
                ['nama_kategori' => 'Tutorial Pemrograman', 'slug_kategori' => 'tutorial-pemrograman'],
                ['nama_kategori' => 'Review Gadget', 'slug_kategori' => 'review-gadget'],
            ]);
        }

        try {
            $this->db->query('ALTER TABLE artikel ADD CONSTRAINT fk_kategori_artikel FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE SET NULL ON UPDATE CASCADE');
        } catch (\Throwable $e) {
             
        }
    }

    public function down()
    {
        try {
            $this->db->query('ALTER TABLE artikel DROP FOREIGN KEY fk_kategori_artikel');
        } catch (\Throwable $e) {
        }
    }
}
