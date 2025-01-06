<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Module_model extends WebimpModel
{
    protected $_table = 'modules';
    protected $soft_delete = false;

	protected $has_many = [
        'permissions' => [
            'primary_key' => 'module_id',
            'model'       => 'permission_model',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
