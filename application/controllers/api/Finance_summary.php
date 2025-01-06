<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Finance_Summary extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->model('receipt_model');
    }


	public function get_history_post()
	{
		$post = $this->input->post();
		$output = [];

		// $receipts = $this->receipt_model->get_many_by('quotation_id', $post['quotation_id']);
		$histories = $this->db->select('ch.name as client_name, i.number as invoice_number, i.invoice_date, i.invoice_type,
								i.amount as invoice_amount, i.status as invoice_status, r.paid_date, r.payment_method,
								r.created_on as receipt_date, r.paid_amount as receipt_amount, r.created_by as modified_by, u.first_name, u.last_name, r.status as receipt_status')->from('receipt r')
							->join('invoice i', 'r.quotation_id = i.quotation_id')
							->join('detail_receipt dr', 'dr.receipt_id = r.id AND dr.invoice_id = i.id')
							->join('client_history ch', 'i.client_history_id = ch.id')
							->join('user u', 'r.created_by = u.id')
							->where('r.quotation_id', $post['quotation_id'])->get()->result_array();
		if (!empty($histories)) {
			foreach ($histories as $history) {
				$history['modified_by'] = $history['first_name'].' '.$history['last_name'];
				$history['invoice_status'] = invoice_status_badge($history['invoice_status']);
                $history['receipt_status'] = receipt_status_badge($history['receipt_status']);
				$history['invoice_amount'] = money_number_format($history['invoice_amount']);
				$history['receipt_amount'] = money_number_format($history['receipt_amount']);
				$history['receipt_date'] = human_timestamp($history['receipt_date']);
				$temp       = new stdclass();
				$output[] = $history;
			}
		}

		$this->response($output, REST_Controller::HTTP_OK);
	}


}
