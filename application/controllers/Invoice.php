<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends WebimpController
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
        'invoice',
		'certification_scheme',
		'client_history',
		'address_history',
		'contact_history',
		'invoice_history',
		'invoice_status',
		'country',
		'salutation',
		'quotation',
		'contact',
		'address',
		'notification',
		'invoice_note',
		'receipt',
		'receipt_note',
		'invoice_type',
		'detail_receipt',
		'invoice_attachment',
		'certification_cycle',
        'quotation_address',
		'quotation_note'
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

        $this->data['page']['title'] = 'Invoice';
        $this->add_breadcrumb('Invoice');
    }


	public function index() {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['invoice_status'] = $this->invoice_status_model->get_all();
		$this->data['clients'] = $this->client_model->get_all();
		$this->data['notifications'] = $this->invoice_model->with('quotation')->with('user')->with('updated_by')->order_by('id', 'DESC')->get_many_by([
			'request_status = "Pending Create" OR request_status = "Pending Update"'
		]);
	}


	public function create() {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$data = $this->session->userdata('post');
		if (!$data) {
			$data = $this->input->post();
			$invoice = $this->invoice_model->get_by([
				'quotation_id' => $data['quotation_id'],
				'invoice_type' => $data['invoice_type'],
				'request_status' => 'Pending Create'
			]);
			if ($invoice) {
				$this->session->unset_userdata('post');
				$this->set_alert('Invoice already exists. Waiting for Approval', 'error', true);
				redirect('quotation/view/'.$data['quotation_id'], 'refresh');
			}
		}
		$data['request_status'] = 'New';
		// $data['number'] = $this->invoice_model->generate_new_invoice_number();

		$this->data['quotation'] = $quotation = $this->quotation_model->with('certification_cycle')->get($data['quotation_id']);
        // when create invoice get the latest client, client's address, and client's contact data. dont fetch from quotation
        $client = $this->client_history_model->get_latest_by_client($quotation->client->client_id);
        $quotation->client = $client;
        $address = $this->client_model->get_current_primary_address($client->client_id);
        $quotation->address = $address;
        // $contact = $this->client_model->get_current_primary_contact($client->client_id);
        // $quotation->contact = $contact;

		if ($quotation->type == 'ISO') {
			if ($data['invoice_type'] == 'All cycle') {
				$data['amount'] = $quotation->surveillance_year_1 + $quotation->surveillance_year_2 + $quotation->stage_audit;
			} elseif ($data['invoice_type'] == '1st Year Surveillance') {
	        	$data['amount'] = $quotation->surveillance_year_1;
			} elseif ($data['invoice_type'] == '2nd Year Surveillance') {
				$data['amount'] = $quotation->surveillance_year_2;
			} else {
				$data['amount'] = $quotation->stage_audit;
			}
		} elseif ($quotation->type == 'Training') {
			if ($quotation->payment_terms == '100% Upon Confirmation' || $quotation->payment_terms == '100% Upon Completion') {
				$data['amount'] = $quotation->total_amount;
			} else {
				$data['amount'] = $quotation->total_amount / 2;
			}
			$data['invoice_type'] = 'Training';
		} elseif ($quotation->type == 'Bizsafe') {
			$data['invoice_type'] = 'Bizsafe';
			$data['amount'] = $quotation->audit_fee;
		}
		$this->data['countries'] = $this->country_model->get_all();
		$this->data['invoice'] = $data;
		$this->data['invoice_types'] = $this->invoice_type_model->get_by_quotation_cycle($quotation->certification_cycle->name);
		$this->data['invoice_status'] = $status = $this->invoice_status_model->get_all();
		$this->data['salutations'] = $this->salutation_model->get_all();
		$this->data['contacts'] = $contacts = $this->contact_history_model->get_many_by('id IN (SELECT MAX(id) FROM contact_history where client_id = '.$quotation->client->client_id.' GROUP BY contact_id)');
		$this->data['primary_contact'] = $this->contact_model->order_by('primary', 'DESC')->get_many_by([
			'client_id' => $quotation->client->client_id,
			'primary' => 1
		]);
		$this->data['form_contact'] = $this->_generate_contact_fields();
    	$this->session->unset_userdata('post');
	}


	public function edit($invoice_id) {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['invoice'] = $invoice = $this->invoice_model->get($invoice_id);
		// $this->data['quotation'] = $quotation = $this->quotation_model->with('certification_cycle')->get($invoice->quotation_id);
		// $this->data['invoice_status'] = $status = $this->invoice_status_model->get_all();
		// $this->data['countries'] = $this->country_model->get_all();
		// $this->data['salutations'] = $this->salutation_model->get_all();
		// $this->data['contacts'] = $contacts = $this->contact_model->order_by('primary', 'DESC')->get_many_by('client_id', $quotation->client->client_id);
		// $this->data['primary_contact'] = $contacts = $this->contact_model->order_by('primary', 'DESC')->get_many_by([
		// 	'client_id' => $quotation->client->client_id,
		// 	'primary' => 1
		// ]);
		// $this->data['invoice_types'] = $this->invoice_type_model->get_by_quotation_cycle($quotation->certification_cycle->name);
		// $this->data['notes'] = $this->invoice_note_model->with('user')->order_by('id', 'DESC')->get_many_by('invoice_id', $invoice_id);
		$quotation_cycle = $this->certification_cycle_model->get($invoice->quotation->certification_cycle);
		$this->data['invoice_types'] = $this->invoice_type_model->get_by_quotation_cycle($quotation_cycle->name);
		$this->data['countries'] = $this->country_model->get_all();
		$this->data['invoice_status'] = $status = $this->invoice_status_model->get_all();
		$this->data['salutations'] = $this->salutation_model->get_all();
		$this->data['contacts'] = $contacts = $this->contact_history_model->get_many_by('id IN (SELECT MAX(id) FROM contact_history where client_id = '.$invoice->client->client_id.' GROUP BY contact_id)');
		$this->data['form_contact'] = $this->_generate_contact_fields();
	}


	public function view($id) {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$invoice = $this->invoice_model->with('quotation', 'certification_cycle')->get($id);
		$invoice->quotation->certification_scheme = $this->certification_scheme_model->get($invoice->quotation->certification_scheme);
		$invoice->client = $this->client_history_model->with('client')->get($invoice->client_history_id);
		$invoice->address = $this->address_history_model->get($invoice->address_history_id);
		$invoice->contact = $this->contact_history_model->get($invoice->contact_history_id);
		$this->data['invoice'] = $invoice;
	    $history = $this->db->where('invoice_id', $id)->where('number !=', '')->order_by('id', 'DESC')->group_by(
	      'number,
		  client_history_id,
		  address_history_id,
		  contact_history_id,
		  invoice_date,
		  amount,
		  audit_fixed_date,
		  follow_up_date'
	    )->get('invoice_history')->result();
	    $this->data['invoice_history'] = $history;
		$this->data['attachments'] = $attachments = $this->invoice_attachment_model->with('file')->get_many_by('invoice_id', $id);
	}


	public function store() {
		$post = $this->input->post();
		if ($this->form_validation->run()) {
			$post['request_status'] = 'Pending Create';
			$invoice_id = $this->invoice_model->insert($post);
			if ($invoice_id) {
				if ($post['note']) {
					$this->invoice_note_model->insert(['invoice_id' => $invoice_id, 'note' => $post['note']]);
				}

				$this->session->unset_userdata('post');
				$this->set_alert('Invoice created!', 'success', true);
				redirect('invoice', 'refresh');
			}
		} else {
			$session_data = [
				'quotation_id' => $post['quotation_id'],
				'client_id' => $post['client_history_id'],
				'address_id' => $post['address_history_id'],
				'contact_id' => $post['contact_history_id'],
				'invoice_type' => $post['invoice_type']
			];
			if (array_key_exists('audit_fixed_date', $post)) {
				$session_data['audit_fixed_date'] = $post['audit_fixed_date'];
			}
			$this->session->set_userdata('post', $session_data);
			$this->set_alert(validation_errors(), 'error', true);
			redirect('invoice/create', 'refresh');
		}
	}


	public function update($id) {
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			if ($this->any_data_updated($id, $post)) {
				// $post['invoice_id'] = $id;
                // if ($post['status'] != 'Cancelled') {
                //     $post['request_status'] = 'Pending Update';
                // }
				// $this->invoice_model->set_invoice_id($id);
				// $updated = $this->invoice_model->update($id, $post);
				// if ($updated) {
				// 	$contact_id = $this->contact_history_model->get($post['contact_history_id'])->contact_id;
				// 	$client_id = $this->client_history_model->get($post['client_history_id'])->client_id;
				// 	$data = [
				// 		'client_id' => $client_id,
				// 		'contact_id' => $contact_id
				// 	];
				// 	$this->contact_model->switch_main_contact($data);
				// }
				$client_data = [
                    'invoice_id' => $id,
                    'client_id' => $post['client_id'],
                    'name' => $post['name'],
                    'uen' => $post['uen'],
                    'website' => $post['website'],
                    'phone' => $post['phone'],
                    'fax' => $post['fax'],
                    'email' => $post['email'],
                    'status' => $post['status'],
                ];
                $this->db->insert('client_temp', $client_data);
                $client_temp_id = $this->db->insert_id();

                $address_data = [
                    'invoice_id' => $id,
                    'client_id' => $post['client_id'],
                    'address_id' => $post['address_id'],
                    'phone' => $post['phone'],
                    'fax' => $post['fax'],
                    'address' => $post['address'],
                    'address_2' => $post['address_2'],
                    'country' => $post['country'],
                    'postal_code' => $post['postal_code'],
                    'total_employee' => $post['total_employee']
                ];
                $this->db->insert('address_temp', $address_data);
                $address_temp_id = $this->db->insert_id();

                $contact_data = [
                    'invoice_id' => $id,
                    'client_id' => $post['client_id'],
                    'contact_id' => $post['contact_id'],
                    'salutation' => $post['contact_salutation'][0],
                    'name' => $post['contact_name'][0],
                    'position' => $post['contact_position'][0],
                    'department' => $post['contact_department'][0],
                    'phone' => $post['contact_phone'][0],
                    'fax' => $post['contact_fax'][0],
                    'mobile' => $post['contact_mobile'][0],
                    'status' => $post['contact_status'][0]
                ];
                $this->db->insert('contact_temp', $contact_data);
                $contact_temp_id = $this->db->insert_id();

                $invoice_data = [
                    'invoice_id' => $id,
                    'number' => $post['number'],
                    'quotation_id' => $post['quotation_id'],
                    'client_temp_id' => $client_temp_id,
                    'address_temp_id' => $address_temp_id,
                    'contact_temp_id' => $contact_temp_id,
                    'invoice_date' => $post['invoice_date'],
                    'invoice_type' => $post['invoice_type'],
                    'amount'=> $post['amount'],
                    'audit_fixed_date'=> $post['audit_fixed_date'],
                    'follow_up_date'=> $post['follow_up_date'],
                    'status'=> $post['status'],
                ];

                $this->db->insert('invoice_temp', $invoice_data);
                if ($post['status'] != 'Cancelled') {
                    $post['request_status'] = 'Pending Update';
                }
				$this->invoice_model->set_invoice_id($id);
				$this->invoice_model->update($id, ['request_status' => 'Pending Update']);

                if ($this->data['current_user']['group_id'] == 1) {
                    return $this->approve($id);
                }
			}
			if ($post['note']) {
				$this->invoice_note_model->insert(['invoice_id' => $id, 'note' => $post['note']]);
			}
			$this->set_alert('Invoice updated!', 'success', true);
			redirect('invoice', 'refresh');
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('invoice/edit/'.$id, 'refresh');
		}
	}


	public function check_client_updated($post) {
		$client_history = $this->client_history_model->get($post['client_id']);
		if (
			$client_history->name != $post['name'] ||
			$client_history->uen != $post['uen'] ||
			$client_history->website != $post['website'] ||
			$client_history->phone != $post['phone'] ||
			$client_history->fax != $post['fax'] ||
			$client_history->email != $post['email']
		) {
			$this->client_model->set_client_id($client_history->client_id);
			$updated = $this->client_model->update($client_history->client_id, $post);
			if ($updated) {
				$post['client_id'] = $this->client_history_model->get_latest_by_client($client_history->client_id)->id;
			}
		}

		$address_history = $this->address_history_model->get($post['address_id']);
		if (
			$address_history->phone != $post['phone'] ||
			$address_history->fax != $post['fax'] ||
			$address_history->address != $post['address'] ||
			$address_history->address_2 != $post['address_2'] ||
			$address_history->country != $post['country'] ||
			$address_history->postal_code != $post['postal_code'] ||
			$address_history->total_employee != $post['total_employee']
		) {
			$this->address_model->set_address_id($address_history->address_id);
			$updated = $this->address_model->update($address_history->address_id, $post);
			if ($updated) {
				$post['address_id'] = $this->address_history_model->get_latest_by_address($address_history->address_id)->id;
			}
		}
		return $post;
	}


	public function add_note() {
		$post = $this->input->post();
		if ($this->form_validation->run()) {
			$note_id = $this->invoice_note_model->insert($post);
			if ($note_id) {
				$this->set_alert("Note created!", 'success', true);
				redirect('invoice', 'refresh');
			} else {
				$this->set_alert("Note can't be created!", 'error', true);
				redirect('invoice', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('invoice', 'refresh');
		}
	}

	public function approve($invoice_id) {
        $invoice = $this->invoice_model->get($invoice_id);
		if ($invoice->request_status == 'Pending Create') {
    		$update_data = [
    			'request_status' => 'Approved',
    			'approved_on' => time(),
    			'approved_by' => $this->session->userdata()['user_id']
    		];
			$this->invoice_model->set_action('Approve Create');
            $this->invoice_model->set_invoice_id($invoice_id);
            $updated = $this->invoice_model->update($invoice_id, $update_data);
		}
		if ($invoice->request_status == 'Pending Update') {
            $invoice = $this->db->where('invoice_id', $invoice_id)->get('invoice_temp')->row();
            $client_history_id = $this->approveClientData($invoice);
            $address_history_id = $this->approveAddressData($invoice);
            $contact_history_id = $this->approveContactData($invoice);
    		$update_data = [
                'number' => $invoice->number,
                'client_history_id' => $client_history_id,
                'address_history_id' => $address_history_id,
                'contact_history_id' => $contact_history_id,
                'invoice_date' => $invoice->invoice_date,
                'invoice_type' => $invoice->invoice_type,
                'amount' => $invoice->amount,
                'audit_fixed_date' => $invoice->audit_fixed_date,
                'follow_up_date' => $invoice->follow_up_date,
                'status' => $invoice->status,
    			'request_status' => 'Approved',
    			'approved_on' => time(),
    			'approved_by' => $this->session->userdata()['user_id']
    		];
			$this->invoice_model->set_action('Approve Update');
            $this->invoice_model->set_invoice_id($invoice_id);
            $updated = $this->invoice_model->update($invoice_id, $update_data);
            $this->db->where('invoice_id', $invoice->invoice_id)->delete('client_temp');
            $this->db->where('invoice_id', $invoice->invoice_id)->delete('address_temp');
            $this->db->where('invoice_id', $invoice->invoice_id)->delete('contact_temp');
            $this->db->where('invoice_id', $invoice->invoice_id)->delete('invoice_temp');
		}
		if ($updated) {
			$this->set_alert('Invoice approved', 'success', true);
			redirect('invoice', 'refresh');
		} else {
			$this->set_alert("Can't approve invoice", 'error', true);
			redirect('invoice', 'refresh');
		}
	}

	public function decline($invoice_id) {
		$post = $this->input->post();
		$invoice = $this->invoice_model->get($invoice_id);

		$this->invoice_model->set_invoice_id($invoice_id);
		if ($invoice->request_status == 'Pending Update') {
            $update_invoice = $this->invoice_model->update($invoice_id, [
                'request_status' => 'Approved',
            ]);
            $this->db->where('invoice_id', $invoice_id)->delete('client_temp');
            $this->db->where('invoice_id', $invoice_id)->delete('address_temp');
            $this->db->where('invoice_id', $invoice_id)->delete('contact_temp');
            $this->db->where('invoice_id', $invoice_id)->delete('invoice_temp');
            $message = 'Request for editing invoice rejected';
		} else {
			$update_invoice = $this->invoice_model->update($invoice_id, [
				'request_status' => 'Declined',
			]);
            $message = 'Invoice rejected';
		}

		if ($update_invoice) {
			$post['invoice_id'] = $invoice_id;
            $post['note'] = 'Invoice for ' . $invoice->invoice_type . ' is <b>Rejected</b><br>Reason : <br>' . $post['note'];
			$this->invoice_note_model->insert($post);

			// save rejection note in quotation notes also
			$quotation_id = $this->invoice_model->get($invoice_id)->quotation_id;
			$this->quotation_note_model->insert([
				'quotation_id' => $quotation_id,
				'note' => $post['note']
			]);
		}

		if ($update_invoice) {
			$this->set_alert($message, 'success', true);
			redirect('invoice', 'refresh');
		} else {
			$this->set_alert('Cannot reject invoice!', 'error', true);
			redirect('invoice', 'refresh');
		}
	}

	public function generate_receipt($quotation_id) {
		$post = $this->input->post();
		$discount = array_filter($post['discount']);
		$discount = array_shift($discount);
		$post['discount'] = $discount;
		if ($post['payment_method'] == 'Cash' || $post['payment_method'] == 'NETs') {
			$post['paid_date'] = $post['received_date'];
		} elseif ($post['payment_method'] == 'Bank Transfer') {
			$post['paid_date'] = $post['transfer_date'];
		} elseif ($post['payment_method'] == 'Cheque') {
			$post['paid_date'] = $post['cheque_date'];
		}
		if ($this->form_validation->run()) {
			$post['quotation_id'] = $quotation_id;
			$post['paid_amount'] = $total_paid_amount = $post['paid_amount'];
			$post['status'] = 'Success';
            $receipt_id = $this->receipt_model->insert($post);
			if ($receipt_id) {
				if ($post['note']) {
					$post['receipt_id'] = $receipt_id;
					$this->receipt_note_model->insert($post);
				}

				$invoice_ids = $post['invoice_id'];
				foreach ($invoice_ids as $invoice_id) {
					if ($total_paid_amount > 0) {
						$invoice = $this->invoice_model->get($invoice_id);
						$invoice_remaining_amount = $invoice->amount - $invoice->paid;
						if (($total_paid_amount + $discount) >= $invoice_remaining_amount) {
							$status = 'Paid';
							$pay = $invoice_remaining_amount;
						} else {
							$status = 'Partially Paid';
							$pay = $total_paid_amount;
						}
						$this->invoice_model->set_invoice_id($invoice_id);
						$this->invoice_model->update($invoice_id, ['status' => $status, 'paid' => $invoice->paid + $pay, 'paid_date' => $post['paid_date']]);
						$this->detail_receipt_model->insert([
							'receipt_id' => $receipt_id,
							'invoice_id' => $invoice_id,
							'paid_amount'	=> $pay,
							'invoice_status' => $status
						]);
						$total_paid_amount -= $invoice_remaining_amount;
            			$discount = 0;
					}
				}
				$this->set_alert('Receipt created', 'success', true);
				redirect('finance-summary/view/'.$quotation_id, 'refresh');
			}
			$this->set_alert('Create receipt failed', 'error', true);
			redirect('finance-summary/view/'.$quotation_id, 'refresh');
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('finance-summary/view/'.$quotation_id, 'refresh');
		}
	}

	public function delete($invoice_id) {
		$deleted = $this->invoice_model->delete($invoice_id);
		if ($deleted) {
			$this->set_alert('Invoice cancelled', 'success', true);
			redirect('invoice', 'refresh');
		} else {
			$this->set_alert("Can't cancel invoice", 'error', true);
			redirect('invoice', 'refresh');
		}
	}

	public function add_attachment() {
		$post = $this->input->post();
		$invoice_id = $post['invoice_id'];

		if ( (isset($_FILES['file'])) && ($_FILES['file']['size'] > 0) ) {
			foreach ($_FILES['file']['name'] as $key => $upload) {
				$file['name'] = $_FILES['file']['name'][$key];
				$file['type'] = $_FILES['file']['type'][$key];
				$file['tmp_name'] = $_FILES['file']['tmp_name'][$key];
				$file['error'] = $_FILES['file']['error'][$key];
				$file['size'] = $_FILES['file']['size'][$key];

				$attachment_id = $this->file_model->process_uploaded_file($file, 'invoice_attachment');
				if ($attachment_id) {
					$this->invoice_attachment_model->insert([
						'invoice_id' => $invoice_id,
						'file_id'	=> $attachment_id
					]);
				}
			}
		}
		$this->set_alert("Attachment uploaded", 'success', true);
		redirect('invoice/view/'.$post['invoice_id'], 'refresh');
	}

	public function delete_attachment($attachment_id) {
		$attachment = $this->invoice_attachment_model->get($attachment_id);
		$invoice_id = $attachment->invoice_id;

		$deleted = $this->invoice_attachment_model->delete($attachment_id);
		if ($deleted) {
			$this->set_alert("Attachment deleted", 'success', true);
			redirect('invoice/view/'.$invoice_id, 'refresh');
		} else {
			$this->set_alert("Can't delete attachment", 'error', true);
			redirect('invoice/view/'.$invoice_id, 'refresh');
		}
	}

	public function view_pdf($invoice_id) {
		$this->load->library(['pdf']);
		$invoice = $this->invoice_history_model->with('quotation')->get($invoice_id);
		$invoice->num_order = $this->invoice_model->getInvoiceOrder($invoice->invoice_id, $invoice->quotation_id);
		$invoice->quotation->certification_scheme = $this->certification_scheme_model->get($invoice->quotation->certification_scheme);
    	$invoice->quotation->certification_cycle = $this->certification_cycle_model->get($invoice->quotation->certification_cycle);
		$invoice->client = $this->client_history_model->get($invoice->client_history_id);
		$invoice->address = $this->address_history_model->get($invoice->address_history_id);
		$invoice->contact = $this->contact_history_model->get($invoice->contact_history_id);
    	$invoice->quotation->other_sites = $this->quotation_address_model->get_many_by('quotation_id', $invoice->quotation_id);
		$data['invoice'] = $invoice;

		$rev = $this->getRevNumber($invoice->number);
		$filename = 'ASA-INV-' . $invoice->client->name . $rev;
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
		$this->pdf->stream($filename . '.pdf', ['Attachment' => 0]);
	}

	public function getRevNumber($invoice_number) {
		$pattern = '/Rev-(\d+)/i';
		$rev = '';
		if (preg_match($pattern, $invoice_number, $matches)) {
		    $rev = '-' . $matches[0];
		}
		return $rev;
	}


	protected function _generate_contact_fields($data = null)
	{
		$salutation_options = [
			'' => '-- Choose Salutation --',
			'Mr' => 'Mr',
			'Mrs' => 'Mrs',
			'Ms' => 'Ms',
			'Mdm' => 'Mdm',
			'Dr' => 'Dr'
		];

		$status_options = [
			'' => '-- Please select Status --',
			'Active' => 'Active',
			'Non-Active' => 'Non-Active',
		];

		$output = [
			'salutation' => [
				'name'        => '',
				'options'     => $salutation_options,
				'selected'    => set_value('salutation'),
				'attr'        => [
					'id'          => 'contact_salutation',
					'class'       => 'form-control contact_salutation create_contact_field primary-contact-id',
				],
			],
			'contact_name' => [
				'id'          => 'contact_name',
				'class'       => 'form-control contact_name create_contact_field primary-contact-salutation',
				'placeholder' => '',
				'maxlength'   => 200,
			],
			'contact_email' => [
				'id'          => 'contact_email',
				'class'       => 'form-control contact_email create_contact_field',
				'type'		  => 'email',
				'placeholder' => 'company@mail.com',
				'maxlength'   => 150,
			],
			'position' => [
				'id'          => 'contact_position',
				'class'       => 'form-control contact_position create_contact_field',
				'placeholder' => '',
				'maxlength'   => 150,
			],
			'department' => [
				'id'          => 'contact_department',
				'class'       => 'form-control contact_department create_contact_field',
				'placeholder' => '',
				'maxlength'   => 200,
			],
			'contact_phone' => [
				'id'          => 'contact_phone',
				'class'       => 'form-control contact_phone create_contact_field',
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number'
			],
			'contact_fax' => [
				'id'          => 'contact_fax',
				'class'       => 'form-control contact_fax create_contact_field',
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number'
			],
			'contact_mobile' => [
				'id'          => 'contact_mobile',
				'class'       => 'form-control contact_mobile create_contact_field',
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number'
			],
			'contact_status' => [
				'name'        => '',
				'options'     => $status_options,
				'selected'    => set_value('status'),
				'attr'        => [
					'id'          => 'contact_status',
					'class'       => 'form-control contact_status create_contact_field',
				],
			],
		];

		return $output;
	}

	public function download_attachment($file_id) {
		$file = $this->file_model->get($file_id);
		if (!$file) {
			$this->set_alert("Attachment not found", 'error');
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		$path = file_get_contents($file->url);
		$name = $file->filename;
		force_download($name, $path);
	}



	public function any_data_updated($id, $new_invoice) {
		$old_invoice = $this->invoice_model->get($id);
		if (
			$old_invoice->client_history_id != $new_invoice['client_history_id'] ||
			$old_invoice->address_history_id != $new_invoice['address_history_id'] ||
			$old_invoice->contact_history_id != $new_invoice['contact_history_id'] ||
			$old_invoice->invoice_date != $new_invoice['invoice_date'] ||
			$old_invoice->invoice_type != $new_invoice['invoice_type'] ||
			$old_invoice->amount != $new_invoice['amount'] ||
			$old_invoice->audit_fixed_date != $new_invoice['audit_fixed_date'] ||
			$old_invoice->follow_up_date != $new_invoice['follow_up_date'] ||
			$old_invoice->status != $new_invoice['status']
		) {
			return true;
		}
		return false;
	}

    protected function approveClientData($invoice) {
        $client = $this->db->where('id', $invoice->client_temp_id)->get('client_temp')->row();
        $update_client = [
            'name'	=> $client->name,
            'uen'	=> $client->uen,
            'website'	=> $client->website,
            'phone'	=> $client->phone,
            'fax'	=> $client->fax,
            'email'	=> $client->email
        ];

        $this->client_model->set_client_id($client->client_id);
        $this->client_model->update($client->client_id, $update_client);
        $client_history_id = $this->client_history_model->order_by('id', 'DESC')->limit(1)->get_by('client_id', $client->client_id)->id;
        return $client_history_id;
    }

    protected function approveAddressData($invoice) {
        $address = $this->db->where('id', $invoice->address_temp_id)->get('address_temp')->row();
        $update_address = [
            'address'	=> $address->address,
            'address_2'	=> $address->address_2,
            'phone'		=> $address->phone,
            'fax'		=> $address->fax,
            'country'	=> $address->country,
            'postal_code'	=> $address->postal_code,
            'total_employee'	=> $address->total_employee
        ];

        $this->address_model->set_address_id($address->address_id);
        $this->address_model->update($address->address_id, $update_address);
        $address_history_id = $this->address_history_model->order_by('id', 'DESC')->limit(1)->get_by('address_id', $address->address_id)->id;
        return $address_history_id;
    }

    protected function approveContactData($invoice) {
        $contact = $this->db->where('id', $invoice->contact_temp_id)->get('contact_temp')->row();
        $update_contact = [
            'salutation'	=> $contact->salutation,
            'status'	=> $contact->status,
            'name'		=> $contact->name,
            'email'		=> $contact->email,
            'position'	=> $contact->position,
            'department'	=> $contact->department,
            'phone'	=> $contact->phone,
            'fax'	=> $contact->fax,
            'mobile'	=> $contact->mobile
        ];

        $this->contact_model->set_contact_id($contact->contact_id);
        $this->contact_model->update($contact->contact_id, $update_contact);
        $contact_history_id = $this->contact_history_model->order_by('id', 'DESC')->limit(1)->get_by('contact_id', $contact->contact_id)->id;
        return $contact_history_id;
    }



}
