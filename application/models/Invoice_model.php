<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_model extends WebimpModel
{
    protected $_table = 'invoice';

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
		'invoice_receipt' => [
            'primary_key' => 'invoice_id',
            'model'       => 'invoice_receipt_model',
        ],
    ];

	protected $before_create = ['_format_submission', '_record_created'];
	protected $after_create  = ['_get_created_invoice', '_create_history'];

	protected $before_update = ['_format_submission', '_compareAuditDate', '_record_updated', '_invoice_number'];
	protected $after_update  = ['_get_updated_invoice', '_create_history'];

	protected $after_relate  = ['_get_details'];

	protected $before_delete = ['_set_canceled'];

	protected $invoice_id;
	protected $invoice;
	protected $action = '';

    public function __construct()
    {
        parent::__construct();
		$this->load->model('invoice_history_model');
		$this->load->model('client_history_model');
		$this->load->model('address_history_model');
		$this->load->model('contact_history_model');
		$this->load->model('quotation_model');
    }

	protected function _format_submission($post)
	{
		$data = [];

		if (array_key_exists('number', $post)) {
			$data['number'] = $post['number'];
		}

		if (array_key_exists('quotation_id', $post)) {
			$data['quotation_id'] = $post['quotation_id'];
		}

		if (array_key_exists('client_history_id', $post)) {
			$data['client_history_id'] = $post['client_history_id'];
		}

		if (array_key_exists('address_history_id', $post)) {
			$data['address_history_id'] = $post['address_history_id'];
		}

		if (array_key_exists('contact_history_id', $post)) {
			$data['contact_history_id'] = $post['contact_history_id'];
		}

		if (array_key_exists('invoice_date', $post)) {
			$data['invoice_date'] = $post['invoice_date'];
		}

		if (array_key_exists('invoice_type', $post)) {
			$data['invoice_type'] = $post['invoice_type'];
		}

		if (array_key_exists('amount', $post)) {
			$data['amount'] = $post['amount'];
		}

		if (array_key_exists('paid', $post)) {
			$data['paid'] = $post['paid'];
		}

		if (array_key_exists('audit_fixed_date', $post)) {
			$data['audit_fixed_date'] = $post['audit_fixed_date'];
		}

		if (array_key_exists('follow_up_date', $post)) {
			$data['follow_up_date'] = $post['follow_up_date'];
		}

		if (array_key_exists('due_date', $post)) {
			$data['due_date'] = $post['due_date'];
		}

		if (array_key_exists('paid_date', $post)) {
			$data['paid_date'] = $post['paid_date'];
		}

		if (array_key_exists('status', $post)) {
			$data['status'] = $post['status'];
		}

		if (array_key_exists('request_status', $post)) {
			$data['request_status'] = $post['request_status'];
		}

		if (array_key_exists('approved_on', $post)) {
			$data['approved_on'] = $post['approved_on'];
		}

		if (array_key_exists('approved_by', $post)) {
			$data['approved_by'] = $post['approved_by'];
		}

		return $data;
	}

	protected function _invoice_number($post) {
		if ($this->action == 'Approve Create') {
			$post['number'] = $this->generate_new_invoice_number();
		}

		if ($this->action == 'Approve Update') {
            if ($post['status'] == 'Cancelled') {
                return $post;
            }
			if ($this->_updated_core_data($post)) {
				$post['number']  = $this->_set_revision_number($post['number']);
            }
		}
		return $post;
	}


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();
		return $post;
	}


	protected function _record_updated($post)
    {
        $current_user = $this->session->userdata();

        $post['updated_by'] = $current_user['user_id'];
        $post['updated_on'] = time();
        return $post;
    }



	public function generate_new_invoice_number() {
		$year = date('Y');

		$number = '0001';

		$last_invoice = $this->order_by('number', 'DESC')->get_by([
			'FROM_UNIXTIME(created_on, "%Y") = '."$year" . ' AND number != "" AND request_status != "Declined"'
		]);
		if ($last_invoice) {
			$last_invoice_number = explode('/', explode('-', $last_invoice->number)[2])[0];
			$number = str_pad(intval($last_invoice_number) + 1, strlen($last_invoice_number), '0', STR_PAD_LEFT);
		}
		return 'ASA-INV-'.$number.'/'.date('Y');
	}


	protected function _get_created_invoice($invoice_id)
	{
		$this->invoice = $this->get((int) $invoice_id);
	}


	protected function _get_updated_invoice()
	{
		$this->invoice = $this->get((int) $this->invoice_id);
	}


	public function set_invoice_id($invoice_id)
    {
        $this->invoice_id = (int) $invoice_id;

        return $this;
    }

	public function set_action($action)
    {
        $this->action = $action;
        return $this;
    }


	protected function _set_revision_number($invoice_number) {
		$arr_invoice_number = explode('-', $invoice_number);
		if (count($arr_invoice_number) > 3) {
			$currentRevNumber = $arr_invoice_number[4];
			$lastTwoDigits = substr($currentRevNumber, strlen($currentRevNumber) - 2);
			$incrementedDigits = intval($lastTwoDigits) + 1;
			$incrementedDigitsString = str_pad(strval($incrementedDigits), 2, '0', STR_PAD_LEFT);
			$newRevNumber = substr_replace($currentRevNumber, $incrementedDigitsString, -2);
		} else {
			$newRevNumber = 'Rev-01';
		}
		$new_invoice_number = implode("-", array_replace($arr_invoice_number, array(4 => $newRevNumber)));
		return $new_invoice_number;
	}


	protected function _create_history()
	{
		$this->invoice->invoice_id = $this->invoice->id;

		unset($this->invoice->created_on);
		unset($this->invoice->created_by);
		unset($this->invoice->id);

		unset($this->invoice->client);
		unset($this->invoice->address);
		unset($this->invoice->contact);
		unset($this->invoice->quotation);

		$invoice_history = $this->invoice_history_model->insert((array)$this->invoice);
		return $invoice_history;
	}

	protected function _get_details($row)
	{
		if (is_object($row)) {
			$row->client = $this->client_history_model->get($row->client_history_id);
			$row->address = $this->address_history_model->get($row->address_history_id);
			$row->contact = $this->contact_history_model->get($row->contact_history_id);
			$row->quotation = $this->quotation_model->get($row->quotation_id);
		} elseif (is_array($row)) {
			$row['client'] = $this->client_history_model->get($row['client_history_id']);
			$row['address'] = $this->address_history_model->get($row['address_history_id']);
			$row['contact'] = $this->contact_history_model->get($row['contact_history_id']);
			$row['quotation'] = $this->quotation_model->get($row['quotation_id']);
		}

		return $row;
	}

	private function _updated_core_data($post) {
		$invoice_history = $this->invoice_history_model->order_by('id', 'DESC')->limit(1)->get_many_by(['invoice_id' => $this->invoice_id]);
		$latest_data = (object)$post;
		$previous_data = $invoice_history[0];

		if ($latest_data->invoice_date != $previous_data->invoice_date) {
			return true;
		}

		if ($latest_data->amount != $previous_data->amount) {
			return true;
		}

		if ($latest_data->client_history_id != $previous_data->client_history_id) {
			$latest_client = $this->client_history_model->get($latest_data->client_history_id);
			$previous_client = $this->client_history_model->get($previous_data->client_history_id);
			$client_updated = $this->client_model->updated_core_data($latest_client, $previous_client);

			$latest_address = $this->address_history_model->get($latest_data->address_history_id);
			$previous_address = $this->address_history_model->get($previous_data->address_history_id);
			$address_updated = $this->address_model->updated_core_data($latest_address, $previous_address);

			if ($client_updated || $address_updated) {
				return true;
			}
		}

		if ($latest_data->address_history_id != $previous_data->address_history_id) {
			$latest_address = $this->address_history_model->get($latest_data->address_history_id);
			$previous_address = $this->address_history_model->get($previous_data->address_history_id);
			return $this->address_model->updated_core_data($latest_address, $previous_address);
		}

		if ($latest_data->contact_history_id != $previous_data->contact_history_id) {
			$latest_contact = $this->contact_history_model->get($latest_data->contact_history_id);
			$previous_contact = $this->contact_history_model->get($previous_data->contact_history_id);
			return $this->contact_model->updated_core_data($latest_contact, $previous_contact);
		}

		return false;
	}

	protected function _set_canceled($invoice_id) {
		$this->invoice_id = $invoice_id;

		$invoice = $this->get($invoice_id);
		$this->update($invoice_id, ['status' => 'Cancelled']);
		return $invoice_id;
	}

	public function set_soft_delete_false() {
		return $this->soft_delete = false;
	}

	// check if all the invoices already created for particular quotation
	public function has_reached_max_total_invoice($quotation_id) {
		$quotation = $this->quotation_model->get($quotation_id);
		switch ($quotation->type) {
			case 'Training':
				$max_invoice = 2;
				if ($quotation->payment_terms == '100% Upon Confirmation' || $quotation->payment_terms == '100% Upon Completion') {
					$max_invoice = 1;
				}
				break;

			case 'Bizsafe':
				$max_invoice = 1;
				break;

			case 'ISO':
				$max_invoice = 3;
				if ($quotation->invoice_to == 'Client' && $quotation->client_pay_3_years == 'Yes') {
					$max_invoice = 1;
				}
				break;

			default:
				$max_invoice = 1;
				break;
		}
		$total_invoice_created = $this->count_by([
			'quotation_id' => $quotation_id,
			'request_status' => 'Approved'
		]);
		if ($total_invoice_created < $max_invoice) {
			return false;
		}
		return true;
	}

    public function getInvoiceOrder($key, $quotation_id) {
		$invoices = $this->invoice_model->get_many_by('quotation_id', $quotation_id);
		$invoices_ids = array_column($invoices, 'id');
		$order = array_search($key, $invoices_ids) + 1; // $key = 2;
		return $order;
	}

    protected function _compareAuditDate($post) {
        if (isset($post['status']) && in_array($post['status'], ['Cancelled', 'Paid', 'Partially Paid'])) {
            return $post;
        }
        if (array_key_exists('audit_fixed_date', $post)) {
            // Get the current date, yesterday, and one day before one month ago as strings
            $today = date('Y-m-d');
            $new_audit_date = date('Y-m-d', strtotime($post['audit_fixed_date']));
            $one_month_ago = date('Y-m-d', strtotime('-1 month +1 day')); // One day before one month ago
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            if ($new_audit_date > $today) {
                $post['status'] = 'New';
            } else if ($new_audit_date <= $yesterday && $new_audit_date > $one_month_ago) {
                $post['status'] = 'Due';
            } else if ($new_audit_date <= $one_month_ago) {
                $post['status'] = 'Late';
            }
        }
        return $post;
    }

}
