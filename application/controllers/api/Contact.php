<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Contact extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('contact_model');
		$this->load->model('contact_history_model');

		$this->load->library('form_validation');

    }




    /**
     * Delete contact.
     *
     * @access public
     * @return json
     */
    public function delete_post()
    {
        $contact_id = $this->post("contact_id");

        if ($contact_id > 0) {

            $contact = $this->contact_model->get($contact_id);

            if (!empty($contact)) {
				$this->contact_model->set_contact_id($contact_id);

                $deleted = $this->contact_model->delete($contact->id);

                if ($deleted) {
                    $this->response(true, REST_Controller::HTTP_OK);
                } else {
                    $this->response(false, REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(false, REST_Controller::HTTP_OK);
            }
        }

        $this->response(false, REST_Controller::HTTP_OK);
    }





	/**
     * Switch client's main contact.
     *
     * @access public
     * @return json
     */
    public function switch_main_contact_post()
    {
        $contact_id = $this->post("contact_id");

		$post = [
			'client_id' => $this->post('client_id'),
			'contact_id' => $this->post('contact_id'),
		];

        if ($contact_id > 0) {

            if (!empty($contact_id)) {
				$updated = $this->contact_model->switch_main_contact($post);

                if ($updated) {
					$this->response(true, REST_Controller::HTTP_OK);
                } else {
                    $this->response(false, REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(false, REST_Controller::HTTP_OK);
            }
        }

        $this->response(false, REST_Controller::HTTP_OK);
    }





	/**
	 * Get contact by client.
	 *
	 * @access public
	 * @return json
	 */
	public function get_by_client_post()
	{
		$post = $this->input->post();
		$contacts = $this->contact_model->get_many_by('client_id', $post['client_id']);
		$result        	   = new stdclass();
		$result->contacts   = $contacts;

		$this->response($result, REST_Controller::HTTP_OK);
	}






	/**
	 * Get contact detail.
	 *
	 * @access public
	 * @return json
	 */
	public function get_detail_post()
	{
		$post 				= $this->input->post();
		$contact_detail		= $this->contact_history_model->get($post['contact_history_id']);
		$result        	    = new stdclass();
		$result->contact_detail   = $contact_detail;

		$this->response($result, REST_Controller::HTTP_OK);
	}







	/**
	 * Create contact.
	 *
	 * @access public
	 * @return json
	 */
	public function create_post()
	{
		$this->form_validation->set_message('required', 'Please enter %s');
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$contact_id = $this->contact_model->insert($post);
			if ($post['primary'] == 1) {
				$data = [
					'client_id' => $post['client_id'],
					'contact_id' => $contact_id,
				];
				$this->contact_model->switch_main_contact($data);
			}

			$contact_history_id	= $this->contact_history_model->get_by('contact_id', $contact_id)->id;

			$response     	 				= new stdclass();
			$response->status 				= true;
			$response->data					= new stdClass();
			$response->data->contact_id 	= $contact_id;
			$response->data->contact_history_id 	= $contact_history_id;

			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}



	public function update_post() {
		$post = $this->input->post();
		$this->form_validation->set_message('required', 'Please enter %s');
		if ($this->form_validation->run()) {
			$update_contact_data = [
				'client_id' 	=> $post['client_id'],
				'salutation'	=> $post['salutation'],
				'status'		=> $post['status'],
				'name'			=> $post['name'],
				'email'			=> $post['email'],
				'phone'			=> $post['phone'],
				'fax'			=> $post['fax'],
				'mobile'		=> $post['mobile'],
				'position'		=> $post['position'],
				'department'	=> $post['department']
			];

			$this->contact_model->set_contact_id($post['contact_id']);
			$this->contact_model->update($post['contact_id'], $update_contact_data);
			$new_contact_history_id = $this->contact_history_model->order_by('id', 'DESC')->limit(1)->get_by('contact_id', $post['contact_id'])->id;

			$response     	 				= new stdclass();
			$response->status 				= true;
			$response->data					= new stdClass();
			$response->data->contact_history_id 	= $new_contact_history_id;

			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}

	}




	public function set_as_primary_post()
    {
		$post = $this->input->post();
		$updated = $this->contact_model->switch_main_contact($post);
		if ($updated) {
			$contact = $this->contact_model->get($post['contact_id']);
			$this->response($contact, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}

        $this->response(false, REST_Controller::HTTP_OK);
    }







	/**
     * Validation rules for contact form.
     *
     * @access protected
     * @return void
     */
    protected function _validation_rules()
    {
        $rules = [
			[
                'field' => 'salutation',
                'label' => 'Salutation',
                'rules' => 'trim|required|max_length[20]',
			],
			[
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|max_length[200]|alpha_numeric',
				'errors' => array(
					'alpha_numeric' => "Special character not allowed for field Name",
				),
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
                'field' => 'mobile',
                'label' => 'Mobile',
				'rules' => 'trim|required|min_length[8]',
			],
			[
				'field' => 'phone',
				'label' => 'Phone',
				'rules' => 'trim|required|min_length[8]',
			],
			[
				'field' => 'fax',
				'label' => 'Fax',
				'rules' => 'trim|required|min_length[8]',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|max_length[200]|valid_email',
			]
        ];


        return $rules;
    }

    public function validate_post() {
		$post = $this->input->post();
        if($post['country'] == 'Singapore') {
			$this->form_validation->set_rules($this->_update_validation_rules_singapore_company());
		} else {
			$this->form_validation->set_rules($this->_validation_rules_non_singapore_company());
		}
		$this->form_validation->set_message('required', 'Please enter %s');
		if ($this->form_validation->run()) {
			$update_contact_data = [
				'client_id' 	=> $post['client_id'],
				'salutation'	=> $post['salutation'],
				'status'		=> $post['status'],
				'name'			=> $post['name'],
				'email'			=> $post['email'],
				'phone'			=> $post['phone'],
				'fax'			=> $post['fax'],
				'mobile'		=> $post['mobile'],
				'position'		=> $post['position'],
				'department'	=> $post['department']
			];

			$response     	 				= new stdclass();
			$response->status 				= true;
			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}

	}

    protected function _update_validation_rules_singapore_company()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Client Name',
				'rules' => 'trim|required',
			],
			[
				'field' => 'phone',
				'label' => 'Phone',
				'rules' => 'trim|min_length[8]|max_length[8]',
			],
			[
				'field' => 'fax',
				'label' => 'Fax',
				'rules' => 'trim|min_length[8]|max_length[8]',
			],
            [
				'field' => 'mobile',
				'label' => 'Mobile',
				'rules' => 'trim|min_length[8]|max_length[8]',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|max_length[200]|valid_email',
			]
		];

		return $rules;
	}

    protected function _validation_rules_non_singapore_company()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Client Name',
				'rules' => 'trim|required',
			],
			[
				'field' => 'salutation',
				'label' => 'Salutation',
				'rules' => 'trim|required',
			],
            [
				'field' => 'status',
				'label' => 'Salutation',
				'rules' => 'trim|required',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email',
			]
		];


		return $rules;
	}




}
