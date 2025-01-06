<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Memo extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('memo_model');
		$this->load->model('client_history_model');
		$this->load->model('quotation_model');
    }



	/**
     * get referrer by like post value.
     *
     * @access public
     * @return json
     */
    public function generate_post()
    {
		$quotation = $this->quotation_model->get($this->post('quotation_id'));
		$client = $this->client_history_model->get($quotation->client_history_id);
		if ($this->post('type') == 'A') {
			$message = "
<p>This is to inform that $client->name had appointed Advanced System Assurance Pte Ltd as a Certification Body to Achieve ISO 9001:2015 & ISO 45001:2018 Certification.</p>

<p>$client->name, Certification Management Audit has been planned to conduct before Mid of next Month. Once the audit is completed, they will receive the Certificate from Advanced System Assurance Pte Ltd latest by 30th June 2022.</p>

<p>Hope that information provided is sufficient for your necessary action, please do not hesitate to contact me at 90086261(H/P) or 6444 1218 (office) if you require any further clarifications</p>";
		} else {
			$message = "
<p>This is to inform that Client Name had appointed Advanced System Assurance Pte Ltd as a Certification Body to Achieve ISO 9001:2015	& ISO 14001:2015 Certification.</p>

<p>$client->name, Certification	Management Audit had been conducted on 21st	April 2022. The audit had been completed with no major non-conformance; they will receive the Certificate from Advanced System Assurance
Pte Ltd latest by 31st May 2022.</p>

<p>Hope that information provided is sufficient for your necessary action, please do not hesitate to contact me at 90086261(H/P) or 64441218 (office) if you require any further clarifications.</p>";
		}
		$post = [
			'number'		=> $this->memo_model->generate_new_memo_number(),
			'quotation_id'	=> $this->post('quotation_id'),
			'type'			=> $this->post('type'),
			'status'		=> 'New',
			'message'		=> $message,
			'memo_date'		=> human_timestamp(time(), 'Y-m-d')
		];
		$memo_id = $this->memo_model->insert($post);
        if ($memo_id) {
			$memo = $this->memo_model->get($memo_id);
			$memo->created_on = human_timestamp($memo->created_on);
			$this->response($memo, REST_Controller::HTTP_OK);
        }

        $this->response([], REST_Controller::HTTP_OK);
    }


	public function get_post() {
		$memo_id = $this->post('memo_id');
		$memo = $this->memo_model->get($memo_id);
		if ($memo) {
			$memo_message = $memo->message;
			$this->response($memo_message, REST_Controller::HTTP_OK);
		} else {
			$this->response('', REST_Controller::HTTP_OK);
		}
	}


}
