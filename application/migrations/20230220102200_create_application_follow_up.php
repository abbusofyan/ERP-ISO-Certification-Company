<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Application_Follow_Up extends CI_Migration
{
	function up()
    {
        // invoice
        if (!$this->db->table_exists('application_follow_up')) {
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
                'application_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'       => true,
                ],
                'notes' => [
                    'type'       => 'TEXT',
                    'null'       => true,
					'default'	 => NULL
                ],
                'clarification_date' => [
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
				'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
					'null'		 => true,
                    'default'    => 0,
                ],
            ]);

            $this->dbforge->create_table('application_follow_up', true);
        }
    }




    function down()
    {
        if ($this->db->table_exists('application_follow_up')) {
            $this->dbforge->drop_table('application_follow_up', true);
        }
    }
}
