<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Application_Form extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
		$this->load->model('application_form_model');
		$this->load->library(['form_validation']);
    }

	public function delete_post() {
		$post = $this->post();
		$deleted = $this->application_form_model->delete($post['application_id']);
		if ($deleted) {
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}


	public function get_like_post()
    {
        $key = $this->post("key");
		$applications = $this->application_form_model->get_like($key);

        if ($applications) {
			$res = [];
			foreach ($applications as $row) {
				array_push($res, [
					'label'	=> $row->number . ' (' . $row->client_name . ') ',
					'value'	=> $row->number
				]);
			}
			$this->response($res, REST_Controller::HTTP_OK);
        }

        $this->response([], REST_Controller::HTTP_OK);
    }


}
