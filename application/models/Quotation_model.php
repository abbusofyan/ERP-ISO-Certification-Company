<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_model extends WebimpModel
{
    protected $_table = 'quotation';
    protected $soft_delete = true;

    protected $belongs_to = [
		'client' => [
            'primary_key' => 'client_history_id',
            'model'       => 'client_history_model',
        ],

		'contact' => [
            'primary_key' => 'contact_history_id',
            'model'       => 'contact_history_model',
        ],

		'address' => [
            'primary_key' => 'address_history_id',
            'model'       => 'address_history_model',
        ],

		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],

		'certification_cycle' => [
            'primary_key' => 'certification_cycle',
            'model'       => 'certification_cycle_model',
        ],

		'certification_scheme' => [
            'primary_key' => 'certification_scheme',
            'model'       => 'certification_scheme_model',
        ],

		'accreditation' => [
            'primary_key' => 'accreditation',
            'model'       => 'accreditation_model',
        ],

		'created_by' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],

		'updated_by' => [
            'primary_key' => 'updated_by',
            'model'       => 'user_model',
        ],

		'deleted_by' => [
            'primary_key' => 'deleted_by',
            'model'       => 'user_model',
        ],

		'training_type' => [
            'primary_key' => 'training_type',
            'model'       => 'training_type_model',
        ],
    ];

	protected $has_many = [
		'quotation_address' => [
			'primary_key' => 'quotation_id',
			'model'       => 'quotation_address_model',
		],
		'invoice' => [
			'primary_key' => 'quotation_id',
			'model'       => 'invoice_model',
		],
		'assessment_fee_file' => [
			'primary_key' => 'quotation_id',
			'model'       => 'assessment_fee_file_model',
		],
		'past_certification_report' => [
			'primary_key' => 'quotation_id',
			'model'       => 'certificate_and_report_file_model',
		],
	];

   protected $before_create = ['_format_submission', '_record_created'];
   // protected $after_create = ['_get_created_quotation', '_create_history', '_set_archive'];
   protected $after_create = ['_get_created_quotation', '_create_history'];

    protected $before_update = ['_format_submission', '_record_updated'];
	protected $after_update  = ['_get_updated_quotation', '_create_history'];


    protected $before_delete = ['_cache_quotation'];
    protected $after_delete  = ['_create_history'];

	protected $after_relate  = ['_get_details', '_group_of_companies', '_certification_scheme', '_accreditation', '_training_type'];

    protected $quotation_id;     // caching for use later
    protected $quotation;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('quotation_history_model');
		$this->load->model('client_history_model');
		$this->load->model('client_model');
		$this->load->model('address_history_model');
		$this->load->model('contact_history_model');
		$this->load->model('training_type_model');

		$this->format_group_company = true;
    }






	/**
	 * Set address ID.
	 *
	 * @access public
	 * @param int $contact_id
	 * @return obj
	 */
	public function set_quotation_id($quotation_id)
	{
		$this->quotation_id = (int) $quotation_id;

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
      if(isset($post['type'])) {
        $type = $post['type'];
        if ($type == 'ISO') {
          return $this->_format_submission_iso($post);
        } elseif ($type == 'Training') {
          return $this->_format_submission_training($post);
        } else {
          return $this->_format_submission_bizsafe($post);
        }
      }
      return $post;
    }



	protected function _format_submission_iso($post) {
		if (isset($post['quotation_id'])) {
			$quote_number = $post['number'];

			if ($this->_updated_core_quotation_data($post)) {
                $quote_number = $this->generate_rev_number($post['number']);
			}

			$quote_status = $post['status'];
    	} else {
			$quote_number = $this->generate_new_quote_number($post['type']);
			$quote_status = constant('DEFAULT_QUOTATION_STATUS');
		}

		$certification_scheme = $post['certification_scheme'];
		$accreditation = $post['accreditation'];

		$certification_scheme = array_filter($certification_scheme, function ($value) {
		    return $value !== "";
		});

		$accreditation = array_filter($accreditation, function ($value) {
		    return $value !== "";
		});

		if(in_array($post['certification_cycle'], [1, 4, 5])) {
			$year_cycle = 3;
		} elseif ($post['certification_cycle'] == 2) {
			$year_cycle = 2;
		} else {
			$year_cycle = 1;
		}

		$data = [
			'number'					=> $quote_number,
			'type'						=> $post['type'],
			'status'					=> $quote_status,
			'client_history_id' 		=> $post['client_history_id'],
			'contact_history_id' 		=> $post['contact_history_id'],
			'address_history_id'		=> $post['address_history_id'],
			'quote_date' 				=> $post['quote_date'],
			'referred_by'				=> $post['referred_by'],
			'certification_cycle'		=> $post['certification_cycle'],
			'year_cycle'				=> $year_cycle,
			'certification_scheme'		=> implode(',', $certification_scheme),
			'accreditation'				=> implode(',', $accreditation),
			'invoice_to'				=> $post['invoice_to'],
			'num_of_sites'				=> $post['num_of_sites'],
			'scope'						=> $post['scope'],
			'terms_and_conditions'		=> $post['terms_and_conditions'],
			'consultant_pay_3_years'	=> $post['consultant_pay_3_years'],
			'client_pay_3_years'		=> $post['client_pay_3_years'],
			'application_form'			=> $post['application_form'],
			'stage_audit'				=> $post['stage_audit'] ? $post['stage_audit'] : NULL,
			'surveillance_year_1'		=> $post['surveillance_year_1'] ? $post['surveillance_year_1'] : NULL,
			'surveillance_year_2'		=> $post['surveillance_year_2'] ? $post['surveillance_year_2'] : NULL,
			'transportation'			=> $post['transportation'],
		];

    if (isset($post['group_company'])) {
      $data['group_company'] = json_encode($post['group_company']);
    }

		if ($post['certification_cycle'] != constant('QUOTE_CYCLE_NEW')) {
			$data['received_prev_reports'] = $post['received_prev_reports'];
			$data['prev_cert_issue_date'] = $post['prev_cert_issue_date'] ? $post['prev_cert_issue_date'] : 0;
			$data['prev_cert_exp_date'] = $post['prev_cert_exp_date'] ? $post['prev_cert_exp_date'] : 0;
			$data['prev_certification_scheme'] = $post['prev_certification_scheme'];
			$data['prev_accreditation'] = $post['prev_accreditation'];
			$data['prev_certification_body'] = $post['prev_certification_body'];
		}

		$this->quotation = $data;
    return $data;
	}



	protected function _format_submission_bizsafe($post) {
		if (isset($post['quotation_id'])) {
			$quote_number = $post['number'];

			if ($this->_updated_core_quotation_data($post)) {
				$quote_number = $this->generate_rev_number($post['number']);
			}

			$quote_status = $post['status'];
    	} else {
			$quote_number = $this->generate_new_quote_number($post['type']);
			$quote_status = constant('DEFAULT_QUOTATION_STATUS');
		}

		$certification_scheme = $post['certification_scheme'];
		$accreditation = $post['accreditation'];

		$certification_scheme = array_filter($certification_scheme, function ($value) {
		    return $value !== "";
		});

		$accreditation = array_filter($accreditation, function ($value) {
		    return $value !== "";
		});

		$data = [
			'number'					=> $quote_number,
			'type'						=> $post['type'],
			'status'					=> $quote_status,
			'client_history_id' 		=> $post['client_history_id'],
			'contact_history_id' 		=> $post['contact_history_id'],
			'address_history_id'		=> $post['address_history_id'],
			'quote_date' 				=> $post['quote_date'],
			'referred_by'				=> $post['referred_by'],
			'certification_cycle'		=> $post['certification_cycle'],
			'year_cycle'				=> 1,
			'certification_scheme'		=> implode(',', $certification_scheme),
			'accreditation'				=> implode(',', $accreditation),
			'invoice_to'				=> $post['invoice_to'],
			'num_of_sites'				=> 1,
			'scope'						=> $post['scope'],
			'terms_and_conditions'		=> $post['terms_and_conditions'],
			'audit_fee'					=> $post['audit_fee'],
		];

		if (isset($post['group_company'])) {
		$data['group_company'] = json_encode($post['group_company']);
		}

		$this->quotation = $data;
    	return $data;

	}



	protected function _format_submission_training($post) {
    if (isset($post['quotation_id'])) {
      $quote_number = $post['number'];

      if ($this->_updated_core_quotation_data($post)) {
        $quote_number = $this->generate_rev_number($post['number']);
      }

			$quote_status = $post['status'];
    } else {
			$quote_number = $this->generate_new_quote_number($post['type']);
			$quote_status = constant('DEFAULT_QUOTATION_STATUS');
		}

		$data = [
			'number'					=> $quote_number,
			'type'						=> $post['type'],
			'status'					=> $quote_status,
			'client_history_id' 		=> $post['client_history_id'],
			'contact_history_id' 		=> $post['contact_history_id'],
			'address_history_id'		=> $post['address_history_id'],
			'quote_date' 				=> $post['quote_date'],
			'referred_by'				=> $post['referred_by'],
			'certification_cycle'		=> $post['certification_cycle'],
			'year_cycle'				=> 1,
			'training_type'				=> implode(',', $post['training_type']),
			'training_description'		=> $post['training_description'],
			'invoice_to'				=> $post['invoice_to'],
			'num_of_sites'				=> 1,
			'terms_and_conditions'		=> $post['terms_and_conditions'],
			'total_amount'				=> $post['total_amount'],
			'discount'					=> $post['discount'],
			'payment_terms'				=> $post['payment_terms'],
			'duration'					=> $post['duration'],
			'transportation'			=> $post['transportation'],
		];

    if (isset($post['group_company'])) {
      $data['group_company'] = json_encode($post['group_company']);
    }

		$this->quotation = $data;

    return $data;

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





	protected function _group_of_companies($data) {
		if ($this->format_group_company) {
			if ($data && $data->group_company) {
				$group_company = array_filter(json_decode($data->group_company));
				$companies_name = array();
				foreach ($group_company as $client_id) {
					$client = $this->client_model->get($client_id);
					array_push($companies_name, $client->name);
				}

				$data->group_company_name = $companies_name;
			}
		}

		return $data;
	}


	protected function _certification_scheme($quotation) {
		if (!$quotation) {
			return false;
		}
		if (!$quotation->certification_scheme) {
			$quotation->certification_scheme_arr = [];
			return $quotation;
		}
    $certification_scheme = explode(',', $quotation->certification_scheme);
		$quotation->certification_scheme_arr = [];

		if (is_array($certification_scheme)) {
			foreach ($certification_scheme as $scheme_id) {
				$scheme = $this->certification_scheme_model->get($scheme_id);
				if ($scheme) {
					$quotation->certification_scheme_arr[] = $scheme->name;
				}
			}
			return $quotation;
		}

		$scheme = $this->certification_scheme_model->get($certification_scheme);
		if ($scheme) {
			$quotation->certification_scheme_arr[] = $scheme->name;
		}
		return $quotation;
	}



	protected function _accreditation($quotation) {
		if (!$quotation) {
			return false;
		}
        if ($quotation->accreditation) {
            $accreditations = explode(',', $quotation->accreditation);
        } else {
            $accreditations = [];
        }
		$quotation->accreditation_arr = [];

		if (is_array($accreditations)) {
			foreach ($accreditations as $accreditation_id) {
				$accreditation = $this->accreditation_model->get($accreditation_id);
				if ($accreditation) {
					$quotation->accreditation_arr[] = $accreditation->name;
				}
			}
			return $quotation;
		}

		$accreditation = $this->accreditation_model->get($accreditations);
		if ($accreditation) {
			$quotation->accreditation_arr[] = $accreditation->name;
		}
		return $quotation;
	}



	protected function _cache_quotation($quotation_id)
	{
		$this->quotation = $this->get((int) $quotation_id);
		$this->quotation->deleted = 1;
		return $this;
	}







	/**
	 * get created data to save to history
	 *
	 * @access protected
	 * @param int $contact_id
	 * @return obj
	 */
	protected function _get_created_quotation($quotation_id)
	{
		$this->format_group_company = false;
		$this->quotation = $this->get((int) $quotation_id);
	}



	protected function _get_updated_quotation()
	{
		$this->format_group_company = false;
		$this->quotation = $this->get((int) $this->quotation_id);
	}




	protected function _get_details($row)
	{
		if (is_object($row)) {
			$row->client = $this->client_history_model->get($row->client_history_id);
			$row->address = $this->address_history_model->get($row->address_history_id);
			$row->contact = $this->contact_history_model->get($row->contact_history_id);
		} elseif (is_array($row)) {
			$row['client'] = $this->client_history_model->get($row['client_history_id']);
			$row['address'] = $this->address_history_model->get($row['address_history_id']);
			$row['contact'] = $this->contact_history_model->get($row['contact_history_id']);
		}

		return $row;
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
		$this->quotation->quotation_id = $this->quotation->id;

		unset($this->quotation->created_on);
		unset($this->quotation->created_by);
		unset($this->quotation->id);

		unset($this->quotation->client);
		unset($this->quotation->address);
		unset($this->quotation->contact);
		unset($this->quotation->certification_scheme_arr);
		unset($this->quotation->accreditation_arr);
		unset($this->quotation->training_type_arr);

    $this->quotation_history_model->insert((array)$this->quotation);


		return $this->quotation->quotation_id;
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

		// $this->_create_history();

		return $query;
    }





	public function generate_new_quote_number($quote_type) {
		$year = date('Y');

		$number = '0001';

		switch ($quote_type) {
			case 'ISO':
				$code = 'CQN';
				break;

			case 'Bizsafe':
				$code = 'CQN';
				break;

			case 'Training':
				$code = 'TRN';
				break;

			default:
				// code...
				break;
		}

		$last_quote = $this->order_by('id', 'DESC')->get_by([
			'FROM_UNIXTIME(created_on, "%Y") = '."$year"
		]);

		if ($last_quote) {
			$last_quote_number = explode('/', explode('-', $last_quote->number)[2])[0];
			$number = str_pad(intval($last_quote_number) + 1, strlen($last_quote_number), '0', STR_PAD_LEFT);
		}
		return 'ASA-'.$code.'-'.$number.'/'.date('Y');
	}


	public function generate_rev_number($quote_number) {
		$quote_number_arr = explode('-', $quote_number);
		if (count($quote_number_arr) == 3) {
			return $quote_number.'-Rev-01';
		}
		$rev_number = $quote_number_arr[4];
		$next_rev_number = str_pad(intval($rev_number) + 1, strlen($rev_number), '0', STR_PAD_LEFT);
		$quote_number_arr[4] = $next_rev_number;
		$new_quote_number = implode('-', $quote_number_arr);
		return $new_quote_number;
	}


	public function update_status($post) {
		$current_user = $this->session->userdata();
		$data = [
			'status' => $post['status'],
			'updated_on'	=> time(),
			'updated_by'	=> $current_user['user_id']
		];
		if ($post['status'] == 'Confirmed') {
			if (isset($post['confirmed_on'])) {
				$data['confirmed_on'] = $post['confirmed_on'];
				$data['confirmed_by'] = $current_user['user_id'];
			}
		}
		$this->db->set($data);
		$this->db->where('id', $post['quotation_id']);
		$this->db->update($this->_table);

		$this->format_group_company = false;
		$this->quotation = $this->get((int) $post['quotation_id']);

		$quotation = $this->_create_history();

		// update status client according to its quotations
		$quotation = $this->quotation_model->get($post['quotation_id']);
		$client_id = $this->client_history_model->get($quotation->client_history_id)->client_id;

		$this->client_model->set_client_id($client_id);
		$this->client_model->update_status($client_id);

		return $quotation;
	}

	public function change_contact($post) {
		$this->db->set('contact_history_id', $post['contact_id']);
		$this->db->where('id', $post['quotation_id']);
		$this->db->update($this->_table);

		$this->format_group_company = false;
		$this->quotation = $this->get((int) $post['quotation_id']);

		// $quotation = $this->_create_history();

		return $quotation;
	}


	public function get_by_client_id($client_id, $quotation_id) {
		$this->db->select('q.*');
		$this->db->from('client c');
		$this->db->join('client_history ch', 'ch.client_id = c.id');
		$this->db->join('quotation q', 'q.client_history_id = ch.id');
		$this->db->where('c.id', $client_id);
		$this->db->where('q.id !=', $quotation_id);
		$data = $this->db->get()->result();

		$result = [];
		foreach ($data as $quote) {
			$quote->created_by = $this->user_model->get($quote->created_by);
			$quote->updated_by = $this->user_model->get($quote->updated_by);
			$quote->address = $this->address_history_model->get($quote->address_history_id);
			$quote = $this->_certification_scheme($quote);
			$quote = $this->_accreditation($quote);
			$quote = $this->_training_type($quote);
			array_push($result, $quote);
		}
		return $result;
	}


	public function update_follow_up_date($quotation_id, $date) {
		$this->db->set('follow_up_date', $date);
		$this->db->where('id', $quotation_id);
		$this->db->update($this->_table);

		$this->format_group_company = false;
		$this->quotation = $this->get((int) $quotation_id);
		// $this->_create_history();

		return $date;
	}

	public function hasCertificationAndAccreditation($client_history_id, $certification_scheme, $accreditation) {
        // return json_encode(['message' => NULL]);
        //
        // it always return null because this function has been deactivated for a moment until further notice from client
        // remove the return above to activate this validation function again
        //

		$this->db->select('q.id, q.certification_scheme, q.accreditation, q.status');
		$this->db->from('quotation q');
		$this->db->join('client_history ch1', 'q.client_history_id = ch1.id');
		$this->db->join('client_history ch2', 'ch1.client_id = ch2.client_id');
		$this->db->where('ch2.id', $client_history_id);
        // $this->db->where('DATE_ADD(FROM_UNIXTIME(q.created_on), INTERVAL q.year_cycle YEAR) > CURDATE()');
        $this->db->where('DATE_ADD(FROM_UNIXTIME(q.created_on), INTERVAL 26 MONTH) > CURDATE()');
		$this->db->like('q.certification_scheme', "$certification_scheme", 'both', false);
		if ($accreditation) {
			$this->db->like('q.accreditation', "$accreditation", 'both', false);
		}
		$this->db->order_by('q.status', 'ASC');
		$quotations = $this->db->get()->result();
		foreach ($quotations as $quotation) {
			$certification_scheme_arr = explode(',', $quotation->certification_scheme);
			$accreditation_arr = explode(',', $quotation->accreditation);

			$find_certification_scheme = array_search($certification_scheme, $certification_scheme_arr);
			$find_accreditation = array_search($accreditation, $accreditation_arr);

			$error_message = NULL;

			if ($quotation->status == 'Confirmed') {
				if (!$find_certification_scheme) {
					$error_message = 'This client already has a confirmed quotation with this certification scheme!';
				}
			} else {
				for ($i = 0; $i < count($certification_scheme_arr); $i++) {
				    if ($certification_scheme_arr[$i] === $certification_scheme && $accreditation_arr[$i] === $accreditation) {
						$error_message = 'This client already has a quotation with this certification scheme & accreditation!';
				    }
				}
			}
            if ($error_message) {
                return json_encode(['message' => $error_message]);
            }
		}
		return json_encode(['message' => NULL]);
	}

	// public function client_with_certification_scheme_exists($client_history_id, $certification_scheme, $accreditation) {
	// 	$this->db->select('q.id, q.certification_scheme, q.accreditation, q.status');
	// 	$this->db->from('quotation q');
	// 	$this->db->join('client_history ch1', 'q.client_history_id = ch1.id');
	// 	$this->db->join('client_history ch2', 'ch1.client_id = ch2.client_id');
	// 	$this->db->where('ch2.id', $client_history_id);
	// 	$this->db->like('q.certification_scheme', "$certification_scheme", 'both', false);
	// 	$this->db->like('q.accreditation', "$accreditation", 'both', false);
	// 	$this->db->order_by('q.status', 'ASC');
	// 	$quotations = $this->db->get()->result();
	// 	foreach ($quotations as $quotation) {
	// 		$certification_scheme_arr = explode(',', $quotation->certification_scheme);
	// 		$accreditation_arr = explode(',', $quotation->accreditation);
	//
	// 		$find_certification_scheme = array_search($certification_scheme, $certification_scheme_arr);
	// 		$find_accreditation = array_search($accreditation, $accreditation_arr);
	//
	// 		$error_message = '';
	//
	// 		if ($quotation->status == 'Confirmed') {
	// 			if (!$find_certification_scheme) {
	// 				$error_message = 'This client already has a confirmed quotation with this certification scheme!';
	// 			}
	// 		} else {
	// 			for ($i = 0; $i < count($certification_scheme_arr); $i++) {
	// 			    if ($certification_scheme_arr[$i] === $certification_scheme && $accreditation_arr[$i] === $accreditation) {
	// 					$error_message = 'This client already has a quotation with this certification scheme & accreditation!';
	// 			    }
	// 			}
	// 		}
	// 		return json_encode(['message' => $error_message]);
	// 	}
	// }

	public function update_confirm_date($quotation_id, $date) {
		$this->db->set('confirmed_on', strtotime($date));
		$this->db->where('id', $quotation_id);
		$this->db->update($this->_table);

		$this->format_group_company = false;
		$this->quotation = $this->get((int) $quotation_id);
		$this->_create_history();

		return $date;
	}


	protected function _training_type($quotation) {
		if (!$quotation) {
			return false;
		}
		if (!$quotation->training_type) {
			$quotation->training_type_arr = [];
			return $quotation;
		}
		$training_type = explode(',', $quotation->training_type);
		$quotation->training_type_arr = [];

		if (is_array($training_type)) {
			foreach ($training_type as $type_id) {
				$type = $this->certification_scheme_model->get($type_id);
				if ($type) {
					$quotation->training_type_arr[] = $type->name;
				}
			}
			return $quotation;
		}

		$type = $this->certification_scheme_model->get($training_type);
		if ($type) {
			$quotation->training_type_arr[] = $type->name;
		}
		return $quotation;
	}

  private function _updated_core_quotation_data($post) {
    $old_data = $this->get($post['quotation_id']);

    if ($old_data->quote_date != $post['quote_date']) {
      return true;
    }

    if ($old_data->client_history_id != $post['client_history_id']) {
      $old_client = $this->client_history_model->get($old_data->client_history_id);
      $new_client = $this->client_history_model->get($post['client_history_id']);
      $client_updated = $this->_updated_core_client_data($old_client, $new_client);

      $old_address = $this->address_history_model->get($old_data->address_history_id);
      $new_address = $this->address_history_model->get($post['address_history_id']);
      $address_updated = $this->_updated_core_address_data($old_address, $new_address);

      if ($client_updated || $address_updated) {
        return true;
      }
    }

    if ($old_data->address_history_id != $post['address_history_id']) {
      $old_address = $this->address_history_model->get($old_data->address_history_id);
      $new_address = $this->address_history_model->get($post['address_history_id']);
      $address_updated = $this->_updated_core_address_data($old_address, $new_address);
      if ($address_updated) {
          return true;
      }
    }

    if ($old_data->contact_history_id != $post['contact_history_id']) {
      $old_contact = $this->contact_history_model->get($old_data->contact_history_id);
      $new_contact = $this->contact_history_model->get($post['contact_history_id']);
      $contact_updated = $this->_updated_core_contact_data($old_contact, $new_contact);
      if ($contact_updated) {
          return true;
      }
    }

    if ($post['type'] == 'ISO') {

      $old_certification_scheme = explode(',', $old_data->certification_scheme);
      $new_certification_scheme = $post['certification_scheme'];
      $diff_scheme = array_diff($old_certification_scheme, $new_certification_scheme);

      if (!empty($diff_scheme)) {
        return true;
      }

      $old_accreditation = explode(',', $old_data->accreditation);
      $new_accreditation = $post['accreditation'];
      $diff_accreditation = array_diff($old_accreditation, $new_accreditation);

      if (array_filter($old_accreditation) !== $new_accreditation) {
          return true;
      }

      // if (!empty($diff_accreditation)) {
      //   return true;
      // }

      if ($old_data->scope != $post['scope']) {
        return true;
      }

      if ($old_data->num_of_sites != $post['num_of_sites']) {
        return true;
      }

      if ($old_data->stage_audit != $post['stage_audit']) {
        return true;
      }

      if ($old_data->surveillance_year_1 != $post['surveillance_year_1']) {
        return true;
      }

      if ($old_data->surveillance_year_2 != $post['surveillance_year_2']) {
        return true;
      }

      if ($old_data->transportation != $post['transportation']) {
        return true;
      }
    }

    if ($post['type'] == 'Bizsafe') {
      $old_certification_scheme = explode(',', $old_data->certification_scheme);
      $new_certification_scheme = $post['certification_scheme'];
      $diff_scheme = array_diff($old_certification_scheme, $new_certification_scheme);

      if (!empty($diff_scheme)) {
        return true;
      }

      $old_accreditation = explode(',', $old_data->accreditation);
      $new_accreditation = $post['accreditation'];
      $diff_accreditation = array_diff($old_accreditation, $new_accreditation);

      if (!empty($diff_accreditation)) {
        return true;
      }

      if ($old_data->scope != $post['scope']) {
        return true;
      }

      if ($old_data->audit_fee != $post['audit_fee']) {
        return true;
      }
    }

    if ($post['type'] == 'Training') {
      $old_training_type = explode(',', $old_data->training_type);
      $new_training_type = $post['training_type'];
      $diff_type = array_diff($old_training_type, $new_training_type);

      if (!empty($diff_type)) {
        return true;
      }

      if (trim($old_data->training_description) != trim($post['training_description'])) {
        return true;
      }

      if ($old_data->payment_terms != $post['payment_terms']) {
        return true;
      }

      if ($old_data->duration != $post['duration']) {
        return true;
      }

      if ($old_data->total_amount != $post['total_amount']) {
        return true;
      }
    }

    return false;
  }

  private function _updated_core_client_data($old_client, $new_client) {
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

  private function _updated_core_address_data($old_address, $new_address) {
    if ($old_address->address != $new_address->address) {
      return true;
    }

	if ($old_address->address_2 != $new_address->address_2) {
      return true;
    }

	if ($old_address->postal_code != $new_address->postal_code) {
      return true;
    }

	if ($old_address->country != $new_address->country) {
      return true;
    }

    return false;
  }

  private function _updated_core_contact_data($old_contact, $new_contact) {
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

// public function _set_archive($quotation_id) {
//   $quotation = $this->get($quotation_id);
//   $this->db->select('q.*');
//   $this->db->from('client c');
//   $this->db->join('client_history ch', 'ch.client_id = c.id');
//   $this->db->join('quotation q', 'q.client_history_id = ch.id');
//   $this->db->where('c.id', $quotation->client->client_id);
// $this->db->group_start()
// 			->where('q.status', 'Non-Active')
// 			->or_where('q.status', 'Dropped by ASA')
// 			->or_where('q.status', 'Dropped by Client')
// 			->or_where('q.status', 'On-Hold')
// ->group_end();
// $this->db->where('q.created_on <= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 365 DAY))');
//   $non_active_quotes = $this->db->get()->result();
//   foreach ($non_active_quotes as $quote) {
//     $this->set_quotation_id($quote->id);
//     $this->update($quote->id, ['status' => 'Archive']);
//   }
// }

  public function get_client_history($quotation_id) {
    $output = [];
    $histories = $this->db->select('ch.*, u.first_name, u.last_name, ah.address, ah.address_2, ah.country, ah.postal_code, ah.total_employee, qh.updated_on as qh_updated_on')
          ->join('client_history ch', 'qh.client_history_id = ch.id')
          ->join('address_history ah', 'qh.address_history_id =ah.id')
          ->join('user u', 'qh.updated_by = u.id', 'LEFT')
          ->where('qh.quotation_id', $quotation_id)
          ->group_by('ch.name, ch.uen, ch.website, ch.phone, ch.fax, ch.email')
		  ->order_by('id', 'DESC')
          ->get('quotation_history qh')->result();
    if (!empty($histories)) {
      foreach ($histories as $history) {
            $history->updated_on = $history->qh_updated_on ? human_timestamp($history->qh_updated_on) : '';
            $history->updated_by = $history->first_name . ' ' . $history->last_name;
            $history->address = full_address($history);
        $temp       = new stdclass();
        $output[] = $history;
      }
    }
    return $output;
  }

  public function get_contact_history($quotation_id) {
    $output = [];
    $histories = $this->db->select('ch.*, u.first_name, u.last_name, qh.updated_on as qh_updated_on')
          ->join('contact_history ch', 'qh.contact_history_id = ch.id')
          ->join('user u', 'ch.created_by = u.id')
          ->where('qh.quotation_id', $quotation_id)
          ->group_by('qh.contact_history_id')
		  ->order_by('id', 'DESC')
          ->get('quotation_history qh')->result();
	if (!empty($histories)) {
		$output_length = 0;
		foreach ($histories as $history) {
			if ($output_length == 0) {
				$history->updated_on = $history->qh_updated_on ? human_timestamp($history->qh_updated_on) : '';
				$history->updated_by = '';
				$output[] = $history;
				$output_length++;
			} else {
				if ($this->_detect_contact_history($output[$output_length-1], $history)) {
					$history->updated_on = $history->qh_updated_on ? human_timestamp($history->qh_updated_on) : '';
					$history->updated_by = $history->first_name . ' ' . $history->last_name;
					$output[] = $history;
					$output_length++;
				}
			}
		}
	}
    return $output;
  }

  public function get_address_history($quotation_id) {
    $output = [];
    $histories = $this->db->select('u.first_name, u.last_name, ah.*, qh.updated_on as qh_updated_on')
          ->join('address_history ah', 'qh.address_history_id = ah.id')
          ->join('user u', 'qh.updated_by = u.id', 'LEFT')
          ->where('qh.quotation_id', $quotation_id)
          ->group_by('qh.client_history_id')
		  ->order_by('id', 'DESC')
          ->get('quotation_history qh')->result();
    if (!empty($histories)) {
        $output_length = 0;
        foreach ($histories as $history) {
          if ($output_length == 0) {
            $history->updated_on = $history->qh_updated_on ? human_timestamp($history->qh_updated_on) : '';
            $history->updated_by = $history->first_name . ' ' . $history->last_name;
			$history->complete_address = full_address($history);
            $output[] = $history;
            $output_length++;
          } else {
            if ($this->_detect_address_history($output[$output_length-1], $history)) {
              $history->updated_on = $history->qh_updated_on ? human_timestamp($history->qh_updated_on) : '';
              $history->updated_by = $history->first_name . ' ' . $history->last_name;
			  $history->complete_address = full_address($history);
              $output[] = $history;
              $output_length++;
            }
          }
        }
    }
    return $output;
  }

  private function _detect_address_history($previous, $current) {
		if ($previous->address != $current->address ||
			$previous->address_2 != $current->address_2 ||
			$previous->country != $current->country ||
			$previous->postal_code != $current->postal_code ||
			$previous->phone != $current->phone ||
			$previous->fax != $current->fax ||
			$previous->total_employee != $current->total_employee
		) {
			return true;
		}
		return false;
	}

	private function _detect_contact_history($previous, $current) {
		if ($previous->salutation != $current->salutation ||
			$previous->name != $current->name ||
			$previous->position != $current->position ||
			$previous->department != $current->department ||
			$previous->phone != $current->phone ||
			$previous->fax != $current->fax ||
			$previous->email != $current->email ||
			$previous->mobile != $current->mobile
		) {
			return true;
		}
		return false;
	}

}
