<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Accreditation extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->model('accreditation_model');
		$this->load->library('form_validation');
		$this->load->library('session');
	}



	public function get_post() {
		$post = $this->input->post();
		$accreditation_id = $post['accreditation_id'];
		$accreditation = $this->accreditation_model->get($accreditation_id);
		if ($accreditation) {
			$this->response($accreditation, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}


	public function validate_create_post() {
		if ($this->form_validation->run()) {
			$response = [
				'status' => true,
				'data' => ''
			];
			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}

	public function validate_update_post() {
		if ($this->form_validation->run()) {
			$response = [
				'status' => true,
				'data' => ''
			];
			$this->response($response, REST_Controller::HTTP_OK);
		} else {
			$response = [
				'status' => false,
				'data' => validation_errors()
			];
			$this->response($response, REST_Controller::HTTP_OK);
		}
	}

	public function accreditation_name($name) {
		if (preg_match('/^[^a-zA-Z0-9\s]+$/', $name)) {
			return false; // error if all char is special char
		} else {
			return true;
		}
	}

	public function is_unique_update() {
		$post = $this->input->post();
		$accreditation = $this->accreditation_model->get($post['id']);
		if($accreditation->name == $post['name']) {
			return true;
		} else {
			$accreditation = $this->accreditation_model->get_by('name', $post['name']);
			if($accreditation) {
				return false;
			}
			return true;
		}
	}

	public function is_unique_create() {
		$post = $this->input->post();
		$accreditation = $this->accreditation_model->get_by('name', $post['name']);
		if($accreditation) {
			return false;
		}
		return true;
	}

    public function delete_post()
    {
        // post
        $accreditation_id = $this->post("accreditation_id");

        if ($accreditation_id > 0) {
            $accreditation = $this->accreditation_model->get($accreditation_id);

            if (!empty($accreditation)) {
                // delete
                $deleted = $this->accreditation_model->delete($accreditation->id);

                if ($deleted) {
                    $this->response(true, REST_Controller::HTTP_OK);
                } else {
                    $this->response(false, REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(false, REST_Controller::HTTP_OK);
            }
        }

        $this->response(false, REST_Controller::HTTP_OK);
    }


}
