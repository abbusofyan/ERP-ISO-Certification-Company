<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_fee_file_model extends WebimpModel
{
    protected $_table = 'assessment_fee_file';
    protected $soft_delete = false;

    protected $belongs_to = [
        'quotataion' => [
            'primary_key' => 'quotation_id',
            'model'       => 'quotation_model',
        ],
    ];


    public function __construct()
    {
        parent::__construct();

        $this->load->model('contact_history_model');
		$this->load->model('contact_model');
    }

	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}


}
