<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Certification_Scheme extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('certification_scheme')) {
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
					'null'		 => true,
					'default'    => 0,
				],
            ]);

			if ($this->dbforge->create_table('certification_scheme', true)) {
				$this->db->insert_batch('certification_scheme', [
					[
						'id'            => 1,
						'name'			=> 'Certification Scheme 1',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
					[
						'id'            => 2,
						'name'			=> 'Certification Scheme 2',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
					[
						'id'            => 3,
						'name'			=> 'Certification Scheme 3',
						'created_by'	=> 1,
						'created_on'	=> time(),
					],
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('certification_scheme', true);
    }
}
