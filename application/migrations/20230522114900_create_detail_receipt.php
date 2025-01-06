<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Detail_Receipt extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('detail_receipt')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('invoice_id');
			$this->dbforge->add_key('receipt_id');

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
                    'null'           => false,
                    'unsigned'       => true,
                ],
                'receipt_id' => [
					'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'paid_amount' => [
					'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                ],
				'invoice_status' => [
					'type'           => 'VARCHAR',
                    'constraint'     => 255,
                    'null'           => false,
                ],
            ]);

            $this->dbforge->create_table('detail_receipt', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('detail_receipt', true);
    }
}
