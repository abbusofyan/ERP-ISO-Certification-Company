<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_address_model extends WebimpModel
{
    protected $_table = 'quotation_address';
    protected $soft_delete = false;

    protected $belongs_to = [
        'quotataion' => [
            'primary_key' => 'quotation_id',
            'model'       => 'quotation_model',
        ],

		'address' => [
            'primary_key' => 'address_history_id',
            'model'       => 'address_history_model',
        ],
    ];


    protected $before_update = ['_record_updated'];
    protected $after_update  = ['_get_updated_address', '_create_history'];

    protected $after_relate  = ['_get_details'];

    protected $before_delete = ['_cache_address'];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('contact_history_model');
		$this->load->model('contact_model');
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



  protected function _get_details($row)
  {
    if (is_object($row)) {
      $row->address = $this->address_history_model->get($row->address_history_id);
    } elseif (is_array($row)) {
      $row['address'] = $this->address_history_model->get($row['address_history_id']);
    }
    return $row;
  }
}
