<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt extends WebimpController
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
        'receipt_note',
		'receipt'
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

        $this->data['page']['title'] = 'Receipt';
        $this->add_breadcrumb('Receipt');
    }

	public function add_note() {
		$post = $this->input->post();
		$receipt = $this->receipt_model->get($post['receipt_id']);

		if ($this->form_validation->run()) {
			$note_id = $this->receipt_note_model->insert($post);
			if ($note_id) {
				$this->set_alert("Note created!", 'success', true);
				redirect('finance-summary/view/' . $receipt->quotation_id, 'refresh');
			} else {
				$this->set_alert("Note can't be created!", 'error', true);
				redirect('invoice', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('finance-summary/view/' . $receipt->quotation_id, 'refresh');
		}
	}

}
