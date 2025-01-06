<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends WebimpController
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
        'client',
		'client_history',
		'contact_history',
		'contact',
        'file',
		'client_note'
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

        $this->data['current_user'] = $this->session->userdata();

        $this->load->library('form_validation');

        $this->data['page']['title'] = 'Contact';
        $this->add_breadcrumb('Client');
    }




    /**
	 * Client listing
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

		$this->form_validation->set_rules($this->_validation_note_rules());

		if ($this->form_validation->run()) {

			$post = $this->input->post();

			$inserted_id = $this->note_model->insert($post);

			if ($inserted_id) {
				$this->set_alert('Note created!', 'success', true);
				redirect('client', 'refresh');
			} else {
				$this->set_alert("Can't create client.", 'error');
				redirect('client', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error');
		}

		$this->data['form'] = array_merge(
			$this->_generate_note_fields(),
			[
				'action' => site_url('client'),
				'action_main_contact' => site_url('client/change_main_contact'),
			]
		);
    }




	/**
	 * Client listing
	 *
	 * @access public
	 * @return void
	 */
	public function create()
	{
		$this->add_script(['datatable_scripts', 'sweetalert_scripts']);

		$this->form_validation->set_rules($this->_validation_rules());

		$this->form_validation->set_message('validate_url','Website URL is invalid!');

		if ($this->form_validation->run()) {

			$post = $this->input->post();

			$client = [
				'name'			=> $post['name'],
				'uen'			=> $post['uen'],
				'address'		=> $post['address'],
				'address_2'		=> $post['address_2'],
				'postal_code'	=> $post['postal_code'],
				'website'		=> $post['website'],
				'phone'			=> $post['phone'],
				'fax'			=> $post['fax'],
				'email'			=> $post['email'],
				'status'		=> $post['status'],
			];

			$client_id = $this->client_model->insert($client);

			if ($client_id) {
				$primary = 1;
				foreach ($post['contact_name'] as $key => $contact) {
					$data = [
						'client_id' 	=> $client_id,
						'salutation'	=> $post['salutation'][$key],
						'name' 			=> $post['contact_name'][$key],
						'email'			=> $post['contact_email'][$key],
						'position'		=> $post['position'][$key],
						'department'	=> $post['department'][$key],
						'phone'			=> $post['contact_phone'][$key],
						'fax'			=> $post['contact_fax'][$key],
						'mobile'		=> $post['contact_mobile'][$key],
						'status'		=> $post['contact_status'][$key],
						'primary'		=> $primary
					];
					$this->contact_model->insert($data);
					$primary = 0;
				}
			} else {
				$this->set_alert("Can't create client.", 'error');
				redirect('client', 'refresh');
			}

			$this->set_alert('Client created!', 'success', true);
			redirect('client', 'refresh');
		} else {
			$this->set_alert(validation_errors(), 'error');
		}

		$this->data['form'] = array_merge(
			$this->_generate_fields(),
			$this->_generate_multiple_contact_fields(),
			[
				'action' => site_url('client/create'),
			]
		);

	}





    /**
	 * View client details
	 *
	 * @access public
	 * @param int $client_id
	 * @return void
	 */
	public function view($client_id)
	{
        // get client details
        $this->data['client'] = $client = $this->client_model->with('contact')->get($client_id);
        if (!$client) {
            redirect('client', 'refresh');
        }

		$this->data['primary_contact'] = $this->contact_model->get_by('client_id', $client_id);

        $this->form_validation->set_rules($this->_validation_create_contact_rules());

        if ($this->form_validation->run()) {

            $post = $this->input->post();
            $post['client_id'] = $client->id;

            $contact_id = $this->contact_model->insert($post);

            if ($contact_id) {
                $this->set_alert('Contact successfully created!', 'success', true);
                redirect('client/' . $client->id, 'refresh');
            } else {
                $this->set_alert("Can't create contact.", 'error');
                redirect('client/' . $client->id, 'refresh');
            }
        } else {
            $this->set_alert(validation_errors(), 'error');
        }

        $this->data['form'] = array_merge(
            $this->_generate_contact_fields(),
            [
                'action' => site_url('client/' . $client->id),
            ]
        );

        // load scripts
        $this->add_script([
            'datatable_scripts',
            'sweetalert_scripts'
        ]);
    }




    /**
	 * Edit client form
	 *
	 * @access public
     * @param int $client_id
	 * @return void
	 */
	public function update($contact_id = null)
	{
        if (can(['update-client', 'update-quotation'])) {
			if ($contact_id) {
				$contact = $this->contact_model->get($contact_id);
	            if ($this->form_validation->run()) {
	                $post = $this->input->post();
					$this->contact_model->set_contact_id($contact_id);
					$updated = $this->contact_model->update($contact_id, $post);

	                if ($updated) {
						$this->set_alert('Contact information successfully updated!', 'success', true);
						redirect('client/view/'.$contact->client_id, 'refresh');
	                } else {
						$this->set_alert("Can't update contact.", 'error');
						redirect('client/view/'.$contact->client_id, 'refresh');
	                }
	            } else {
	                $this->set_alert(validation_errors(), 'error', true);
					redirect('client/view/'.$contact->client_id, 'refresh');
	            }
	        }
        } else {
			$this->set_alert('Action not allowed!', 'error', true);
			redirect('client/view/'.$contact_id, 'refresh');
		}
    }




	/**
	 * Client listing
	 *
	 * @access public
	 * @return void
	 */
	public function change_main_contact()
	{
		$this->form_validation->set_rules($this->_validation_change_main_contact_rules());

		if ($this->form_validation->run()) {

			$post = $this->input->post();
			$update = $this->contact_model->switch_main_contact($post);

			if ($update) {
				$this->set_alert('Main contact switched!', 'success', true);
				redirect('client', 'refresh');
			} else {
				$this->set_alert("Can't switch main contact.", 'error');
				redirect('client', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error');
			redirect('client', 'refresh');
		}
	}




	public function store() {
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$contact_id = $this->contact_model->insert($post);

			if ($contact_id) {
				$this->set_alert('Contact successfully created!', 'success', true);
				redirect('client/view/' . $post['client_id'], 'refresh');
			} else {
				$this->set_alert("Can't create contact.", 'error', true);
				redirect('client/view/' . $post['client_id'], 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('client/view/' . $this->input->post('client_id'), 'refresh', true);
		}
	}






	/**
	 * Fields for note form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _generate_note_fields($data = null)
	{
		$data = parse_args($data, [
			'note'            => '',
		]);

		$output = [
			'note' => [
				'id'          => 'note',
                'name'        => 'note',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('note', $data['note'], false),
                'placeholder' => 'Write note here',
                'maxlength'   => 200,
                'rows'        => 3,
			]
		];

		return $output;
	}



	/**
     * Fields for contact form.
     *
     * @access protected
     * @return void
     */
    protected function _generate_multiple_contact_fields($data = null)
    {
		$salutation_options = [
			'' => '-- Choose Salutation --',
			'Mr' => 'Mr',
			'Mrs' => 'Mrs',
			'Ms' => 'Ms',
			'Mdm' => 'Mdm',
			'Dr' => 'Dr'
		];

		$status_options = [
			'' => '-- Please select Status --',
			'Active' => 'Active',
			'Past' => 'Past',
		];

        $output = [
			'salutation' => [
                'name'        => 'salutation[]',
                'options'     => $salutation_options,
                'selected'    => set_value('salutation'),
                'attr'        => [
                    'id'          => 'salutation',
                    'class'       => 'form-control select2 select-select2',
                    'required'    => true,
                ],
            ],
			'contact_name' => [
                'id'          => 'name',
                'name'        => 'contact_name[]',
                'class'       => 'form-control',
                'required'    => true,
                'placeholder' => '',
                'maxlength'   => 200,
			],
			'contact_email' => [
				'id'          => 'email',
				'name'        => 'contact_email[]',
				'class'       => 'form-control',
				'required'    => true,
				'type'		  => 'email',
				'placeholder' => 'company@mail.com',
				'maxlength'   => 150,
			],
			'position' => [
                'id'          => 'position',
                'name'        => 'position[]',
                'class'       => 'form-control',
                'required'    => true,
                'placeholder' => '',
                'maxlength'   => 150,
			],
            'department' => [
                'id'          => 'department',
                'name'        => 'department[]',
                'class'       => 'form-control',
                'required'    => true,
                'placeholder' => '',
                'maxlength'   => 200,
            ],
			'contact_phone' => [
                'id'          => 'phone',
                'name'        => 'contact_phone[]',
                'class'       => 'form-control',
                'placeholder' => '6344488889',
                'maxlength'   => 20,
			],
			'contact_fax' => [
                'id'          => 'fax',
                'name'        => 'contact_fax[]',
                'class'       => 'form-control',
                'placeholder' => '6344488889',
                'maxlength'   => 150,
			],
			'contact_mobile' => [
                'id'          => 'mobile',
                'name'        => 'contact_mobile[]',
                'class'       => 'form-control',
                'required'    => true,
                'placeholder' => '6344488889',
                'maxlength'   => 150,
			],
			'contact_status' => [
                'name'        => 'contact_status[]',
                'options'     => $status_options,
                'selected'    => set_value('status'),
                'attr'        => [
                    'id'          => 'status',
                    'class'       => 'form-control select2 select-select2',
                    'required'    => true,
                ],
            ],
        ];

        return $output;
    }




    /**
     * Fields for contact form.
     *
     * @access protected
     * @return void
     */
    protected function _generate_fields($data = null)
    {
        $data = parse_args($data, [
			'name'				=> '',
			'salutation'		=> '',
			'status'			=> '',
			'email'				=> '',
			'position'			=> '',
			'department'		=> '',
			'phone'				=> '',
			'fax'				=> '',
			'mobile'			=> ''
        ]);

		$salutation_options = [
			'' => '-- Choose Salutation --',
			'Mr' => 'Mr',
			'Mrs' => 'Mrs',
			'Ms' => 'Ms',
		];

		$status_options = [
			'' => '-- Please select Status --',
			'Active' => 'Active',
			'Past' => 'Past',
		];

        $output = [
			'name' => [
                'id'          => 'name',
                'name'        => 'name',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('name', $data['name'], false),
                'maxlength'   => 200,
			],
			'email' => [
                'id'          => 'email',
                'name'        => 'email',
                'class'       => 'form-control',
				'type' 		  => 'email',
                'value'       => set_value('email', $data['email']),
                'placeholder' => 'example@mail.com',
                'maxlength'   => 200,
			],
			'phone' => [
                'id'          => 'phone',
                'name'        => 'phone',
                'class'       => 'form-control',
                'value'       => set_value('phone', $data['phone']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
				'type'		  => 'number'
            ],
			'fax' => [
                'id'          => 'fax',
                'name'        => 'fax',
                'class'       => 'form-control',
                'value'       => set_value('fax', $data['fax']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
				'type'		  => 'number'
            ],
			'mobile' => [
                'id'          => 'mobile',
                'name'        => 'mobile',
                'class'       => 'form-control',
                'value'       => set_value('mobile', $data['mobile']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
				'type'		  => 'number'
            ],
            'position' => [
                'id'          => 'position',
                'name'        => 'position',
                'class'       => 'form-control',
                'value'       => set_value('position', $data['position']),
                'maxlength'   => 200,
			],
			'department' => [
                'id'          => 'department',
                'name'        => 'department',
                'class'       => 'form-control',
                'value'       => set_value('department', $data['department']),
                'maxlength'   => 200,
			],
			'status' => [
                'name'        => 'status',
                'options'     => $status_options,
                'selected'    => set_value('status', $data['status']),
                'attr'        => [
                    'id'          => 'status',
                    'class'       => 'form-control select2 select-select2',
                    'required'    => true,
                ],
            ],
			'salutation' => [
                'name'        => 'salutation',
                'options'     => $salutation_options,
                'selected'    => set_value('salutation', $data['salutation']),
                'attr'        => [
                    'id'          => 'salutation',
                    'class'       => 'form-control select2 select-select2',
                    'required'    => true,
                ],
            ],
        ];

        return $output;
    }





	/**
	 * Validation rules for client form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_note_rules()
	{
		$rules = [
			[
				'field' => 'client_id',
				'label' => 'Client id',
				'rules' => 'trim|required',
			],
			[
				'field' => 'note',
				'label' => 'Note',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}




	/**
	 * Validation rules for change client's main contact.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_change_main_contact_rules()
	{
		$rules = [
			[
				'field' => 'client_id',
				'label' => 'Client id',
				'rules' => 'trim|required',
			],
			[
				'field' => 'contact_id',
				'label' => 'contact_id id',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}





	/**
	 * Validation rules contact
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_rules()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required',
			],
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'trim|required',
			],
			[
				'field' => 'position',
				'label' => 'Position',
				'rules' => 'trim|required',
			],
			[
				'field' => 'department',
				'label' => 'Department',
				'rules' => 'trim|required',
			],
			[
				'field' => 'phone',
				'label' => 'Phone',
				'rules' => 'trim|required',
			],
			[
				'field' => 'mobile',
				'label' => 'Mobile',
				'rules' => 'trim|required',
			],
			[
				'field' => 'fax',
				'label' => 'Fax',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}





    /**
     * Validation rules for branch form.
     *
     * @access protected
     * @return void
     */
    protected function _validation_branch_rules()
    {
        $rules = [
            [
                'field' => 'code',
                'label' => 'Branch Code',
                'rules' => 'trim|required|max_length[100]',
			],
            [
                'field' => 'tel_no',
                'label' => 'Tel No',
                'rules' => 'trim|max_length[25]',
			],
            [
                'field' => 'tel_no_2',
                'label' => 'Tel No (Optional)',
                'rules' => 'trim|max_length[25]',
			],
            [
                'field' => 'fax_no',
                'label' => 'Fax No',
                'rules' => 'trim|max_length[25]',
			],
			[
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|required|max_length[200]',
			],
			[
                'field' => 'bic_name',
                'label' => 'Manager/BIC Name',
                'rules' => 'trim|max_length[200]',
			],
            [
                'field' => 'bic_designation',
                'label' => 'Manager/BIC Designation',
                'rules' => 'trim|max_length[100]',
            ],
            [
                'field' => 'bic_contact',
                'label' => 'Manager/BIC Contact',
                'rules' => 'trim|max_length[25]',
			]
        ];

        return $rules;
    }





	function validate_url($url)	{
		if($this->client_model->validate_url($url))
		{
			return TRUE;
		}
		return FALSE;
	}
}
