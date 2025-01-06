<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class application_follow_up_model extends WebimpModel
{
    protected $_table = 'application_follow_up';

	protected $soft_delete = true;

	protected $belongs_to = [
        'application' => [
            'primary_key' => 'application_id',
            'model'       => 'application_form_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

    protected $has_many = [
		'attachment' => [
            'primary_key' => 'follow_up_id',
            'model'       => 'application_follow_up_attachment_model',
        ],
    ];

	protected $before_create = ['_format_submission', '_record_created'];

    public function __construct()
    {
        parent::__construct();
    }


	protected function _format_submission($post)
	{
		$data = [];

		if (array_key_exists('application_id', $post)) {
			$data['application_id'] = $post['application_id'];
		}

		if (array_key_exists('clarification_date', $post)) {
			$data['clarification_date'] = $post['clarification_date'];
		}

		if (array_key_exists('notes', $post)) {
			$data['notes'] = $post['notes'];
		}

		$this->application_follow_up = $data;

		return $data;
	}


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}
}
