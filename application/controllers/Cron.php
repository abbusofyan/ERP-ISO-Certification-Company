<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
  	public function __construct()
  	{
      parent::__construct();
      $this->load->library(['form_validation', 'auth']);
      $this->load->model('quotation_model');
      $this->load->model('invoice_model');
    }

    public function updateStatus() {
      $this->session->set_userdata([
        'user_id' => "1",
        'group_id' => "1",
        'email' => "asa@mail.com",
      ]);

	  $this->setQuotationAsNonActive();
	  $this->setClientAsPastActive();
      $this->setClientAsNonActive();
      $this->setInvoiceAsDue();
      $this->setInvoiceAsLate();
      dd('Status synced');
    }

	public function setQuotationAsNonActive() {
		// every quotation with status new and already past 60 days will be set to non active
		$quotations = $this->db->select('id, number')->where('created_on <= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 60 DAY))')->where('status', 'New')->get('quotation')->result_array();
        foreach ($quotations as $quotation) {
          $data = [
            'status' => 'Non-Active',
            'quotation_id' => $quotation['id'],
          ];
          $this->quotation_model->update_status($data);
        }
	}

	public function setQuotationAsArchive() {
		// every quotation with status non-active and already past 365 days will be set to archive
		$quotations = $this->db->select('id, number')->where('created_on <= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 365 DAY))')->where('status', 'Non-Active')->get('quotation')->result_array();
        foreach ($quotations as $quotation) {
			$data = [
				'status' => 'Archive',
				'quotation_id' => $quotation['id'],
			];
			$this->quotation_model->update_status($data);
		}
	}

    public function setClientAsNonActive() {
		// every quotation with status new or onhold and already past 60 days, its client will be set to non active
        $quotations = $this->db->select('c.id as client_id')
            ->join('client_history ch', 'ch.id = q.client_history_id')
            ->join('client c', 'c.id = ch.client_id')
            ->where('q.created_on <= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 60 DAY))')
            ->group_start()
                ->where('q.status', 'New')
                ->or_where('q.status', 'On-Hold')
            ->group_end()
            ->get('quotation q')
            ->result_array();

        foreach ($quotations as $quotation) {
          $data = [
            'status' => 'Non-Active',
          ];

          $this->db->where('id', $quotation['client_id']);
          $this->db->update('client', $data);

          $client = $this->client_model->get($quotation['client_id']);
          $client->client_id = $quotation['client_id'];

  		  unset($client->created_on);
  		  unset($client->created_by);
  		  unset($client->updated_on);
  		  unset($client->updated_by);
  		  unset($client->id);
  		  unset($client->flagged);

  		  $this->client_history_model->insert((array)$client);

        }
	}

	public function setClientAsPastActive() {
        // $quotations = $this->db
        //     ->select('quotation.ids, client.id as client_id, client.name as client_name, client.status as client_sttus, quotation.number')
        //     ->from('quotation')
        //     ->join('client_history', 'quotation.client_history_id = client_history.id')
        //     ->join('client', 'client_history.client_id = client.id')
        //     ->where('DATE_ADD(FROM_UNIXTIME(quotation.created_on), INTERVAL year_cycle YEAR) < CURDATE()')
        //     ->where('quotation.status', 'Confirmed')
        //     ->where('client.status !=', 'Past Active')
        //     ->get()
        //     ->result_array();
        // foreach ($quotations as $quotation) {
        //     $this->client_model->set_client_id($quotation['client_id']);
    	// 	$this->client_model->update_status($quotation['client_id']);
		// }

        $clients = $this->client_model->get_many_by('status', 'Active');
        $arr = [];
        foreach ($clients as $client) {
            $has_ongoing_confirmed_quotation = $this->client_model->_has_ongoing_confirmed_quotation($client->id);
            $has_past_confirmed_quotation = $this->client_model->_has_past_confirmed_quotation($client->id);
            if(!$has_ongoing_confirmed_quotation && $has_past_confirmed_quotation) {
                $this->client_model->set_client_id($client->id);
        		$this->client_model->update_status($client->id);
    		}
        }
	}

    public function setInvoiceAsDue() {
        $today = date('Y-m-d');
        $invoices = $this->invoice_model->get_many_by([
            'status' => 'New',
            'request_status' => 'Approved',
            'audit_fixed_date <=' => $today
        ]);
        if (!$invoices) {
            return false;
        }
        foreach ($invoices as $invoice) {
            if ($invoice->audit_fixed_date) {
                $this->invoice_model->set_invoice_id($invoice->id);
                $this->invoice_model->update($invoice->id, ['status' => 'Due']);
            }
        }
        return true;;
	}

    public function setInvoiceAsLate() {
        $today = date('Y-m-d');
        $one_month_ago = date('Y-m-d', strtotime('-1 month', strtotime($today)));
        $invoices = $this->invoice_model->get_many_by([
            'status' => 'Due',
            'audit_fixed_date <=' => $one_month_ago
        ]);
        if (!$invoices) {
            return false;
        }
        foreach ($invoices as $invoice) {
            if ($invoice->audit_fixed_date) {
                $this->invoice_model->set_invoice_id($invoice->id);
                $this->invoice_model->update($invoice->id, ['status' => 'Late']);
            }
        }
        return true;;
	}

}
