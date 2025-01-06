<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Application_Form extends CI_Migration
{
	function up()
    {
        // invoice
        if (!$this->db->table_exists('application_form')) {
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
                'number' => [
                    'type'       	 => 'INT',
                    'constraint' 	 => 11,
                    'null'       	 => false,
                ],
				'client_name' => [
					'type'       => 'VARCHAR',
					'constraint' => 200,
					'null'       => false,
				],
                'standard' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
                'send_quotation_status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
                'send_date' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'receive_date' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
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
                'updated_on' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'unsigned'       => true,
					'default'		 => NULL
                ],
				'updated_by' => [
                    'type'           => 'INT',
                    'constraint'     => 15,
                    'unsigned'       => true,
					'default'		 => NULL
                ],
				'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
            ]);

            $this->dbforge->create_table('application_form', true);
        }
    }




    function down()
    {
        if ($this->db->table_exists('application_form')) {
            $this->dbforge->drop_table('application_form', true);
        }
    }
}
