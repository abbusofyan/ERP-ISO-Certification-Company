<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_scheme_model extends WebimpModel
{
    protected $_table = 'certification_scheme';
    protected $soft_delete = true;

    protected $belongs_to = [
        'client' => [
            'primary_key' => 'client_id',
            'model'       => 'client_model',
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

        $this->load->model('client_model');
    }

	public function soft_delete() {
		return $this->soft_delete = false;
	}

	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
	}

	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}

}
