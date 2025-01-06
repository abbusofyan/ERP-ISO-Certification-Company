<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Invoice_Status extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('invoice_status')) {
            $this->dbforge->add_key('id', true);

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
                ]
            ]);

            // create table
			if ($this->dbforge->create_table('invoice_status', true)) {
				$this->db->insert_batch('invoice_status', [
					[
						'id'            => 1,
						'name'			=> 'New',
					],
                    [
						'id'            => 2,
						'name'			=> 'Due',
					],
                    [
						'id'            => 3,
						'name'			=> 'Late',
					],
                    [
						'id'            => 4,
						'name'			=> 'Partially Paid',
					],
                    [
						'id'            => 5,
						'name'			=> 'Paid',
					],
                    [
						'id'            => 6,
						'name'			=> 'Cancelled',
					],
                    [
						'id'            => 7,
						'name'			=> 'Draft',
					]
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('invoice_status')) {
            $this->dbforge->drop_table('invoice_status', true);
        }
    }
}
