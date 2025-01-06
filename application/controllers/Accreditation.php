<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accreditation extends WebimpController
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
		'user'
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

        $this->data['page']['title'] = 'Accreditation';
        $this->add_breadcrumb('Certification_Scheme');
    }



	public function index()
	{
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

		$this->data['form'] = array_merge(
			$this->_generate_fields(),
			[
				'action' => site_url('accreditation/store'),
			]
		);
    }


	public function store() {
		$post = $this->input->post();

		$inserted_id = $this->accreditation_model->insert($post);

		if ($inserted_id) {
			$this->set_alert('Accreditation created!', 'success', true);
			redirect('accreditation', 'refresh');
		} else {
			$this->set_alert("Can't create accreditation.", 'error');
			redirect('accreditation', 'refresh');
		}
	}



	public function update($id) {
		$post = $this->input->post();
		$updated = $this->accreditation_model->update($id, $post);
		if ($updated) {
			$this->set_alert('Saved Successfully!', 'success', true);
			redirect('accreditation', 'refresh');
		} else {
			$this->set_alert("Can't update accreditation.", 'error');
			redirect('accreditation', 'refresh');
		}

	}



	public function delete($id) {
		$deleted = $this->accreditation_model->delete($id);
		if ($deleted) {
			$this->set_alert('Accreditation deleted!', 'success', true);
			redirect('accreditation', 'refresh');
		} else {
			$this->set_alert("Can't delete accreditation.", 'error');
			redirect('accreditation', 'refresh');
		}
	}






	/**
	 * Fields for client form.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _generate_fields($data = null)
	{
		$data = parse_args($data, [
			'name'         => '',
		]);

		$output = [
			'name' => [
				'id'          => 'name',
				'name'        => 'name',
				'class'       => 'form-control',
				'required'    => true,
				'value'       => set_value('name', $data['name']),
			],
		];

		return $output;
	}

}
