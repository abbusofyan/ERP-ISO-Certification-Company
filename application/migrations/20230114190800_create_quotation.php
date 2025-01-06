<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_Quotation extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('quotation')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('client_history_id');
			$this->dbforge->add_key('contact_history_id');
			$this->dbforge->add_key('address_history_id');
            $this->dbforge->add_key('created_by');
            $this->dbforge->add_key('updated_by');
			$this->dbforge->add_key('confirmed_by');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
				'number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
				'quote_date' => [
                    'type'       => 'DATE',
                    'null'       => false,
                ],
				'follow_up_date' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
					'default'	 => NULL
                ],
				'type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
				'training_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
					'default'	 => NULL
                ],
				'training_description' => [
                    'type'       => 'TEXT',
                    'null'       => true,
					'default'	 => NULL
                ],
				'certification_cycle' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
				'year_cycle' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'null'       => true,
					'default'	 => 0
                ],
				'certification_scheme' => [
					'type'           => 'VARCHAR',
                    'constraint'     => 255,
					'null'           => true,
					'default'	     => NULL
                ],
				'accreditation' => [
					'type'           => 'VARCHAR',
                    'constraint'     => 255,
					'null'           => true,
					'default'	     => NULL
                ],
				'status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
				'client_history_id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'contact_history_id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'address_history_id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 15,
                    'null'           => false,
                    'unsigned'       => true,
                ],
				'referred_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
					'null'           => true,
					'default'	     => NULL
                ],
				'received_prev_reports' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'           => true,
					'default'	     => NULL
                ],
				'prev_cert_issue_date' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 20,
                    'unsigned'       => true,
                    'null'           => true,
					'default'	     => NULL
                ],
				'prev_cert_exp_date' => [
                    'type'           => 'VARCHAR',
                    'constraint'     => 20,
                    'unsigned'       => true,
					'null'           => true,
					'default'	     => NULL
                ],
				'prev_certification_scheme' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'           => true,
					'default'	     => NULL
                ],
				'prev_accreditation' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 266,
                    'null'           => true,
					'default'	     => NULL
                ],
				'prev_certification_body' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
					'null'           => true,
					'default'	     => NULL
                ],
				'invoice_to' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
				'group_company' => [
                    'type'       => 'TEXT',
                    'null'           => true,
					'default'	     => NULL
                ],
				'consultant_pay_3_years' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 5,
                    'null'           => true,
					'default'	     => NULL
                ],
				'client_pay_3_years' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 5,
                    'null'           => true,
					'default'	     => NULL
                ],
				'application_form' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'           => true,
					'default'	     => NULL
                ],
				'terms_and_conditions' => [
                    'type'       => 'TEXT',
                    'null'           => true,
					'default'	     => NULL
                ],
				'scope' => [
                    'type'       => 'TEXT',
					'null'           => true,
					'default'	     => NULL
                ],
				'num_of_sites' => [
                    'type'       => 'INT',
					'constraint' => 3,
                    'null'           => true,
					'default'	     => 0
                ],
				'stage_audit' => [
                    'type'           => 'INT',
					'constraint' 	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'surveillance_year_1' => [
                    'type'           => 'INT',
					'constraint' 	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'surveillance_year_2' => [
                    'type'           => 'INT',
					'constraint' 	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'total_amount' => [
                    'type'           => 'INT',
					'constraint' 	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'discount' => [
                    'type'           => 'INT',
					'constraint' 	 => 20,
                    'null'           => true,
					'default'		 => NULL
                ],
				'payment_terms' => [
					'type'           => 'VARCHAR',
					'constraint' 	 => 100,
                    'null'           => true,
					'default'		 => NULL
                ],
				'duration' => [
                    'type'           => 'VARCHAR',
					'constraint' 	 => 255,
					'null'           => true,
					'default'		 => NULL
                ],
				'transportation' => [
                    'type'           => 'VARCHAR',
					'constraint' 	 => 100,
					'null'           => true,
					'default'		 => NULL
                ],
				'audit_fee' => [
                    'type'           => 'INT',
					'constraint' 	 => 10,
					'null'           => true,
					'default'		 => NULL
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
				'confirmed_by' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'unsigned'       => true,
                    'null'           => true,
					'default'		 => NULL
                ],
                'confirmed_on' => [
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
				'flagged' => [
                    'type'           => 'INT',
                    'constraint'     => 1,
					'null'			 => true,
					'default'		 => 0
                ],
            ]);

			$this->dbforge->create_table('quotation', true);
        }


    }

    public function down()
    {
        $this->dbforge->drop_table('quotation', true);
    }
}
