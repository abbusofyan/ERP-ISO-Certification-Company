<?php defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Import extends WebimpController
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
		$this->load->library(['form_validation', 'auth']);

		$this->data['page']['title'] = 'Dashboard';
	}

	public function client() {
		if ($this->input->post()) {
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

			if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

				if(is_uploaded_file($_FILES['file']['tmp_name'])){

					$file_name = $_FILES["file"]["tmp_name"];

					$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

					$spreadsheet 	= $reader->load($file_name);
					$sheet_data 	= $spreadsheet->getActiveSheet()->toArray();
					$client_exists = [];
					foreach($sheet_data as $key => $cell) {
						if($key != 0) {
							$client_name = $cell[0];

							if($client_name) {
								$client = $this->client_model->get_by('name', ltrim($client_name));
								if ($client) {
									array_push($client_exists, $client_name);
									continue;
								}

								$uen = $cell[1];
								$website = $cell[2];
								$phone = $cell[3];
								$fax = $cell[4];
								$email = $cell[5];
								$insertClientData['name'] = $cell[0];
								$insertClientData['uen'] = $uen ? $uen : '';
								$insertClientData['website'] = $website ? $website : '';
								$insertClientData['phone'] = $phone ? $phone : '';
								$insertClientData['fax'] = $fax ? $fax : '';
								$insertClientData['email'] = $email ? $email : '';
								$insertClientData['status'] = 'New';
								$clientId = $this->client_model->insert($insertClientData);

								$address = $cell[6];
								$address_2 = $cell[7];
								$country = $cell[8];
								$postal_code = $cell[9];
								$total_employee = $cell[10];

								$insertAddressData['client_id'] = $clientId;
								$insertAddressData['name'] = 'Main Address';
								$insertAddressData['phone'] = $phone ? $phone : '';
								$insertAddressData['fax'] = $fax ? $fax : '';
								$insertAddressData['address'] = $address ? $address : '';
								$insertAddressData['address_2'] = $address_2 ? $address_2 : '';
								$insertAddressData['country'] = $country ? rtrim(ltrim($country)) : '';
								$insertAddressData['postal_code'] = $postal_code ? rtrim(ltrim($postal_code)) : '';
								$insertAddressData['total_employee'] = $total_employee ? $total_employee : '';
								$insertAddressData['primary'] = 1;
								$address_id = $this->address_model->insert($insertAddressData);
							}
						}
					}
					if ($client_exists) {
						dd($client_exists);
					}
					$this->set_alert('Client data imported', 'success', true);
					redirect('import/client', 'refresh');
				}
			} else {
				$this->set_alert('Only file with .xlsx format allowed', 'error', true);
				redirect('client', 'refresh');
			}
		}

	}

    public function contact() {
		if ($this->input->post()) {
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

			if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

				if(is_uploaded_file($_FILES['file']['tmp_name'])){

					$file_name = $_FILES["file"]["tmp_name"];

					$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

					$spreadsheet 	= $reader->load($file_name);
					$sheet_data 	= $spreadsheet->getActiveSheet()->toArray();

					$client_not_found = [];
					foreach ($sheet_data as $key => $cell) {
						if ($key != 0) {
							$client_name = $cell[0];
							$client = $this->client_model->get_by('name', ltrim($client_name));
							if($client) {
								$name = rtrim($cell[2]);
								$client_id = $client->id;

								$salutation = $cell[1];
								$email = $cell[3];
								$position = $cell[4];
								$department = $cell[5];
								$phone = $cell[6];
								$fax = $cell[7];
								$mobile = $cell[8];
								$primary = $cell[9];

								$insertContactData['client_id'] = $client_id;
								$insertContactData['salutation'] = $salutation ? str_replace(".", "", trim($salutation)) : '';
								$insertContactData['name'] = $name ? $name : '';
								$insertContactData['email'] = $email ? $email : '';
								$insertContactData['position'] = $position ? $position : '';
								$insertContactData['department'] = $department ? $department : '';
								$insertContactData['phone'] = $phone ? $phone : '';
								$insertContactData['fax'] = $fax ? $fax : '';
								$insertContactData['mobile'] = $mobile ? $mobile : '';
								$insertContactData['status'] = 'Active';
								$insertContactData['primary'] = $primary ? $primary : 0;

								$contact = $this->contact_model->get_by(['name' => $name, 'client_id' => $client_id]);
								if ($contact) {
									$this->contact_model->set_contact_id($contact->id);
									$contact_id = $this->contact_model->update($contact->id, $insertContactData);
									continue;
								} else {
									$contact_id = $this->contact_model->insert($insertContactData);
								}

							} else {
								array_push($client_not_found, 'client not found at row : ' . ($key + 1));
							}
						}
					}

					if ($client_not_found) {
						dd($client_not_found);
					}
					$this->set_alert('Contact data imported', 'success', true);
					redirect('import/contact', 'refresh');
				}
			} else {
				$this->set_alert('Only file with .xlsx format allowed', 'error', true);
				redirect('client', 'refresh');
			}
		}
    }

    public function importCertificationScheme($certification_scheme) {
        $resultArray = array_map(function($item) {
            return $item[0];
        }, $certification_scheme);
        $scheme_arr = array_unique(array_filter($resultArray));
        $new_scheme_arr = [];
        foreach ($scheme_arr as $key => $arr) {
            $certification_scheme_arr = explode(',', preg_replace('/\s*,\s*/', ',', $arr));
            foreach ($certification_scheme_arr as $scheme) {
				if ($scheme != '*') {
					$x = htmlspecialchars(rtrim(strtoupper(trim(preg_replace('/\s+/', ' ', $scheme)))));
					// $x = strtoupper(ltrim(rtrim($scheme)));
					if (!in_array($x, $new_scheme_arr)) {
						array_push($new_scheme_arr, $x);
					}
				}
            }
        }

        foreach ($new_scheme_arr as $key =>$scheme) {
			$certification_scheme = $this->certification_scheme_model->get_by('name', $scheme);
			if (!$certification_scheme) {
				$this->certification_scheme_model->insert(['name' => $scheme]);
			}
        }
        return true;
    }

    public function importAccreditation($accreditation) {
        $resultArray = array_map(function($item) {
            return $item[0];
        }, $accreditation);
        $acc_arr = array_unique(array_filter($resultArray));
        $new_acc_arr = [];
        foreach ($acc_arr as $key => $arr) {
			$accreditation_arr = explode(',', preg_replace('/\s*,\s*/', ',', $arr));
			$new_acc_arr = array_merge($new_acc_arr, $accreditation_arr);
        }
        $clean_acc_arr = array_unique(array_filter($new_acc_arr));
        foreach ($clean_acc_arr as $arr) {
			if ($arr != '*') {
				$accreditation = $this->accreditation_model->get_by('name', ltrim($arr));
				if (!$accreditation) {
					$this->accreditation_model->insert(['name' => ltrim($arr)]);
				}
			}
        }
        return true;
    }

	public function getAmount($money)
	{
		if ($money) {
			$cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
			$onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

			$separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

			$stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
			$removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

			return (float) str_replace(',', '.', $removedThousandSeparator);
		}
		return '';
	}

    public function quotation() {
		if ($this->input->post()) {
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

			if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){

				if(is_uploaded_file($_FILES['file']['tmp_name'])){

					$file_name = $_FILES["file"]["tmp_name"];

					$reader 	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

					$spreadsheet 	= $reader->load($file_name);
					$sheet_data 	= $spreadsheet->getActiveSheet()->toArray();

					// input all scheme first
					$maxRow = $spreadsheet->getActiveSheet()->getHighestRow();

					$certification_scheme = $spreadsheet->getActiveSheet()->rangeToArray('S7:S'.$maxRow);
					$this->importCertificationScheme($certification_scheme);

					// input all accreditation
					$accreditation = $spreadsheet->getActiveSheet()->rangeToArray('T7:T'.$maxRow);
					$this->importAccreditation($accreditation);
					// dd('scheme and acc imported');

					$errors = [];

					foreach ($sheet_data as $key => $cell) {
						if ($key < 6) {
							continue;
						}
						$insertQuotationData = [];
						$client_name = $cell[0];
						$client_uen = $cell[33];
						// if ($client_uen) {
						// 	$client_history = $this->client_history_model->get_by('uen', $client_uen);
						// } else {
						// 	$client_history = $this->client_history_model->get_by('name', $client_name);
						// }
						$client_history = $this->client_history_model->get_by('name', $client_name);
						if (!$client_history) {
							array_push($errors, 'client not found at row : ' . ($key + 1));
							if (($key + 1) == $maxRow) {
								break;
							} else {
								continue;
							}
						}

						$address_history = $this->address_history_model->get_by('client_id', $client_history->client_id);
						if (!$address_history) {
							array_push($errors, 'address not found at row : ' . ($key + 1));
							if (($key + 1) == $maxRow) {
								break;
							} else {
								continue;
							}
						}

						$contact_name = $cell[1];
						$contact_history = $this->contact_history_model->get_by(['name' => $contact_name, 'client_id' => $client_history->client_id]);
						if (!$contact_history) {
							array_push($errors, 'contact not found at row : ' . ($key + 1));
							if (($key + 1) == $maxRow) {
								break;
							} else {
								continue;
							}
						}

						$quote_type = $cell[2];
						if ($quote_type) {
							$quote_type = preg_replace('/\s+/', '', $quote_type);
							if ($quote_type != 'ISO' && $quote_type != 'Bizsafe' && $quote_type != 'Training') {
								array_push($errors, 'quote type unknown at row : ' . ($key + 1));
								continue;
							}
						}
						$quote_date = $this->convertExcelDate($cell[3]);
						// $quote_date = $cell[3];
						$referred_by = $cell[4];
						$invoice_to = $cell[5];
						$group_company = $cell[6];
						$terms = $cell[7];
						$status = $cell[35];
						if (!$status) {
							$status = 'New';
						}
						$insertQuotationData['number'] = $cell[34];
						$insertQuotationData['type'] = $quote_type;
						$insertQuotationData['status'] = $status;
						$insertQuotationData['client_history_id'] = $client_history->id;
						$insertQuotationData['contact_history_id'] = $contact_history->id;
						$insertQuotationData['address_history_id'] = $address_history->id;
						$insertQuotationData['quote_date'] = $quote_date;
						$insertQuotationData['referred_by'] = $referred_by;
						$insertQuotationData['invoice_to'] = $invoice_to;
						$insertQuotationData['num_of_sites'] = $cell[16] ? $cell[16] : 0;
						$insertQuotationData['scope'] = $cell[17];
						$insertQuotationData['terms_and_conditions'] = $terms;

						$certification_cycle = $cell[8];
						$certification_cycle = $this->certification_cycle_model->get_by('name', $certification_cycle);
						if (!$certification_cycle) {
							array_push($errors, 'certification cycle not found at row : ' . ($key + 1));
							continue;
						}
						$insertQuotationData['certification_cycle'] = $certification_cycle->id;

						if ($quote_type == 'ISO') {
							$insertQuotationData['application_form'] = $cell[9];
							$insertQuotationData['received_prev_reports'] = $cell[10];
							$insertQuotationData['prev_cert_issue_date'] = $cell[11];
							$insertQuotationData['prev_cert_exp_date'] = $cell[12];
							$insertQuotationData['prev_certification_scheme'] = $cell[13];
							$insertQuotationData['prev_accreditation'] = $cell[14];
							$insertQuotationData['prev_certification_body'] = $cell[15];
							$insertQuotationData['consultant_pay_3_years'] = $cell[31];
							$insertQuotationData['client_pay_3_years'] = $cell[32];

							$stage_audit = $cell[22];
							$insertQuotationData['stage_audit'] = $stage_audit ? $this->getAmount($stage_audit) : 0;

							$surveillance_year_1 = $cell[23];
							$insertQuotationData['surveillance_year_1'] = $surveillance_year_1 ? $this->getAmount($surveillance_year_1) : 0;

							$surveillance_year_2 = $cell[24];
							$insertQuotationData['surveillance_year_2'] = $surveillance_year_2 ? $this->getAmount($surveillance_year_2) : 0;

							$insertQuotationData['transportation'] = $cell[30];
						}

						if ($quote_type == 'ISO' || $quote_type == 'Bizsafe') {
							$certification_scheme = $cell[18];
							if ($certification_scheme) {
								$certification_scheme_arr = explode(',', preg_replace('/\s*,\s*/', ',', $certification_scheme));
								$scheme_arr = [];
								foreach ($certification_scheme_arr as $value) {
									$scheme = $this->certification_scheme_model->select('id')->get_by('name', $value);
									if ($scheme) {
										array_push($scheme_arr, $scheme->id);
									}
								}
								$insertQuotationData['certification_scheme'] = implode(',', $scheme_arr);
							} else {
								$insertQuotationData['certification_scheme'] = '';
							}

							$accreditation = $cell[19];
							if ($accreditation) {
								$accreditation_arr = explode(',', preg_replace('/\s*,\s*/', ',', $accreditation));
								$acc_id_arr = [];
								foreach ($certification_scheme_arr as $key => $value) {
									if (!array_key_exists($key, $accreditation_arr) || $accreditation_arr[$key] == '*') {
										$acc_id = 0;
									} else {
										$acc = $accreditation_arr[$key];
										$acc = $this->accreditation_model->select('id')->get_by('name', ltrim($acc));
										if (!$acc) {
											$acc_id = 0;
										} else {
											$acc_id = $acc->id;
										}
									}
									array_push($acc_id_arr, $acc_id);
								}
								$combined_accreditation_str = implode(',', $acc_id_arr);
								$insertQuotationData['accreditation'] = implode(',', $acc_id_arr);
							} else {
								$insertQuotationData['accreditation'] = '';
							}
						}

						if ($quote_type == 'Bizsafe') {
							$audit_fee = $cell[25];
							$insertQuotationData['audit_fee'] = $audit_fee ? $this->getAmount($audit_fee) : 0;
						}

						if ($quote_type == 'Training') {

							$training_type = $cell[20];
							if ($training_type) {
								$training_type_arr = explode(',', preg_replace('/\s*,\s*/', ',', $training_type));
								$training_type_arr = [];
								foreach ($training_type_arr as $value) {
									$type = $this->certification_scheme_model->select('id')->get_by('name', $value);
									if ($type) {
										array_push($training_type_arr, $type->id);
									}
								}
								$insertQuotationData['training_type'] = implode(',', $training_type_arr);
							} else {
								$insertQuotationData['training_type'] = '';
							}

							$insertQuotationData['training_description'] = $cell[21];
							$total_amount = $cell[26];
							$insertQuotationData['total_amount'] = $total_amount ? $this->getAmount($total_amount) : 0;

							$discount = $cell[27];
							$insertQuotationData['discount'] = $discount ? $this->getAmount($discount) : 0;

							$insertQuotationData['payment_terms'] = $cell[28];
							$insertQuotationData['duration'] = $cell[29];
							$insertQuotationData['transportation'] = $cell[30];
						}

						if(in_array($insertQuotationData['certification_cycle'], [1, 4, 5])) {
							$year_cycle = 3;
						} elseif ($insertQuotationData['certification_cycle'] == 2) {
							$year_cycle = 2;
						} else {
							$year_cycle = 1;
						}
						$insertQuotationData['year_cycle'] = $year_cycle;
						$insertQuotationData['created_on'] = strtotime($quote_date);
						$insertQuotationData['created_by'] = 1;
						$confirmed_on = $cell[36];
						if ($confirmed_on) {
							// $insertQuotationData['confirmed_on'] = strtotime($this->convertExcelDate($confirmed_on));
							$insertQuotationData['confirmed_on'] = strtotime($confirmed_on);
							$insertQuotationData['confirmed_by'] = 1;
						}
						$this->db->insert('quotation', $insertQuotationData);
						$quotation_id = $this->db->insert_id();
						$insertQuotationData['quotation_id'] = $quotation_id;
						$this->db->insert('quotation_history', $insertQuotationData);
					}

					$this->clearingQuotationData();

					$this->updateClientStatus();

					if ($errors) {
						dd($errors);
					}

					$this->set_alert('Quotation data imported', 'success', true);
					redirect('import/quotation', 'refresh');
				}
			} else {
				$this->set_alert('Only file with .xlsx format allowed', 'error', true);
				redirect('client', 'refresh');
			}
		}
    }

    public function convertExcelDate($date) {
        // $unixTimestamp = ($date - 25569) * 86400; // Convert Excel date to Unix timestamp
        // $phpDate = date('Y-m-d', $unixTimestamp);
        $phpDate = date('Y-m-d', strtotime($date));
        return $phpDate;
    }

	public function updateClientStatus() {
        $quotations = $this->db
            ->select('quotation.id, client.id as client_id, client.name as client_name, client.status as client_sttus, quotation.number') // Replace 'client_column' with the actual column you want from the client table
            ->from('quotation')
            ->join('client_history', 'quotation.client_history_id = client_history.id')
            ->join('client', 'client_history.client_id = client.id')
            ->get()
            ->result_array();
        foreach ($quotations as $quotation) {
            $this->client_model->set_client_id($quotation['client_id']);
    		$this->client_model->update_status($quotation['client_id']);
		}
		return true;
	}

	public function clearingQuotationData() {
		$this->db->set('STATUS', 'On-Hold');
		$this->db->where_in('STATUS', ['Doing bizsafe on their own', 'On Hold']);
		$this->db->update('quotation');

		$this->db->set('STATUS', 'Chosen Other CB');
		$this->db->where_in('STATUS', ['Chosen d', 'Chose other CB', 'Chosen other Org.', 'Chosen other CB']);
		$this->db->update('quotation');

		$this->db->set('STATUS', 'Confirmed');
		$this->db->where_in('STATUS', ['Confimed', 'Confrimed']);
		$this->db->update('quotation');

		$this->db->set('STATUS', 'Dropped by ASA');
		$this->db->where('STATUS', 'Dropped');
		$this->db->update('quotation');

		// Update for 'quotation_history' table
		$this->db->set('STATUS', 'On-Hold');
		$this->db->where_in('STATUS', ['Doing bizsafe on their own', 'On Hold']);
		$this->db->update('quotation_history');

		$this->db->set('STATUS', 'Chosen Other CB');
		$this->db->where_in('STATUS', ['Chosen d', 'Chose other CB', 'Chosen other Org.', 'Chosen other CB']);
		$this->db->update('quotation_history');

		$this->db->set('STATUS', 'Confirmed');
		$this->db->where_in('STATUS', ['Confimed', 'Confrimed']);
		$this->db->update('quotation_history');

		$this->db->set('STATUS', 'Dropped by ASA');
		$this->db->where('STATUS', 'Dropped');
		$this->db->update('quotation_history');
		return true;
	}

}
