<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Country_model extends WebimpModel
{
    protected $_table = 'countries';
    protected $soft_delete = false;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
    }

}
