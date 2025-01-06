<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends WebimpController
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
        'client',
		'client_history',
		'address_history',
		'contact',
        'file',
		'address',
		'country'
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

        $this->data['page']['title'] = 'Address';
        $this->add_breadcrumb('Client');
    }



	/**
	 * Edit client form
	 *
	 * @access public
	 * @param int $client_id
	 * @return void
	 */
	public function edit($address_id = null)
	{

		if ($address_id) {

			$address = $this->address_model->get($address_id);

			$this->form_validation->set_rules($this->_validation_edit_address_rules());

			if ($this->form_validation->run()) {

				$post = $this->input->post();

				$this->address_model->set_address_id($address_id);

				$updated = $this->address_model->update($address_id, $post);

				if ($updated) {
					$this->set_alert('Address successfully updated!', 'success', true);
					redirect('client/view/'.$address->client_id, 'refresh');
				} else {
					$this->set_alert("Can't update address.", 'error');
					redirect('client/view/'.$address->client_id, 'refresh');
				}
			} else {
				$this->set_alert(validation_errors(), 'error');
			}
		} else {
			redirect('client', 'refresh');
		}
	}

	public function store() {
		$post = $this->input->post();
		$this->form_validation->set_message('required', 'Please enter %s');
		if ($this->form_validation->run()) {
			$post['primary'] = 0;
			$address_id = $this->address_model->insert($post);
			if ($address_id) {
				$this->set_alert('Address successfully created!', 'success', true);
				redirect('client/view/' . $post['client_id'], 'refresh');
			} else {
				$this->set_alert("Can't create address.", 'error', true);
				redirect('client/view/' . $post['client_id'], 'refresh');
			}
		} else {
			$this->set_alert(validation_errors(), 'error', true);
			redirect('client/view/' . $post['client_id'], 'refresh');
		}
	}

	protected function _validation_edit_address_rules()
	{
		$rules = [
			[
				'field' => 'address',
				'label' => 'Address',
				'rules' => 'trim|required',
			],
			[
				'field' => 'postal_code',
				'label' => 'Postal Code',
				'rules' => 'trim|required',
			],
			[
				'field' => 'country',
				'label' => 'Country',
				'rules' => 'trim|required',
			],
		];


		return $rules;
	}

}
