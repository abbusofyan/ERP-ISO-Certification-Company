<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Client_Status extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('client_status')) {
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
			if ($this->dbforge->create_table('client_status', true)) {
				$this->db->insert_batch('client_status', [
					[
						'id'            => 1,
						'name'			=> 'New',
					],
                    [
						'id'            => 2,
						'name'			=> 'Active',
					],
                    [
						'id'            => 3,
						'name'			=> 'Past Active',
					],
                    [
						'id'            => 4,
						'name'			=> 'Non-Active',
					]
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('client_status')) {
            $this->dbforge->drop_table('client_status', true);
        }
    }
}
