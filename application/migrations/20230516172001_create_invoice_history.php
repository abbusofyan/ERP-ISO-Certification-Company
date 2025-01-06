<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Invoice_History extends CI_Migration
{
    public function up()
    {
		// $this->dbforge->drop_table('invoice');
        if (!$this->db->table_exists('invoice_history')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('quotation_id');
			$this->dbforge->add_key('invoice_id');
			$this->dbforge->add_key('client_history_id');
			$this->dbforge->add_key('address_history_id');
			$this->dbforge->add_key('contact_history_id');
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
                    'null'           => true,
                    'unsigned'       => true,
                ],
				'number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
                'quotation_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => true,
                    'unsigned'       => true,
                ],
				'client_history_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => true,
                    'unsigned'       => true,
                ],
				'address_history_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => true,
                    'unsigned'       => true,
                ],
				'contact_history_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => true,
                    'unsigned'       => true,
                ],
				'invoice_date' => [
					'type'           => 'VARCHAR',
					'constraint'	 => 20,
					'null'           => true,
					'default'		 => NULL
				],
				'invoice_type' => [
					'type'           => 'VARCHAR',
					'constraint'	 => 255,
					'null'           => false,
				],
				'amount' => [
                    'type'           => 'INT',
					'constraint'	 => 11,
                    'null'           => true,
					'default'		 => 0
                ],
				'paid' => [
                    'type'	=> 'INT',
					'constraint' => 20,
                    'null'  => true,
					'default'	=> 0,
                ],
				'audit_fixed_date' => [
                    'type'           => 'VARCHAR',
					'constraint'	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'follow_up_date' => [
					'type'           => 'VARCHAR',
					'constraint'	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'paid_date' => [
					'type'           => 'VARCHAR',
					'constraint'	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'status' => [
					'type'			=> 'VARCHAR',
					'constraint'	=> 50,
					'null'			=> true,
					'defualt'		=> NULL
				],
				'request_status' => [
					'type'			=> 'VARCHAR',
					'constraint'	=> 50,
					'null'			=> true,
					'defualt'		=> NULL
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
                    'null'           => true,
					'defualt'		=> NULL
                ],
                'updated_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => true,
					'defualt'		=> NULL
                ],
				'approved_by' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => true,
					'defualt'		=> NULL
                ],
                'approved_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => true,
					'defualt'		=> NULL
                ],
				'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
					'null'		 => true,
                    'default'    => 0,
                ],
            ]);
            $this->dbforge->create_table('invoice_history', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('invoice_history', true);
    }
}
