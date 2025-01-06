<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Group extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('group_permission_model');
		$this->load->model('group_model');
		$this->load->model('permission_model');
    }

	public function get_permission_post() {
		$post = $this->post();
		$group = $this->group_model->with('permissions')->get($post['group_id']);
		$group->created_on = human_timestamp($group->created_on);
		if ($group) {
			$this->response($group, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	public function delete_post() {
		$post = $this->post();
		$deleted = $this->group_model->delete($post['group_id']);
		if ($deleted) {
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}
}
