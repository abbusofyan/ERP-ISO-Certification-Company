<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_detail_model extends WebimpModel
{
    protected $_table = 'quotation_detail';
    protected $soft_delete = false;

    protected $belongs_to = [
        'quotation' => [
            'primary_key' => 'quotation_id',
            'model'       => 'quotation_model',
        ],
    ];

    protected $before_create = ['_format_submission', '_record_created'];

    protected $before_update = ['_record_updated'];
	protected $after_update  = ['_get_updated_address', '_create_history'];


    protected $before_delete = ['_cache_address'];
    protected $after_delete  = ['_create_history'];

    protected $address_id;     // caching for use later
    protected $address;

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
     * Format the post submission.
     *
     * @access protected
     * @param array $post
     * @return array
     */
    protected function _format_submission($post)
    {
		$data = [
			'quotation_id'				=> $post['quotation_id'],
			'quote_date' 				=> $post['quote_date'],
			'referred_by'				=> $post['referred_by'],
			'certification_cycle'		=> $post['certificate_cycle'],
			'invoice_to'				=> $post['invoice_to'],
			'group_company'				=> json_encode(explode(',', $post['group_of_companies'])),
			'consultant_pay_3_years'	=> $post['consultant_pay_3_years'],
			'client_pay_3_years'		=> $post['client_pay_3_years'],
			'application_form'			=> $post['application_form'],
			'terms_and_conditions'		=> $post['terms'],
			'certification_scheme'		=> $post['certification_scheme_id'],
			'accreditation'				=> $post['accreditation_id'],
			'scope'						=> $post['scope'],
			'num_of_sites'				=> $post['no_of_sites'],
			'stage_audit'				=> $post['stage_audit'],
			'surveillance_year_2'		=> $post['year_surveillance_2'],
			'surveillance_year_3'		=> $post['year_surveillance_3'],
			'transportation'			=> $post['transportation'],
			'assesment_note'			=> $post['assesment_note']
		];

		if ($post['certificate_cycle'] != constant('NEW')) {
			$data['received_prev_reports'] = $post['received_prev_reports'];
			$data['prev_cert_issue_date'] = $post['prev_cert_issue_date'];
			$data['prev_cert_exp_date'] = $post['prev_cert_exp_date'];
			$data['prev_certification_scheme'] = $post['prev_standard'];
			$data['prev_accreditation'] = $post['prev_accreditation'];
			$data['prev_certification_body'] = $post['prev_cb'];
		}

        return $data;
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





	public function generate_new_quote_number($quote_type) {
		$year = date('Y');

		$number = '0001';

		$last_quote = $this->order_by('id', 'DESC')->get_by([
			'type' => $quote_type,
			'FROM_UNIXTIME(created_on, "%Y") = '."$year"
		]);

		switch ($quote_type) {
			case 'ISO':
				$code = 'CQN';
				break;

			case 'Bizsfe':
				$code = 'CQN';
				break;

			case 'Training':
				$code = 'TRN';
				break;

			default:
				// code...
				break;
		}

		if ($last_quote) {
			$last_quote_number = explode('/', explode('-', $last_quote->number)[2])[0];
			$number = str_pad(intval($last_quote_number) + 1, strlen($last_quote_number), '0', STR_PAD_LEFT);
		}

		return 'ASA-'.$code.'-'.$number.'/'.date('Y');
	}

}
