<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends WebimpController
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
        'accreditation',
		'user',
		'permission',
		'module'
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

        $this->data['page']['title'] = 'User Permission';
        $this->add_breadcrumb('Certification_Scheme');
    }



	public function store() {
		$methods = ['create', 'read', 'update', 'delete'];

		$post = $this->input->post();
		$module_id = $this->module_model->insert($post);
		if ($module_id) {
			foreach ($methods as $method) {
				$module_name = $post['name'];
				$module_slug = str_replace(" ", "-", strtolower($module_name));

				$new_permission = $this->permission_model->insert([
					'module_id'	=> $module_id,
					'name' 		=> $method.'-'.$module_slug,
					'display_name' => ucfirst($method),
					'status' 	=> 1,
				]);
			}
			$this->set_alert('Permission Added.', 'success', true);
			redirect('user/group', 'refresh');
		}
	}

}
