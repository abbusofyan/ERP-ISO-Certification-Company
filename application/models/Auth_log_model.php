<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_log_model extends WebimpModel
{
    protected $_table = 'auth_log';

    protected $soft_delete = false;

    protected $belongs_to = [
		'user' => [
            'primary_key' => 'user_id',
            'model'       => 'user_model',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
