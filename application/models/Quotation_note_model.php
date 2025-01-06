<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_note_model extends WebimpModel
{
    protected $_table = 'quotation_note';
    protected $soft_delete = false;

    protected $belongs_to = [
        'quotation' => [
            'primary_key' => 'quotation_id',
            'model'       => 'quotation_model',
        ],

		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],

		'created_by' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

    protected $before_create = ['_format_submission'];
    protected $before_update = ['_format_submission'];
    protected $before_delete = ['_cache_client'];
    protected $after_delete  = ['_delete_related'];

    protected $client_id;     // caching for use later
    protected $client;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('quotation_model');
    }



    protected function _format_submission($post)
    {
        $current_user = $this->session->userdata();
		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

        return $post;
    }

}
