<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_note_model extends WebimpModel
{
    protected $_table = 'client_note';
    protected $soft_delete = false;

    protected $belongs_to = [
        'client' => [
            'primary_key' => 'client_id',
            'model'       => 'client_model',
        ],

		'user' => [
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

        $this->load->model('client_model');
    }




    /**
     * Cache client as an object for use later.
     *
     * @access protected
     * @param int $client_id
     * @return obj
     */
    protected function _cache_client($client_id)
    {
        $this->client = $this->get((int) $client_id);

        return $this;
    }




	/**
     * Format the post submission.
     *
     * @access protected
     * @param array $post
     * @return array
     */
    protected function _format_submission($post)
    {
        $current_user = $this->session->userdata();
		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

        return $post;
    }




    /**
     * Set client ID.
     *
     * @access public
     * @param int $client_id
     * @return obj
     */
    public function set_client_id($client_id)
    {
        $this->client_id = (int) $client_id;

        return $this;
    }




    /**
     * Delete related.
     *
     * @access protected
     * @param array $data
     * @return void
     */
    protected function _delete_related($data)
    {
        if (isset($this->client->id)) {
            // get branchs by client ID
            $branches = $this->branch_model->get_many_by('client_id', $this->client->id);

            if (!empty($branches)) {
                foreach ($branches as $branch) {
                    // get asset group
                    $asset_group = $this->asset_model->get_by('branch_id', $branch->id);

                    // delete branch
                    $this->branch_model->delete($branch->id);
                }
            }
        }

        return $data;
    }

}
