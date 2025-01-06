<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_otp_model extends WebimpModel
{
    protected $_table = 'auth_otp';
    protected $soft_delete = false;

    public function __construct()
    {
        parent::__construct();
    }

}
