<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Quotation extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
		$this->load->model('contact_model');
		$this->load->model('contact_history_model');
		$this->load->model('client_history_model');
		$this->load->model('address_history_model');
        $this->load->model('quotation_note_model');
		$this->load->model('address_model');
		$this->load->model('quotation_model');
		$this->load->model('quotation_address_model');
		$this->load->model('invoice_model');
		$this->load->model('memo_model');

		$this->load->library('form_validation');
		$this->load->library('session');

    }




    public function get_notes_post()
    {
        $post = $this->input->post();
        $output = [];

		$notes = $this->quotation_note_model->with('user')->order_by('id', 'DESC')->get_many_by('quotation_id', $post['quotation_id']);

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


	public function get_history_post()
    {
        $post = $this->input->post();
		$histories = $this->quotation_history_model->get_history($post['quotation_id']);
        $this->response($histories, REST_Controller::HTTP_OK);
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
		$client_id = $post['client_id'];

		$contacts = $this->contact_history_model->get_many_by('id IN (SELECT MAX(id) FROM contact_history where client_id = '.$client_id.' GROUP BY contact_id)');
		$primary_contact = $this->contact_model->get_by([
			'client_id' => $client_id,
			'primary'	=> 1
		]);

		$addresses = $this->address_history_model->get_many_by('id IN (SELECT MAX(id) FROM address_history where client_id = '.$client_id.' GROUP BY address_id)');
		$primary_address = $this->address_model->get_by([
			'client_id' => $client_id,
			'primary'	=> 1,
		]);

		$result        	   			= new stdclass();
		$result->contacts   		= $contacts;
		$result->primary_contact 	= $primary_contact;
		$result->addresses 			= $addresses;
		$result->primary_address 	= $primary_address;

		$this->response($result, REST_Controller::HTTP_OK);
	}


    /**
     * Delete quotation.
     *
     * @access public
     * @return json
     */
    public function delete_post()
    {
        // post
        $quotation_id = $this->post("quotation_id");

        if ($quotation_id > 0) {
            $quotation = $this->quotation_model->get($quotation_id);

            if (!empty($quotation)) {
                // delete
                $deleted = $this->quotation_model->delete($quotation->id);

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
     * FLag quotation.
     *
     * @access public
     * @return json
     */
    public function flag_post()
    {
        // post

		$post = $this->input->post();

		$updated = $this->db->set('flagged', $post['flagged'])
							->where('id', $post['quotation_id'])
							->update('quotation');

		if ($updated) {
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}

        $this->response(false, REST_Controller::HTTP_OK);
    }





	/**
     * Update quotation status.
     *
     * @access public
     * @return json
     */
    public function update_status_post()
    {
		$post = $this->input->post();
        $client_history = $this->quotation_model->get_client_history($post['quotation_id']);
		$updated = $this->quotation_model->update_status($post);

		if ($updated) {
            $this->client_model->update_status($client_history[0]->client_id);
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}

        $this->response(false, REST_Controller::HTTP_OK);
    }


	public function change_contact_post()
    {
		$post = $this->input->post();

		$updated = $this->quotation_model->change_contact($post);

		if ($updated) {
			// $contact = $this->contact_history_model->get($post['contact_id']);
			// $post = [
			// 	'client_id' => $contact->client_id,
			// 	'contact_id' => $contact->contact_id
			// ];
			// $updated = $this->contact_model->switch_main_contact($post);
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}

        $this->response(false, REST_Controller::HTTP_OK);
    }


	public function remove_address_post() {
		$post = $this->input->post();
		$quotation = $this->quotation_model->get($post['quotation_id']);
		$quotation_address_id = $this->quotation_address_model->get_by([
			'quotation_id'	=> $post['quotation_id'],
			'address_history_id'	=> $post['address_id']
		])->id;
		$deleted = $this->quotation_address_model->delete($quotation_address_id);
		if ($deleted) {
			$quotation = $this->quotation_model->get($post['quotation_id']);
			$updated = $this->quotation_model->update($post['quotation_id'], [
				'num_of_sites'	=> $quotation->num_of_sites - 1
			]);

			if ($updated) {
				$this->response(true, REST_Controller::HTTP_OK);
			} else {
				$this->response(false, REST_Controller::HTTP_OK);
			}
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}


	public function add_address_post() {
		$post = $this->input->post();
		$post['primary'] = 0;
		$address_id = $this->address_model->insert($post);
		if ($address_id) {
			$address_history_id = $this->address_history_model->order_by('id', 'DESC')->limit(1)->get_by('address_id', $address_id)->id;

			// add address to quotation
			$this->quotation_address_model->insert([
				'quotation_id'	=> $post['quotation_id'],
				'address_history_id'	=> $address_history_id
			]);

			// update quotation
			$quotation = $this->quotation_model->get($post['quotation_id']);
			$quotation->num_of_sites = $quotation->num_of_sites + 1;
			$quotation->quotation_id = $quotation->id;
			$this->quotation_model->set_quotation_id($quotation->id);
			$updated = $this->quotation_model->update($post['quotation_id'], (array)$quotation);

			if ($updated) {
				$response     	 			= new stdclass();
				$response->status 			= true;
				$response->data				= new stdClass();
				$response->data->address_id = $address_history_id;
				$this->response($response, REST_Controller::HTTP_OK);
			}
		}
	}


	public function get_invoices_post() {
		$post = $this->post();
		$invoice_types = ['Stage 1 & Stage 2 Audit', 'Stage 2 Audit', '1st Year Surveillance', '2nd Year Surveillance', 'All cycle', 'Training', 'Bizsafe'];
		$invoices = [];
		foreach ($invoice_types as $type) {
			$invoice = $this->invoice_model->get_by([
				'quotation_id' => $post['quotation_id'],
				'invoice_type'	=> $type,
		        'request_status' => 'Approved'
			]);
			if ($invoice) {
				$invoices[$type] = $invoice;
			}
		}

		if ($invoices) {
			$total_amount = array_sum(array_column($invoices, 'amount'));

			$response     	 			= new stdclass();
			$response->status 			= true;
			$response->data				= new stdClass();
			$response->data->invoices 	= $invoices;
			$response->data->total_amount 	= $total_amount;
			$this->response($response, REST_Controller::HTTP_OK);
		}
		$this->response(false, REST_Controller::HTTP_OK);
	}







	/**
	 * Validation rules for client form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _add_note_validation_rules()
	{
		$rules = [
			[
				'field' => 'quotation_id',
				'label' => 'Quotation Id',
				'rules' => 'required',
			],
			[
				'field' => 'note',
				'label' => 'Note',
				'rules' => 'required',
			],
		];


		return $rules;
	}


	// create client website validation
	function validate_url($url)	{
		if($this->client_model->validate_url($url))
		{
			return TRUE;
		}
		return FALSE;
	}

	public function update_follow_up_date_post() {
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$updated = $this->quotation_model->update_follow_up_date($post['quotation_id'], $post['follow_up_date']);
			if ($updated) {
				$this->response(human_date($post['follow_up_date'], 'd/m/Y'), REST_Controller::HTTP_OK);
			} else {
				$this->response(false, REST_Controller::HTTP_OK);
			}
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	public function client_with_accreditation_exists_post() {
		$post = $this->input->post();
		$quotation = $this->quotation_model->hasCertificationAndAccreditation($post['client_history_id'], $post['certification_scheme'], $post['accreditation']);
		$this->response($quotation, REST_Controller::HTTP_OK);
	}

	public function client_with_certification_scheme_exists_post() {
		$post = $this->input->post();
		$quotation = $this->quotation_model->hasCertificationAndAccreditation($post['client_history_id'], $post['certification_scheme'], $post['accreditation']);
		$this->response($quotation, REST_Controller::HTTP_OK);
	}


	public function update_confirm_date_post() {
		$post = $this->input->post();
		$updated = $this->quotation_model->update_confirm_date($post['quotation_id'], $post['confirm_date']);
		if ($updated) {
			$this->response($post['confirm_date'], REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	public function get_client_history_post()
    {
        $post = $this->input->post();
        $history = $this->quotation_model->get_client_history($post['quotation_id']);
        $this->response($history, REST_Controller::HTTP_OK);
    }

	public function get_contact_history_post()
    {
        $post = $this->input->post();
        $history = $this->quotation_model->get_contact_history($post['quotation_id']);
        $this->response($history, REST_Controller::HTTP_OK);
    }

	public function get_address_history_post()
    {
        $post = $this->input->post();
        $history = $this->quotation_model->get_address_history($post['quotation_id']);
        $this->response($history, REST_Controller::HTTP_OK);
    }

	public function get_memo_post()
	{
		$post = $this->input->post();
		$output = [];

		$memos = $this->memo_model->with('user')->order_by('id', 'DESC')->get_many_by('quotation_id', $post['quotation_id']);
		if (!empty($memos)) {
			foreach ($memos as $memo) {
				$temp       = new stdclass();
				$temp->id   = $memo->id;
				$temp->number = $memo->number;
				$temp->created_on = date('d M Y H:i', $memo->created_on);
				$temp->status = $memo->status;
				// $temp->user = $note->user->first_name . ' ' . $note->user->last_name;
				$output[] = $temp;
			}
		}

		// return
		$this->response($output, REST_Controller::HTTP_OK);
	}


}
