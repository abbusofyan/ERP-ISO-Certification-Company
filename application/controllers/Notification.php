<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends WebimpController
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
		'notification',
		'invoice',
		'invoice_note'
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
		$this->auth->route_access();

        $this->load->helper('download');

        $this->data['page']['title'] = 'Notification';
        $this->add_breadcrumb('Memo');
    }




	public function process($notif_id, $process) {
		$notification = $this->notification_model->get($notif_id);
		if ($notification->category == 'Memo') {
			$memo_id = json_decode($notification->body)->memo_id;
			if ($process == 'Approve') {
				$status = 'Approved';
			} else {
				$status = 'Declined';
			}
			$updated = $this->memo_model->update($memo_id, ['status' => $status]);
			if($updated) {
				$this->notification_model->update($notif_id, ['status' => $status]);
				$this->set_alert('Memo ' . $status, 'success', true);
				redirect('quotation', 'refresh');
			} else {
				$this->set_alert('Cant process memo!', 'error', true);
				redirect('quotation', 'refresh');
			}
		}
	}


	public function approve_invoice($notif_id) {
		$notif = $this->invoice_notification_model->get($notif_id);
		$this->invoice_model->set_invoice_id($notif->invoice_id);

		$update_invoice = $this->invoice_model->update($notif->invoice_id, [
			'request_status' => 'Approved',
			'approved_by'	=> $this->session->userdata()['user_id'],
			'approved_on'	=> time()
		]);

		$update_notification = $this->invoice_notification_model->update($notif_id, ['status' => 'Approved']);

		if ($update_invoice && $update_notification) {
			$this->set_alert('Invoice approved!', 'success', true);
			redirect('invoice', 'refresh');
		} else {
			$this->set_alert('Cannot approve invoice!', 'error', true);
			redirect('invoice', 'refresh');
		}
	}


	public function update($id) {
		$post = $this->input->post();
		if ($post['status'] == 'New') {
			$post['status'] = 'Pending Approval';
			$updated = $this->memo_model->update($id, $post);
			if ($updated) {
				$not_id =  $this->notification_model->send_memo_notification($id);
				$this->set_alert('Memo updated!', 'success', true);
				redirect('quotation/view/'.$post['quotation_id'], 'refresh');
			} else {
				$this->set_alert("Can't update memo.", 'error', true);
				redirect('quotation/view/'.$post['quotation_id'], 'refresh');
			}
		} else {
			dd('create revision memo');
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
		$this->pdf->stream($memo->number.'.pdf', ['Attachment' => 0]);
    }


}
