<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_note_model extends WebimpModel
{
    protected $_table = 'invoice_note';
    protected $soft_delete = false;

    protected $belongs_to = [
        'invoice' => [
            'primary_key' => 'invoice_id',
            'model'       => 'invoice_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

    protected $before_create = ['_record_created'];
    protected $before_update = ['_record_updated'];

    public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
        $this->load->model('quotation_model');
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
