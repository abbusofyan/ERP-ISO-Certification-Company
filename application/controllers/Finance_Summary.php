<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_Summary extends WebimpController
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
		'client',
        'contact',
		'address',
		'notification',
		'invoice',
		'quotation',
		'invoice_type',
		'invoice_status',
		'certification_cycle',
		'quotation_address'
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
		$this->auth->only(['index', 'create', 'view', 'edit', 'update', 'delete']);

        $this->load->helper('download');

        $this->data['page']['title'] = 'Finance Summary';
        $this->add_breadcrumb('Finance Summary');
    }


	public function index() {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['invoice_status'] = $this->invoice_status_model->get_all();
		$this->data['clients'] = $this->client_model->get_all();
	}


	public function view($quotation_id) {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$quotation = $this->quotation_model->with('certification_cycle')->get($quotation_id);
		$this->data['quotation'] = $quotation;

		if ($quotation->type == 'ISO') {
			$this->data['invoice_types'] = $invoice_types = $this->invoice_type_model->get_by_quotation_cycle($quotation->certification_cycle->name);
		}

		if ($quotation->type == 'Bizsafe') {
			$this->data['invoice_types'] = $invoice_types = ['Bizsafe'];
		}

		if ($quotation->type == 'Training') {
			$this->data['invoice_types'] = $invoice_types = ['Training'];
		}

		$invoices = [];
		foreach ($invoice_types as $type) {
			$invoice = $this->invoice_model->get_by([
				'quotation_id' => $quotation_id,
				'invoice_type'	=> $type,
        		'request_status' => 'Approved'
			]);
			$invoices[$type] = $invoice;
		}
		$this->data['total_amount'] = array_sum(array_column($invoices, 'amount'));
		$this->data['invoices'] = $invoices;
	}

	public function download_invoice($invoice_id) {
		$this->load->library(['pdf']);
		$invoice = $this->invoice_model->with('quotation')->get($invoice_id);
        $invoice->num_order = $this->invoice_model->getInvoiceOrder($invoice->id, $invoice->quotation_id);
		$invoice->quotation->certification_scheme = $this->certification_scheme_model->get($invoice->quotation->certification_scheme);
    	$invoice->quotation->certification_cycle = $this->certification_cycle_model->get($invoice->quotation->certification_cycle);
		$invoice->client = $this->client_history_model->get($invoice->client_history_id);
		$invoice->address = $this->address_history_model->get($invoice->address_history_id);
		$invoice->contact = $this->contact_history_model->get($invoice->contact_history_id);
    	$invoice->quotation->other_sites = $this->quotation_address_model->get_many_by('quotation_id', $invoice->quotation_id);
		$data['invoice'] = $invoice;

		$this->pdf->load_view('exports/invoice/pdf', $data);
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
		$this->pdf->stream('Invoice.pdf', ['Attachment' => 0]);
	}




}
