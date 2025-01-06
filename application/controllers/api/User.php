<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class User extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->model('user_model');
    }


	public function delete_post() {
		$post = $this->post();
		$deleted = $this->user_model->delete($post['user_id']);
		if ($deleted) {
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}
}
