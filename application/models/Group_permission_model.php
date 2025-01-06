<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group_permission_model extends WebimpModel
{
    protected $_table = 'group_permissions';
    protected $soft_delete = false;

	protected $has_many = [
        'permissions' => [
            'primary_key' => 'permission_id',
            'model'       => 'permission_model',
        ],
		'group' => [
            'primary_key' => 'group_id',
            'model'       => 'group_model',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
