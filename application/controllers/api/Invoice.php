<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Invoice extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->model('invoice_note_model');
		$this->load->model('invoice_history_model');
		$this->load->model('invoice_model');

		$this->load->library('form_validation');
    }


	public function get_notes_post()
    {
        $post = $this->input->post();
        $output = [];

		$notes = $this->invoice_note_model->with('user')->order_by('id', 'DESC')->get_many_by('invoice_id', $post['invoice_id']);

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
		$histories = $this->invoice_history_model->get_history($post['invoice_id']);
        $this->response($histories, REST_Controller::HTTP_OK);
	}


	public function add_note_post() {
		$this->form_validation->set_message('required', 'Please enter %s');

		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$note_id = $this->invoice_note_model->insert($post);

			if ($note_id) {
				$note = $this->invoice_note_model->with('user')->get($note_id);
				$response     	 				= new stdclass();
				$response->status 				= true;
				$response->data					= new stdClass();
				$response->data->note 			= $note;

				$this->response($response, REST_Controller::HTTP_OK);
			} else {
				$this->response(false, REST_Controller::HTTP_OK);
			}
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}

	public function update_audit_fixed_date_post() {
		if ($this->form_validation->run()) {
			$post = $this->input->post();

			$this->invoice_model->set_invoice_id($post['invoice_id']);
            $invoice = $this->invoice_model->get($post['invoice_id']);
			$updated = $this->invoice_model->update($post['invoice_id'], [
				'audit_fixed_date' => $post['audit_fixed_date'],
                'status' => $invoice->status
			]);

			if ($updated) {
				$this->response(human_date($post['audit_fixed_date'], 'Y-m-d'), REST_Controller::HTTP_OK);
			} else {
				$this->response(false, REST_Controller::HTTP_OK);
			}
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	public function update_follow_up_date_post() {
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$this->invoice_model->set_invoice_id($post['invoice_id']);
			$updated = $this->invoice_model->update($post['invoice_id'], [
				'follow_up_date' => $post['follow_up_date']
			]);
			if ($updated) {
				$this->response(human_date($post['follow_up_date'], 'Y-m-d'), REST_Controller::HTTP_OK);
			} else {
				$this->response(false, REST_Controller::HTTP_OK);
			}
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	// public function cancel_post() {
	// 	$invoice_id = $this->post('invoice_id');
	// 	$this->invoice_model->delete($invoice_id);
	// 	$this->response(true, REST_Controller::HTTP_OK);
	// }

    public function delete_post() {
		$invoice_id = $this->post('invoice_id');
		$this->invoice_model->delete($invoice_id);
		$this->response(true, REST_Controller::HTTP_OK);
	}


}
