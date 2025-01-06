<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Auth_Otp extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('auth_otp')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('user_id');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => true,
                    'unsigned'       => true,
                ],
                'ip' => [
                    'type'           => 'VARCHAR',
					'constraint'     => 50,
                    'null'           => false,
                ],
				'otp' => [
                    'type'           => 'VARCHAR',
					'constraint'     => 10,
                    'null'           => false,
                ],
				'created_on' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
            ]);

            $this->dbforge->create_table('auth_otp', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('auth_otp', true);
    }
}
