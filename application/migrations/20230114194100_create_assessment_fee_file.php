<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Assessment_Fee_File extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('assessment_fee_file')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('file_id');
            $this->dbforge->add_key('quotation_id');

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
                    'unsigned'       => true,
                ],
                'file_id' => [
                    'type'           => 'BIGINT',
                    'constraint'     => 30,
                    'null'           => false,
                    'unsigned'       => true,
                ],
            ]);

            $this->dbforge->create_table('assessment_fee_file', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('assessment_fee_file', true);
    }
}
