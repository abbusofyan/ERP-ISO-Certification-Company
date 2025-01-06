<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Client extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('client')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('created_by');
            $this->dbforge->add_key('updated_by');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
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
                'updated_by' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'default'       => NULL,
                ],
                'updated_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'default'       => NULL,
                ],
                'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
				'flagged' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
            ]);

			if ($this->dbforge->create_table('client', true)) {
				$this->db->insert_batch('client', [
					[
						'id'           => 1,
						'name'     	   => 'Web Imp',
						'uen' 			=> '123456789',
						'website'		=> 'www.webimp.com.sg',
						'phone'			=> '62773897828',
						'fax'			=> '62229901333',
						'email'			=> 'webimp@mail.com',
						'status'		=> 'Active',
						'created_by'	=> 1,
						'created_on'	=> time(),
						'deleted'		=> 0,
						'flagged'		=> 0
					],
				]);
			}
        }


    }

    public function down()
    {
        $this->dbforge->drop_table('client', true);
    }
}
