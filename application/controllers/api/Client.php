<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Client extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
		$this->load->model('contact_model');
		$this->load->model('contact_history_model');
		$this->load->model('client_history_model');
		$this->load->model('address_history_model');
        $this->load->model('client_note_model');
		$this->load->model('address_model');
		$this->load->model('quotation_model');
		$this->load->model('country_model');
        $this->load->model('invoice_model');

		$this->load->library('form_validation');
		$this->load->library('session');

    }




    /**
     * Get branch code.
     *
     * @access public
     * @return json
     */
    public function get_notes_post()
    {
        // post
        $post = $this->input->post();
        $output = [];

        // get client note
		$notes = $this->client_note_model->with('user')->order_by('id', 'DESC')->get_many_by('client_id', $post['client_id']);
        // $notes = $this->note_model->get_client_notes($post['client_id']);

        if (!empty($notes)) {
            foreach ($notes as $note) {
                $temp       = new stdclass();
                $temp->id   = $note->id;
                $temp->note = $note->note;
				$temp->created_on = date('d M Y H:i', $note->created_on);
				$temp->role = $note->user->group->description;
				$temp->user = $note->user->first_name . ' ' . $note->user->last_name;
                $output[] = $temp;
            }
        }

        // return
        $this->response($output, REST_Controller::HTTP_OK);
    }






	/**
	 * Get client by status.
	 *
	 * @access public
	 * @return json
	 */
	public function get_by_status_post()
	{
		$post = $this->input->post();
		$clients = $this->client_history_model->get_all_by_status($post['status']);
		$result        	   = new stdclass();
		$result->clients   = $clients;

		$this->response($result, REST_Controller::HTTP_OK);
	}






	/**
	 * Get client by status.
	 *
	 * @access public
	 * @return json
	 */
	public function get_detail_post()
	{
		$post = $this->input->post();
		$client_history_id = $post['client_history_id'];
		$client_detail = $this->client_history_model->get($client_history_id);
		$client_id = $client_detail->client_id;

		$contacts = $this->contact_history_model->get_many_by('id IN (SELECT MAX(id) FROM contact_history where client_id = '.$client_id.' GROUP BY contact_id)');
		$primary_contact = $this->contact_history_model->order_by('id', 'DESC')->get_by([
			'client_id' => $client_id,
			'primary'	=> 1
		]);

		$addresses = $this->address_history_model->get_many_by("id IN (SELECT MAX(id) FROM address_history where client_id = '$client_id' GROUP BY address_id)");
		$primary_address = $this->address_history_model->order_by('id', 'desc')->get_by([
			'client_id' => $client_id,
			'primary'	=> 1
		]);

		$result        	   			= new stdclass();
		$result->client_detail		= $client_detail;
		$result->contacts   		= $contacts;
		$result->primary_contact 	= $primary_contact;
		$result->addresses 			= $addresses;
		$result->primary_address 	= $primary_address;

		$this->response($result, REST_Controller::HTTP_OK);
	}




	/**
	 * Get branch code.
	 *
	 * @access public
	 * @return json
	 */
	public function get_main_contact_post()
	{
		$post = $this->input->post();
        $output = [];

        $main_contact = $this->contact_model->get($post['contact_id']);
		$contacts = $this->contact_model->get_many_by('client_id', $post['client_id']);

		if (!empty($contacts)) {
			foreach ($contacts as $contact) {
				$temp       	= new stdclass();
				$temp->id   	= $contact->id;
				$temp->salutation 	= $contact->salutation;
				$temp->name 	= $contact->name;
				$temp->position = $contact->position;
				$output[] = $temp;
			}
		}

		$result       = new stdclass();
		$result->main_contact   = $main_contact;
		$result->contact   = $output;

		$this->response($result, REST_Controller::HTTP_OK);
	}




    /**
     * Delete client.
     *
     * @access public
     * @return json
     */
    public function delete_post()
    {
        // post
        $client_id = $this->post("client_id");

        if ($client_id > 0) {
            // get client details
            $client = $this->client_model->get($client_id);

            if (!empty($client)) {
                // delete
                $deleted = $this->client_model->delete($client->id);

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
     * FLag client.
     *
     * @access public
     * @return json
     */
    public function flag_post()
    {
        // post

		$post = $this->input->post();

		$updated = $this->db->set('flagged', $post['flagged'])
							->where('id', $post['client_id'])
							->update('client');

		if ($updated) {
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}

        $this->response(false, REST_Controller::HTTP_OK);
    }




	/**
	 * create client.
	 *
	 * @access public
	 * @return json
	 */
	public function create_post()
	{
		$post = $this->input->post();

		if($post['country'] == 'Singapore') {
			$this->form_validation->set_rules($this->_create_validation_rules_singapore_company());
			$this->form_validation->set_rules('uen', 'UEN', 'required|is_unique[client.uen]|callback_validate_uen');
			$this->form_validation->set_rules('website', 'Website', 'callback_validate_url');
		} else {
			$this->form_validation->set_rules($this->_validation_rules_non_singapore_company());
			// $this->form_validation->set_rules('uen', 'UEN', 'required|is_unique[client.uen]');
			$this->form_validation->set_rules('website', 'Website', 'callback_validate_url');
		}
		$this->form_validation->set_message('validate_name', 'Client name invalid');
        $this->form_validation->set_message('unique_name', 'Client name already exists');
		$this->form_validation->set_message('validate_url','Website URL is invalid!');
		$this->form_validation->set_message('is_unique', '%s already exists');
		$this->form_validation->set_message('required', 'Please enter %s');
		$this->form_validation->set_message('validate_uen', 'Invalid UEN');

		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$client_data = [
				'name' 		=> $post['name'],
				'uen'		=> $post['uen'],
				'website'	=> $post['website'],
				'phone'		=> $post['phone'],
				'fax'		=> $post['fax'],
				'email'		=> $post['email']
			];
			$client_id = $this->client_model->insert($client_data);

			if ($client_id) {
				$address_data = [
					'client_id'			=> $client_id,
					'name'				=> $client_data['name']."'s".' main address',
					'address_name'		=> 'Main address',
					'phone'				=> $post['phone'],
					'fax'				=> $post['fax'],
					'address'			=> $post['address'],
					'address_2'			=> $post['address_2'],
					'postal_code'		=> $post['postal_code'],
					'country'			=> $post['country'],
					'total_employee'	=> $post['total_employee'],
					'primary'			=> 1
				];
				$address_id = $this->address_model->insert($address_data);
			}

			$address_history	= $this->address_history_model->get_by('address_id', $address_id);
			$client_history_id = $this->client_history_model->get_by('client_id', $client_id)->id;

			$response     	 			= new stdclass();
			$response->status 			= true;
			$response->data				= new stdClass();
			$response->data->client_id 	= $client_id;
			$response->data->client_history_id 	= $client_history_id;
			$response->data->address_history_id = $address_history->id;
			$response->data->address_id = $address_history->address_id;

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
		if($post['country'] == 'Singapore') {
			$this->form_validation->set_rules($this->_update_validation_rules_singapore_company());
			$this->form_validation->set_rules('website', 'Website', 'callback_validate_url');

			$uenBelongsToClient = $this->client_model->uenBelongsToClient($post['uen'], $post['client_id']);
			if(!$uenBelongsToClient) {
				$this->form_validation->set_rules('uen', 'UEN', 'trim|required|is_unique[client.uen]|callback_validate_uen');
			}

			$nameBelongsToClient = $this->client_model->nameBelongsToClient($post['name'], $post['client_id']);
			if(!$nameBelongsToClient) {
				$this->form_validation->set_rules('name','Client Name','trim|required|is_unique[client.name]',array('is_unique' => '%s already exists.'));
			}
		} else {
			$this->form_validation->set_rules($this->_validation_rules_non_singapore_company());
			$this->form_validation->set_rules('website', 'Website', 'callback_validate_url');

			$uenBelongsToClient = $this->client_model->uenBelongsToClient($post['uen'], $post['client_id']);
			// if(!$uenBelongsToClient) {
			// 	$this->form_validation->set_rules('uen', 'UEN', 'trim|required|is_unique[client.uen]');
			// }
		}
		$this->form_validation->set_message('validate_url','Website URL is invalid!');
		$this->form_validation->set_message('is_unique', '%s already exists');
		$this->form_validation->set_message('required', 'Please enter %s');
		$this->form_validation->set_message('validate_uen', 'Invalid UEN');

		if($this->form_validation->run()) {
			$update_client = [
				'name'	=> $post['name'],
				'uen'	=> $post['uen'],
				'website'	=> $post['website'],
				'phone'	=> $post['phone'],
				'fax'	=> $post['fax'],
				'email'	=> $post['email']
			];

			$this->client_model->set_client_id($post['client_id']);
			$client_updated = $this->client_model->update($post['client_id'], $update_client);
			if ($client_updated) {
				$new_client_history_id = $this->client_history_model->order_by('id', 'DESC')->limit(1)->get_by('client_id', $post['client_id'])->id;

				$update_primary_address = [
					'address'	=> $post['address'],
					'address_2'	=> $post['address_2'],
					'phone'		=> $post['phone'],
					'fax'		=> $post['fax'],
					'country'	=> $post['country'],
					'postal_code'	=> $post['postal_code'],
					'total_employee'	=> $post['total_employee']
				];

				$this->address_model->set_address_id($post['address_id']);
				$primary_address_updated = $this->address_model->update($post['address_id'], $update_primary_address);
				if ($primary_address_updated) {
					$new_address_history_id = $this->address_history_model->order_by('id', 'DESC')->limit(1)->get_by('address_id', $post['address_id'])->id;

					// $updated = $this->quotation_model->update($post['quotation_id'], [
					// 	'client_history_id' 	=> $new_client_history_id,
					// 	'address_history_id'	=> $new_address_id
					// ]);

					$response     	 			= new stdclass();
					$response->status 			= true;
					$response->data				= new stdClass();
					$response->data->client_history_id 	= $new_client_history_id;
					$response->data->address_history_id = $new_address_history_id;

					$this->response($response, REST_Controller::HTTP_OK);
				}
			}
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}



	public function get_history_post() {
		$post = $this->input->post();
		// $client_history = $this->client_history_model->with('created_by')->order_by('id', 'DESC')->get_many_by('client_id', $post['client_id']);
		$client_history = $this->client_history_model->get_by_client($post['client_id']);
        $address_history = $this->address_history_model->get_by_client($post['client_id']);
		$contact_history = $this->contact_history_model->get_by_client($post['client_id']);

		$response     	 			= new stdclass();
		$response->status 			= true;
		$response->client_history 	= $client_history;
		$response->address_history 	= $address_history;
		$response->contact_history 	= $contact_history;

		$this->response($response, REST_Controller::HTTP_OK);
	}





	protected function _create_validation_rules_singapore_company()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Client Name',
				'rules' => 'trim|required|max_length[200]|callback_unique_name|callback_validate_name',
			],
			[
				'field' => 'address',
				'label' => 'Address 1',
				'rules' => 'trim|required',
			],
			[
				'field' => 'address_2',
				'label' => 'Address 2',
				'rules' => 'trim|required',
			],
			[
				'field' => 'postal_code',
				'label' => 'Postal Code',
				'rules' => 'trim|required|min_length[6]|max_length[6]',
			],
			[
				'field' => 'country',
				'label' => 'Country',
				'rules' => 'trim|required',
			],
			[
				'field' => 'total_employee',
				'label' => 'No of Employee',
				'rules' => 'trim|required',
			],
			[
				'field' => 'website',
				'label' => 'Website',
				'rules' => 'trim|max_length[100]',
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
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|max_length[200]|valid_email',
			]
		];


		return $rules;
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
				'field' => 'uen',
				'label' => 'UEN',
				'rules' => 'trim|required|callback_validate_uen',
			],
			[
				'field' => 'address',
				'label' => 'Address 1',
				'rules' => 'trim|required',
			],
			[
				'field' => 'address_2',
				'label' => 'Address 2',
				'rules' => 'trim|required',
			],
			[
				'field' => 'postal_code',
				'label' => 'Postal Code',
				'rules' => 'trim|required|min_length[6]|max_length[6]',
			],
			[
				'field' => 'country',
				'label' => 'Country',
				'rules' => 'trim|required',
			],
			[
				'field' => 'total_employee',
				'label' => 'No of Employee',
				'rules' => 'trim|required',
			],
			[
				'field' => 'website',
				'label' => 'Website',
				'rules' => 'trim|max_length[100]',
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
				'field' => 'country',
				'label' => 'Country',
				'rules' => 'trim|required',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|max_length[200]|valid_email',
			]
		];


		return $rules;
	}


	function validate_url($url) {
		if(!$url) {
			return true;
		}

        $global_extensions = [
            "com", "org", "net", "edu", "gov", "mil", "int", "arpa", "biz", "info",
            "pro", "coop", "museum", "name", "aero", "asia", "cat", "jobs", "mobi",
            "tel", "travel", "xxx", "us", "uk", "de", "cn", "jp", "in", "br", "ru",
            "fr", "it", "au", "ca", "nl", "kr", "es", "mx", "se", "ch", "pl", "be",
            "at", "dk", "fi", "no", "gr", "cz", "pt", "hu", "ro", "nz", "za", "tr",
            "sg", "hk", "ae", "il", "ua", "ie", "my", "cl", "co", "vn", "id", "ph",
            "th", "ar", "pe", "eg", "ng", "ke", "ve", "pk", "bd", "tw", "ma", "tn",
            "ua", "uz", "kz", "by", "rs", "hr", "bg", "si", "ba", "md", "mk", "me",
            "cy", "lv", "lt", "ee", "rw", "tz", "ug", "zm", "zw", "mu", "mw", "na",
            "bw", "sz", "ls", "gm", "sl", "sn", "cf", "td", "ne", "bj", "bf", "ci",
            "gh", "tg", "gq", "ga", "cg", "cd", "ao", "gw", "cv", "st", "sc", "km",
            "dj", "so", "er", "ss", "bi", "ug", "ke", "et", "sd", "eg", "ly", "tn",
            "dz", "ma", "mr", "eh", "es", "pt", "fr", "mc", "lu", "ie", "is", "no",
            "se", "fi", "ax", "gb", "dk", "sh", "nl", "be", "de", "pl", "cz", "sk",
            "hu", "si", "hr", "ba", "me", "al", "rs", "mk", "bg", "ro", "md", "ua",
            "by", "lv", "lt", "ee", "fo", "is", "ie", "gb", "im", "je", "gg", "ch",
            "at", "sk", "li", "es", "it", "va", "sm", "mt", "gr", "io", "html", "php"
        ];
		$path_parts = pathinfo($url);

		if(!isset($path_parts['extension'])) {
			return false;
		}

		if(!$path_parts['extension']) {
			return false;
		}

		$extension = strtolower($path_parts['extension']);
		if (in_array($extension, $global_extensions)) {
			return true;
		} else {
			$countries = $this->country_model->get_all();
			$country_codes = array_column($countries, 'code');
			$country_fipses = array_column($countries, 'fips_code');
			$country_extensions = array_unique(array_merge($country_codes,$country_fipses), SORT_REGULAR); // sometimes a country url extension could be from its code or its fips code
			if (in_array(strtoupper($extension), $country_extensions)) {
				return true;
			}
			return false;
		}
	}


	public function validate_uen() {
		$client_name = $this->input->post('name');
		$uen = $this->input->post('uen');

		if(!$uen) {
			return true;
		}

		if (strpos(strtolower($client_name), ' pte ltd') || strpos(strtolower($client_name), ' pte. ltd.') || strpos(strtolower($client_name), ' private limited')) {
			if(strlen($uen) != 10) {
				return false;
			}
			$number = substr($uen, 0, 9);
			$letter = substr($uen, 9, 1);
			if(!ctype_digit($number) || !ctype_alpha($letter)) {
				return false;
			}
			return true;
		}

		if (strpos(strtolower($client_name), ' ltd') || strpos(strtolower($client_name), ' ltd.') || strpos(strtolower($client_name), ' limited') || strpos(strtolower($client_name), ' llp')) {
			if(strlen($uen) != 10) { //check uen length
				return false;
			}

			if (substr($uen, 0, 1) != 'T' && substr($uen, 0, 1) != 'S') { //check first digit
				return false;
			}

			if(!ctype_digit(substr($uen, 1, 2))) { // check next 2 digit
				return false;
			}

			if(!ctype_alpha(substr($uen, 3, 1))) {
				return false;
			}

			if(!ctype_alnum(substr($uen, 4, 1))) {
				return false;
			}

			if(!ctype_digit(substr($uen, 5, 4))) {
				return false;
			}

			if(!ctype_alpha(substr($uen, 9, 1))) {
				return false;
			}
			return true;
		}

		if(strlen($uen) != 9) {
			return false;
		}

		$number = substr($uen, 0, 8);
		$letter = substr($uen, 8, 1);
		if(!ctype_digit($number) || !ctype_alpha($letter)) {
			return false;
		}

		return true;
	}


	public function unique_name() {
		$clients = $this->client_model->get_all();
		$client_name_exists = array_column($clients, 'name');

		$post_client_name = $this->input->post('name');

		$pattern = "/\b(?:Pte|pte|private|Private|ltd)\b/i"; // \b for word boundary, i for case-insensitive
		foreach ($client_name_exists as $client_name) {
            $client_name_only = preg_split($pattern, preg_replace('/[^a-zA-Z0-9\s]/', '', $client_name))[0];
			$post_client_name_only = preg_split($pattern, preg_replace('/[^a-zA-Z0-9\s]/', '', $post_client_name))[0];
			if(strtolower($client_name_only) == strtolower($post_client_name_only)) {
				return false;
			}
		}
		return true;
	}

    public function validate_name() {
    	$client_name = $this->input->post('name');

        $patterns = array(
            '/\bprivate\s+limited\b/i',             // Matches "Private Limited"
            '/\bprivate\s+ltd\b/i',                 // Matches "Private Ltd"
            '/\bpte\s+limited\b/i',                 // Matches "Pte Limited"
            '/\bpte\.?\s+ltd\.?\b/i',               // Matches "Pte Ltd", "Pte. Ltd.", "Pte. Ltd", "Pte Ltd."
            '/\(\s*private\s*\)\s+limited\b/i',     // Matches "(Private) Limited"
            '/\(\s*pte\s*\)\s+limited\b/i',         // Matches "(Pte) Limited"
            '/\(\s*pte\s*\)\s+ltd\b/i',             // Matches "(Pte) Ltd"
        );

        $count = 0;
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $client_name, $matches);
            $count += count($matches[0]);
        }
        if ($count > 1) {
            return false;
        }
        return true;
    }

    public function edit_address_post() {
		$post = $this->input->post();
        $address_id = $post['address_id'];

		$this->form_validation->set_rules('name', 'Client Name', 'required');
		if ($post['country'] == 'Singapore') {
			$this->form_validation->set_rules('phone', 'Phone', 'min_length[8]');
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[8]');
			$this->form_validation->set_rules('postal_code', 'Postal Code', 'min_length[5]');
		}
		$this->form_validation->set_message('required', 'Please enter %s');

		if ($this->form_validation->run()) {
			$update_address_data = [
				'client_id'	=> $post['client_id'],
				'name'		=> $post['name'],
				'phone'		=> $post['phone'],
				'fax'		=> $post['fax'],
				'address'	=> $post['address'],
				'address_2'	=> $post['address_2'],
				'country'	=> $post['country'],
				'postal_code'	=> $post['postal_code'],
				'total_employee'	=> $post['total_employee']
			];
			$this->address_model->set_address_id($address_id);
			$this->address_model->update($address_id, $update_address_data);
            // updated the invoice of corresponding address
            // $this->update_ongoing_invoice($address_id);
			$response     	 				= new stdclass();
			$response->status 				= true;
			$response->data					= new stdClass();
			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}

    public function update_ongoing_invoice($address_id) {
        $address = $this->address_model->get($address_id);
        $invoices = $this->db
            ->select('i.id')
            ->join('address_history ah', 'i.address_history_id = ah.id')
            ->join('address a', 'a.id = ah.address_id')
            ->where('a.id', $address_id)
            ->get('invoice i')
            ->result_array();
        if ($invoices) {
            $new_address_history_id = $this->address_history_model->order_by('id', 'DESC')->limit(1)->get_by('address_id', $address_id)->id;
            foreach ($invoices as $invoice) {
                $this->invoice_model->set_invoice_id($invoice['id']);
                $this->invoice_model->update($invoice['id'], ['address_history_id' => $new_address_history_id]);
            }
        }
    }

    public function validate_post() {
		$post = $this->input->post();
		if($post['country'] == 'Singapore') {
			$this->form_validation->set_rules($this->_update_validation_rules_singapore_company());
			$this->form_validation->set_rules('website', 'Website', 'callback_validate_url');

			$uenBelongsToClient = $this->client_model->uenBelongsToClient($post['uen'], $post['client_id']);
			if(!$uenBelongsToClient) {
				$this->form_validation->set_rules('uen', 'UEN', 'trim|required|is_unique[client.uen]|callback_validate_uen');
			}

			$nameBelongsToClient = $this->client_model->nameBelongsToClient($post['name'], $post['client_id']);
			if(!$nameBelongsToClient) {
				$this->form_validation->set_rules('name','Client Name','trim|required|is_unique[client.name]',array('is_unique' => '%s already exists.'));
			}
		} else {
			$this->form_validation->set_rules($this->_validation_rules_non_singapore_company());
			$this->form_validation->set_rules('website', 'Website', 'callback_validate_url');

			$uenBelongsToClient = $this->client_model->uenBelongsToClient($post['uen'], $post['client_id']);
			if(!$uenBelongsToClient) {
				$this->form_validation->set_rules('uen', 'UEN', 'trim|required|is_unique[client.uen]');
			}
		}
		$this->form_validation->set_message('validate_url','Website URL is invalid!');
		$this->form_validation->set_message('is_unique', '%s already exists');
		$this->form_validation->set_message('required', 'Please enter %s');
		$this->form_validation->set_message('validate_uen', 'Invalid UEN');

		if($this->form_validation->run()) {
            $response     	 			= new stdclass();
            $response->status 			= true;
            $this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}



}
