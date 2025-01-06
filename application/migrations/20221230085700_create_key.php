<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Key extends CI_Migration
{
    function up()
    {
        // keys
        if (!$this->db->table_exists('keys')) {
            $this->dbforge->add_key('id', true);

            // fields
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'key' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                    'null'       => false,
                ],
                'level' => [
                    'type'       => 'INT',
                    'constraint' => 2,
                    'null'       => false,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
                'ignore_limits' => [
                    'type'          => 'TINYINT',
                    'constraint'    => 1,
                    'null'          => false,
                    'unsigned'      => true,
                    'default'       => 0,
                ],
            ]);

            // create table
            if ($this->dbforge->create_table('keys', true)) {
                $this->db->insert_batch('keys', [
                    [
                        'id'            => 1,
                        'key'           => X_API_KEY,
                        'level'         => 0,
                        'ignore_limits' => 0,
                    ],
                ]);
            }
        }
    }




    function down()
    {
        $this->dbforge->drop_table('keys', true);
    }
}
