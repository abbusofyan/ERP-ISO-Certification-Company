<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Certification_Cycle extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('certification_cycle')) {
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
                ],
                'description' => [
                    'type'       => 'text',
                    'null'       => true,
					'default'	 => NULL
                ],
            ]);

            // create table
			if ($this->dbforge->create_table('certification_cycle', true)) {
				$this->db->insert_batch('certification_cycle', [
					[
						'id'           	=> 1,
						'name'     	   	=> 'New',
						'description'	=> 'New'
					],
					[
						'id'           	=> 2,
						'name'     	   	=> 'Transfer 1st Year Surveillance',
						'description'	=> 'Transfer 1st Year Surveillance'
					],
					[
						'id'           	=> 3,
						'name'     	   	=> 'Transfer 2nd Year Surveillance',
						'description'	=> 'Transfer 2nd Year Surveillance'
					],
					[
						'id'           	=> 4,
						'name'     	   	=> 'Re-Audit',
						'description'	=> 'Re-Audit'
					],
					[
						'id'           	=> 5,
						'name'     	   	=> 'Re-Audit New',
						'description'	=> 'Re-Audit New'
					],
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('certification_cycle')) {
            $this->dbforge->drop_table('certification_cycle', true);
        }
    }
}
