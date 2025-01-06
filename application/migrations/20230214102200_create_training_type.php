<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Training_Type extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('training_type')) {
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
			if ($this->dbforge->create_table('training_type', true)) {
				$this->db->insert_batch('training_type', [
					[
						'id'            => 1,
						'name'			=> 'ISO 9001:2015',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
					[
						'id'            => 2,
						'name'			=> 'ISO 14001:2015',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
					[
						'id'            => 3,
						'name'			=> 'ISO 45001:2018',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
					[
						'id'            => 4,
						'name'			=> 'Combination of all with 2 & 3',
						'created_on'	=> time(),
						'created_by'	=> 1
					],
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('training_type')) {
            $this->dbforge->drop_table('training_type', true);
        }
    }
}
