<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Auth_Log extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('auth_log')) {
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
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'login_ip' => [
					'type'           => 'VARCHAR',
					'constraint'     => 50,
					'null'           => false,
				],
				'login_time' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => false,
                ],
				'logout_ip' => [
					'type'           => 'VARCHAR',
					'constraint'     => 50,
					'null'           => true,
					'default'		 => NULL
				],
				'logout_time' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
					'null'           => true,
					'default'		 => NULL
                ],
            ]);
            $this->dbforge->create_table('auth_log', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('auth_log', true);
    }
}
