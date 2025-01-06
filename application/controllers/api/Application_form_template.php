<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Application_Form_Template extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('file_model');
        $this->load->model('application_form_template_model');
    }


    public function get_by_id_post() {
        $id = $this->post('template_id');
        $application_form_template = $this->application_form_template_model->get($id);
        $pdf = $this->file_model->get($application_form_template->file_id);
		$this->response($pdf, REST_Controller::HTTP_OK);
    }


    public function delete_post()
    {
        // post
        $id = $this->post("template_id");

        if ($id > 0) {
            // get client details
            $template = $this->application_form_template_model->with('file')->get($id);
			unlink($template->file->path);
            if (!empty($template)) {
                // delete
                $deleted = $this->application_form_template_model->delete($template->id);

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
