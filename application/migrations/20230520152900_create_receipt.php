<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Receipt extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('receipt')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('quotation_id');
			$this->dbforge->add_key('created_by');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'quotation_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                    'unsigned'       => true,
                ],
                'payment_method' => [
                    'type'           => 'VARCHAR',
					'constraint'     => 255,
                    'null'           => false,
                ],
				'paid_amount' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => false,
					'default'		 => 0
                ],
				'paid_date' => [
                    'type'           => 'DATE',
                    'null'           => false,
                ],
				'discount' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 15,
                    'null'           => true,
					'default'		 => NULL
                ],
				'transfer_date' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'received_date' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'cheque_received_date' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'cheque_no' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'cheque_date' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'bank' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'status' => array(
					'type'       => 'VARCHAR',
					'constraint' => 100,
					'null'       => true,
					'default' 	 => NULL
                ),
				'created_on' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'created_by' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'updated_on' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => true,
                ],
				'updated_by' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'null'           => true,
                ],
            ]);

            $this->dbforge->create_table('receipt', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('receipt', true);
    }
}
