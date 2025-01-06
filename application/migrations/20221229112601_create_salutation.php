<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Salutation extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('salutation')) {
            $this->dbforge->add_key('id', true);

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                ],
            ]);

			if ($this->dbforge->create_table('salutation', true)) {
				$this->db->insert_batch('salutation', [
					[
						'id'	=> 1,
						'name'	=> 'Mr'
					],
					[
						'id'	=> 2,
						'name'	=> 'Mrs'
					],
					[
						'id'	=> 3,
						'name'	=> 'Ms'
					],
					[
						'id'	=> 4,
						'name'	=> 'Mdm'
					],
					[
						'id'	=> 5,
						'name'	=> 'Dr'
					],
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('salutation', true);
    }
}
