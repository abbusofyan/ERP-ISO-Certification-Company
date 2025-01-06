<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Quotation_Notification extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('quotation_notification')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('quotation_id');

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
                    'unsigned'       => true,
                ],
				'status' => [
					'type'           => 'VARCHAR',
                    'constraint'     => 255,
					'default'		 => null
				],
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
					'default'		 => null
				],
				'updated_by' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
					'default'		 => null
				],
            ]);

            $this->dbforge->create_table('quotation_notification', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('quotation_notification', true);
    }
}
