<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation_history_model extends WebimpModel
{
    protected $_table = 'quotation_history';
    protected $soft_delete = true;

	protected $belongs_to = [
		'quotation' => [
            'primary_key' => 'quotation_id',
            'model'       => 'quotation_model',
        ],

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

		'updated_by' => [
            'primary_key' => 'updated_by',
            'model'       => 'user_model',
        ],
    ];

	protected $has_many = [
		'quotation_address' => [
			'primary_key' => 'quotation_id',
			'model'       => 'quotation_address_model',
		],
	];

    protected $before_create = ['_record_created'];
    protected $before_delete = ['_cache_quotation'];
    protected $after_delete  = ['_delete_related'];
	protected $after_get = ['_certification_scheme', '_accreditation', '_training_type'];
    protected $after_relate  = ['_get_details', '_group_of_companies', '_certification_scheme', '_accreditation', '_training_type'];


    protected $quotation_id;     // caching for use later
    protected $quotation;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('quotation_model');
        $this->load->model('file_model');
		$this->load->model('certification_scheme_model');
		$this->load->model('accreditation_model');

        $this->format_group_company = true;
    }


    protected function _cache_quotation($quotation_id)
    {
        $this->quotation = $this->get((int) $quotation_id);

        return $this;
    }


	protected function _record_created($post)
	{
		if (!isset($post['created_by'])) {
			$current_user = $this->session->userdata();

			$post['created_by'] = $current_user['user_id'];
			$post['created_on'] = time();
		}

		return $post;
	}


	protected function _certification_scheme($quotation) {
        if ($quotation->type == 'Training') {
            $certification_scheme = explode(',', $quotation->training_type);
        } else {
            $certification_scheme = explode(',', $quotation->certification_scheme);
        }

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
        if ($quotation->type == 'Training') {
            $quotation->accreditation_arr = [];
            return $quotation;
        }
        
        $accreditations = explode(',', $quotation->accreditation);
		$quotation->accreditation_arr = [];
		$this->accreditation_model->soft_delete();

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

	public function get_history($quotation_id) {
		$output = [];
		$histories = $this->with('updated_by')->with('address')->with('certification_cycle')->order_by('id', 'DESC')->get_many_by('quotation_id', $quotation_id);
        if (!empty($histories)) {
            $output_length = 0;
            foreach ($histories as $history) {
				if ($output_length == 0) {
					$history->updated_on = human_timestamp($history->updated_on);
					$history->confirmed_on = human_timestamp($history->confirmed_on);
                    $history->quote_date = human_date($history->quote_date, 'd M Y');
					$history->updated_by = $history->updated_by ? $history->updated_by->first_name.' '.$history->updated_by->last_name : '';
					$output[] = $history;
					$output_length++;
				} else {
					if ($this->_detect_updated($output[$output_length-1], $history)) {
					$history->updated_on = human_timestamp($history->updated_on);
					$history->confirmed_on = human_timestamp($history->confirmed_on);
                    $history->quote_date = human_date($history->quote_date, 'd M Y');
					$history->updated_by = $history->updated_by ? $history->updated_by->first_name.' '.$history->updated_by->last_name : '';
					$output[] = $history;
					$output_length++;
					}
				}
            }
        }
		return $output;
	}

	private function _detect_updated($previous, $current) {
		if ($previous->quote_date != $current->quote_date ||
			$previous->status != $current->status ||
			$previous->address->total_employee != $current->address->total_employee ||
			$previous->confirmed_on != $current->confirmed_on ||
			$previous->certification_cycle != $current->certification_cycle ||
			$previous->certification_scheme != $current->certification_scheme ||
  			$previous->accreditation != $current->accreditation ||
			$previous->scope != $current->scope ||
			$previous->num_of_sites != $current->num_of_sites ||
			$previous->stage_audit != $current->stage_audit ||
			$previous->surveillance_year_1 != $current->surveillance_year_1 ||
			$previous->surveillance_year_2 != $current->surveillance_year_2 ||
			$previous->transportation != $current->transportation ||
			$previous->training_type != $current->training_type ||
			$previous->training_description != $current->training_description ||
			$previous->total_amount != $current->total_amount ||
			$previous->discount != $current->discount ||
			$previous->payment_terms != $current->payment_terms ||
			$previous->duration != $current->duration ||
			$previous->audit_fee != $current->audit_fee
		) {
			return true;
		}
		return false;
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


}
