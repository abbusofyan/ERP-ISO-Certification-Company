<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Application_follow_up_attachment_model extends WebimpModel
{
    protected $_table = 'application_follow_up_attachment';
    protected $soft_delete = false;

    protected $belongs_to = [
        'application' => [
            'primary_key' => 'application_id',
            'model'       => 'application_form_model',
        ],
		'file' => [
            'primary_key' => 'file_id',
            'model'       => 'file_model',
        ],
    ];


    public function __construct()
    {
        parent::__construct();

        $this->load->model('application_form_model');
    }

	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}


}
