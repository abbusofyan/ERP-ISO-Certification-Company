<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Application_Follow_Up_Attachment extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('application_follow_up_attachment')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('file_id');
            $this->dbforge->add_key('application_id');
			$this->dbforge->add_key('follow_up_id');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'application_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                ],
				'follow_up_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                ],
                'file_id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 30,
                    'null'           => false,
                    'unsigned'       => true,
                ],
            ]);

            $this->dbforge->create_table('application_follow_up_attachment', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('application_follow_up_attachment', true);
    }
}
