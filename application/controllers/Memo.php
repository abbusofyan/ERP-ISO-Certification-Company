<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Memo extends WebimpController
{
    public $asides = [
        'header'      => 'asides/header',
        'navbar_side' => 'asides/sidebar',
        'navbar_top'  => 'asides/navbar-top',
        'header_page' => 'asides/header-page',
        'alert'       => 'asides/alert',
        'footer'      => 'asides/footer',
    ];

    public $models = [
        'user',
        'memo',
		'client_history',
		'address_history',
		'notification'
    ];

    protected $layout = 'layouts/private';

	public function __construct()
	{
        parent::__construct();

        if (!$this->user_model->logged_in()) {
            $this->set_alert('Please login to continue.', 'error', true);
            $this->session->set_userdata('referred_from', current_url());
            redirect('login', 'refresh');
        }

        $this->data['current_user'] = $this->session->userdata();

		$this->load->library(['form_validation', 'auth']);
		// $this->auth->route_access();

        $this->load->helper('download');

        $this->data['page']['title'] = 'Memo';
        $this->add_breadcrumb('Memo');
    }



	public function edit($id) {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$memo = $this->memo_model->with('quotation')->get($id);
        $memo->number = $this->generateRevisedNumber($memo->number);
		$memo->quotation->client = $this->client_history_model->get($memo->quotation->client_history_id);
		$memo->quotation->address = $this->address_history_model->get($memo->quotation->address_history_id);
		$this->data['memo'] = $memo;
	}

    function generateRevisedNumber($baseMemo) {
        if (preg_match('/\/Rev-(\d{2})$/', $baseMemo, $matches)) {
            $currentRevision = (int)$matches[1];
            $newRevision = str_pad($currentRevision + 1, 2, '0', STR_PAD_LEFT);
            return preg_replace('/\/Rev-\d{2}$/', "/Rev-$newRevision", $baseMemo);
        } else {
            return $baseMemo . "/Rev-01";
        }
    }

	public function update($memo_id) {
		$post = $this->input->post();
		$status = $post['status'];
		$post['status'] = 'Pending Approval';
		if ($status == 'New') {
			$updated = $this->memo_model->update($memo_id, $post);
		} else {
			$memo_id = $this->memo_model->insert($post);
			$updated = 1;
		}
		if ($updated) {
			$notification_id =  $this->notification_model->send_memo_notification($memo_id);
			$this->set_alert('Memo updated!', 'success', true);
			redirect('quotation/view/'.$post['quotation_id'], 'refresh');
		} else {
			$this->set_alert("Can't update memo.", 'error', true);
			redirect('quotation/view/'.$post['quotation_id'], 'refresh');
		}
	}


	public function download($memo_id)
    {
        $this->view   = false;
        $this->layout = false;
        $this->load->library(['pdf']);

		$this->data['memo'] = $memo = $this->memo_model->with('quotation')->with('user')->get($memo_id);
		$sign_file = '';
		$stamp_file = '';

		if ($memo->status == 'Approved') {
			$sign_file = $this->file_model->get($memo->sign_file_id);
			$stamp_file = $this->file_model->get($memo->stamp_file_id);
		}

        $data = [
            'memo' => $memo,
            'sign_file' => $sign_file,
            'stamp_file' => $stamp_file,
        ];

        $filename = 'ASA-MEMO-' . $memo->quotation->client->name;
		$this->pdf->load_view('controllers/memo/download', $data);
		$this->pdf->set_option('isRemoteEnabled', TRUE);
		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => FALSE,
				'verify_peer_name' => FALSE,
				'allow_self_signed'=> TRUE
			]
		]);
		$this->pdf->setHttpContext($context);
		$this->pdf->render();
        $this->pdf->stream($filename . '.pdf', ['Attachment' => 0]);
    }

    public function generate($memo_type, $quotation_id)
    {
      $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
  		$quotation = $this->quotation_model->get($quotation_id);
		$i = 0; $scheme_arr = []; foreach ($quotation->certification_scheme_arr as $key => $certification_scheme) {
			// $accreditation = $quotation->accreditation_arr[$key] != 'UN ACC' ? '('.$quotation->accreditation_arr[$key].')' : '';
			// $scheme = $certification_scheme . $accreditation;
			$scheme = $certification_scheme;
			array_push($scheme_arr, $scheme);
		}
		if (count($scheme_arr) > 1) {
		    $lastElement = array_pop($scheme_arr); // Remove the last element
		    $full_scheme = implode(', ', $scheme_arr) . ' & ' . $lastElement;
		} else {
		    $full_scheme = implode(', ', $scheme_arr);
		}
  		$client = $this->client_history_model->get($quotation->client_history_id);
  		if ($memo_type == 'A') {
  			$message = "
	        <p>This is to inform that $client->name had appointed Advanced System Assurance Pte Ltd as a Certification Body to Achieve ".$full_scheme." Certification.</p>

	        <p>$client->name, Certification Management Audit has been planned to conduct before Mid of next Month. Once the audit is completed, they will receive the Certificate from Advanced System Assurance Pte Ltd latest by 30th June 2022.</p>

	        <p>Hope that information provided is sufficient for your necessary action, please do not hesitate to contact me at 90086261(H/P) or 6444 1218 (office) if you require any further clarifications</p>";
  		} else {
  			$message = "
	        <p>This is to inform that Client Name had appointed Advanced System Assurance Pte Ltd as a Certification Body to Achieve ".$full_scheme." Certification.</p>

	        <p>$client->name, Certification	Management Audit had been conducted on 21st	April 2022. The audit had been completed with no major non-conformance; they will receive the Certificate from Advanced System Assurance
	        Pte Ltd latest by 31st May 2022.</p>

	        <p>Hope that information provided is sufficient for your necessary action, please do not hesitate to contact me at 90086261(H/P) or 64441218 (office) if you require any further clarifications.</p>";
  		}
  		$this->data['memo'] = [
  			'number'		=> $this->memo_model->generate_new_memo_number(),
  			'quotation'	=> $quotation,
  			'type'			=> $memo_type,
  			'status'		=> 'New',
  			'message'		=> $message,
  			'memo_date'		=> human_timestamp(time(), 'Y-m-d')
  		];
    }

    public function store() {
      $post = $this->input->post();
      $post['status'] = 'Pending Approval';
      $memo_id = $this->memo_model->insert($post);
      if ($memo_id) {
        $notification_id =  $this->notification_model->send_memo_notification($memo_id);
        $this->set_alert("Memo sent.", 'success', true);
  			redirect('quotation/view/'.$post['quotation_id'], 'refresh');
      }
      $this->set_alert("Cannot send memo.", 'error', true);
      redirect('quotation/view/'.$post['quotation_id'], 'refresh');
    }

}
