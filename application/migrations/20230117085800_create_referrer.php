<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Referrer extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('referrer')) {
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
            ]);

            // create table
			if ($this->dbforge->create_table('referrer', true)) {
				$this->db->insert_batch('referrer', [
					[
						'id'            => 1,
						'name'			=> 'User 2',
					],
					[
						'id'            => 2,
						'name'			=> 'User 3',
					],
					[
						'id'            => 3,
						'name'			=> 'Abu Sopyan',
					],
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('referrer')) {
            $this->dbforge->drop_table('referrer', true);
        }
    }
}
