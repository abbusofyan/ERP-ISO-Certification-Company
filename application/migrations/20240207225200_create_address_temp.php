<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Address_Temp extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('address_temp')) {
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
				'address_id' => [
                    'type'       => 'Int',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
				'name' => [
                    'type'       => 'text',
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
            ]);

            $this->dbforge->create_table('address_temp', true);

        }
    }

    public function down()
    {
        $this->dbforge->drop_table('address_temp', true);
    }
}
