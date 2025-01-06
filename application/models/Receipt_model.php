<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_model extends WebimpModel
{
    protected $_table = 'receipt';

	protected $before_create = ['_format_submission', '_record_created'];
	protected $before_update = ['_format_submission', '_record_updated'];
	protected $after_get = ['_relate_invoices'];

    public function __construct()
    {
        parent::__construct();
		$this->load->model('invoice_model');
    }

	protected $belongs_to = [
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];


	protected $has_many = [
		'note' => [
			'primary_key' => 'receipt_id',
			'model'       => 'receipt_note_model',
		],
		'detail' => [
			'primary_key' => 'receipt_id',
			'model'       => 'detail_receipt_model',
		],
	];


	protected function _format_submission($post)
	{
		$data = [];

		if (array_key_exists('quotation_id', $post)) {
			$data['quotation_id'] = $post['quotation_id'];
		}

		if (array_key_exists('payment_method', $post)) {
			$data['payment_method'] = $post['payment_method'];
		}

		if (array_key_exists('paid_amount', $post)) {
			$data['paid_amount'] = $post['paid_amount'];
		}

		if (array_key_exists('transfer_date', $post) && $post['transfer_date']) {
			$data['paid_date'] = $post['transfer_date'];
			$data['transfer_date'] = $post['transfer_date'];
		}

		if (array_key_exists('received_date', $post) && $post['received_date']) {
			$data['paid_date'] = $post['received_date'];
			$data['received_date'] = $post['received_date'];
		}

		if (array_key_exists('cheque_received_date', $post) && $post['cheque_received_date']) {
			$data['paid_date'] = $post['cheque_received_date'];
			$data['cheque_received_date'] = $post['cheque_received_date'];
		}

		if (array_key_exists('discount', $post)) {
			$data['discount'] = $post['discount'];
		}

		if (array_key_exists('cheque_no', $post)) {
			$data['cheque_no'] = $post['cheque_no'];
		}

		if (array_key_exists('cheque_date', $post)) {
			$data['cheque_date'] = $post['cheque_date'];
		}

		if (array_key_exists('bank', $post)) {
			$data['bank'] = $post['bank'];
		}

		if (array_key_exists('status', $post)) {
			$data['status'] = $post['status'];
		}

		return $data;
	}


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();
		return $post;
	}

	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
	}

	protected function _relate_invoices($receipt) {
		$invoices = $this->db->join('detail_receipt dr', 'dr.invoice_id = i.id')->where('dr.receipt_id', $receipt->id)->get('invoice i')->result();
		$receipt->invoice = $invoices;
		return $receipt;
	}

}
