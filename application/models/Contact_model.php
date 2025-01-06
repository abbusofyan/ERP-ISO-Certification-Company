<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends WebimpModel
{
    protected $_table = 'contact';
    protected $soft_delete = true;

    protected $belongs_to = [
        'client' => [
            'primary_key' => 'client_id',
            'model'       => 'client_model',
        ],
    ];

    protected $before_create = ['_format_submission', '_record_created'];
	protected $after_create  = ['_get_created_contact', '_create_history'];

	protected $before_update = ['_format_submission', '_record_updated'];
	protected $after_update  = ['_get_updated_contact', '_create_history'];

    protected $before_delete = ['_cache_contact'];
    protected $after_delete  = ['_create_history'];

    protected $contact_id;
    protected $contact;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('client_model');
        $this->load->model('file_model');
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
     * Cache contact as an object for use later.
     *
     * @access protected
     * @param int $contact_id
     * @return obj
     */
    protected function _cache_contact($contact_id)
    {
        $this->contact = $this->get((int) $contact_id);
		$this->contact->deleted = 1;
        return $this;
    }





	/**
	 * get created data to save to history
	 *
	 * @access protected
	 * @param int $contact_id
	 * @return obj
	 */
	protected function _get_created_contact($contact_id)
	{
		$this->contact = $this->get((int) $contact_id);
	}






	/**
	 * get created data to save to history
	 *
	 * @access protected
	 * @param int $contact_id
	 * @return obj
	 */
	protected function _get_updated_contact()
	{
		$this->contact = $this->get((int) $this->contact_id);
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
        if (isset($post['email'])) {
            $post['email'] = strtolower($post['email']);
        }

        return $post;
    }








    /**
     * Set contact ID.
     *
     * @access public
     * @param int $contact_id
     * @return obj
     */
    public function set_contact_id($contact_id)
    {
        $this->contact_id = (int) $contact_id;

        return $this;
    }






	/**
	 * Create history after creating or updating contact
	 *
	 * @access protected
	 * @param int $client_id
	 * @return void
	 */
	protected function _create_history()
	{
		$this->contact->contact_id = $this->contact->id;

		unset($this->contact->created_on);
		unset($this->contact->created_by);
		unset($this->contact->updated_on);
		unset($this->contact->updated_by);
		unset($this->contact->id);

		return $this->contact_history_model->insert((array)$this->contact);
		// return $this->contact->contact_id;
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
     * switch client's main contact.
     *
     * @access protected
     * @param array $data
     * @return void
     */
    public function switch_main_contact($post)
    {
		$this->db->set('primary', 0);
		$this->db->where('client_id', $post['client_id']);
		$this->db->update($this->_table);

		$this->db->set('primary', 1);
		$this->db->where('client_id', $post['client_id']);
		$this->db->where('id', $post['contact_id']);
		$query = $this->db->update($this->_table);

		$this->contact = $this->get((int) $post['contact_id']);

		$contact = $this->_create_history();

		return $contact;
    }

	public function updated_core_data($old_contact, $new_contact) {
		if ($old_contact->name != $new_contact->name) {
		  return true;
		}

		if ($old_contact->position != $new_contact->position) {
		  return true;
		}

		if ($old_contact->mobile != $new_contact->mobile) {
		  return true;
		}

		if ($old_contact->email != $new_contact->email) {
		  return true;
		}
	}

  public function get_contact_history($client_id) {
    $output = [];
    $histories = $this->db->select('u.first_name, u.last_name, ch.*')
          ->join('user u', 'ch.created_by = u.id', 'LEFT')
          ->where('ch.client_id', $client_id)
          ->where('ch.primary', 1)
          ->group_by('ch.name, ch.email, ch.position, ch.department, ch.phone, ch.fax, ch.mobile')
          ->order_by('ch.id', 'DESC')
          ->get('contact_history ch')->result();
    if (!empty($histories)) {
        foreach ($histories as $key => $history) {
          if ($key + 1 != count($histories)) {
            $history->updated_on = human_timestamp($history->created_on);
            $history->updated_by = $history->first_name . ' ' . $history->last_name;
          } else {
            $history->updated_on = '';
            $history->updated_by = '';
          }
          $output[] = $history;
        }
    }
    return $output;
  }


}
