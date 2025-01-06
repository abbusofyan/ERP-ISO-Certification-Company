<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Certification_scheme extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->model('certification_scheme_model');
		$this->load->library('form_validation');
		$this->load->library('session');
    }


	public function get_post()
	{
		$post = $this->input->post();
		$output = [];

		$certification_scheme = $this->certification_scheme_model->get($post['certification_scheme_id']);

		$this->response($certification_scheme, REST_Controller::HTTP_OK);
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

	public function certification_scheme($name) {
		if (preg_match('/^[^a-zA-Z0-9\s]+$/', $name)) {
			return false; // error if all char is special char
		} else {
			return true;
		}
	}


	public function is_unique_update() {
		$post = $this->input->post();
		$certification_scheme = $this->certification_scheme_model->get($post['id']);
		if($certification_scheme->name == $post['name']) {
			return true;
		} else {
			$certification_scheme = $this->certification_scheme_model->get_by('name', $post['name']);
			if($certification_scheme) {
				return false;
			}
			return true;
		}
	}

	public function is_unique_create() {
		$post = $this->input->post();
		$certification_scheme = $this->certification_scheme_model->get_by('name', $post['name']);
		if($certification_scheme) {
			return false;
		}
		return true;
	}

    /**
     * Delete certification scheme.
     *
     * @access public
     * @return json
     */
    public function delete_post()
    {
        // post
        $scheme_id = $this->post("certification_scheme_id");

        if ($scheme_id > 0) {
            $scheme = $this->certification_scheme_model->get($scheme_id);

            if (!empty($scheme)) {
                // delete
                $deleted = $this->certification_scheme_model->delete($scheme->id);

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
