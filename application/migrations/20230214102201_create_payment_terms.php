<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Payment_Terms extends CI_Migration
{
	function up()
    {
        // invoice
        if (!$this->db->table_exists('payment_terms')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('created_by');

            // fields
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 30,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
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
            ]);

            // create table
			if ($this->dbforge->create_table('payment_terms', true)) {
				$this->db->insert_batch('payment_terms', [
					[
						'id'            => 1,
						'name'			=> '50% Upon Confirmation & 50% Upon Completion',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
					[
						'id'            => 3,
						'name'			=> '100% Upon Confirmation',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
					[
						'id'            => 4,
						'name'			=> '100% Upon Completion',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('payment_terms')) {
            $this->dbforge->drop_table('payment_terms', true);
        }
    }
}
