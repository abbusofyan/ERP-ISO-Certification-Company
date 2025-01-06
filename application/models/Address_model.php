<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address_model extends WebimpModel
{
    protected $_table = 'address';
    protected $soft_delete = true;

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

    protected $before_create = ['_format_submission', '_record_created'];
	protected $after_create  = ['_get_created_address', '_create_history'];

    protected $before_update = ['_format_submission', '_record_updated'];
	protected $after_update  = ['_get_updated_address', '_create_history'];


    protected $before_delete = ['_cache_address'];
    protected $after_delete  = ['_create_history'];

    protected $address_id;     // caching for use later
    protected $address;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
    }






	/**
	 * Set address ID.
	 *
	 * @access public
	 * @param int $contact_id
	 * @return obj
	 */
	public function set_address_id($address_id)
	{
		$this->address_id = (int) $address_id;

		return $this;
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
     * Format the post submission.
     *
     * @access protected
     * @param array $post
     * @return array
     */
    protected function _format_submission($post)
    {
		$address = [];
		if (array_key_exists('client_id', $post)) {
			$address['client_id'] = $post['client_id'];
		}

		if (array_key_exists('address_name', $post)) {
			$address['name'] = $post['address_name'];
		}

		if (array_key_exists('name', $post)) {
			$address['name'] = $post['name'];
		}

		if (array_key_exists('address', $post)) {
			$address['address'] = $post['address'];
		}

		if (array_key_exists('address_2', $post)) {
			$address['address_2'] = $post['address_2'];
		}

		if (array_key_exists('phone', $post)) {
			$address['phone'] = $post['phone'];
		}

		if (array_key_exists('fax', $post)) {
			$address['fax'] = $post['fax'];
		}

		if (array_key_exists('country', $post)) {
			$address['country'] = $post['country'];
		}

		if (array_key_exists('postal_code', $post)) {
			$address['postal_code'] = $post['postal_code'];
		}

		if (array_key_exists('total_employee', $post)) {
			$address['total_employee'] = $post['total_employee'];
		}

		if (array_key_exists('primary', $post)) {
			$address['primary'] = $post['primary'];
		}

		$this->address = $address;

        return $address;
    }




	/**
	 * get created data to save to history
	 *
	 * @access protected
	 * @param int $contact_id
	 * @return obj
	 */
	protected function _get_created_address($address_id)
	{
		$this->address = $this->get((int) $address_id);
	}






	/**
	 * get created data to save to history
	 *
	 * @access protected
	 * @param int $contact_id
	 * @return obj
	 */
	protected function _get_updated_address()
	{
		$this->address = $this->get((int) $this->address_id);
	}






	/**
	 * Cache address as an object for use later.
	 *
	 * @access protected
	 * @param int $address_id
	 * @return obj
	 */
	protected function _cache_address($address_id)
	{
		$this->address = $this->get((int) $address_id);
		$this->address->deleted = 1;
		return $this;
	}







	/**
	 * Create history after creating or updating address
	 *
	 * @access protected
	 * @param int $client_id
	 * @return void
	 */
	protected function _create_history()
	{
		$this->address->address_id = $this->address->id;

		unset($this->address->created_on);
		unset($this->address->created_by);
		unset($this->address->updated_on);
		unset($this->address->updated_by);
		unset($this->address->id);

		$this->address_history_model->insert((array)$this->address);

		return $this->address->address_id;
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

		$this->address = $this->get((int) $post['address_id']);

		$this->_create_history();

		return $query;
    }

	public function updated_core_data($old_address, $new_address) {
		if ($old_address->address != $new_address->address) {
		  return true;
		}
		if ($old_address->address_2 != $new_address->address_2) {
		  return true;
		}
		if ($old_address->country != $new_address->country) {
		  return true;
		}
		if ($old_address->postal_code != $new_address->postal_code) {
		  return true;
		}
		return false;
	}

  public function get_address_history($client_id) {
    $output = [];
    $histories = $this->db->select('u.first_name, u.last_name, ah.*')
          ->join('user u', 'ah.created_by = u.id', 'LEFT')
          ->where('ah.client_id', $client_id)
          ->where('ah.primary', 1)
          ->group_by('ah.name, ah.address, ah.address_2, ah.country, ah.postal_code, ah.total_employee, ah.phone, ah.fax')
          ->order_by('ah.id', 'DESC')
          ->get('address_history ah')->result();
    if (!empty($histories)) {
        foreach ($histories as $key => $history) {
          if ($key + 1 != count($histories)) {
            $history->updated_on = human_timestamp($history->created_on);
            $history->updated_by = $history->first_name . ' ' . $history->last_name;
          } else {
            $history->updated_on = '';
            $history->updated_by = '';
          }
          $history->complete_address = $history->address . ', ' . $history->country . ' ' . $history->postal_code;
          $output[] = $history;
        }
    }
    return $output;
  }

}
