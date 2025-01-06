<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Address_History extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('address_history')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('client_id');
			$this->dbforge->add_key('address_id');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'client_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
				'address_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
				'name' => [
                    'type'       => 'text',
                    'null'       => false,
                ],
				'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => true,
					'default'    => NULL
                ],
				'fax' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => true,
					'default'    => NULL
                ],
				'address' => [
                    'type'       => 'text',
                    'null'       => false,
                ],
				'address_2' => [
                    'type'       => 'text',
                    'null'       => true,
					'default'    => NULL
                ],
				'country' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => false,
                ],
				'postal_code' => [
					'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => false,
                ],
				'total_employee' => [
					'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
					'default'    => NULL
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
                    'null'       => true,
					'default'    => 0
                ],
				'primary' => [
					'type'           => 'INT',
					'constraint'     => 10,
					'null'       => true,
					'default'    => 0
				],
            ]);

			if ($this->dbforge->create_table('address_history', true)) {
				$this->db->insert_batch('address_history', [
					[
						'id'           	=> 1,
						'client_id'     => 1,
						'address_id'     => 1,
						'name'			=> "company's main address",
						'address'		=> 'test address 1',
						'address_2'		=> 'test address 2',
						'phone'			=> '62773897828',
						'fax'			=> '62229901333',
						'postal_code'	=> '12345',
						'total_employee'=> 100,
						'country'		=> 'Singapore',
						'created_by'	=> 1,
						'created_on'	=> time(),
						'deleted'		=> 0,
						'primary'		=> 1
					],
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('address_history', true);
    }
}
