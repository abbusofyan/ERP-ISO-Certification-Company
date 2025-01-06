<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Accreditation extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('accreditation')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('created_by');
			$this->dbforge->add_key('updated_by');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'name' => [
                    'type'       => 'text',
                    'null'       => false,
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
					'null'           => true,
					'default'		 => 0
				],
            ]);

			if ($this->dbforge->create_table('accreditation', true)) {
				$this->db->insert_batch('accreditation', [
					[
						'id'            => 1,
						'name'			=> 'UKAS',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
					[
						'id'            => 2,
						'name'			=> 'UN ACC',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
					[
						'id'            => 3,
						'name'			=> 'SAC',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('accreditation', true);
    }
}
