<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Authorized_Ip extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('authorized_ip')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'ip' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 20,
                ],
				'role' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 20,
                ],
            ]);

            $this->dbforge->create_table('authorized_ip', true);

			// IP of user, cath, and developer
			$ips = [
				'122.11.177.250' => 'User',
				'118.189.152.146' => 'PM',
				'103.149.34.5' => 'Developer'
			];
			foreach ($ips as $ip => $role) {
				$this->db->insert('authorized_ip', [
					'ip'	=> $ip,
					'role' => $role
				]);
			}
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('authorized_ip', true);
    }
}
