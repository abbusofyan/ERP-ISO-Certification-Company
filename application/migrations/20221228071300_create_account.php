<?php
defined('BASEPATH') or exit('No direct script access allowed');

use controllers\Accreditation;

class Migration_Create_Account extends CI_Migration
{
    private $password_hashed = '';

    private function use_config()
    {
        /*
         * If you have the parameter set to use the config table and join names
         * this will change them for you
         */
        $this->config->load('account', true);

        $this->load->model('user_model');

        $this->password_hashed = $this->user_model->hash_password('password');
    }

    public function up()
    {
        $this->use_config();


		if (!$this->db->table_exists('permissions')) {
            $this->dbforge->add_key('id', true);
			$this->dbforge->add_key('module_id');

            $this->dbforge->add_field([
				'id' => [
	                'type' => 'INT',
	                'constraint' => 11,
	                'auto_increment' => TRUE
	            ],
				'module_id' => [
	                'type' => 'INT',
	                'constraint' => 11,
					'null'           => false,
					'unsigned'       => true,
	            ],
	            'name' => [
	                'type' => 'VARCHAR',
	                'constraint' => 128,
	            ],
	            'display_name' => [
	                'type' => 'VARCHAR',
	                'constraint' => 30,
	            ],
	            'status' => [
	                'type' => 'TINYINT',
	                'constraint' => 1,
	                'default' => 1
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
				],
				'updated_on' => [
					'type'           => 'INT',
					'constraint'     => 11,
					'unsigned'       => true,
					'null'           => true,
				],
	            'deleted_at' => [
	                'type' => 'timestamp',
	                'default' => NULL,
	            ],
            ]);

			if ($this->dbforge->create_table('permissions', true)) {

				if (!$this->db->table_exists('modules')) {
		            $this->dbforge->add_key('id', true);
		            $this->dbforge->add_field([
						'id' => [
			                'type' => 'INT',
			                'constraint' => 11,
			                'auto_increment' => TRUE
			            ],
			            'name' => [
			                'type' => 'VARCHAR',
			                'constraint' => 255,
			            ],
		            ]);
					if ($this->dbforge->create_table('modules', true)) {
						$permissions = $this->_permission_list();
						$module_id = 1;
						foreach ($permissions as $module => $permission) {
							$module_name = $module;
							$this->db->insert('modules', [
								'id'	=> $module_id,
								'name'	=> $module_name
							]);
							foreach ($permission['method'] as $key => $method) {
								$module_slug = str_replace(" ", "-", strtolower($module_name));
								$new_permission = $this->db->insert('permissions', [
									'module_id'	=> $module_id,
									'name' 		=> $method,
									'display_name' => ucfirst($permission['display_name'][$key]),
									'status' 	=> 1,
									'created_by'	=> 1,
									'created_on'	=> time()
								]);
							}
						$module_id++;
						}
					}
		        }
            }
        }


		if (!$this->db->table_exists('group_permissions')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_field([
				'group_id' => [
	                'type' => 'INT',
	                'constraint' => 11,
	            ],
	            'permission_id' => [
	                'type' => 'INT',
	                'constraint' => 11,
	            ]
            ]);
			if ($this->dbforge->create_table('group_permissions', true)) {
                $this->db->insert_batch('group_permissions', [
                    [
                        'group_id'          => 1,
                        'permission_id'     => 1,
                    ],
					[
                        'group_id'          => 1,
                        'permission_id'     => 2,
                    ],
					[
                        'group_id'          => 1,
                        'permission_id'     => 3,
                    ],
					[
                        'group_id'          => 1,
                        'permission_id'     => 4,
                    ],
                ]);
            }
        }


        if (!$this->db->table_exists('group')) {
            $this->dbforge->add_key('id', true);

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'TINYINT',
                    'constraint'     => 1,
                    'unsigned'       => true,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'description' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
				'full_access' => [
                    'type'       => 'INT',
                    'constraint' => 1,
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
                ],
                'updated_on' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => true,
                ],
                'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
                    'default'    => 0,
                ],
            ]);

            if ($this->dbforge->create_table('group', true)) {
                $this->db->insert_batch('group', [
                    [
                        'id'          => 1,
                        'name'        => 'superadmin',
                        'description' => 'Superadmin',
						'full_access' => 1,
						'created_on'  => time(),
						'created_by'  => 1
                    ],
                ]);
            }
        }

        if (!$this->db->table_exists('user')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('group_id');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 10,
                    'null'           => false,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'group_id' => [
                    'type'       => 'TINYINT',
                    'constraint' => 5,
                    'null'       => false,
                    'unsigned'   => true,
                ],
                'first_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
				'last_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'null'       => false,
                ],
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => false,
                ],
                'contact' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => false,
                ],
				'reference_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => null,
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => false,
                ],
                'forgotten_password_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                    'default'    => null,
                ],
                'forgotten_password_time' => [
                    'type'    => 'TIMESTAMP',
                    'default' => null,
                ],
                'last_login' => [
                    'type'    => 'TIMESTAMP',
                    'default' => null,
                ],
				'status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 25,
                    'null'       => false,
                ],
                'deleted' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'unsigned'   => true,
                    'default'    => 0,
                ]
            ]);

            if ($this->dbforge->create_table('user', true)) {
                $this->db->insert_batch('user', [
                    [
                        'id'           => 1,
                        'group_id'     => 1,
						'first_name'   => 'Super',
						'last_name'    => 'Admin',
                        'email'        => 'asa@mail.com',
                        'contact'      => 63346659,
						'status'	   => 'Active',
                        'password'     => $this->password_hashed,
                    ],
                ]);
            }
        }

        if (!$this->db->table_exists('login_attempt')) {
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('ip_address');
            $this->dbforge->add_key('login');

            $this->dbforge->add_field([
                'id' => [
                    'type'           => 'MEDIUMINT',
                    'constraint'     => 8,
                    'unsigned'       => true,
                    'null'           => false,
                    'auto_increment' => true,
                ],
                'ip_address' => [
                    'type'       => 'VARBINARY',
                    'constraint' => 16,
                    'null'       => false,
                ],
                'login' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
                'time' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => false,
                ],
            ]);

            $this->dbforge->create_table('login_attempt', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('user', true);
		$this->dbforge->drop_table('group', true);
		$this->dbforge->drop_table('modules', true);
		$this->dbforge->drop_table('permissions', true);
		$this->dbforge->drop_table('group_permissions', true);
        $this->dbforge->drop_table('login_attempt', true);
    }

	private function _permission_list() {
		return [
			'Quotation' => [
				'method'	=> [
					'create-quotation',
					'read-quotation',
					'update-quotation',
					'delete-quotation',
				],
				'display_name' => [
					'Create', 'Read', 'Update', 'Delete'
				]
			],

			'Certification Scheme' => [
				'method'	=> [
					'create-certification-scheme',
					'read-certification-scheme',
					'update-certification-scheme',
					'delete-certification-scheme',
				],
				'display_name'	=> [
					'Create', 'Read', 'Update', 'Delete'
				]
			],

			'Accreditation'	=> [
				'method'	=> [
					'create-accreditation',
					'read-accreditation',
					'update-accreditation',
					'delete-accreditation',
				],
				'display_name'	=> [
					'Create', 'Read', 'Update', 'Delete'
				]
			],

			'Client' => [
				'method'	=> [
					'create-client',
					'read-client',
					'update-client',
					'delete-client',
					'import-client',
					'export-client'
				],
				'display_name' => [
					'Create', 'Read', 'Update', 'Delete', 'Import', 'Export'
				]
			],

			'Application Form' => [
				'method'	=> [
					'create-application-form',
					'read-application-form',
					'update-application-form',
					'delete-application-form',
				],
				'display_name'	=> [
					'Create', 'Read', 'Update', 'Delete'
				]
			],

			'Application Form Template' => [
				'method'	=> [
					'upload-application-form-template',
					'read-application-form-template',
					'download-application-form-template',
					'delete-application-form-template',
				],
				'display_name'	=> [
					'Upload', 'Read', 'Download', 'Delete'
				]
			],

			'User'	=> [
				'method'	=> [
					'create-user',
					'read-user',
					'update-user',
					'delete-user'
				],
				'display_name' => [
					'Create', 'Read', 'Update', 'Delete'
				]
			],

			'Invoice'	=> [
				'method'	=> [
					'create-invoice',
					'read-invoice',
					'update-invoice',
					'delete-invoice'
				],
				'display_name' => [
					'Create', 'Read', 'Update', 'Delete'
				]
			],

			'Finance Summary'	=> [
				'method'	=> [
					'create-finance-summary',
					'read-finance-summary',
					'update-finance-summary',
					'delete-finance-summary'
				],
				'display_name' => [
					'Create', 'Read', 'Update', 'Delete'
				]
			],
		];
	}
}
