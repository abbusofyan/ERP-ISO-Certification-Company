<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_note_model extends WebimpModel
{
    protected $_table = 'receipt_note';
    protected $soft_delete = false;

    protected $belongs_to = [
        'receipt' => [
            'primary_key' => 'receipt_id',
            'model'       => 'receipt_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

    protected $before_create = ['_format_submission', '_record_created'];
    protected $before_update = ['_record_updated'];

    public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
        $this->load->model('quotation_model');
    }



	protected function _format_submission($post)
	{
		$data = [];

		if (array_key_exists('receipt_id', $post)) {
			$data['receipt_id'] = $post['receipt_id'];
		}

		if (array_key_exists('note', $post)) {
			$data['note'] = $post['note'];
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

}
