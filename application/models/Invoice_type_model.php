<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_type_model extends WebimpModel
{
    protected $_table = '';

    public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
        $this->load->model('quotation_model');
    }


	public function get_by_quotation_cycle($cycle) {
		switch ($cycle) {
			case 'New':
				return [
					'Stage 1 & Stage 2 Audit',
					'1st Year Surveillance',
					'2nd Year Surveillance'
				];
				break;

			case 'Transfer 1st Year Surveillance':
				return [
					'1st Year Surveillance',
					'2nd Year Surveillance'
				];
				break;

			case 'Transfer 2nd Year Surveillance':
				return [
					'2nd Year Surveillance'
				];
				break;

			case 'Re-Audit':
				return [
					'Stage 2 Audit',
					'1st Year Surveillance',
					'2nd Year Surveillance'
				];
				break;

			case 'Re-Audit New':
				return [
					'Stage 1 & Stage 2 Audit',
					'1st Year Surveillance',
					'2nd Year Surveillance'
				];
				break;

			default:
				return [];
				break;
		}
	}


}
