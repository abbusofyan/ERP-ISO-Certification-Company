<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Quotation_Address extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('quotation_address')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('quotation_id');
			$this->dbforge->add_key('address_history_id');

            // fields
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 30,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'quotation_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
				'address_history_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
            ]);

            // create table
			$this->dbforge->create_table('quotation_address', true);
        }
    }




    function down()
    {
        if ($this->db->table_exists('quotation_address')) {
            $this->dbforge->drop_table('quotation_address', true);
        }
    }
}
