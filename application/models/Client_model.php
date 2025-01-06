<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends WebimpModel
{
    protected $_table = 'client';
    protected $soft_delete = true;

    protected $belongs_to = [
        'group' => [
            'primary_key' => 'group_id',
            'model'       => 'group_model',
        ],
        'file' => [
            'primary_key' => 'file_id',
            'model'       => 'file_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

	protected $has_many = [
        'contact' => [
            'primary_key' => 'client_id',
            'model'       => 'contact_model',
        ],
		'address' => [
            'primary_key' => 'client_id',
            'model'       => 'address_model',
        ],
		'client_history' => [
            'primary_key' => 'client_id',
            'model'       => 'client_history_model',
        ],
    ];

    protected $before_create = ['_format_submission', '_record_created', '_set_status'];
	protected $after_create  = ['_create_related'];
    protected $before_update = ['_format_submission', '_record_updated'];
	protected $after_update  = ['_get_updated_client', '_create_history'];
    protected $before_delete = ['_cache_client'];
    // protected $after_delete  = ['_delete_related'];

    protected $client_id;     // caching for use later
	protected $contact;
	protected $address;
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
		$data = [];

		if (array_key_exists('name', $post)) {
			$data['name'] = $post['name'];
		}

		if (array_key_exists('uen', $post)) {
			$data['uen'] = $post['uen'];
		}

		if (array_key_exists('website', $post)) {
			$data['website'] = $post['website'];
		}

		if (array_key_exists('phone', $post)) {
			$data['phone'] = $post['phone'];
		}

		if (array_key_exists('fax', $post)) {
			$data['fax'] = $post['fax'];
		}


		if (array_key_exists('deleted', $post)) {
			$data['deleted'] = $post['deleted'];
		}

		if (array_key_exists('email', $post)) {
            $data['email'] = $post['email'];
        }

		if (array_key_exists('contact_name', $post)) {
			$this->contact = $post;
		}

		if (array_key_exists('address', $post)) {
			$this->address = $post;
		}

		$this->client = $data;

        return $data;
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
	 * get created data to save to history
	 *
	 * @access protected
	 * @param int $contact_id
	 * @return obj
	 */
	protected function _get_updated_client()
	{
		$this->client = $this->get((int) $this->client_id);
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
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}






	/**
	 * Set client status
	 *
	 * @access protected
	 * @param array $post
	 * @return array
	 */
	protected function _set_status($post)
	{
		// set status function here
		$post['status'] = 'New';

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
	 * Create address and contact and client history after client
	 *
	 * @access protected
	 * @param int $client_id
	 * @return void
	 */
	protected function _create_related($client_id)
	{
		$this->client_id = $client_id;

		if ($this->contact) {
			$this->contact['client_id'] = $client_id;
			$this->_create_contact();
		}


		if ($this->address) {
			$this->address['client_id'] = $client_id;
			$this->_create_address();
		}

		$this->client = $this->get((int) $client_id);

		$this->_create_history();

		return $client_id;
	}




	protected function _create_contact() {
		$primary = 1;
		foreach ($this->contact['contact_name'] as $key => $contact) {
			$data = [
				'client_id' 	=> $this->contact['client_id'],
				'salutation'	=> $this->contact['salutation'][$key],
				'name' 			=> $this->contact['contact_name'][$key],
				'email'			=> $this->contact['contact_email'][$key],
				'position'		=> $this->contact['position'][$key],
				'department'	=> $this->contact['department'][$key],
				'phone'			=> $this->contact['contact_phone'][$key],
				'fax'			=> $this->contact['contact_fax'][$key],
				'mobile'		=> $this->contact['contact_mobile'][$key],
				'status'		=> $this->contact['contact_status'][$key],
				'primary'		=> $primary
			];

			$contact_id = $this->contact_model->insert($data);
			$primary = 0;
		}
	}





	protected function _create_address() {
		$contact_id = $this->address_model->insert([
			'client_id'		=> $this->address['client_id'],
			'address_name'	=> 'Main address',
			'address'		=> $this->address['address'],
			'address_2'		=> $this->address['address_2'],
			'phone'			=> $this->address['phone'],
			'fax'			=> $this->address['fax'],
			'postal_code'	=> $this->address['postal_code'],
			'country'		=> $this->address['country'],
			'total_employee'=> $this->address['total_employee'],
			'primary'		=> 1
		]);

	}






	/**
	 * Create history after creating or updating client
	 *
	 * @access protected
	 * @param int $client_id
	 * @return void
	 */
	protected function _create_history()
	{
		$this->client->client_id = $this->client->id;

		unset($this->client->created_on);
		unset($this->client->created_by);
		unset($this->client->updated_on);
		unset($this->client->updated_by);
		unset($this->client->id);
		unset($this->client->flagged);

		$client_history = $this->client_history_model->insert((array)$this->client);
		return $client_history;
	}



	// validate website url
	function validate_url($url) {
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
			return FALSE;
		}
		return TRUE;
	}

	public function update_status($client_id) {
		if($this->_has_ongoing_confirmed_quotation($client_id)) {
			$status = 'Active';
		} elseif ($this->_has_past_confirmed_quotation($client_id)) {
			$status = 'Past Active';
		} elseif ($this->_has_non_active_quotation($client_id)) {
			$status = 'Non-Active';
		} else {
			$status = 'New';
		};
		$data['status'] = $status;
		// $data['created_by'] = $this->session->userdata()['user_id'];
		// $data['created_on'] = time();
		// $this->update($client_id, $data);
		$this->db->where('id', $client_id);
		$this->db->update($this->_table, $data);

    	$this->client = $this->get($client_id);
		$this->_create_history();
	}

	public function _has_ongoing_confirmed_quotation($client_id) {
		$year = date('Y');
		$this->db->select('q.id');
		$this->db->from('client c');
		$this->db->join('client_history ch', 'ch.client_id = c.id');
		$this->db->join('quotation q', 'q.client_history_id = ch.id');
		$this->db->where('c.id', $client_id);
		$this->db->where('DATE_ADD(FROM_UNIXTIME(q.created_on), INTERVAL q.year_cycle YEAR) > CURDATE()');
		$this->db->where('q.status', 'Confirmed');
		$query = $this->db->get();
		$num_rows = $query->num_rows();
		if ($num_rows) {
			return true;
		}
		return false;
	}

	public function _has_past_confirmed_quotation($client_id) {
		$year = date('Y');
		$this->db->select('q.id');
		$this->db->from('client c');
		$this->db->join('client_history ch', 'ch.client_id = c.id');
		$this->db->join('quotation q', 'q.client_history_id = ch.id');
		$this->db->where('c.id', $client_id);
		$this->db->where('DATE_ADD(FROM_UNIXTIME(q.created_on), INTERVAL q.year_cycle YEAR) < CURDATE()');
		$this->db->where('q.status', 'Confirmed');
		$query = $this->db->get();
		$num_rows = $query->num_rows();
		if ($num_rows) {
			return true;
		}
		return false;
	}

	public function _has_non_active_quotation($client_id) {
		$this->db->select('q.id');
		$this->db->from('client c');
		$this->db->join('client_history ch', 'ch.client_id = c.id');
		$this->db->join('quotation q', 'q.client_history_id = ch.id');
		$this->db->where('c.id', $client_id);
		$this->db->group_start()
			->where('q.status', 'Non-Active')
			->or_where('q.status', 'Archive')
            ->or_where('q.status', 'Chosen Other CB')
		->group_end();
		$query = $this->db->get();
		$num_rows = $query->num_rows();
		if ($num_rows > 0) {
			return true;
		}
		return false;
	}

	// public function get_client_detial_for_quotation($client_id) {
	// 	$this->db->select('c.id as client_id, con.id as contact_history_id, con.name as contact_name, a.id as address_history_id, a.address, a.country');
	// 	$this->db->from('client c');
	// 	$this->db->join('contact_history con', 'con.client_id = c.id');
	// 	$this->db->join('address_history a', 'a.client_id = c.id');
	// 	$this->db->where('c.id', $client_id);
	// 	$this->db->where('con.primary', 1);
	// 	$this->db->where('a.primary', 1);
	// 	$query = $this->db->get();
	// 	return $query->row();
	// }


	public function uenBelongsToClient($uen, $client_id) {
		return $this->get_by([
			'uen' => $uen,
			'id' => $client_id
		]);
	}

	public function nameBelongsToClient($name, $client_id) {
		return $this->get_by([
			'name' => $name,
			'id' => $client_id
		]);
	}

 	 public function quotation($client_id) {
		$this->db->select('q.*');
		$this->db->from('quotation q');
		$this->db->join('client_history ch', 'ch.id = q.client_history_id');
		$this->db->join('client c', 'c.id = ch.client_id');
		$this->db->where('c.id', $client_id);
		$data = $this->db->get()->result();
		return $data;
	}

	public function updated_core_data($old_client, $new_client) {
		if ($old_client->name != $new_client->name) {
		  return true;
		}

		if ($old_client->fax != $new_client->fax) {
		  return true;
		}

		if ($old_client->website != $new_client->website) {
		  return true;
		}

		return false;
  }

  public function get_client_history($client_id) {
    $output = [];
    $histories = $this->db->select('ch.*, u.first_name, u.last_name, ah.address, ah.country, ah.postal_code, ah.total_employee, ch.created_on as ch_created_on')
          ->join('address_history ah', 'ch.client_id = ah.client_id')
          ->join('user u', 'ch.created_by = u.id', 'LEFT')
          ->where('ch.client_id', $client_id)
          ->group_by('ch.name, ch.uen, ch.phone, ch.fax, ch.email, ch.website')
          ->order_by('ch.id', 'DESC')
          ->get('client_history ch')->result();
    if (!empty($histories)) {
      foreach ($histories as $history) {
            $history->created_on = $history->ch_created_on ? human_timestamp($history->ch_created_on) : '';
            $history->created_by = $history->first_name . ' ' . $history->last_name;
            $history->address = $history->address . ', ' . $history->country . ' ' . $history->postal_code;
        $temp       = new stdclass();
        $output[] = $history;
      }
    }
    return $output;
  }

  public function get_current_primary_contact($client_id) {
      $primary_contact = $this->contact_model->get_by(['client_id' => $client_id, 'primary' => 1]);
      $latest_contact_data = $this->contact_history_model->limit(1)->order_by('id', 'DESC')->get_by('contact_id', $primary_contact->id);
      return $latest_contact_data;
  }

  public function get_current_primary_address($client_id) {
      $primary_address = $this->address_model->get_by(['client_id' => $client_id, 'primary' => 1]);
      $latest_address_data = $this->address_history_model->limit(1)->order_by('id', 'DESC')->get_by('address_id', $primary_address->id);
      return $latest_address_data;
  }

  public function set_soft_delete($soft_delete) {
      $this->soft_delete = $soft_delete;
      return $this;
  }

}
