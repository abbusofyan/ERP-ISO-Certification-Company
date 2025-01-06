<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_history_model extends WebimpModel
{
    protected $_table = 'invoice_history';
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
		'address' => [
            'primary_key' => 'address_history_id',
            'model'       => 'address_history_model',
        ],
		'contact' => [
            'primary_key' => 'contact_history_id',
            'model'       => 'contact_history_model',
        ],
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
		'updated_by' => [
            'primary_key' => 'updated_by',
            'model'       => 'user_model',
        ],
    ];

    protected $before_create = ['_record_created'];

    protected $invoice_id;
    protected $invoice;

    public function __construct()
    {
        parent::__construct();

        // $this->load->model('file_model');
    }


    public function set_invoice_id($invoice_id)
    {
        $this->invoice_id = (int) $invoice_id;

        return $this;
    }


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();
		return $post;
	}

	public function get_history($invoice_id) {
		$output = [];
		$histories = $this->with('updated_by')->with('address')->with('client')->with('contact')->order_by('id', 'asc')->get_many_by('invoice_id', $invoice_id);
        if (!empty($histories)) {
            $output_length = 0;
            foreach ($histories as $history) {
				$history->status = invoice_status_badge($history->status);
                $history->amount = money_number_format($history->amount, $history->address->country);
				if ($output_length == 0) {
					$history->updated_on = human_timestamp($history->updated_on);
					$history->updated_by = $history->updated_by ? $history->updated_by->first_name.' '.$history->updated_by->last_name : '';
					$output[] = $history;
					$output_length++;
				} else {
					if ($this->_detect_updated($output[$output_length-1], $history)) {
					$history->updated_on = human_timestamp($history->updated_on);
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
		if ($previous->client->name != $current->client->name ||
			$previous->address->address != $current->address->address ||
			$previous->address->address_2 != $current->address->address_2 ||
			$previous->address->postal_code != $current->address->postal_code ||
			$previous->contact->salutation != $current->contact->salutation ||
			$previous->contact->name != $current->contact->name ||
			$previous->contact->email != $current->contact->email ||
			$previous->contact->mobile != $current->contact->mobile ||
			$previous->status != $current->status ||
			$previous->audit_fixed_date != $current->audit_fixed_date ||
			$previous->invoice_date != $current->invoice_date ||
			$previous->amount != $current->amount ||
			$previous->follow_up_date != $current->follow_up_date
		) {
			return true;
		}
		return false;
	}

}
