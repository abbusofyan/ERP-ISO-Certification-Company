<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quotation extends WebimpController
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
        'quotation',
		'quotation_detail',
		'quotation_note',
		'certification_scheme',
		'accreditation',
		'certification_cycle',
		'country',
		'quotation_address',
		'assessment_fee_file',
		'certificate_and_report_file',
		'quotation_type',
		'salutation',
		'training_type',
		'payment_terms',
		'quotation_status',
		'memo',
		'notification',
		'referrer',
		'invoice_type',
		'quotation_notification',
		'application_form',
        'address'
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

        $this->load->helper('download');
		$this->load->library(['form_validation', 'auth', 'excel']);
		$this->auth->only(['index', 'create', 'view', 'edit', 'update', 'delete']);

        $this->data['page']['title'] = 'Quotation';
        $this->add_breadcrumb('Certification_Scheme');
    }

	public function set_as_primary($id) {
		$this->address_model->update($id, );
	}



	/**
	 * Quotation listing
	 *
	 * @access public
	 * @return void
	 */
	public function index($status = null)
	{
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
        if ($status !== null) {
            $decodedStatus = urldecode($status);
        } else {
            $decodedStatus = '';
        }
		$this->data['selected_status'] = $decodedStatus;
		$this->data['quotation_status'] = $this->quotation_status_model->get_all();
		$this->data['notifications'] = $this->notification_model->order_by('id', 'DESC')->get_many_by('status', NULL);
		// $this->data['quotation_notifications'] = $this->quotation_notification_model->order_by('id', 'DESC')->get_many_by('status', NULL);
		$this->data['accreditations'] = $this->accreditation_model->get_all();
		$this->data['form_note'] = array_merge(
			$this->_generate_note_fields(),
			[
				'action' => site_url('quotation/add-note'),
			]
		);
    }




	public function view($id)
	{
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['memo_a'] = $this->memo_model->order_by('id', 'DESC')->get_many_by(['quotation_id' => $id, 'type'	=> 'A']);
		$this->data['memo_b'] = $this->memo_model->order_by('id', 'DESC')->get_many_by(['quotation_id' => $id, 'type'	=> 'B']);
		$quotation = $this->quotation_model->with('certification_cycle')
			->with('client')
			->with('contact')
			->with('address')
			->with('updated_by')
			->with('assessment_fee_file')
			->with('past_certification_report')
			->with('created_by')->get($id);
        $this->client_model->set_soft_delete(false);
		$quotation->client->client = $this->client_model->get($quotation->client->client_id);
		$quotation->invoice = $this->invoice_model->get_many_by(['quotation_id' => $id, '(request_status = "Approved" OR request_status = "Pending Update")']);
		if ($quotation->assessment_fee_file) {
			$files = [];
			foreach ($quotation->assessment_fee_file as $key => $file) {
				$attachment = $this->file_model->get($file->file_id);
				$quotation->assessment_fee_file[$key] = $attachment;
			}
		} else {
			$quotation->assessment_fee_file = '';
		}

		if ($quotation->past_certification_report) {
			$files = [];
			foreach ($quotation->past_certification_report as $key => $file) {
				$attachment = $this->file_model->get($file->file_id);
				$quotation->past_certification_report[$key] = $attachment;
			}
		} else {
			$quotation->past_certification_report = '';
		}
		$this->data['quotation'] = $quotation;
		$this->data['quotation_address'] = $this->quotation_address_model->with('address')->get_many_by('quotation_id', $id);
		$client_history = $this->client_history_model->get($quotation->client_history_id);
		$client_id = $client_history->client_id;
		$this->data['other_quotes'] = $other_quotes = $this->quotation_model->get_by_client_id($client_id, $id);
		$this->data['contacts'] = $contacts = $this->contact_history_model->get_many_by('id IN (SELECT MAX(id) FROM contact_history where client_id = '.$quotation->client->client_id.' GROUP BY contact_id)');
		$this->data['contact_primary'] = $this->contact_history_model->order_by('id', 'DESC')->get_by([
			'client_id' => $client_id,
			'primary'	=> 1
		]);
		$invoice_created = $this->invoice_model->get_many_by([
			'quotation_id' => $quotation->id,
			'(request_status = "Approved" OR request_status = "Pending Update")'
		]);
		$this->data['invoice_type_created'] = array_column($invoice_created, 'invoice_type');
		$this->data['invoice_types'] = $this->invoice_type_model->get_by_quotation_cycle($quotation->certification_cycle->name);
		$this->data['quotation_history'] = $this->quotation_history_model->get_history($id);
	    $this->data['client_history'] = $this->quotation_model->get_client_history($id);
	    $this->data['address_history'] = $this->quotation_model->get_address_history($id);
	    $this->data['contact_history'] = $this->quotation_model->get_contact_history($id);
		$this->data['quotation_notes'] = $this->quotation_note_model->with('created_by')->order_by('id', 'desc')->get_many_by('quotation_id', $id);
		$this->data['has_reached_max_total_invoice'] = $this->invoice_model->has_reached_max_total_invoice($id);
		// dd($this->data['has_reached_max_total_invoice']);
  }


	public function edit($id)
	{
	    $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
	    $this->data['certification_schemes'] = $this->certification_scheme_model->get_all();
	    $this->data['accreditations'] = $this->accreditation_model->get_all();
	    $this->data['quotation'] = $quotation = $this->quotation_model->with('certification_cycle')
	        ->with('client')
	        ->with('contact')
	        ->with('address')->get($id);
	    $client_id = $quotation->client->client_id;
	    $quotation_address = $this->quotation_address_model->get_many_by('quotation_id', $id);

	    // get addresses that belongs to this quotation except the primary one
	    $this->data['quotation_address'] = $this->quotation_address_model->with('address')->get_many_by('quotation_id', $id);

	    // get client's contact from contact history except the selected one
	    $this->data['contacts'] = $this->contact_history_model->get_many_by('contact_id !=  ' . $quotation->contact->contact_id. ' AND id IN (SELECT MAX(id) FROM contact_history where client_id = '.$client_id.' GROUP BY contact_id)');

		$this->data['certification_cycle'] = $this->certification_cycle_model->get_all();
		$this->data['countries'] = $this->country_model->select('name')->get_all();
		$this->data['training_type'] = $this->training_type_model->get_all();
		$this->data['payment_terms'] = $this->payment_terms_model->get_all();
	    $this->data['clients'] = $this->client_model->get_all();
	    $this->data['notes'] = $this->quotation_note_model->with('user')->order_by('id', 'DESC')->get_many_by('quotation_id', $id);

		$this->data['form'] = array_merge(
			[
				'action' => site_url('quotation/update'),
			]
		);
		$this->data['application_form'] = $this->application_form_model->get_all();
		$this->data['form_client'] = $this->_generate_client_fields();
		$this->data['form_contact'] = $this->_generate_contact_fields();
		$this->data['form_address'] = $this->_generate_address_fields();
	}


	public function update($quotation_id) {
		$post = $this->input->post();
    	$data['client_history_id'] = $post['client_history_id'];
		$data['contact_history_id'] = $post['contact_history_id'];
		$data['address_history_id'] = $post['address_history_id'];
		$data['type'] = $post['type'];
		$data['number'] = $post['number'];
		$data['status'] = $post['status'];
		$data['quotation_id'] = $quotation_id;

		$quote_type = strtolower($post['type']);
		foreach ($post as $key => $row) {
			$prefix = explode('-', $key)[0];
			if ($prefix == $quote_type) {
				$data[str_replace($quote_type.'-', '', $key)] = $row;
			}
		}
		if (!$this->referrer_model->check_referrer_exist($data['referred_by'])) {
			$this->referrer_model->insert(['name' => $data['referred_by']]);
		}

		$this->quotation_model->set_quotation_id($quotation_id);
		$updated = $this->quotation_model->update($quotation_id, $data);

		$data['quotation_id'] = $quotation_id;
		if ($quote_type == 'iso') {

		$quotation_note  = $post['iso-note'];

	  $this->quotation_address_model->delete_by('quotation_id', $quotation_id);
      if (array_key_exists('iso-other_address_id', $post)) {
        $address_history_id = array_filter($data['other_address_id']);
        foreach ($address_history_id as $address_id) {
          $this->quotation_address_model->insert([
            'quotation_id' => $quotation_id,
            'address_history_id' => $address_id
          ]);
        }
	}
      if ( (isset($_FILES['iso-assesment_fee_attachments'])) && ($_FILES['iso-assesment_fee_attachments']['size'] > 0) ) {
        foreach ($_FILES['iso-assesment_fee_attachments']['name'] as $key => $upload) {
          $file['name'] = $_FILES['iso-assesment_fee_attachments']['name'][$key];
          $file['type'] = $_FILES['iso-assesment_fee_attachments']['type'][$key];
          $file['tmp_name'] = $_FILES['iso-assesment_fee_attachments']['tmp_name'][$key];
          $file['error'] = $_FILES['iso-assesment_fee_attachments']['error'][$key];
          $file['size'] = $_FILES['iso-assesment_fee_attachments']['size'][$key];

          $assesment_fee_attachments_id = $this->file_model->process_uploaded_file($file, 'assesment_fee_attachments');
          if ($assesment_fee_attachments_id) {
            $this->assessment_fee_file_model->insert([
              'quotation_id' => $quotation_id,
              'file_id'	=> $assesment_fee_attachments_id
            ]);
          }
        }
      }
      if ( (isset($_FILES['iso-certification_and_reports_file'])) && ($_FILES['iso-certification_and_reports_file']['size'] > 0) ) {
        foreach ($_FILES['iso-certification_and_reports_file']['name'] as $key => $upload) {
          $file['name'] = $_FILES['iso-certification_and_reports_file']['name'][$key];
          $file['type'] = $_FILES['iso-certification_and_reports_file']['type'][$key];
          $file['tmp_name'] = $_FILES['iso-certification_and_reports_file']['tmp_name'][$key];
          $file['error'] = $_FILES['iso-certification_and_reports_file']['error'][$key];
          $file['size'] = $_FILES['iso-certification_and_reports_file']['size'][$key];

          $certificate_and_report_file_id = $this->file_model->process_uploaded_file($file, 'certification_and_reports_file');
          if ($certificate_and_report_file_id) {
            $this->certificate_and_report_file_model->insert([
              'quotation_id' => $quotation_id,
              'file_id'	=> $certificate_and_report_file_id
            ]);
          }
        }
      }
    }

    if ($quote_type == 'bizsafe') {

      $quotation_note  = $post['bizsafe-note'];

      if ( (isset($_FILES['bizsafe-assesment_fee_attachments'])) && ($_FILES['bizsafe-assesment_fee_attachments']['size'] > 0) ) {
        foreach ($_FILES['bizsafe-assesment_fee_attachments']['name'] as $key => $upload) {
          $file['name'] = $_FILES['bizsafe-assesment_fee_attachments']['name'][$key];
          $file['type'] = $_FILES['bizsafe-assesment_fee_attachments']['type'][$key];
          $file['tmp_name'] = $_FILES['bizsafe-assesment_fee_attachments']['tmp_name'][$key];
          $file['error'] = $_FILES['bizsafe-assesment_fee_attachments']['error'][$key];
          $file['size'] = $_FILES['bizsafe-assesment_fee_attachments']['size'][$key];

          $assesment_fee_attachments_id = $this->file_model->process_uploaded_file($file, 'assesment_fee_attachments');
          if ($assesment_fee_attachments_id) {
            $this->assessment_fee_file_model->insert([
              'quotation_id' => $quotation_id,
              'file_id'	=> $assesment_fee_attachments_id
            ]);
          }
        }
      }
    }

    if ($quote_type == 'training') {

      $quotation_note  = $post['training-note'];

      if ( (isset($_FILES['training-assesment_fee_attachments'])) && ($_FILES['training-assesment_fee_attachments']['size'] > 0) ) {
        foreach ($_FILES['training-assesment_fee_attachments']['name'] as $key => $upload) {
          $file['name'] = $_FILES['training-assesment_fee_attachments']['name'][$key];
          $file['type'] = $_FILES['training-assesment_fee_attachments']['type'][$key];
          $file['tmp_name'] = $_FILES['training-assesment_fee_attachments']['tmp_name'][$key];
          $file['error'] = $_FILES['training-assesment_fee_attachments']['error'][$key];
          $file['size'] = $_FILES['training-assesment_fee_attachments']['size'][$key];

          $assesment_fee_attachments_id = $this->file_model->process_uploaded_file($file, 'assesment_fee_attachments');
          if ($assesment_fee_attachments_id) {
            $this->assessment_fee_file_model->insert([
              'quotation_id' => $quotation_id,
              'file_id'	=> $assesment_fee_attachments_id
            ]);
          }
        }
      }
    }

    if ($updated) {
      $contact_id = $this->contact_history_model->get($post['contact_history_id'])->contact_id;
      $client_id = $this->client_history_model->get($post['client_history_id'])->client_id;
      $data = [
        'client_id' => $client_id,
        'contact_id' => $contact_id
      ];
      $this->contact_model->switch_main_contact($data);

      // $this->quotation_notification_model->insert([
      // 	'quotation_id' => $quotation_id,
      // 	'status' => NULL,
      // ]);

      if ($quotation_note) {
        $this->quotation_note_model->insert([
          'quotation_id' => $quotation_id,
          'note' => $quotation_note
        ]);
      }

      $this->set_alert('Quotation updated!', 'success', true);
      redirect('quotation', 'refresh');
    } else {
      $this->set_alert("Can't edit quotation.", 'error');
      redirect('quotation/create', 'refresh');
    }

		// if ($updated) {
		// 	$this->set_alert('Quotation updated!', 'success', true);
		// 	redirect('quotation/view/'.$post['quotation_id'], 'refresh');
		// } else {
		// 	$this->set_alert("Can't update quotation.", 'error');
		// 	redirect('quotation/view/'.$post['quotation_id'].'/edit', 'refresh');
		// }
	}


    public function store() {

        $this->db->trans_start();

        $post = $this->input->post();
        $data['client_history_id'] = $post['client_history_id'];
        $data['contact_history_id'] = $post['contact_history_id'];
        $data['address_history_id'] = $post['address_history_id'];
        $data['type'] = $post['type'];

        $quote_type = strtolower($post['type']);
        foreach ($post as $key => $row) {
            $prefix = explode('-', $key)[0];
            if ($prefix == $quote_type) {
                $data[str_replace($quote_type . '-', '', $key)] = $row;
            }
        }

        if (!$this->referrer_model->check_referrer_exist($data['referred_by'])) {
            $this->referrer_model->insert(['name' => $data['referred_by']]);
        }

        $quotation_id = $this->quotation_model->insert($data);

        $data['quotation_id'] = $quotation_id;

        if ($quote_type == 'iso') {
            $quotation_note = $post['iso-note'];

            if (array_key_exists('iso-other_address_id', $post)) {
                $address_history_id = array_filter($data['other_address_id']);
                foreach ($address_history_id as $address_id) {
                    $this->quotation_address_model->insert([
                        'quotation_id' => $quotation_id,
                        'address_history_id' => $address_id
                    ]);
                }
            }

            if (isset($_FILES['iso-assesment_fee_attachments']) && $_FILES['iso-assesment_fee_attachments']['size'] > 0) {
                foreach ($_FILES['iso-assesment_fee_attachments']['name'] as $key => $upload) {
                    $file = [
                        'name' => $_FILES['iso-assesment_fee_attachments']['name'][$key],
                        'type' => $_FILES['iso-assesment_fee_attachments']['type'][$key],
                        'tmp_name' => $_FILES['iso-assesment_fee_attachments']['tmp_name'][$key],
                        'error' => $_FILES['iso-assesment_fee_attachments']['error'][$key],
                        'size' => $_FILES['iso-assesment_fee_attachments']['size'][$key],
                    ];
                    $assesment_fee_attachments_id = $this->file_model->process_uploaded_file($file, 'assesment_fee_attachments');
                    if ($assesment_fee_attachments_id) {
                        $this->assessment_fee_file_model->insert([
                            'quotation_id' => $quotation_id,
                            'file_id' => $assesment_fee_attachments_id
                        ]);
                    }
                }
            }
        }

        if ($quotation_id) {
            $contact_id = $this->contact_history_model->get($post['contact_history_id'])->contact_id;
            $client_id = $this->client_history_model->get($post['client_history_id'])->client_id;
            $data = [
                'client_id' => $client_id,
                'contact_id' => $contact_id
            ];
            $this->contact_model->switch_main_contact($data);

            if (!empty($quotation_note)) {
                $this->quotation_note_model->insert([
                    'quotation_id' => $quotation_id,
                    'note' => $quotation_note
                ]);
            }

            $this->set_alert('Quotation created!', 'success', true);
        } else {
            $this->db->trans_rollback();
            $this->set_alert("Can't create quotation.", 'error');
            redirect('quotation/create', 'refresh');
            return;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->set_alert("An error occurred. Quotation was not created.", 'error');
            redirect('quotation/create', 'refresh');
        } else {
            redirect('quotation', 'refresh');
        }
    }

	protected function _validation_create_iso() {
		return [
			array(
	            'field' => 'client_status',
	            'label' => 'Client Status',
	            'rules' => 'required'
	        ),
		];
	}



	protected function _generate_note_fields($data = null)
	{
		$data = parse_args($data, [
			'note'            => '',
		]);

		$output = [
			'note' => [
				'id'          => 'note',
                'name'        => 'note',
                'class'       => 'form-control',
                'required'    => true,
                'value'       => set_value('note', $data['note'], false),
                'placeholder' => 'Write note here',
                'rows'        => 3,
			]
		];

		return $output;
	}





	/**
	 * Create quotation
	 *
	 * @access public
	 * @return void
	 */
	public function create()
	{
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

		$this->data['certification_schemes'] = $this->certification_scheme_model->get_all();
		$this->data['accreditations'] = $this->accreditation_model->get_all();
		$this->data['clients'] = $this->client_model->get_all();
		$this->data['certification_cycle'] = $this->certification_cycle_model->get_all();
		$this->data['quote_detail'] = '';
		$this->data['countries'] = $this->country_model->select('name')->get_all();
		$this->data['training_type'] = $this->training_type_model->get_all();
		$this->data['payment_terms'] = $this->payment_terms_model->get_all();
		$this->data['application_form'] = $this->application_form_model->get_all();
		$this->data['form'] = array_merge(
			[
				'action' => site_url('quotation/create'),
			]
		);

		$this->data['form_client'] = $this->_generate_client_fields();
		$this->data['form_contact'] = $this->_generate_contact_fields();
		$this->data['form_address'] = $this->_generate_address_fields();
	}




	public function add_note() {
		$post = $this->input->post();

		if ($this->form_validation->run()) {
			$note_id = $this->quotation_note_model->insert($post);
			if ($note_id) {
				$this->set_alert("Note created!", 'success', true);
                redirect($_SERVER['HTTP_REFERER'], 'refresh');
				// redirect('quotation', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('quotation', 'refresh');
		}
	}

	public function export($id) {
		$this->load->library(['pdf']);
		$quotation = $this->quotation_model->with('certification_cycle')
				->with('client')
				->with('contact')
				->with('address')
				->with('updated_by')
				->with('assessment_fee_file')
				->with('created_by')->get($id);
		$quotation->other_address = $this->quotation_address_model->with('address')->get_many_by('quotation_id', $id);
		$certification_scheme = $quotation->certification_scheme_arr;
		$total_certification_scheme = count($certification_scheme);
		$formatted_certification_scheme = '';
		for ($i=0; $i < $total_certification_scheme; $i++) {
			if($i > 0) {
				if ($i == $total_certification_scheme-1) {
					$formatted_certification_scheme .= ' & ';
				} else {
					$formatted_certification_scheme .= ', ';
				}
			}
			$formatted_certification_scheme .= $certification_scheme[$i];
		}
		$quotation->certification_scheme = $formatted_certification_scheme;
		$this->session->set_flashdata('quotation', $quotation);

		if($quotation->type == 'ISO') {
			$this->pdf->load_view('exports/quotation/iso');
		} elseif ($quotation->type == 'Bizsafe') {
			$this->pdf->load_view('exports/quotation/bizsafe');
		} else {
			$this->pdf->load_view('exports/quotation/training');
		}
		$this->pdf->set_option('isRemoteEnabled', TRUE);
		$this->pdf->set_option("isPhpEnabled", true);
		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => FALSE,
				'verify_peer_name' => FALSE,
				'allow_self_signed'=> TRUE
			]
		]);

		$rev_no = $this->extractRevNumber($quotation->number);
		$filename = 'ASA-PP-'.$quotation->client->name.$rev_no.'.pdf';
		$this->pdf->setHttpContext($context);
		$this->pdf->render();
		$this->pdf->stream($filename, ['Attachment' => 0]);
	}


	function extractRevNumber($inputString) {
	    $pattern = '/-Rev-(\d+)/';
	    if (preg_match($pattern, $inputString, $matches)) {
	        return '-Rev-'.$matches[1];
	    } else {
	        return '';
	    }
	}




	/**
	 * Fields for client form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _generate_client_fields($data = null)
	{
		$data = parse_args($data, [
			'client_name'            => '',
			'client_uen'             => '',
			'client_address'         => '',
			'client_address_2'       => '',
			'client_country'		  => '',
			'client_total_employee'  => '',
			'client_postal_code'     => '',
			'client_website'         => '',
			'client_phone'			  => '',
			'client_fax'			  => '',
			'client_email'			  => '',
		]);

		$country_options = ['' => '-- Please Select Country --'] + $this->country_model->dropdown('name', 'name');

		$output = [
			'client_name' => [
				'id'          => 'client_name',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_name', $data['client_name'], false),
				'placeholder' => 'Web Imp Pte Ltd',
				'maxlength'   => 200,
			],
			'client_uen' => [
				'id'          => 'client_uen',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_uen', $data['client_uen']),
				'placeholder' => '196700066N',
				'maxlength'   => 20,
			],
			'client_address' => [
				'id'          => 'client_address',
				'class'       => 'form-control client_field',
				'rows'         => 3,
				// 'value'       => set_value('client_address', $data['client_address'], false),
				'placeholder' => 'Branch address',
				'maxlength'   => 200,
			],
			'client_address_2' => [
				'id'          => 'client_address_2',
				'class'       => 'form-control client_field',
				'rows'         => 3,
				// 'value'       => set_value('client_address_2', $data['client_address_2'], false),
				'placeholder' => '2nd Branch address',
				'maxlength'   => 200,
			],
			'client_postal_code' => [
				'id'          => 'client_postal_code',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_postal_code', $data['client_postal_code']),
				'placeholder' => '16517',
				'maxlength'   => 6,
				'type'		  => 'number'
			],
			'client_total_employee' => [
				'id'          => 'client_total_employee',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_total_employee', $data['client_total_employee']),
				'placeholder' => '100',
				'type'		  => 'number'
			],
			'client_website' => [
				'id'          => 'client_website',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_website', $data['client_website']),
				'placeholder' => 'company.com',
				'maxlength'   => 200,
			],
			'client_phone' => [
				'id'          => 'client_phone',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_phone', $data['client_phone']),
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number',
				"onkeydown"	  => "return event.keyCode !== 69"
			],
			'client_fax' => [
				'id'          => 'client_fax',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_fax', $data['client_fax']),
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number',
				"onkeydown"	  => "return event.keyCode !== 69"
			],
			'client_email' => [
				'id'          => 'client_email',
				'class'       => 'form-control client_field',
				// 'value'       => set_value('client_email', $data['client_email']),
				'placeholder' => 'company@mail.com',
				'maxlength'   => 150,
			],
			'client_country' => [
				'name'		  => '',
				'options'     => $country_options,
				// 'selected'    => set_value('client_country', $data['client_country']),
				'attr'        => [
					'id'          => 'client_country',
					'class'       => 'form-control client_country select2 select-select2 client_field',
				],
			],
		];

		return $output;
	}






	/**
	 * Fields for contact form.
	 *
	 * @access protected
	 * @return void
	 */
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
		];

		$output = [
			'salutation' => [
				'name'        => '',
				'options'     => $salutation_options,
				'selected'    => set_value('salutation'),
				'attr'        => [
					'id'          => 'contact_salutation',
					'class'       => 'form-control contact_salutation create_contact_field',
				],
			],
			'contact_name' => [
				'id'          => 'contact_name',
				'class'       => 'form-control contact_name create_contact_field',
				'placeholder' => '',
				'maxlength'   => 200,
			],
			'contact_email' => [
				'id'          => 'contact_email',
				'class'       => 'form-control contact_email create_contact_field',
				'type'		  => 'email',
				'maxlength'   => 150,
			],
			'position' => [
				'id'          => 'contact_position',
				'class'       => 'form-control contact_position create_contact_field',
				'maxlength'   => 150,
			],
			'department' => [
				'id'          => 'contact_department',
				'class'       => 'form-control contact_department create_contact_field',
				'maxlength'   => 200,
			],
			'contact_phone' => [
				'id'          => 'contact_phone',
				'class'       => 'form-control contact_phone create_contact_field',
				'maxlength'   => 8,
				'type'		  => 'number',
				"onkeydown"	  => "return event.keyCode !== 69"
			],
			'contact_fax' => [
				'id'          => 'contact_fax',
				'class'       => 'form-control contact_fax create_contact_field',
				'maxlength'   => 8,
				'type'		  => 'number',
				"onkeydown"	  => "return event.keyCode !== 69"
			],
			'contact_mobile' => [
				'id'          => 'contact_mobile',
				'class'       => 'form-control contact_mobile create_contact_field',
				'maxlength'   => 8,
				'type'		  => 'number',
				"onkeydown"	  => "return event.keyCode !== 69"
			],
			'contact_status' => [
				'name'        => '',
				'options'     => $status_options,
				'selected'    => 'Active',
				'attr'        => [
					'id'          => 'contact_status',
					'class'       => 'form-control contact_status create_contact_field',
				],
			],
		];

		return $output;
	}







	/**
	 * Fields for client form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _generate_address_fields($data = null)
	{
		$data = parse_args($data, [
			'name' 	          => '',
			'address'         => '',
			'address_2'       => '',
			'country'		  => '',
			'total_employee'  => '',
			'postal_code'     => '',
		]);

		$country_options = ['' => '-- Please Select Country --'] + $this->country_model->dropdown('name', 'name');

		$output = [
			'address_name' => [
				'id'          => 'address_name',
				'class'       => 'form-control address_name create_address_field',
				'value'       => set_value('name', $data['name']),
			],
			'address' => [
				'id'          => 'address_address',
				'class'       => 'form-control address_address create_address_field',
				'rows'         => 3,
				'value'       => set_value('address', $data['address'], false),
				'placeholder' => 'Branch address',
				'maxlength'   => 200,
			],
			'address_2' => [
				'id'          => 'address_address_2',
				'class'       => 'form-control address_address_2 create_address_field',
				'rows'         => 3,
				'value'       => set_value('address_2', $data['address_2'], false),
				'placeholder' => '2nd Branch address',
				'maxlength'   => 200,
			],
			'postal_code' => [
				'id'          => 'address_postal_code',
				'class'       => 'form-control address_postal_code create_address_field',
				'value'       => set_value('postal_code', $data['postal_code']),
				'placeholder' => '16517',
				'maxlength'   => 6,
				'type'		  => 'number'
			],
			'total_employee' => [
				'id'          => 'address_total_employee',
				'class'       => 'form-control address_total_employee create_address_field',
				'value'       => set_value('total_employee', $data['total_employee']),
				'placeholder' => '9',
				'type'		  => 'number'
			],
			'address_phone' => [
				'id'          => 'address_phone',
				'class'       => 'form-control address_phone create_address_field',
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number'
			],
			'address_fax' => [
				'id'          => 'address_fax',
				'class'       => 'form-control address_fax create_address_field',
				'placeholder' => '6344488889',
				'maxlength'   => 8,
				'type'		  => 'number'
			],
			'country' => [
				'name'        => '',
				'options'     => $country_options,
				'selected'    => set_value('country', $data['country']),
				'attr'        => [
					'id'          => 'country',
					'class'       => 'form-control address_country create_address_field',
				],
			],
		];

		return $output;
	}


	public function confirm($quotation_id) {
		$data = [
			'status' => 'Confirmed',
			'quotation_id' => $quotation_id,
			'confirmed_on' => strtotime($this->input->post('confirm_date'))
		];
		$this->quotation_model->update_status($data);
		$this->set_alert('Quotation Confirmed', 'success', true);
		redirect('quotation/view/'.$quotation_id);
		$this->set_alert('Action not allowed', 'error', true);
		redirect('quotation/view/'.$quotation_id);
	}

    public function download_attachment($id) {
		$attachment = $this->file_model->get($id);
		$path = file_get_contents($attachment->url);
        $name = $attachment->filename;
		force_download($name, $path);
	}

}
