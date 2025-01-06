<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_history_model extends WebimpModel
{
    protected $_table = 'client_history';
    protected $soft_delete = true;

	protected $belongs_to = [
		'user' => [
			'primary_key' => 'created_by',
			'model'       => 'user_model',
		],
        'client' => [
			'primary_key' => 'client_id',
			'model'       => 'client_model',
		],
		'quotation' => [
			'primary_key' => 'client_id',
			'model'       => 'quotation_model',
		],
		'created_by' => [
			'primary_key' => 'created_by',
			'model'       => 'user_model',
		],
	];

    protected $before_create = ['_record_created'];
    protected $before_delete = ['_cache_client'];
    protected $after_delete  = ['_delete_related'];

    protected $client_id;     // caching for use later
    protected $client;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
        $this->load->model('file_model');
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
        // poc email
        if (isset($post['poc_email'])) {
            $post['poc_email'] = strtolower($post['poc_email']);
        }

        $current_user = $this->session->userdata();

        if (!$this->client_id) {
            $post['created_by'] = $current_user['user_id'];
            $post['created_on'] = time();
        } else {
            $post['updated_by'] = $current_user['user_id'];
            $post['updated_on'] = time();
        }

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




	/**
	 * Record created by & created on.
	 *
	 * @access protected
	 * @param array $post
	 * @return array
	 */
	protected function _record_created($post)
	{
		if (!isset($post['created_by'])) {
			$current_user = $this->session->userdata();

			$post['created_by'] = $current_user['user_id'];
			$post['created_on'] = time();
		}

		return $post;
	}



	/**
	 * get all the latest client's history data by status
	 *
	 * @access public
	 * @return void
	 */
	public function get_all_by_status($status)
	{
		$query = $this->db->where('status', $status)
				->where('id IN (SELECT MAX(id) FROM client_history GROUP BY client_id)')
        ->where('status', $status)
				->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}


    public function get_clients()
	{
		$query = $this->db->where('id IN (SELECT MAX(id) FROM client_history GROUP BY client_id)')
				->get($this->_table);

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function get_latest_by_client($client_id) {
		$this->db->where('client_id', $client_id);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);
		$client_history = $this->db->get($this->_table)->row();
		return $client_history;
	}

    public function get_by_client($client_id) {
        $client_history = $this->with('created_by')
            ->group_by(['name', 'uen', 'website', 'phone', 'fax', 'email', 'status'])
            ->order_by('id', 'DESC')
            ->get_many_by('client_id', $client_id);
        $data_client = [];
        foreach ($client_history as $key => $client) {
            $created_by = $client->created_by->first_name.' '.$client->created_by->last_name;
            $created_on = human_timestamp($client->created_on);
            if (count($client_history) == 1 || $key + 1 == count($client_history)) {
                $created_by = $created_on = '';
            }
            $status = client_status_badge($client->status);
            $client->status = $status;
			$client->created_by = $created_by;
			$client->created_on = $created_on;
			array_push($data_client, $client);
		}
        return $data_client;
    }
}
