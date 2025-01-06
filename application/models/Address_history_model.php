<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address_history_model extends WebimpModel
{
    protected $_table = 'address_history';
    protected $soft_delete = true;

    protected $belongs_to = [
        'client' => [
            'primary_key' => 'client_id',
            'model'       => 'client_model',
        ],
		'address' => [
            'primary_key' => 'address_id',
            'model'       => 'address_model',
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

    protected $before_create = ['_record_created'];
    protected $before_update = ['_record_updated'];
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
	 * Record created by & created on.
	 *
	 * @access protected
	 * @param array $post
	 * @return array
	 */
	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}






	/**
	 * Record updated by & updated on.
	 *
	 * @access protected
	 * @param array $post
	 * @return array
	 */
	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
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
		if (array_key_exists('updated_by', $post)) {
			unset($post['updated_by']);
		}

		if (array_key_exists('id', $post)) {
			unset($post['id']);
		}

		if (array_key_exists('updated_on', $post)) {
			unset($post['updated_on']);
		}

		if (array_key_exists('created_by', $post)) {
			unset($post['created_by']);
		}

		if (array_key_exists('created_on', $post)) {
			unset($post['created_on']);
		}

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



	/**
     * switch client's main address.
     *
     * @access protected
     * @param array $data
     * @return void
     */
    public function switch_main_address($post)
    {
		$this->db->set('primary', 0);
		$this->db->where('client_id', $post['client_id']);
		$this->db->update($this->_table);

		$this->db->set('primary', 1);
		$this->db->where('client_id', $post['client_id']);
		$this->db->where('id', $post['address_id']);
		$query = $this->db->update($this->_table);

		return $query;
    }





	/**
	 * get all the latest contact's history data by client id
	 *
	 * @access public
	 * @return void
	 */
	 public function get_all_by_client($client_id)
	 {
	 	$query = $this->db->select('*')
	 			 ->where("id IN (SELECT MAX(id) FROM contact_history where client_id = $client_id GROUP BY contact_id)")
				 ->where('primary', 1)
	 			 ->get($this->_table);
	 	if ($query->num_rows() > 0) {
	 		return $query->row();
	 	} else {
	 		return null;
	 	}
	 }


	public function get_latest_by_address($address_id) {
 		$this->db->where('address_id', $address_id);
 		$this->db->order_by('id', 'DESC');
 		$this->db->limit(1);
 		$address_history = $this->db->get($this->_table)->row();
 		return $address_history;
 	}


    public function get_by_client($client_id) {
        $address_history = $this->with('created_by')
            ->group_by(['name', 'address', 'address_2', 'country', 'postal_code', 'total_employee'])
            ->order_by('id', 'DESC')
            ->get_many_by('client_id', $client_id);
        $data_address = [];
		foreach ($address_history as $key => $address) {
            $created_by = $address->created_by->first_name.' '.$address->created_by->last_name;
            $created_on = human_timestamp($address->created_on);
            if (count($address_history) == 1 || $key + 1 == count($address_history)) {
                $created_by = $created_on = '';
			}
			$address->created_by = $created_by;
			$address->created_on = $created_on;
			array_push($data_address, $address);
		}
        return $data_address;
    }
}
