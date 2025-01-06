<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Authorized_ip_model extends WebimpModel
{
    protected $_table = 'authorized_ip';
    protected $soft_delete = false;

    public function __construct()
    {
        parent::__construct();
    }

}
