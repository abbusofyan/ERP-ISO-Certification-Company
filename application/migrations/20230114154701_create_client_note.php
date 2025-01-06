<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Client_Note extends CI_Migration
{
    function up()
    {
        // invoice
        if (!$this->db->table_exists('client_note')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('client_id');
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
				'client_id' => [
					'type'           => 'BIGINT',
					'constraint'     => 30,
					'null'           => false,
					'unsigned'       => true,
				],
                'note' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                    'default'    => '',
                ],
                'created_on' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => false,
                    'unsigned'   => true,
                ],
                'created_by' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 20,
                    'unsigned'       => true,
                    'null'           => false,
                ],
            ]);

            // create table
            $this->dbforge->create_table('client_note', true);
        }
    }




    function down()
    {
        if ($this->db->table_exists('client_note')) {
            $this->dbforge->drop_table('client_note', true);
        }
    }
}
