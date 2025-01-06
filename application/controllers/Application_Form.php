<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Application_Form extends WebimpController
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
        'file',
		'client',
		'send_quotation_status',
		'certification_scheme',
		'application_follow_up',
		'application_follow_up_attachment'
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
		$this->auth->route_access();

        $this->load->helper('download');

        $this->data['page']['title'] = 'Application Form';
        $this->add_breadcrumb('Application Form');
    }

	public function set_as_primary($id) {
		$this->address_model->update($id, );
	}


	public function index() {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts', 'pdf_scripts']);

		$latest_application_form_template = $this->application_form_template_model->order_by('id', 'DESC')->limit(1)->with('file')->get_all();
		if ($latest_application_form_template) {
			$this->data['template_id'] = $latest_application_form_template[0]->id;
		} else {
			$this->data['template_id'] = 0;
		}
	}


	public function create() {
		$post = $this->input->post();
		if ($this->form_validation->run()) {
			$application_id = $this->application_form_model->insert($post);
			if ($application_id) {
				$post['application_id'] = $application_id;
				if ($post['notes'] || $_FILES['attachment']['size'][0]) {
					$follow_up_id = $this->application_follow_up_model->insert($post);
					if ( (isset($_FILES['attachment'])) && ($_FILES['attachment']['size'] > 0) ) {
						foreach ($_FILES['attachment']['name'] as $key => $upload) {
							$file['name'] = $_FILES['attachment']['name'][$key];
							$file['type'] = $_FILES['attachment']['type'][$key];
							$file['tmp_name'] = $_FILES['attachment']['tmp_name'][$key];
							$file['error'] = $_FILES['attachment']['error'][$key];
							$file['size'] = $_FILES['attachment']['size'][$key];

							$file_id = $this->file_model->process_uploaded_file($file, 'application_follow_up_attachment');
							if ($file_id) {
								$this->application_follow_up_attachment_model->insert([
									'file_id'	=> $file_id,
									'application_id'	=> $application_id,
									'follow_up_id'		=> $follow_up_id
								]);
							}
						}
					}
				}
				$this->set_alert("Application Created!", 'success', true);
				redirect('application-form', 'refresh');
			} else {
				$this->set_alert("Can't create application form!", 'error');
				redirect('application-form/create', 'refresh');
			}
		}
		if($post && isset($post['standard'])) {
			$this->data['post_standard'] = $post['standard'];
		}
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['validation_errors'] = $this->session->flashdata('validation_errors');

		$this->data['clients'] = $clients = $this->client_model->get_all();
		$this->data['certification_scheme'] = $this->certification_scheme_model->get_all();
		$this->data['status_send'] = $this->send_quotation_status_model->get_all();
	}


	public function store() {
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$application_id = $this->application_form_model->insert($post);
			if ($application_id) {
				$post['application_id'] = $application_id;
				if ($post['notes'] || $post['clarification_date']) {
					$follow_up_id = $this->application_follow_up_model->insert($post);
					if ( (isset($_FILES['attachment'])) && ($_FILES['attachment']['size'] > 0) ) {
						foreach ($_FILES['attachment']['name'] as $key => $upload) {
							$file['name'] = $_FILES['attachment']['name'][$key];
							$file['type'] = $_FILES['attachment']['type'][$key];
							$file['tmp_name'] = $_FILES['attachment']['tmp_name'][$key];
							$file['error'] = $_FILES['attachment']['error'][$key];
							$file['size'] = $_FILES['attachment']['size'][$key];

							$file_id = $this->file_model->process_uploaded_file($file, 'application_follow_up_attachment');
							if ($file_id) {
								$this->application_follow_up_attachment_model->insert([
									'file_id'	=> $file_id,
									'application_id'	=> $application_id,
									'follow_up_id'		=> $follow_up_id
								]);
							}
						}
					}
				}
				$this->set_alert("Application Created!", 'success', true);
				redirect('application-form', 'refresh');
			} else {
				$this->set_alert("Can't create application form!", 'error');
				redirect('application-form/create', 'refresh');
			}
		}
		$this->session->set_flashdata('validation_errors', validation_errors());
		// $this->set_alert(validation_errors(), 'error', true);
		redirect('application-form/create');
	}



	public function template() {
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts', 'pdf_scripts']);
		$this->data['form'] = array_merge(
			[
				'action' => site_url('client'),
				'action_main_contact' => site_url('client/change_main_contact'),
			]
		);
	}


	public function upload_template() {
		if ( (isset($_FILES['file'])) && ($_FILES['file']['size'] > 0) ) {
			$file_id = $this->file_model->process_uploaded_file($_FILES['file'], 'application_form_template');

			if ($file_id) {
				$post = $this->input->post();

				$template_id = $this->application_form_template_model->insert([
					'name' => $post['name'],
					'file_id'	=> $file_id
				]);

				if ($template_id) {
					$this->set_alert("New application form template uploaded.", 'success', true);
					redirect('application-form/template', 'refresh');
				} else {
					$this->set_alert("Upload application form template failed!", 'error');
					redirect('application-form/template', 'refresh');
				}
			}
		}
	}


	public function download_template($id) {
		$template = $this->application_form_template_model->with('file')->get($id);
		$path = file_get_contents($template->file->url);
		$name = $template->name.'.pdf';
		force_download($name, $path);
	}


	public function view($id) {
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts', 'pdf_scripts']);
		$this->data['application'] = $this->application_form_model->get($id);
		$this->data['follow_up'] = $follow_up = $this->application_follow_up_model->order_by('id', 'DESC')->with('user')->with('attachment')->get_many_by('application_id', $id);
    }


	public function edit($id) {
		if ($this->form_validation->run()) {
			$post = $this->input->post();
			$post['application_id'] = $id;
			if ($_FILES['attachment']['name'][0] || $post['notes']) {
				$follow_up_id = $this->application_follow_up_model->insert($post);
				if ($follow_up_id) {
					if ( (isset($_FILES['attachment'])) && ($_FILES['attachment']['size'] > 0) ) {
						foreach ($_FILES['attachment']['name'] as $key => $upload) {
							$file['name'] = $_FILES['attachment']['name'][$key];
							$file['type'] = $_FILES['attachment']['type'][$key];
							$file['tmp_name'] = $_FILES['attachment']['tmp_name'][$key];
							$file['error'] = $_FILES['attachment']['error'][$key];
							$file['size'] = $_FILES['attachment']['size'][$key];

							$file_id = $this->file_model->process_uploaded_file($file, 'application_follow_up_attachment');
							if ($file_id) {
								$this->application_follow_up_attachment_model->insert([
									'file_id'	=> $file_id,
									'application_id'	=> $id,
									'follow_up_id'		=> $follow_up_id
								]);
							}
						}
					}
				}
			}
			$updated = $this->application_form_model->update($id, $post);
			if ($updated) {
				$this->set_alert("Application Updated.", 'success', true);
				redirect('application-form', 'refresh');
			} else {
				$this->set_alert("Can't edit application!", 'error');
				redirect('application-form/edit/'.$id, 'refresh');
			}
		}
		$this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);
		$this->data['clients'] = $clients = $this->client_model->get_all();
		$this->data['certification_scheme'] = $this->certification_scheme_model->get_all();
		$this->data['follow_up'] = $follow_up = $this->application_follow_up_model->order_by('id', 'DESC')->with('user')->get_many_by('application_id', $id);
		$this->data['status_send'] = $this->send_quotation_status_model->get_all();
		$this->data['application'] = $this->application_form_model->with('client')->get($id);
	}


	public function update($id) {

	}


}
