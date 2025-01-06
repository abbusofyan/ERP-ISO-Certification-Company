<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_File extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('file')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('created_by');

            // fields
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 30,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'filename' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                    'default'    => 'Untitled.txt',
                ],
                'path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
                'url' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 1000,
                    'null'       => false,
                ],
                'mime' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                    'default'    => 'text/plain',
                ],
                'size' => [
                    'type'       => 'BIGINT',
                    'constraint' => 20,
                    'null'       => false,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'created_on' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
                'created_by' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 20,
                    'unsigned'       => true,
                    'null'           => false,
                ],
            ]);

            // create table
            $this->dbforge->create_table('file', true);
        }
    }




    function down()
    {
        if ($this->db->table_exists('file')) {
            $this->dbforge->drop_table('file', true);
        }
    }
}
