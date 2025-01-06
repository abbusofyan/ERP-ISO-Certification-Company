<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends WebimpController
{

	public $models = [
        'user',
		'address',
        'client',
		'client_history',
		'contact_history',
		'contact',
        'file',
		'client_note',
		'quotation',
    ];

	public $errors = [];

	public function __construct()
	{
        parent::__construct();

        $this->add_breadcrumb('Client');
    }

	public function ip()
	{
		echo 'HTTP_X_FORWARDED_FOR = ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
		echo '<br>';
		echo 'REMOTE_ADDR' . $_SERVER['REMOTE_ADDR'];
		die;
    }

	public function fixDuplication() {
		$clients = [
		    "Ecoclean Maintenance Pte Ltd",
		    "Nash Engineering & Construction Pte Ltd",
		    "OSLYN (S.E.A.) Pte Ltd",
		    "R S 108 Global Private Limited",
		    "SAT Group (S) Pte Ltd",
		    "Siemens Logistics Pte Ltd",
			"Standard Piping Service Pte Ltd",
		    "Triple 'S' Protection Pte Ltd",
		    "LAP Engineering (Singapore) Pte Ltd",
		    "Supreme Security Agency Pte Ltd",
			"Airfresh Building Services Pte Ltd",
			"Amara Sanctuary Resort Sentosa",
			"E-Technik Synergie Pte Ltd",
			"Tong Men Technology Pte Ltd ",
			"Wee Meng Construction Engrg. Pte Ltd",
			"P & P Engineering & Construction Pte Ltd",
			"Hock Tat Polythene Pte Ltd",
			"Singapore Hospitality Group Pte Ltd"
		];

		$companiesToDelete = [
		    "Eco Clean Maintenance Pte Ltd",
		    "Nash Engineering and Construction Pte Ltd",
		    "OSLYN (S.E.A) Pte Ltd",
		    "R S 108 Global Pte Ltd",
		    "SAT Group(S) Pte Ltd",
		    "Siemens Logistics Pte. Ltd.",
			"Standard Piping Services Pte Ltd ",
		    "Triple ‘S’ Protection Pte Ltd",
		    "LAP Engineering (Singapore ) Pte Ltd ",
		    "Supreme Security Agency Pte. Ltd",
			"Airfresh Building Services Pte. Ltd.",
			"AMARA SANCTUARY & RESORT SENTOSA",
			"E-Technik Synergie Pte. Ltd",
			"Tong Men Technology Pte. Ltd.",
			"WEE MENG CONSTRUCTION ENGRG. PTE. LTD.",
			"P&P Engineering & Construction Pte Ltd",
			"Hock Tat Polythene (Private) Limited",
			"Singapore Hospitality Group Pte Ltd Branch I- Royal Palm"
		];

		foreach ($companiesToDelete as $key => $toDelete) {
			$clientToDelete = $this->client_model->with('contact')->with('address')->get_by('name', $toDelete);
			$clientToRetain = $this->client_model->get_by('name', $clients[$key]);
			if (!isset($clientToDelete)) {
				array_push($this->errors, 'client not found : ' . $toDelete);
				continue;
			}
			if (!isset($clientToRetain)) {
				array_push($this->errors, 'client to retail not found : ' . $clientToRetain);
				continue;
			}
			$this->syncContact($clientToDelete, $clientToRetain);
			$this->syncAddress($clientToDelete, $clientToRetain);
			$this->syncQuotation($clientToDelete, $clientToRetain);

			$this->client_model->delete($clientToDelete->id);
		}
		if ($this->errors) {
			dd($this->errors);
		} else {
			dd('Success');
		}
	}

	protected function syncContact($clientToDelete, $clientToRetain) {
		foreach ($clientToDelete->contact as $contactToDelete) {
			$contact = $this->contact_model->get_by([
				'client_id' => $clientToRetain->id,
				'name' => $contactToDelete->name,
			]);
			if (!$contact) {
				$contact = json_decode(json_encode($contactToDelete), true);
				$contact['client_id'] = $clientToRetain->id;
				$this->contact_model->set_contact_id($contactToDelete->id);
				$this->contact_model->update($contactToDelete->id, $contact);
			}
		}
	}

	protected function syncAddress($clientToDelete, $clientToRetain) {
		foreach ($clientToDelete->address as $addressToDelete) {
			$address = $this->address_model->get_by([
				'client_id' => $clientToRetain->id,
				'name' => $addressToDelete->name,
			]);
			if (!$address) {
				$address = json_decode(json_encode($addressToDelete), true);
				$address['client_id'] = $clientToRetain->id;
				$this->address_model->set_address_id($addressToDelete->id);
				$this->address_model->update($addressToDelete->id, $address);
			}
		}
	}

	protected function syncQuotation($clientToDelete, $clientToRetain) {
		$clientToDeleteHistory = $this->client_history_model->get_many_by('client_id', $clientToDelete->id);
		$clientToRetainHistory = $this->client_history_model->get_many_by('client_id', $clientToRetain->id);
		$clientToRetainHistory = $clientToRetainHistory[count($clientToRetainHistory) - 1];
		foreach ($clientToDeleteHistory as $clientHistory) {
			$quotations = $this->quotation_model->get_many_by('client_history_id', $clientHistory->id);
			foreach ($quotations as $quotation) {
				$contact = $this->contact_history_model->get($quotation->contact_history_id);
				$newContact = $this->contact_history_model->get_many_by([
					'name' => $contact->name,
					'client_id' => $clientToRetain->id
				]);
				if (!$newContact) {
					array_push($this->errors, 'contact not found : ' . $contact->name . ' client id : ' . $clientToRetain->id);
				}

				$address = $this->address_history_model->get($quotation->address_history_id);
				$newAddress = $this->address_history_model->get_many_by([
					'name' => $address->name,
					'client_id' => $clientToRetain->id
				]);
				if (!$newAddress) {
					array_push($this->errors, 'address not found : ' . $address->name . ' client id : ' . $clientToRetain->id);
				}

				$this->db->set([
					'client_history_id' => $clientToRetainHistory->id,
					'contact_history_id' => $newContact[0]->id,
					'address_history_id' => $newAddress[0]->id
				]);
				$this->db->where('id', $quotation->id);
				$this->db->update('quotation');

				$this->db->set([
					'client_history_id' => $clientToRetainHistory->id,
					'contact_history_id' => $newContact[0]->id,
					'address_history_id' => $newAddress[0]->id
				]);
				$this->db->where('id', $quotation->id);
				$this->db->update('quotation_history');

				$this->client_model->update_status($clientToRetain->id);
			}
		}
	}


}
