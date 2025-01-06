<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Client_History extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('client_history')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('client_id', true);
            $this->dbforge->add_key('created_by');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'client_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => false,
                ],
                'name' => [
                    'type'       => 'TEXT',
                    'null'       => false,
                ],
                'uen' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false,
                ],
				'website' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'default'       => NULL,
                ],
				'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
				'fax' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
				'email' => [
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => false,
				],
				'status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
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
                'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
            ]);


			if ($this->dbforge->create_table('client_history', true)) {
				$this->db->insert_batch('client_history', [
					[
						'id'           => 1,
						'client_id'           => 1,
						'name'     	   => 'Web Imp',
						'uen' 			=> '123456789',
						'website'		=> 'www.webimp.com.sg',
						'phone'			=> '62773897828',
						'fax'			=> '62229901333',
						'email'			=> 'webimp@mail.com',
						'status'		=> 'Active',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('client_history', true);
    }
}
