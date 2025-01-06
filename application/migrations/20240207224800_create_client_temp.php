<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Client_Temp extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('client_temp')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('client_id');
            $this->dbforge->add_key('created_by');

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
                    'null'       => true,
                ],
            ]);

            $this->dbforge->create_table('client_temp', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('client_temp', true);
    }
}
