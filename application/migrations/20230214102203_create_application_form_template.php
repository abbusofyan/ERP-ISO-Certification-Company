<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Application_Form_Template extends CI_Migration
{
	function up()
    {
        // invoice
        if (!$this->db->table_exists('application_form_template')) {
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
                'file_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'       => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
				'notes' => [
					'after'			 => 'name',
					'type'           => 'TEXT',
					'null'           => true,
					'default'		 => NULL
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

            $this->dbforge->create_table('application_form_template', true);
        }
    }




    function down()
    {
        if ($this->db->table_exists('application_form_template')) {
            $this->dbforge->drop_table('application_form_template', true);
        }
    }
}
