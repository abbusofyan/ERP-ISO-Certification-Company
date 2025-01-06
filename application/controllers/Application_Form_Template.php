<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Application_Form_Template extends WebimpController
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
        'application_form',
		'application_form_template',
		'file'
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

		$this->load->library(['form_validation', 'auth']);
		// $this->auth->route_access();

        $this->load->helper('download');

        $this->data['page']['title'] = 'Application Form Template';
        $this->add_breadcrumb('Application Form Template');
    }


	public function index() {
		$this->form_validation->set_rules('file', '', 'callback_file_check');
		$this->form_validation->set_rules('name', 'Template Name', 'required');
		$this->form_validation->set_message('required', "%s can't be empty");

		if ($this->form_validation->run()) {
			if ( (isset($_FILES['file'])) && ($_FILES['file']['size'] > 0) ) {
				$file_id = $this->file_model->process_uploaded_file($_FILES['file'], 'application_form_template');

				if ($file_id) {
					$post = $this->input->post();
					$template_id = $this->application_form_template_model->insert([
						'name' => $post['name'],
						'file_id'	=> $file_id,
						'notes'		=> $post['notes']
					]);

					if ($template_id) {
						$this->set_alert("New application form template uploaded.", 'success', true);
						redirect('application-form-template', 'refresh');
					} else {
						$this->set_alert("Upload application form template failed!", 'error');
						redirect('application-form-template', 'refresh');
					}
				}
			}
		}
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts', 'pdf_scripts']);
	}


	public function download($id) {
		$template = $this->application_form_template_model->with('file')->get($id);
		$path = file_get_contents($template->file->url);
		$name = $template->file->filename;
		force_download($name, $path);
	}


	public function file_check($str){
        $allowed_mime_type_arr = array('application/pdf','image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');
        $mime = get_mime_by_extension($_FILES['file']['name']);
        if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=""){
			return true;
        }else{
            $this->form_validation->set_message('file_check', 'Please choose a file to upload.');
            return false;
        }
    }


}
