<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Address extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('address_model');
		$this->load->model('address_history_model');
		$this->load->model('quotation_address_model');

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
        $address_id = $this->post("address_id");

        if ($address_id > 0) {

            $address = $this->address_model->get($address_id);

            if (!empty($address)) {
                $deleted = $this->address_model->delete($address->id);

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
	 * Create address.
	 *
	 * @access public
	 * @return json
	 */
	public function create_post()
	{
		$post = $this->input->post();
        $this->form_validation->set_rules('name', 'Site Name', 'required');
		if (array_key_exists('country', $post) && $post['country'] == 'Singapore') {
			$this->form_validation->set_rules('phone', 'Phone', 'min_length[8]');
			$this->form_validation->set_rules('fax', 'Fax', 'min_length[8]');
			$this->form_validation->set_rules('postal_code', 'Postal Code', 'min_length[5]');
		}
		$this->form_validation->set_message('required', 'Please enter %s');

		if ($this->form_validation->run()) {
			$post['primary'] = 0;
			$address_id = $this->address_model->insert($post);

			$address_history_id	= $this->address_history_model->get_by('address_id', $address_id)->id;

			$response     	 				= new stdclass();
			$response->status 				= true;
			$response->data					= new stdClass();
			$response->data->address_history_id 	= $address_history_id;

			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}







	/**
     * Switch client's main address.
     *
     * @access public
     * @return json
     */
    public function switch_main_address_post()
    {
        $address_id = $this->post("address_id");

		$post = [
			'client_id' => $this->post('client_id'),
			'address_id' => $this->post('address_id'),
		];

        if ($address_id > 0) {

            if (!empty($address_id)) {
				$updated = $this->address_model->switch_main_address($post);
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



	public function update_post() {
		$post = $this->input->post();
		$address_history_id = $post['address_history_id'];

		$address_history = $this->address_history_model->get($address_history_id);
		$address_id = $address_history->address_id;

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
			$new_address_history_id = $this->address_history_model->order_by('id', 'DESC')->limit(1)->get_by('address_id', $address_id)->id;
			// update quotation address
			// $quotation_address = $this->quotation_address_model->get_by([
			// 	'quotation_id' => $post['quotation_id'],
			// 	'address_history_id'	=> $address_history_id
			// ]);
			// $this->quotation_address_model->delete($quotation_address->id);
			// $this->quotation_address_model->insert([
			// 	'quotation_id'	=> $post['quotation_id'],
			// 	'address_history_id'	=> $new_address_id
			// ]);

			$response     	 				= new stdclass();
			$response->status 				= true;
			$response->data					= new stdClass();
			$response->data->address_history_id 	= $new_address_history_id;
			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}


	}


	public function get_post() {
		$post = $this->post();
		$address = $this->address_model->get($post['address_id']);
		$response     	 				= new stdclass();
		$response->status 				= true;
		$response->data					= $address;

		$this->response($response, REST_Controller::HTTP_OK);
	}


  public function get_by_client_post() {
    $client_id = $this->input->post('client_id');
    $addresses = $this->address_history_model->get_many_by("id IN (SELECT MAX(id) FROM address_history where client_id = '$client_id' GROUP BY address_id)");
		$result        	   			= new stdclass();
		$result->addresses 			= $addresses;
		$this->response($result, REST_Controller::HTTP_OK);
  }

}
