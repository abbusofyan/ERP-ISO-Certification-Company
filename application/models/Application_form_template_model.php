<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application_form_template_model extends WebimpModel
{
    protected $_table = 'application_form_template';

    protected $before_create = ['_record_created'];

    protected $belongs_to = [
        'file' => [
            'primary_key' => 'file_id',
            'model'       => 'file_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}

}
