<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Memo extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('memo')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('quotation_id');
			$this->dbforge->add_key('created_by');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'quotation_id' => [
					'type'           => 'INT',
					'constraint'     => 10,
					'null'           => true,
					'unsigned'       => true,
				],
				'number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
				'type' => [
                    'type'           => 'VARCHAR',
					'constraint'	=> 1,
                    'null'           => false,
                ],
				'memo_date' => [
                    'type'           => 'DATE',
                    'null'           => false,
                ],
				'message' => [
                    'type'           => 'TEXT',
                    'null'           => false,
                ],
				'status' => [
					'type'			=> 'VARCHAR',
					'constraint'	=> 50,
					'null'			=> true,
					'default'		=> NULL
				],
				'sign_file_id' => [
					'type'       => 'MEDIUMINT',
					'constraint' => 25,
					'unsigned'   => true,
					'default'    => null,
				],
				'stamp_file_id' => [
					'type'       => 'MEDIUMINT',
					'constraint' => 25,
					'unsigned'   => true,
					'default'    => null,
				],
				'created_by' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => false,
                ],
                'created_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => false,
                ],
				'updated_by' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => true,
					'default'		 => NULL
                ],
                'updated_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => true,
					'default'		 => NULL
                ],
                'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
					'null'		 => true,
                    'default'    => 0,
                ],
            ]);
            $this->dbforge->create_table('memo', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('memo', true);
    }
}
