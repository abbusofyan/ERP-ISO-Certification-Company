<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Application_Follow_Up extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

		header('Access-Control-Allow-Origin: *');

        $this->load->model('user_model');
		$this->load->model('application_follow_up_model');
        $this->load->model('application_follow_up_attachment_model');
    }


	public function get_latest_attachment_by_id_post() {
		$post = $this->input->post();
		$attachments = $this->application_follow_up_attachment_model->with('file')->get_many_by('follow_up_id', $post['follow_up_id']);
		if ($attachments) {
			foreach ($attachments as $key => $attachment) {
				$attachments[$key]->file->created_on = human_timestamp($attachment->file->created_on);
			}
			$this->response($attachments, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	public function get_attachments() {
		$post = $this->input->post();
		$attachment = $this->application_follow_up_attachment_model->with('file')->get_by('follow_up_id', $post['follow_up_id']);
		if ($attachment) {
			$this->response($attachment->file, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}

	public function delete_post() {
		$post = $this->post();
		$deleted = $this->application_follow_up_model->delete($post['follow_up_id']);
		if ($deleted) {
			$this->response(true, REST_Controller::HTTP_OK);
		} else {
			$this->response(false, REST_Controller::HTTP_OK);
		}
	}


}
