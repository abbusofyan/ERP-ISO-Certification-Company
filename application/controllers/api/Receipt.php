<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Receipt extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->model('receipt_model');
		$this->load->model('detail_receipt_model');
        $this->load->model('receipt_note_model');
		$this->load->model('user_model');
		$this->load->library('form_validation');
    }


	public function get_detail_post()
    {
        $post = $this->input->post();
        $output = [];

		$receipt = $this->receipt_model->with('note')->with('detail')->get($post['receipt_id']);
        if (!empty($receipt)) {
			$receipt->created_on = human_timestamp($receipt->created_on, 'd/m/Y');
			$receipt->transfer_date = human_date($receipt->transfer_date, 'd/m/Y');
			$receipt->cheque_date = human_date($receipt->cheque_date, 'd/m/Y');
			$receipt->cheque_received_date = human_date($receipt->cheque_received_date, 'd/m/Y');
			$receipt->received_date = human_date($receipt->received_date, 'd/m/Y');
            foreach ($receipt->note as $key => $note) {
				$user = $this->user_model->get($note->created_by);
				$note->created_on = human_timestamp($note->created_on, 'd/m/Y H:i a');
				$note->created_by = $user->first_name . ' ' . $user->last_name;
				$receipt->note[$key] = $note;
            }
			foreach ($receipt->detail as $key => $detail) {
				$invoice = $this->invoice_model->get($detail->invoice_id);
				$receipt->detail[$key]->invoice_number = $invoice->number;
			}

			$this->response($receipt, REST_Controller::HTTP_OK);
        }
		$this->response(false, REST_Controller::HTTP_OK);
    }


	public function get_history_post()
	{
		$post = $this->input->post();
		$output = [];

		$histories = $this->invoice_history_model->with('user')->with('client')->with('address')->with('contact')->order_by('id', 'DESC')->get_many_by('invoice_id', $post['invoice_id']);
		if (!empty($histories)) {
			foreach ($histories as $history) {
				$history->created_on = human_timestamp($history->created_on);
				$history->created_by = $history->user->first_name.' '.$history->user->last_name;
				if ($history->approved) {
					$history->status = invoice_status_badge($history->status);
				} else {
					$history->status = invoice_status_badge('Draft');
				}
				$temp       = new stdclass();
				$output[] = $history;
			}
		}

		$this->response($output, REST_Controller::HTTP_OK);
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

	public function cancel_post() {
		$receipt_id = $this->input->post('receipt_id');
		$receipt = $this->receipt_model->with('detail')->get($receipt_id);
		$invoices = $receipt->invoice;
		foreach ($receipt->detail as $receipt_detail) {
			$invoice = $this->invoice_model->get($receipt_detail->invoice_id);
			$new_paid_amount = $invoice->paid - $receipt_detail->paid_amount;
			if ($new_paid_amount == 0) {
				$status = 'New';
			}
			if ($new_paid_amount > 0 && $new_paid_amount < $invoice->amount) {
				$status = 'Partially Paid';
			}
			$this->invoice_model->set_invoice_id($invoice->id);
			$this->invoice_model->update($invoice->id, [
				'paid' => $new_paid_amount,
				'status' => $status
			]);

			$this->detail_receipt_model->update($receipt_detail->receipt_id, [
				'invoice_status' => $status
			]);
			$this->receipt_model->update($receipt_id, [
				'status' => 'Cancelled'
			]);
		}
		$this->response(true, REST_Controller::HTTP_OK);
	}

    public function get_notes_post()
    {
        $post = $this->input->post();
        $output = [];

		$notes = $this->receipt_note_model->with('user')->order_by('id', 'DESC')->get_many_by('receipt_id', $post['receipt_id']);

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


}
