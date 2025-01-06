<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_history_model extends WebimpModel
{
    protected $_table = 'contact_history';
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
    		'created_by' => [
    			'primary_key' => 'created_by',
    			'model'       => 'user_model',
    		],
        'updated_by' => [
    			'primary_key' => 'updated_by',
    			'model'       => 'user_model',
    		],
    ];

    protected $has_many = [
          'quotation' => [
              'primary_key' => 'client_history_id',
              'model'       => 'quotation_model',
          ],
      ];

    protected $before_create = ['_format_submission', '_record_created'];
    protected $before_update = ['_format_submission'];
    protected $before_delete = ['_cache_client'];
    // protected $after_delete  = ['_delete_related'];

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
        if (isset($post['email'])) {
            $post['email'] = strtolower($post['email']);
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
	 * get all the latest contact's history data by client id
	 *
	 * @access public
	 * @return void
	 */
	public function get_all_by_client($client_id)
	{
		$query = $this->db->select('*')
			 	 ->where('id IN (SELECT MAX(id) FROM contact_history where client_id = '.$client_id.' GROUP BY contact_id)')
				 ->get($this->_table);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

    public function get_by_client($client_id) {
        $contact_history = $this->with('created_by')
            ->group_by(['salutation', 'name', 'email', 'position', 'department', 'phone', 'fax', 'mobile', 'status'])
            ->order_by('id', 'DESC')
            ->get_many_by('client_id', $client_id);
        $data_contact = [];
		foreach ($contact_history as $key => $contact) {
            $created_by = $contact->created_by->first_name.' '.$contact->created_by->last_name;
            $created_on = human_timestamp($contact->created_on);
            if (count($contact_history) == 1 || $key + 1 == count($contact_history)) {
                $created_by = $created_on = '';
			}
			$contact->created_by = $created_by;
			$contact->created_on = $created_on;
			array_push($data_contact, $contact);
		}
        return $data_contact;
    }
}
