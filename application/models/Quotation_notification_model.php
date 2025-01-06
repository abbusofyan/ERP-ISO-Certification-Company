<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation_notification_model extends WebimpModel
{
    protected $_table = 'quotation_notification';

    protected $before_create = ['_record_created'];

	protected $after_relate  = ['_get_details'];

	protected $belongs_to = [
		'quotation' => [
			'primary_key' => 'quotation_id',
			'model'       => 'quotation_model',
		],
		
		'created_by' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],

		'updated_by' => [
            'primary_key' => 'updated_by',
            'model'       => 'user_model',
        ],

	];


    public function __construct()
    {
        parent::__construct();
    }

	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();
		return $post;
	}

	protected function _get_details($row)
	{
		if (is_object($row)) {
			$row->quotation = $this->quotation_model->get($row->quotation_id);
			$row->created_by = $this->user_model->get($row->created_by);
			$row->updated_by = $this->user_model->get($row->updated_by);
		} elseif (is_array($row)) {
			$row['quotation'] = $this->quotation_model->get($row['quotation']);
			$row['created_by'] = $this->user_model->get($row['created_by']);
			$row['updated_by'] = $this->user_model->get($row['updated_by']);
		}

		return $row;
	}
}
