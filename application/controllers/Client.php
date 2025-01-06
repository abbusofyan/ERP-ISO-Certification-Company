<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Client extends WebimpController
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
		'client_history',
		'contact_history',
		'address_history',
		'contact',
		'client_note',
		'country',
		'address',
		'salutation',
		'postal_area',
        'quotation'
    ];

    protected $layout = 'layouts/private';

	public function __construct()
	{
        parent::__construct();

		require FCPATH . 'vendor/autoload.php';

        if (!$this->user_model->logged_in()) {
            $this->set_alert('Please login to continue.', 'error', true);
            $this->session->set_userdata('referred_from', current_url());
            redirect('login', 'refresh');
        }

		$this->load->library(['auth']);
		// $this->auth->route_access();

        $this->data['current_user'] = $this->session->userdata();

		$this->load->library(['form_validation','excel', 'auth']);
		$this->auth->except('read-client');

        // navigation
        $this->data['page']['title'] = 'Client';
        $this->add_breadcrumb('Client');
    }




    /**
	 * Client listing
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['postal_area'] = $this->db->select('postal_district, general_location, GROUP_CONCAT(postal_sector SEPARATOR ",") AS postal_sector_arr')->group_by(array('postal_district'))->get('postal_area')->result_array();
		$this->data['form'] = array_merge(
			$this->_generate_note_fields(),
			[
				'action' => site_url('client/add-note'),
				'action_main_contact' => site_url('client/change_main_contact'),
			]
		);
    }


	public function add_note() {
		if ($this->form_validation->run()) {

			$post = $this->input->post();
			$inserted_id = $this->client_note_model->insert($post);

			if ($inserted_id) {
				$this->set_alert('Note created!', 'success', true);
				redirect('client', 'refresh');
			} else {
				$this->set_alert("Can't create client.", 'error');
				redirect('client', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error');
		}
	}




	/**
	 * Client listing
	 *
	 * @access public
	 * @return void
	 */
	public function create()
	{
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

		if ($this->form_validation->run()) {
			$this->set_alert('Client created!', 'success', true);
			redirect('client', 'refresh');
		} else {
			$this->set_alert(validation_errors(), 'error');
		}

		$this->data['form_client'] = $this->_generate_client_fields();
		$this->data['form_contact'] = $this->_generate_contact_fields();
		$this->data['form_address'] = $this->_generate_address_fields();


	}




    /**
	 * View client details
	 *
	 * @access public
	 * @param int $client_id
	 * @return void
	 */
	public function view($client_id)
	{
		$this->client_id = $client_id;

		$this->data['client'] = $client = $this->client_model->with('contact')->with('user')->get($client_id);
	    $this->data['client_history'] = $this->client_history_model->get_by_client($client_id);
	    $this->data['address_history'] = $this->address_history_model->get_by_client($client_id);
	    $this->data['contact_history'] = $this->contact_history_model->get_by_client($client_id);
        $this->data['countries'] = $this->country_model->select('name')->get_all();
		$client_quotation = $this->client_model->quotation($client_id);
		$application_form = array_column($client_quotation, 'application_form');
		if (!$client) {
            redirect('client', 'refresh');
        }

		$this->data['primary_contact'] = $this->contact_model->get_by([
			'client_id' => $client_id,
			'primary' => 1
		]);

		$this->data['notes'] = $this->client_note_model->with('user')->order_by('id', 'DESC')->get_many_by('client_id', $client_id);

        $this->data['form_add_note'] = array_merge(
			$this->_generate_note_fields(),
            [
                'action' => site_url('client/add-note'),
            ]
        );

		$this->data['form_add_contact'] = array_merge(
			$this->_generate_contact_fields(),
			[
				'action' => site_url('contact/store'),
			]
		);

		$this->data['form_add_address'] = array_merge(
			$this->_generate_address_fields(),
            [
				'action' => site_url('address/store'),
            ]
        );

        $this->add_script([
            'datatable_scripts',
            'sweetalert_scripts',
		        'form_scripts'
        ]);
    }




	/**
	 * View client details
	 *
	 * @access public
	 * @param int $client_id
	 * @return void
	 */
	public function switch_main_address($client_id)
	{
		$this->client_id = $client_id;
		$this->data['client'] = $client = $this->client_model->with('contact')->get($client_id);
		if (!$client) {
			redirect('client', 'refresh');
		}

		$request_method = $this->input->server('REQUEST_METHOD');

		if ($request_method == 'POST') {

			$post = $this->input->post();

			if (!empty($post['note'])) {
				$this->_create_note($post);
			}

			if (!empty($post['name'])) {
				$this->_create_contact($post);
			}
		}

		$this->data['primary_contact'] = $this->contact_model->get_by([
			'client_id' => $client_id,
			'primary' => 1
		]);

		$this->data['notes'] = $this->client_note_model->with('user')->order_by('id', 'DESC')->get_many_by('client_id', $client_id);

		$this->data['form'] = array_merge(
			$this->_generate_contact_fields(),
			$this->_generate_note_fields(),
			[
				'action' => site_url('client/' . $client->id),
			]
		);

		$this->data['form_add_address'] = array_merge(
			$this->_generate_address_fields(),
			[
				'action' => site_url('client/' . $client->id . '/switch_main_address'),
			]
		);

		$this->add_script([
			'datatable_scripts',
			'sweetalert_scripts',
			'form_scripts'
		]);
	}




	protected function _create_note() {
		$this->form_validation->set_rules($this->_validation_note_rules());

		if ($this->form_validation->run()) {
            $post = $this->input->post();
			$inserted_id = $this->client_note_model->insert($post);
            if ($inserted_id) {
                $this->set_alert('Note successfully created!', 'success', true);
                redirect('client/view/' . $this->client_id, 'refresh');
            } else {
                $this->set_alert("Can't create note.", 'error');
                redirect('client/view/' . $this->client_id, 'refresh');
            }
        } else {
            $this->set_alert(validation_errors(), 'error');
        }
	}





    /**
	 * Edit client form
	 *
	 * @access public
     * @param int $client_id
	 * @return void
	 */
	public function edit($client_id = null)
	{
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

        if ($client_id) {

			$client = $this->client_model->get($client_id);
			$primary_address = $this->address_model->get_by('client_id', $client_id);
			$client->primary_address = $primary_address;
			$this->data['client'] = $client;
			$this->data['countries'] = $this->country_model->get_all();
            if (!$client) {
                redirect('client', 'refresh');
            }

            $this->data['form'] = array_merge(
                $this->_generate_client_fields((array) $client),
                [
                    'action' => site_url('client/update/'. $client->id),
                ]
            );
        } else {
            redirect('client', 'refresh');
        }
    }


	public function update($client_id) {
		if ($this->form_validation->run()) {

			$post = $this->input->post();

			$this->client_model->set_client_id($client_id);

			$updated = $this->client_model->update($client_id, $post);

			if ($updated) {
				$this->set_alert('Client information successfully updated!', 'success', true);
				redirect('client/view/'. $client_id, 'refresh');
			} else {
				$this->set_alert("Can't update client.", 'error', true);
				redirect('client/edit/'. $client_id, 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('client/edit/'. $client_id, 'refresh');
		}
	}




	/**
	 * Switch client's primary contact
	 *
	 * @access public
	 * @return void
	 */
	public function change_main_contact()
	{

		$this->form_validation->set_rules($this->_validation_change_main_contact_rules());

		if ($this->form_validation->run()) {

			$post = $this->input->post();
			$update = $this->contact_model->switch_main_contact($post);

			if ($update) {
				// $contact = $this->contact_model->get($post['contact_id']);
				if ($update) {
					$this->set_alert('Main contact switched!', 'success', true);
					redirect('client', 'refresh');
				} else {
					$this->set_alert("Can't switch main contact.", 'error');
					redirect('client', 'refresh');
				}
			} else {
				$this->set_alert("Can't switch main contact.", 'error');
				redirect('client', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error');
			redirect('client', 'refresh');
		}

	}






	/**
	 * Switch client's primary address
	 *
	 * @access public
	 * @return void
	 */
	public function change_main_address()
	{

		$this->form_validation->set_rules($this->_validation_change_main_address_rules());

		if ($this->form_validation->run()) {

			$post = $this->input->post();
			$update = $this->address_model->switch_main_address($post);

			if ($update) {
				$this->set_alert('Main address switched!', 'success', true);
				redirect('client', 'refresh');
			} else {
				$this->set_alert("Can't switch main address.", 'error');
				redirect('client', 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error');
			redirect('client', 'refresh');
		}

	}


	public function import(){
        $csvMimes = array('application/vnd.ms-excel',
                         'application/msexcel',
                         'application/x-msexcel',
                         'application/x-ms-excel',
                         'application/x-excel',
                         'application/x-dos_ms_excel',
                         'application/xls',
                         'application/x-xls',
                         'application/excel',
                         'application/download',
                         'application/vnd.ms-office',
                         'application/msword',
                         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                         'application/zip',
                         'application/vnd.ms-excel',
                         'application/msword',
                         'application/x-zip');

        // Validate whether selected file is a CSV file
        if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

            // If the file is uploaded
            if(is_uploaded_file($_FILES['file']['tmp_name'])){

                $path = $_FILES["file"]["tmp_name"];
                $object = PHPExcel_IOFactory::load($path);
                // Parse data from CSV file line by line from sheet 1
				$sheet = 1;
				foreach($object->getWorksheetIterator() as $worksheet)
                {
					if($sheet == 1) {
						$maxRow = $worksheet->getHighestRow();
						$highestColumn = $worksheet->getHighestColumn();

						for($row = 2; $row <= $maxRow; $row++)
						{
							$client_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();

							if($client_name) {
								$primary = 1;

								$insertClientData['name'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
								$insertClientData['status'] = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
								$insertClientData['uen'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
								$insertClientData['website'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
								$insertClientData['email'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
								$insertClientData['phone'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
								$insertClientData['fax'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
								$clientId = $prevClientId = $this->client_model->insert($insertClientData);

							} else {
								$primary = 0;
								$clientId = $prevClientId;
							}

							$address = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
							if($address) {
								$insertAddressData['client_id'] = $clientId;
								$insertAddressData['address_name'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
								$insertAddressData['phone'] = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
								$insertAddressData['fax'] = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
								$insertAddressData['address'] = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
								$insertAddressData['address_2'] = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
								$insertAddressData['country'] = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
								$insertAddressData['postal_code'] = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
								$insertAddressData['total_employee'] = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
								$insertAddressData['primary'] = $primary;
								$this->address_model->insert($insertAddressData);
							}

							$contact_name = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
							if($contact_name) {
								$insertContactData['client_id'] = $clientId;
								$insertContactData['salutation'] = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
								$insertContactData['name'] = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
								$insertContactData['email'] = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
								$insertContactData['mobile'] = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
								$insertContactData['phone'] = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
								$insertContactData['fax'] = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
								$insertContactData['position'] = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
								$insertContactData['department'] = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
								$insertContactData['status'] = $worksheet->getCellByColumnAndRow(21, $row)->getValue();
								$insertContactData['primary'] = $primary;
								$this->contact_model->insert($insertContactData);
							}

						}

						$sheet++;
					}

                }
				$this->set_alert('Client data imported', 'success', true);
                redirect('client', 'refresh');
            }
        }else{
            $this->set_alert('Only file with .xlsx format allowed', 'error', true);
            redirect('client', 'refresh');
        }
    }


	public function export() {

		// $clients = $this->client_model->with('address')->with('contact')->get_all();
        $this->db->select('cl.*, co.name as contact_name, co.salutation, co.email as contact_email, co.mobile as contact_mobile, co.phone as contact_phone,
        co.fax as contact_fax, co.position, co.department, co.primary, co.status as contact_status, ad.name as address_name, ad.phone as address_phone, ad.fax as address_fax,
        ad.address, ad.address_2, ad.country, ad.postal_code, ad.total_employee');
        $this->db->from('client cl');
        $this->db->join('contact co', 'co.client_id = cl.id AND co.primary = 1', 'left');
        $this->db->join('address ad', 'ad.client_id = cl.id AND ad.primary = 1', 'left');
        $this->db->where('cl.deleted', 0);
        $clients = $this->db->get()->result();

		$this->excel->setActiveSheetIndex(0);

       	$this->excel->getActiveSheet()->setCellValue('A1', 'Client Name');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Status');
        $this->excel->getActiveSheet()->setCellValue('C1', 'UEN');
        $this->excel->getActiveSheet()->setCellValue('D1', 'Website');
		$this->excel->getActiveSheet()->setCellValue('E1', 'Email');
        $this->excel->getActiveSheet()->setCellValue('F1', 'Main Phone');
        $this->excel->getActiveSheet()->setCellValue('G1', 'Main Fax');
        $this->excel->getActiveSheet()->setCellValue('H1', 'Address');
        $this->excel->getActiveSheet()->setCellValue('I1', 'Total Employee');
        $this->excel->getActiveSheet()->setCellValue('J1', 'Contact Salutation');
        $this->excel->getActiveSheet()->setCellValue('K1', 'Contact Name');
        $this->excel->getActiveSheet()->setCellValue('L1', 'Email');
        $this->excel->getActiveSheet()->setCellValue('M1', 'Mobile');
        $this->excel->getActiveSheet()->setCellValue('N1', 'Phone (Direct)');
        $this->excel->getActiveSheet()->setCellValue('O1', 'Fax (Direct)');
        $this->excel->getActiveSheet()->setCellValue('P1', 'Position');
        $this->excel->getActiveSheet()->setCellValue('Q1', 'Department');
        $this->excel->getActiveSheet()->setCellValue('R1', 'Contact Status');

        $rows = 2;
        foreach ($clients as $client){
            $this->excel->getActiveSheet()->setCellValue('A' . $rows, $client->name);
            $this->excel->getActiveSheet()->setCellValue('B' . $rows, $client->status);
            $this->excel->getActiveSheet()->setCellValue('C' . $rows, $client->uen);
            $this->excel->getActiveSheet()->setCellValue('D' . $rows, $client->website);
	    	$this->excel->getActiveSheet()->setCellValue('E' . $rows, $client->email);

            $this->excel->getActiveSheet()->setCellValue('F' . $rows, $client->address_phone);
            $this->excel->getActiveSheet()->setCellValue('G' . $rows, $client->address_fax);

            if ($client->address_2) {
                $address = $client->address . ', ' . $client->address_2 . ', ' . $client->country . ', ' . $client->postal_code;
            } else {
                $address = $client->address . ', '. $client->country . ', ' . $client->postal_code;
            }

            $this->excel->getActiveSheet()->setCellValue('H' . $rows, $address);
            $this->excel->getActiveSheet()->setCellValue('I' . $rows, $client->total_employee);
            $this->excel->getActiveSheet()->setCellValue('J' . $rows, $client->salutation);
            $this->excel->getActiveSheet()->setCellValue('K' . $rows, $client->contact_name);
            $this->excel->getActiveSheet()->setCellValue('L' . $rows, $client->contact_email);
            $this->excel->getActiveSheet()->setCellValue('M' . $rows, $client->contact_mobile);
            $this->excel->getActiveSheet()->setCellValue('N' . $rows, $client->contact_phone);
            $this->excel->getActiveSheet()->setCellValue('O' . $rows, $client->contact_fax);
            $this->excel->getActiveSheet()->setCellValue('P' . $rows, $client->position);
            $this->excel->getActiveSheet()->setCellValue('Q' . $rows, $client->department);
            $this->excel->getActiveSheet()->setCellValue('R' . $rows, $client->contact_status);


			// $sum_contact = count($client->contact);
			// $sum_address = count($client->address);
            //
			// if($sum_contact > $sum_address) {
			// 	$data = $client->contact;
			// } else {
			// 	$data = $client->address;
			// }
            //
			// foreach ($data as $key => $row) {
            //
			// 	// load address data
			// 	if(isset($client->address[$key])) {
			// 		$this->excel->getActiveSheet()->setCellValue('F' . $rows, $client->address[$key]->name);
			// 		$this->excel->getActiveSheet()->setCellValue('G' . $rows, $client->address[$key]->phone);
			// 		$this->excel->getActiveSheet()->setCellValue('H' . $rows, $client->address[$key]->fax);
			// 		$this->excel->getActiveSheet()->setCellValue('I' . $rows, $client->address[$key]->address);
			// 		$this->excel->getActiveSheet()->setCellValue('J' . $rows, $client->address[$key]->address_2);
			// 		$this->excel->getActiveSheet()->setCellValue('K' . $rows, $client->address[$key]->country);
			// 		$this->excel->getActiveSheet()->setCellValue('L' . $rows, $client->address[$key]->postal_code);
			// 		$this->excel->getActiveSheet()->setCellValue('M' . $rows, $client->address[$key]->total_employee);
			// 	}
            //
			// 	if(isset($client->contact[$key])) {
			// 		// load contact data
			// 		$this->excel->getActiveSheet()->setCellValue('N' . $rows, $client->contact[$key]->salutation);
			// 		$this->excel->getActiveSheet()->setCellValue('O' . $rows, $client->contact[$key]->name);
			// 		$this->excel->getActiveSheet()->setCellValue('P' . $rows, $client->contact[$key]->email);
			// 		$this->excel->getActiveSheet()->setCellValue('Q' . $rows, $client->contact[$key]->mobile);
			// 		$this->excel->getActiveSheet()->setCellValue('R' . $rows, $client->contact[$key]->phone);
			// 		$this->excel->getActiveSheet()->setCellValue('S' . $rows, $client->contact[$key]->fax);
			// 		$this->excel->getActiveSheet()->setCellValue('T' . $rows, $client->contact[$key]->position);
			// 		$this->excel->getActiveSheet()->setCellValue('U' . $rows, $client->contact[$key]->department);
			// 		$this->excel->getActiveSheet()->setCellValue('V' . $rows, $client->contact[$key]->status);
			// 	}
				$rows++;
			// }
        }

		$filename = 'client_detail.xls';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
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
            'name'            => '',
            'uen'             => '',
            'address'         => '',
            'address_2'       => '',
			'postal_code'     => '',
			'country'		  => '',
			'website'         => '',
			'phone'			  => '',
			'fax'			  => '',
			'email'			  => '',
			'total_employee'  => '',
        ]);

		$country_options = ['' => '-- Please Select Country --'] + $this->country_model->dropdown('name', 'name');

        $output = [
			'name' => [
                'id'          => 'client_name',
                'name'        => 'name',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('name', $data['name'], false),
                'placeholder' => 'Web Imp Pte Ltd',
                'maxlength'   => 200,
			],
            'uen' => [
                'id'          => 'client_uen',
                'name'        => 'uen',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('uen', $data['uen']),
                'placeholder' => '196700066N',
                'maxlength'   => 20,
            ],
			'address' => [
                'id'          => 'client_address',
                'name'        => 'address',
                'class'       => 'form-control create_client_field',
                'rows'         => 3,
                'required'    => true,
                'value'       => set_value('address', $data['address'], false),
                'placeholder' => 'Branch address',
                'maxlength'   => 200,
			],
			'address_2' => [
                'id'          => 'client_address_2',
                'name'        => 'address_2',
                'class'       => 'form-control create_client_field',
                'rows'         => 3,
                'value'       => set_value('address_2', $data['address_2'], false),
                'placeholder' => '2nd Branch address',
                'maxlength'   => 200,
			],
			'postal_code' => [
                'id'          => 'client_postal_code',
                'name'        => 'postal_code',
				'type'		  => 'number',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('postal_code', $data['postal_code']),
                'placeholder' => '16517',
                'maxlength'   => 150,
			],
			'total_employee' => [
				'id'          => 'client_total_employee',
				'name'        => 'total_employee',
				'type'		  => 'number',
				'class'       => 'form-control create_client_field',
				'required'    => true,
				'value'       => set_value('total_employee', $data['total_employee']),
				'placeholder' => '100',
			],
            'website' => [
                'id'          => 'client_website',
                'name'        => 'website',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('website', $data['website']),
                'placeholder' => 'company.com',
                'maxlength'   => 200,
            ],
			'phone' => [
                'id'          => 'client_phone',
                'name'        => 'phone',
				'type'		  => 'number',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('phone', $data['phone']),
                'placeholder' => '6344488889',
                'maxlength'   => 20,
			],
			'fax' => [
                'id'          => 'client_fax',
                'name'        => 'fax',
				'type'		  => 'number',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('fax', $data['fax']),
                'placeholder' => '6344488889',
                'maxlength'   => 150,
			],
			'email' => [
                'id'          => 'client_email',
                'name'        => 'email',
				'type'		  => 'email',
                'class'       => 'form-control create_client_field',
                'required'    => true,
                'value'       => set_value('email', $data['email']),
                'placeholder' => 'company@mail.com',
                'maxlength'   => 150,
			],
			'country' => [
                'name'        => 'client_country',
                'options'     => $country_options,
                'selected'    => set_value('country', $data['country']),
                'attr'        => [
                    'id'          => 'client_country',
                    'class'       => 'form-control create_client_field select2 select-select2',
                    'required'    => true,
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
			'address_name'	  => '',
			'address'         => '',
			'address_2'       => '',
			'phone'			  => '',
			'fax'			  => '',
			'country'		  => '',
			'postal_code'     => '',
			'total_employee'  => '',
		]);

		$country_options = ['' => '-- Please Select Country --'] + $this->country_model->dropdown('name', 'name');

		$output = [
			'name' => [
				'id'          => 'name',
				'name'        => 'address_name',
				'class'       => 'form-control address-input-name address-field',
				'value'       => set_value('address_name', $data['address_name']),
                'title'       => 'Site Name',
                'required'    => true
			],
			'address' => [
				'id'          => 'address',
				'name'        => 'address',
				'class'       => 'form-control address-input-address address-field',
				'rows'         => 3,
				'value'       => set_value('address', $data['address'], false),
				'placeholder' => 'Branch address',
				'maxlength'   => 200,
			],
			'address_2' => [
				'id'          => 'address_2',
				'name'        => 'address_2',
				'class'       => 'form-control address-input-address2 address-field',
				'rows'         => 3,
				'value'       => set_value('address_2', $data['address_2'], false),
				'placeholder' => '2nd Branch address',
				'maxlength'   => 200,
			],
			'postal_code' => [
				'id'          => 'postal_code',
				'name'        => 'postal_code',
				'type'		  => 'number',
				'class'       => 'form-control address-input-postal-code address-field',
				'value'       => set_value('postal_code', $data['postal_code']),
				'placeholder' => '16517',
				'maxlength'   => 150,
			],
			'phone' => [
				'id'          => 'phone',
				'name'        => 'phone',
				'type'		  => 'number',
				'class'       => 'form-control address-input-phone address-field',
				'value'       => set_value('phone', $data['phone']),
				'placeholder' => '61772889',
			],
			'fax' => [
				'id'          => 'fax',
				'name'        => 'fax',
				'type'		  => 'number',
				'class'       => 'form-control address-input-fax address-field',
				'value'       => set_value('fax', $data['fax']),
				'placeholder' => '61772889',
			],
			'total_employee' => [
				'id'          => 'total_employee',
				'name'        => 'total_employee',
				'type'		  => 'number',
				'class'       => 'form-control address-input-total-employee address-field',
				'value'       => set_value('total_employee', $data['total_employee']),
				'placeholder' => '100',
			],
			'country' => [
				'name'        => 'country',
				'options'     => $country_options,
				'selected'    => set_value('country', $data['country']),
				'attr'        => [
					'id'          => 'country',
					'class'       => 'form-control address-select-country address-field',
				],
			],
		];

		return $output;
	}





	/**
	 * Fields for note form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _generate_note_fields($data = null)
	{
		$data = parse_args($data, [
			'note'            => '',
		]);

		$output = [
			'note' => [
				'id'          => 'note',
                'name'        => 'note',
                'class'       => 'form-control note-field',
                'required'    => true,
                'value'       => set_value('note', $data['note'], false),
                'placeholder' => 'Write note here',
                'maxlength'   => 200,
                'rows'        => 3,
			]
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
        $data = parse_args($data, [
			'salutation'		=> '',
			'status'			=> '',
			'name'				=> '',
			'email'				=> '',
			'position'			=> '',
			'department'		=> '',
			'phone'				=> '',
			'fax'				=> '',
			'mobile'			=> ''
        ]);

		$salutation_options = ['' => '-- Please Select Salutation --'] + $this->salutation_model->dropdown('name', 'name');

		$status_options = [
			'' => '-- Please select Status --',
			'Active' => 'Active',
		];

        $output = [
			'name' => [
                'id'          => 'name',
                'name'        => 'name',
                'class'       => 'form-control poc-input-name create_contact_field',
                'value'       => set_value('name', $data['name'], false),
                'maxlength'   => 200,
			],
			'email' => [
                'id'          => 'email',
                'name'        => 'email',
                'class'       => 'form-control poc-input-email create_contact_field',
				'type' 		  => 'email',
                'value'       => set_value('email', $data['email']),
                'placeholder' => 'example@mail.com',
                'maxlength'   => 200,
			],
			'phone' => [
                'id'          => 'phone',
                'name'        => 'phone',
                'class'       => 'form-control poc-input-phone create_contact_field',
                'value'       => set_value('phone', $data['phone']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
				'type'		  => 'number'
            ],
			'fax' => [
                'id'          => 'fax',
                'name'        => 'fax',
                'class'       => 'form-control poc-input-fax create_contact_field',
                'value'       => set_value('fax', $data['fax']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
				'type'		  => 'number'
            ],
			'mobile' => [
                'id'          => 'mobile',
                'name'        => 'mobile',
                'class'       => 'form-control poc-input-mobile create_contact_field',
                'value'       => set_value('mobile', $data['mobile']),
                'placeholder' => '63346659',
                'onkeypress'  => 'return isNumberKey(event);',
                'onpaste'     => 'return false;',
                'maxlength'   => 25,
				'type'		  => 'number'
            ],
            'position' => [
                'id'          => 'position',
                'name'        => 'position',
                'class'       => 'form-control poc-input-position create_contact_field',
                'value'       => set_value('position', $data['position']),
                'maxlength'   => 200,
			],
			'department' => [
                'id'          => 'department',
                'name'        => 'department',
                'class'       => 'form-control poc-input-department create_contact_field',
                'value'       => set_value('department', $data['department']),
                'maxlength'   => 200,
			],
			'status' => [
                'name'        => 'status',
                'options'     => $status_options,
                'selected'    => 'Active',
                'attr'        => [
                    'id'          => 'status',
                    'class'       => 'form-control poc-select-status create_contact_field',
                ],
            ],
			'salutation' => [
                'name'        => 'salutation',
                'options'     => $salutation_options,
                'selected'    => set_value('salutation'),
                'attr'        => [
                    'id'          => 'salutation',
                    'class'       => 'form-control poc-select-salutation create_contact_field',
                ],
            ],
        ];

        return $output;
    }




	/**
	 * Validation rules for edit client form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_edit_client_rules()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required|max_length[200]',
			],
			[
				'field' => 'uen',
				'label' => 'UEN',
				'rules' => 'trim|required|max_length[20]',
			],
			[
				'field' => 'website',
				'label' => 'Website',
				'rules' => 'trim|required|max_length[100]',
			],
			[
				'field' => 'phone',
				'label' => 'Phone',
				'rules' => 'trim|required|max_length[25]',
			],
			[
				'field' => 'fax',
				'label' => 'Fax',
				'rules' => 'trim|required|max_length[25]',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|max_length[200]',
			],
		];


		return $rules;
	}





	/**
	 * Validation rules for add address.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_add_address_rules()
	{
		$rules = [
			[
				'field' => 'address',
				'label' => 'Address',
				'rules' => 'trim|required',
			],
			[
				'field' => 'postal_code',
				'label' => 'Postal Code',
				'rules' => 'trim|required|max_length[10]',
			],
			[
				'field' => 'country',
				'label' => 'Country',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}





	/**
	 * Validation rules for change client's main contact.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_change_main_contact_rules()
	{
		$rules = [
			[
				'field' => 'client_id',
				'label' => 'Client id',
				'rules' => 'trim|required',
			],
			[
				'field' => 'contact_id',
				'label' => 'Contact id',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}




	/**
	 * Validation rules for change client's main address.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_change_main_address_rules()
	{
		$rules = [
			[
				'field' => 'client_id',
				'label' => 'Client id',
				'rules' => 'trim|required',
			],
			[
				'field' => 'address_id',
				'label' => 'Address id',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}





	/**
	 * Validation rules for create a new client's contact
	 *
	 * @access protected
	 * @return void
	 */
	protected function _validation_create_contact_rules()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'trim|required',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required',
			],
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'trim|required',
			],
			[
				'field' => 'position',
				'label' => 'Position',
				'rules' => 'trim|required',
			],
			[
				'field' => 'department',
				'label' => 'Department',
				'rules' => 'trim|required',
			],
			[
				'field' => 'phone',
				'label' => 'Phone',
				'rules' => 'trim|required',
			],
			[
				'field' => 'mobile',
				'label' => 'Mobile',
				'rules' => 'trim|required',
			],
			[
				'field' => 'fax',
				'label' => 'Fax',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}





    /**
     * Validation rules for branch form.
     *
     * @access protected
     * @return void
     */
    protected function _validation_branch_rules()
    {
        $rules = [
            [
                'field' => 'code',
                'label' => 'Branch Code',
                'rules' => 'trim|required|max_length[100]',
			],
            [
                'field' => 'tel_no',
                'label' => 'Tel No',
                'rules' => 'trim|max_length[25]',
			],
            [
                'field' => 'tel_no_2',
                'label' => 'Tel No (Optional)',
                'rules' => 'trim|max_length[25]',
			],
            [
                'field' => 'fax_no',
                'label' => 'Fax No',
                'rules' => 'trim|max_length[25]',
			],
			[
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|required|max_length[200]',
			],
			[
                'field' => 'bic_name',
                'label' => 'Manager/BIC Name',
                'rules' => 'trim|max_length[200]',
			],
            [
                'field' => 'bic_designation',
                'label' => 'Manager/BIC Designation',
                'rules' => 'trim|max_length[100]',
            ],
            [
                'field' => 'bic_contact',
                'label' => 'Manager/BIC Contact',
                'rules' => 'trim|max_length[25]',
			]
        ];

        return $rules;
    }

}
