<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Notification extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('notification')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'category' => [
					'type'           => 'VARCHAR',
					'constraint'     => 50,
					'null'           => false,
				],
				'body' => [
                    'type'       => 'TEXT',
                    'null'       => false,
                ],
				'seen' => [
                    'type'          => 'INT',
					'constraint'	=> 1,
                    'null'          => false,
					'default'		=> 0
                ],
				'status' => [
                    'type'          => 'VARCHAR',
					'constraint'	=> 50,
                    'null'          => true,
					'default'		=> NULL
                ],
				'created_by' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => false,
                ],
                'created_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => false,
                ],
            ]);
            $this->dbforge->create_table('notification', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('notification', true);
    }
}
