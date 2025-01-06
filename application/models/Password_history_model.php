<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Password_history_model extends WebimpModel
{
    protected $_table = 'password_history';

    protected $belongs_to = [
		'user' => [
            'primary_key' => 'user_id',
            'model'       => 'user_model',
        ],
    ];

    protected $before_create = ['_format_submission'];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }

	protected function _format_submission($post)
    {
        if (isset($post['password']) && !empty($post['password'])) {
            $post['password'] = $this->user_model->hash_password($post['password']);
        } else {
            unset($post['password']);
        }
        return $post;
    }

}
