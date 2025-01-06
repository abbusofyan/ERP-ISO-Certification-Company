<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends WebimpController
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
		'group',
		'group_permission'
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

        $this->data['page']['title'] = 'User Group';
        $this->add_breadcrumb('Certification_Scheme');
    }



	public function store() {
		$post = $this->input->post();
		if (!array_key_exists('permission_id', $post)) {
			$this->set_alert('Select the permissions for the role.', 'error', true);
			redirect('user/role', 'refresh');
		}
		$group_id = $this->group_model->insert($post);

		if ($group_id) {
			$permissions = $post['permission_id'];
			foreach ($permissions as $permission_id) {
				if ($permission_id != 'all') {
					$this->group_permission_model->insert([
						'group_id'		=> $group_id,
						'permission_id'	=> $permission_id,
					]);
				}
			}

			$this->set_alert('Role Added.', 'success', true);
			redirect('user/role', 'refresh');
		}
	}


	public function update($id) {
		$post = $this->input->post();
		if (!array_key_exists('permission_id', $post)) {
			$this->set_alert('Select the permissions for the role.', 'error', true);
			redirect('user/role', 'refresh');
		}
		$updated = $this->group_model->update($id, $post);
		if ($updated) {
			$permissions = $post['permission_id'];
			$this->group_permission_model->delete_by('group_id', $id);
			foreach ($permissions as $permission_id) {
				if ($permission_id != 'all') {
					$this->group_permission_model->insert([
						'group_id'		=> $id,
						'permission_id'	=> $permission_id,
					]);
				}
			}
			$this->set_alert('Role Updated.', 'success', true);
			redirect('user/role', 'refresh');
		}
	}

}
