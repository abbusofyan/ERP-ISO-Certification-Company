<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_Scheme extends WebimpController
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
        'certification_scheme',
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

        $this->data['page']['title'] = 'Certification Scheme';
        $this->add_breadcrumb('Certification_Scheme');
    }


	public function index()
	{
        $this->add_script(['datatable_scripts', 'sweetalert_scripts', 'form_scripts']);

		$this->data['form'] = array_merge(
			$this->_generate_fields(),
			[
				'action' => site_url('certification-scheme/store'),
			]
		);
    }


	public function store() {
		$post = $this->input->post();
		$inserted_id = $this->certification_scheme_model->insert($post);

		if ($inserted_id) {
			$this->set_alert('Certification scheme created!', 'success', true);
			redirect('certification-scheme', 'refresh');
		} else {
			$this->set_alert("Can't create certification scheme.", 'error');
			redirect('certification-scheme', 'refresh');
		}
	}


	public function update($id) {
		$this->form_validation->set_rules($this->_validation_rules());
		$post = $this->input->post();
		$updated = $this->certification_scheme_model->update($id, $post);
		if ($updated) {
			$this->set_alert('Saved Successfully!', 'success', true);
			redirect('certification-scheme', 'refresh');
		} else {
			$this->set_alert("Can't update certification scheme.", 'error');
			redirect('certification-scheme', 'refresh');
		}
	}



	public function delete($id) {
		$deleted = $this->certification_scheme_model->delete($id);
		if ($deleted) {
			$this->set_alert('Certification scheme deleted!', 'success', true);
			redirect('certification-scheme', 'refresh');
		} else {
			$this->set_alert("Can't delete certification scheme.", 'error');
			redirect('certification-scheme', 'refresh');
		}
	}




    protected function _validation_rules()
    {
        $rules = [
            [
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|max_length[200]|is_unique[certification_scheme.name]|callback_certification_scheme_name',
				'errors' => array(
	                    'is_unique' => "Can't save the same Certification Scheme",
						'certification_scheme_name' => "Can't accept ONLY special characters",
	            ),
			],
        ];


        return $rules;
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
