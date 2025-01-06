<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Quotation_Type extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('quotation_type')) {
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
                    'type'       => 'TEXT',
                    'null'       => true,
					'default'	 => NULL
                ],
            ]);

            // create table
			if ($this->dbforge->create_table('quotation_type', true)) {
				$this->db->insert_batch('quotation_type', [
					[
						'id'           	=> 1,
						'name'     	   	=> 'ISO',
						'description'	=> 'ISO'
					],
					[
						'id'           	=> 2,
						'name'     	   	=> 'Bizsafe',
						'description'	=> 'Bizsafe'
					],
					[
						'id'           	=> 3,
						'name'     	   	=> 'Training',
						'description'	=> 'Training'
					],
				]);
			}
        }
    }




    function down()
    {
        if ($this->db->table_exists('quotation_type')) {
            $this->dbforge->drop_table('quotation_type', true);
        }
    }
}
