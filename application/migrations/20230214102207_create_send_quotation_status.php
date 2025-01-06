<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Send_Quotation_Status extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('send_quotation_status')) {
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
			if ($this->dbforge->create_table('send_quotation_status', true)) {
				$this->db->insert_batch('send_quotation_status', [
					[
						'id'            => 1,
						'name'			=> 'App Form Sent',
					],
					[
						'id'            => 2,
						'name'			=> 'App Form Received',
					],
					[
						'id'            => 3,
						'name'			=> 'Quotation Not Sent',
					],
					[
						'id'            => 4,
						'name'			=> 'Quotation Sent',
					],
					[
						'id'            => 5,
						'name'			=> 'Dropped by Client',
					],
					[
						'id'            => 6,
						'name'			=> 'Dropped by ASA',
					]
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('send_quotation_status')) {
            $this->dbforge->drop_table('send_quotation_status', true);
        }
    }
}
