<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Contact extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('contact')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('client_id');
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
				'client_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
				'salutation' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => false,
                ],
				'email' => [
					'type'       => 'VARCHAR',
					'constraint' => 150,
                    'null'		 => true,
					'default' 	 => NULL
				],
				'position' => [
					'type'       => 'VARCHAR',
					'constraint' => 150,
					'null'       => false,
				],
				'department' => [
					'type'       => 'VARCHAR',
					'constraint' => 150,
					'default'       => NULL,
				],
				'phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'default'       => NULL,
                ],
				'fax' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'default'       => NULL,
                ],
				'mobile' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => false,
                ],
				'status' => [
					'type'       => 'VARCHAR',
					'constraint' => 100,
                    'null'		 => true,
					'default' 	 => NULL
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
					'null'		 => true,
                    'default'    => 0,
                ],
				'primary' => [
					'type'       => 'INT',
					'constraint' => 1,
					'null'		 => true,
					'default' 	 => 0
				],
            ]);

			if ($this->dbforge->create_table('contact', true)) {
				$this->db->insert_batch('contact', [
					[
						'id'            => 1,
						'client_id'		=> 1,
						'salutation'	=> 'Mr',
						'name'			=> 'John',
						'email'			=> 'john@mail.com',
						'position'		=> 'Manager',
						'department'	=> 'HR',
						'phone'			=> '6277289332',
						'fax'			=> '6212309289',
						'mobile'		=> '67339823989',
						'status'		=> 'Active',
						'primary'		=> 1,
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('contact', true);
    }
}
