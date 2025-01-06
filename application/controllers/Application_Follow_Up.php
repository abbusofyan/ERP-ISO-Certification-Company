<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Application_Follow_Up extends WebimpController
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
		'file',
        'application_form',
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

        $this->load->library('form_validation');

        $this->load->helper('download');

        $this->data['page']['title'] = 'Application Follow Up';
        $this->add_breadcrumb('Application Form');
    }

	public function download_attachment($id) {
		$attachments = $this->application_follow_up_attachment_model->with('file')->with('application')->get_many_by('follow_up_id', $id);
		// $this->load->library('zip');
		// foreach($attachments as $attachment)
		// {	
		// 	$this->zip->read_file($attachment->file->path);

		// 	$application = $attachment->application;
		// 	$filename = 'Attachments_#' . $application->number . '_' . $application->client_name . '.zip';
		// }
		// $this->zip->download($filename . '.zip');
		
		if ($attachments) {
			$zip = new ZipArchive;
			$tmp_file = 'uploads/application_follow_up_attachment/temporary.zip';

			if ($zip->open($tmp_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
				die ("An error occurred creating your ZIP file.");
			}
			
			if ($zip->open($tmp_file,  ZipArchive::CREATE)) {
				foreach ($attachments as $attachment) {
					$incoming_file = $attachment->file->path;
					$dest_file_name= $attachment->file->filename;
					$zip->addFile($incoming_file, $dest_file_name);

					$application = $attachment->application;
					$filename = 'Attachments_#' . $application->number . '_' . $application->client_name . '.zip';
				}
				$zip->close();
				header('Content-disposition: attachment; filename='.$filename);
				header('Content-type: application/zip');
				readfile($tmp_file);
				if(file_exists($tmp_file)){
					unlink($tmp_file);
				}
			} else {
				$this->set_alert("Failed to download attachments", 'error', true);
				redirect('application-form', 'refresh');
			}
			$this->set_alert("Attachments downloaded", 'success', true);
			redirect('application-form', 'refresh');
		}
	}


	public function download_attachment_file($file_id) {
		$file = $this->file_model->get($file_id);
		$path = file_get_contents($file->url);
		$name = $file->filename;
		force_download($name, $path);
	}


	public function store() {
		$post = $this->input->post();
		$application_id = $post['application_id'];

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
							'application_id'	=> $application_id,
							'follow_up_id'		=> $follow_up_id
						]);
					}
				}

			}
			$this->set_alert("Follow Up Added", 'success', true);
			redirect('application-form/view/'.$application_id, 'refresh');
		}
		$this->set_alert("Can't Add Follow Up", 'error');
		redirect('application-form/view/'.$application_id, 'refresh');
	}


}
