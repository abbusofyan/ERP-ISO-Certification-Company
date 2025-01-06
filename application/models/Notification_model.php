<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_model extends WebimpModel
{
    protected $_table = 'notification';

	protected $before_create = ['_record_created'];

    public function __construct()
    {
        parent::__construct();
		$this->load->model('memo_model');
		$this->load->model('invoice_model');
    }


	protected $belongs_to = [
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}


	public function send_memo_notification($memo_id) {
		$memo = $this->memo_model->with('user')->with('quotation')->get($memo_id);
		$message = '<b>' . $memo->user->first_name . ' ' . $memo->user->last_name . ' </b> has created a memo <br> <b class="text-danger">' . $memo->number . '</b> for quotation <b class="text-danger"> ' . $memo->quotation->number . '</b>';

		$body = [
			'memo_id'	=> $memo_id,
			'message'	=> $message
		];

		$post = [
			'category'	=> 'Memo',
			'body'		=> json_encode($body)
		];

		$this->insert($post);
	}


	public function send_invoice_notification($invoice_id) {
		$invoice = $this->invoice_model->with('user')->with('quotation')->get($invoice_id);
		$message = '<p class="text-justify"><b>' . $invoice->user->first_name . ' ' . $invoice->user->last_name . '</b> has request to generate invoice for confirmed quotation <b class="text-danger"> ' . $invoice->quotation->number . '</b><p>';
		$body = [
			'invoice_id'	=> $invoice_id,
			'message'	=> $message
		];

		$post = [
			'category'	=> 'Invoice',
			'body'		=> json_encode($body)
		];

		$this->insert($post);
	}


	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
	}

}
