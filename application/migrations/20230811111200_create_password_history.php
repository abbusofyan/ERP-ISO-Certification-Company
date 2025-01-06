<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Password_History extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('password_history')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('user_id');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => true,
                    'unsigned'       => true,
                ],
                'password' => [
                    'type'           => 'TEXT',
                    'null'           => false,
                ],
            ]);

            $this->dbforge->create_table('password_history', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('password_history', true);
    }
}
