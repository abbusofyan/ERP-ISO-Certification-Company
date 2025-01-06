<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Salutation_model extends WebimpModel
{
    protected $_table = 'salutation';
    protected $soft_delete = false;

    public function __construct()
    {
        parent::__construct();
    }

}
