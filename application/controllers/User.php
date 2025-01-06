<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends WebimpController
{
    public $asides = [
        'header'      => 'asides/header',
        'navbar_side' => 'asides/sidebar',
        'navbar_top'  => 'asides/navbar-top',
        'header_page' => 'asides/header-page',
        'alert'       => 'asides/alert',
        'footer'      => 'asides/footer',
    ];

    public $models = [
        'user',
		'module',
		'permission',
		'password_history'
    ];

    protected $layout = 'layouts/private';

	public function __construct()
	{
        parent::__construct();

        if (!$this->user_model->logged_in()) {
            $this->set_alert('Please login to continue.', 'error', true);
            $this->session->set_userdata('referred_from', current_url());
            redirect('login', 'refresh');
        }

		$this->load->library(['form_validation', 'auth']);
		// $this->auth->route_access();

        $this->data['current_user'] = $this->session->userdata();

        $this->data['page']['title'] = 'User';
        $this->add_breadcrumb('User');
    }




    /**
	 * User listing
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
        // load scripts
        $this->add_script(['datatable_scripts', 'sweetalert_scripts']);
		$this->data['modules'] = $modules = $this->module_model->with('permissions')->get_all();
		$groups = $this->group_model->with('permissions')->get_all();
		foreach ($groups as $group) {
			foreach ($group->permissions as $key => $permission) {
				$permission = $this->permission_model->with('module')->get($permission->permission_id);
				$group->permissions[$key] = $permission;
			}
		}
		$this->data['groups'] = $groups;
	}



	/**
	 * User group
	 *
	 * @access public
	 * @return void
	 */
	public function role()
	{
        // load scripts
        $this->add_script(['datatable_scripts', 'sweetalert_scripts']);
		$this->data['modules'] = $modules = $this->module_model->with('permissions')->order_by('name', 'ASC')->get_all();
		$groups = $this->group_model->with('permissions')->get_all();
		foreach ($groups as $group) {
			foreach ($group->permissions as $key => $permission) {
				$permission = $this->permission_model->with('module')->get($permission->permission_id);
				$group->permissions[$key] = $permission;
			}
		}
		$this->data['groups'] = $groups;
	}




	/**
	 * Create user page
	 *
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$inserted_id = $this->user_model->insert($post);

			if ($inserted_id) {
				$this->password_history_model->insert([
					'user_id' => $inserted_id,
					'password' => $post['password']
				]);
				$this->set_alert('User created!', 'success', true);
				redirect('user', 'refresh');
			} else {
				$this->set_alert("Can't create user.", 'error');
				redirect('user', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
		}
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

        $this->data['form'] = array_merge(
            $this->_generate_form_fields(),
            [
                'action' => site_url('user/create'),
            ]
        );
    }





    /**
	 * View user details
	 *
	 * @access public
	 * @param int $user_id
	 * @return void
	 */
	public function view($user_id)
	{
        // get user details
        $this->data['user'] = $user = $this->user_model->with('file')
                                                       ->with('group')
                                                       ->get($user_id);
        if (!$user) {
            redirect('user', 'refresh');
        }

        // load scripts
        $this->add_script([
            'datatable_scripts',
            'sweetalert_scripts'
        ]);

        $this->form_validation->set_rules($this->_attachment_validation_rules());

        if ($this->form_validation->run()) {
            // post
            $post = $this->input->post();
            $post['user_id'] = $user->id;

            if ( (isset($_FILES['attachment'])) && ($_FILES['attachment']['size'] > 0) ) {
                // upload
                $file_id = $this->file_model->process_uploaded_file($_FILES['attachment'], 'user_attachment');

                if (!$file_id) {
                    $this->set_alert($this->upload->display_errors(), 'error');
                    redirect('user/' . $user->id, 'refresh');
                } else {
                    $post['file_id'] = $file_id;
                }
            }

            // insert
            $inserted_id = $this->user_file_model->insert($post);

            if ($inserted_id) {
                $this->set_alert('Attachment file successfully uploaded!', 'success', true);
                redirect('user/' . $user->id, 'refresh');
            } else {
                $this->set_alert("Can't upload attachment file.", 'error');
                redirect('user/' . $user->id, 'refresh');
            }
        } else {
            $this->set_alert(validation_errors(), 'error');
        }

        $this->data['form'] = array_merge(
            $this->_generate_attachment_fields(),
            [
                'action' => site_url('user/' . $user->id),
            ]
        );
    }



	public function edit($user_id) {
		$this->add_script(['form_scripts', 'sweetalert_scripts']);
		if ($this->input->method() == 'post') {

			$post = $this->input->post();

			if ($post['new_password']) {
				$this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]|callback_not_reuse_password['.$this->input->post("password") . $user_id .' ]');
			}

			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[200]|regex_match[/^([a-z ])+$/i]');
			$this->form_validation->set_rules('contact', 'Mobile', 'trim|required|min_length[8]|max_length[8]');

			$this->form_validation->set_message('regex_match', "Special character not allowed for field First Name");
			$this->form_validation->set_message('not_reuse_password', "Cannot reuse old password");

			if ($this->user_changed_email($user_id, $post['email'])) {
				$this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]');
				$this->form_validation->set_message('is_unique', "Sorry, the %s is already being used.");
			}

			if ($this->form_validation->run()) {
				$post['password'] = $post['new_password'];
				unset($post['old_password']);
				unset($post['new_password']);
				$updated = $this->user_model->update($user_id, $post);
				if ($updated) {
					if ($post['password']) {
            			$this->password_history_model->insert([
							'user_id' => $user_id,
							'password' => $post['password']
						]);
					}
					$this->set_alert('User updated!', 'success', true);
					redirect('user', 'refresh');
				}
			} else {
				$this->set_alert(validation_errors(), 'error', true);
			}
		}
		$this->data['user'] = $user = $this->user_model->with('file')->with('group')->get($user_id);
		$this->data['form'] = array_merge(
			$this->_generate_form_fields((array) $user),
			[
				'action' => site_url('user/'. $user->id .'/edit'),
			]
		);
	}

	function not_reuse_password($password, $user_id) {
		$password_history = $this->password_history_model->get_many_by('user_id', $user_id);
		if(!$password_history) {
			return true;
		}

		$password_reused = false;
		foreach ($password_history as $old_password) {
			if(password_verify($password, $old_password->password)) {
				$password_reused = true;
			}
		}

		if($password_reused) {
			return false;
		}

		return true;
	}


	public function update($user_id) {
		$post = $this->input->post();
		if ($post['password']) {
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
		}
		if ($this->form_validation->run()) {
			if (!$post['password']) {
				unset($post['password']);
			}
			$updated = $this->user_model->update($user_id, $post);
			if ($updated) {
				$this->set_alert('User updated!', 'success', true);
				redirect('user', 'refresh');
			}
		}
		$this->set_alert(validation_errors(), 'error', true);
		redirect('user/' . $user_id . '/edit', 'refresh');
	}




    /**
	 * Edit user form
	 *
	 * @access public
     * @param int $user_id
	 * @return void
	 */
	public function form($user_id = null)
	{
        // load scripts
        $this->add_script(['form_scripts', 'sweetalert_scripts']);
        $this->load->library('upload');

        if ($user_id) {
            $this->data['user'] = $user = $this->user_model->with('file')->with('group')->get($user_id);
            if (!$user) {
                redirect('user', 'refresh');
            }

            //validate form input
            $this->form_validation->set_rules($this->_validation_rules($user_id));

            if ($this->form_validation->run()) {
                // post
                $post = $this->input->post();

                if ( (isset($_FILES['image'])) && ($_FILES['image']['size'] > 0) ) {
                    $file_id = $this->file_model->process_uploaded_file($_FILES['image'], 'user');
                    if (!$file_id) {
                        $this->set_alert($this->upload->display_errors(), 'error');
                        redirect('user/' . $user->id . '/form', 'refresh');
                    } else {
                        $post['file_id'] = $file_id;

                        // delete previous upload image related to the user
                        if (!empty($user->file_id)) {
                            $this->file_model->delete($user->file_id);
                        }
                    }
                }

                // update
                $update = $this->user_model->update($user_id, $post);

                if ($update) {
                    $this->set_alert('User updated!', 'success', true);
                    redirect('user/' . $user->id, 'refresh');
                } else {
                    $this->set_alert("Can't update user.", 'error');
                    redirect('user/' . $user->id, 'refresh');
                }
            } else {
                $this->set_alert(validation_errors(), 'error');
            }

            $this->data['form'] = array_merge(
                $this->_generate_fields((array) $user),
                [
                    'action' => site_url('user/'. $user->id .'/form'),
                ]
            );
        } else {
            redirect('user', 'refresh');
        }
    }




    /**
     * Download the user attachment file.
     *
     * @access public
     * @param int $user_file_id
     * @return void
     */
    public function download_attachment($user_file_id)
    {
        $this->view   = false;
        $this->layout = false;

        // get file
        $user_file = $this->user_file_model->with('file')->get((int) $user_file_id);

        if (!$user_file) {
            redirect('user', 'refresh');
        }

        $storageClient = register_stream_wrapper();
        $this->load->helper('download');
        $data  =   file_get_contents($user_file->file->path);
        $name  = $user_file->file->filename;

        // download
        force_download($name, $data);
    }




    /**
     * Delete user's attachment file.
     *
     * @access public
     * @param int $user_file_id
     * @return void
     */
    public function delete_attachment($user_file_id)
    {
        // get user file details
        $user_file = $this->user_file_model->with('file')->get((int) $user_file_id);

        if (!$user_file || (empty($user_file->file))) {
            redirect('user/' . $user_file->user_id . '/view', 'redirect');
        }

        // delete
        $deleted = $this->user_file_model->delete($user_file->id);

        if ($deleted) {
            $this->set_alert("Attachment file deleted!", 'success', true);
        } else {
            $this->set_alert("Attachment file cannot be deleted!", 'danger', true);
        }

        redirect('user/' . $user_file->user_id . '/view', 'redirect');
    }




    /**
     * Fields for user form.
     *
     * @access protected
     * @return void
     */
    protected function _generate_fields($data = null)
    {
        $data = parse_args($data, [
            'username'       => '',
            'name'           => '',
            'email'          => '',
            'contact'        => '',
            'reference_id'   => ''
        ]);

        $output = [
            'first_name' => [
                'id'          => 'first_name',
                'name'        => 'first_name',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('first_name', $data['first_name'], false),
                'placeholder' => 'first name',
                'maxlength'   => 200,
			],
			'last_name' => [
                'id'          => 'last_name',
                'name'        => 'last_name',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('last_name', $data['last_name'], false),
                'placeholder' => 'last name',
                'maxlength'   => 200,
			],
			'email' => [
                'id'          => 'email',
                'type'        => 'email',
                'name'        => 'email',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('email', $data['email'], false),
                'placeholder' => 'johndoe@gmail.com',
                'maxlength'   => 150,
            ],
            'contact' => [
                'id'          => 'contact',
                'name'        => 'contact',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('contact', $data['contact']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
            ],
            'reference_id' => [
                'id'          => 'reference_id',
                'name'        => 'reference_id',
                'class'       => 'form-control',
                'value'       => set_value('reference_id', $data['reference_id'], false),
                'placeholder' => 'S1234567A',
                'maxlength'   => 50,
			],
            'password' => [
                'id'          => 'password',
                'name'        => 'password',
                'type'        => 'password',
                'class'       => 'form-control',
                'maxlength'   => 30,
            ],
            'confirm_password' => [
                'id'          => 'confirm_password',
                'name'        => 'confirm_password',
                'type'        => 'password',
                'class'       => 'form-control',
                'maxlength'   => 30,
            ],
        ];

        return $output;
    }




    /**
     * Fields for user attachment form.
     *
     * @access protected
     * @return void
     */
    protected function _generate_attachment_fields()
    {
        $output = [
			'name' => [
                'id'          => 'name',
                'name'        => 'name',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('name'),
                'placeholder' => 'Offer Letter',
                'maxlength'   => 200,
			],
			'attachment' => [
                'id'          => 'attachment',
                'type'        => 'file',
                'name'        => 'attachment',
                'class'       => 'form-control-file',
                'accept'      => 'application/msword, application/vnd.ms-excel, application/pdf, image/*',
                'required'    => true,
            ]
        ];

        return $output;
    }




    /**
     * Validation rules for user attachment form.
     *
     * @access protected
     * @return void
     */
    protected function _attachment_validation_rules()
    {
        $rules = [
            [
                'field' => 'name',
                'label' => 'File Name',
                'rules' => 'trim|required|max_length[200]',
			]
        ];


        return $rules;
    }




	/**
     * Fields for add user form.
     *
     * @access protected
     * @return void
     */
    protected function _generate_form_fields($data = null)
    {
		$group_options = ['' => '-- Please select Role --'] + $this->group_model->dropdown('description');
		$status_options = ['' => '-- Please select Status --'] + ['Active' => 'Active'] + ['Non-Active' => 'Non-Active'];

		if ($data == null) {
			$data = [
				'first_name'	=> '',
				'last_name'		=> '',
				'email'			=> '',
				'contact'		=> '',
				'group_id'		=> '',
				'status'		=> '',
				'password'		=> '',
			];
		}

        $output = [
			'first_name' => [
                'id'          => 'first_name',
                'name'        => 'first_name',
                'class'       => 'form-control',
                'maxlength'   => 200,
				'value'       => set_value('first_name', $data['first_name']),
                'placeholder' => 'Type first name',
			],
            'last_name' => [
                'id'          => 'last_name',
                'name'        => 'last_name',
                'class'       => 'form-control',
                'maxlength'   => 200,
                'value'       => set_value('last_name', $data['last_name']),
                'placeholder' => 'Type last name',
			],
            'email' => [
                'id'          => 'email',
                'name'        => 'email',
                'class'       => 'form-control',
                'maxlength'   => 200,
                'value'       => set_value('email', $data['email']),
			],
			'contact' => [
				'id'          => 'contact',
				'name'        => 'contact',
				'type'        => 'number',
				'class'       => 'form-control',
				'maxlength'   => 200,
				'value'       => set_value('contact', $data['contact']),
			],
			'group' => [
                'name'        => 'group_id',
                'options'     => $group_options,
                'selected'    => set_value('group_id', $data['group_id']),
                'attr'        => [
                    'id'          => 'group_id',
                    'class'       => 'form-control select2 select-select2',
                ],
            ],
			'password' => [
                'id'          => 'password',
                'name'        => 'password',
                'type'        => 'password',
                'class'       => 'form-control',
				'value'		  => set_value('password', $data['password']),
                'maxlength'   => 30,
            ],
            'confirm_password' => [
                'id'          => 'confirm_password',
                'name'        => 'confirm_password',
                'type'        => 'password',
                'class'       => 'form-control',
                'maxlength'   => 30,
            ],
			'old_password' => [
                'id'          => 'old_password',
                'name'        => 'old_password',
                'type'        => 'password',
                'class'       => 'form-control',
				'value'       => set_value('password', $data['password']),
                'maxlength'   => 30,
				'readonly'	  => true
            ],
            'new_password' => [
                'id'          => 'new_password',
                'name'        => 'new_password',
                'type'        => 'password',
                'class'       => 'form-control',
                'maxlength'   => 30,
            ],
			'status' => [
                'name'        => 'status',
                'options'     => $status_options,
                'selected'    => set_value('status', $data['status']),
                'attr'        => [
                    'id'          => 'group_id',
                    'class'       => 'form-control select2 select-select2',
                ],
            ],
        ];

        return $output;
    }




    /**
     * To check a fields is already being used in respected table in database
     *
     * @param $value (sent from codeigniter form_validation callback function)
     * @param $params ($table.$field.$current_id)
     *
     * @return bool
     */
    public function edit_unique($value, $params)  {
        $this->form_validation->set_message('edit_unique', "Sorry, the %s is already being used.");

        list($table, $field, $current_id) = explode(".", $params);

        $query = $this->db->select()->from($table)->where($field, $value)->limit(1)->get();

        if ($query->row() && $query->row()->id != $current_id)
        {
            return FALSE;
        } else {
            return TRUE;
        }
    }


	public function delete($user_id) {
		$deleted = $this->user_model->delete($user_id);
		if ($deleted) {
			$this->set_alert('User deleted!', 'success', true);
			redirect('user', 'refresh');
		} else {
			$this->set_alert("Can't delete user.", 'error');
			redirect('user', 'refresh');
		}
	}


	public function user_changed_email($user_id, $new_email) {
		$user = $this->user_model->get($user_id);
		if ($user) {
			$old_email = $user->email;
			if ($old_email != $new_email) {
				return true;
			}
			return false;
		}
		return false;
	}

	public function grant() {
		$post = $this->input->post();
		if ($post) {
			$this->authorized_ip_model->insert($post);
			$this->set_alert('Access Granted', 'success', true);
			redirect('user/grant', 'refresh');
		}
	}
}
