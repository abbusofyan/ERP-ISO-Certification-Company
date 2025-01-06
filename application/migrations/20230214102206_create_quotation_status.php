<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Quotation_Status extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('quotation_status')) {
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
			if ($this->dbforge->create_table('quotation_status', true)) {
				$this->db->insert_batch('quotation_status', [
					[
						'id'            => 1,
						'name'			=> 'New',
					],
                    [
						'id'            => 2,
						'name'			=> 'Confirmed',
					],
                    [
						'id'            => 3,
						'name'			=> 'Chosen Other CB',
					],
                    [
						'id'            => 4,
						'name'			=> 'On-Hold',
					],
					[
						'id'            => 5,
						'name'			=> 'Non-Active',
					],
                    [
						'id'            => 6,
						'name'			=> 'Dropped by ASA',
					],
                    [
						'id'            => 7,
						'name'			=> 'Dropped by Client',
					],

				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('quotation_status')) {
            $this->dbforge->drop_table('quotation_status', true);
        }
    }
}
