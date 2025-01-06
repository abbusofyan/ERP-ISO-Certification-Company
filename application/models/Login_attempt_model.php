<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_attempt_model extends WebimpModel
{
    protected $_table = 'login_attempt';


    public function __construct()
    {
        parent::__construct();
    }
}
