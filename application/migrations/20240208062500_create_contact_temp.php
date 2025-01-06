<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Contact_Temp extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('contact_temp')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('client_id');
			$this->dbforge->add_key('contact_id');
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
                'invoice_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => false,
                ],
				'client_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
				'contact_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
				'salutation' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => true,
					'default'    => NULL
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => true,
					'default'    => NULL
                ],
				'email' => [
					'type'       => 'VARCHAR',
					'constraint' => 150,
                    'null'       => true,
					'default'    => NULL
				],
				'position' => [
					'type'       => 'VARCHAR',
					'constraint' => 150,
                    'null'       => true,
					'default'    => NULL
				],
				'department' => [
					'type'       => 'VARCHAR',
					'constraint' => 150,
                    'null'       => true,
					'default'    => NULL
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
				'mobile' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => true,
					'default'    => NULL
                ],
				'status' => [
					'type'       => 'VARCHAR',
					'constraint' => 100,
                    'null'       => true,
					'default'    => NULL
				],
            ]);
            $this->dbforge->create_table('contact_temp', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('contact_temp', true);
    }
}
