<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Application_form_model extends WebimpModel
{
    protected $_table = 'application_form';

	protected $soft_delete = true;

	protected $before_create = ['_format_submission', '_record_created'];

	protected $before_update = ['_format_submission', '_record_updated'];

	protected $belongs_to = [
		'user' => [
            'primary_key' => 'created_by',
            'model'       => 'user_model',
        ],
    ];

	protected $has_many = [
        'follow_up' => [
            'primary_key' => 'application_id',
            'model'       => 'application_follow_up_model',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }


	protected function _format_submission($post)
    {
		$data = [];
		if (!array_key_exists('application_id', $post)) {
			$data['number'] = $this->generate_new_number();
		}

		if (array_key_exists('client_name', $post)) {
			$data['client_name'] = $post['client_name'];
		}

		if (array_key_exists('standard', $post)) {
			$data['standard'] = json_encode($post['standard']);
		}

		if (array_key_exists('send_quotation_status', $post)) {
			$data['send_quotation_status'] = $post['send_quotation_status'];
		}

		if (array_key_exists('send_date', $post)) {
			$data['send_date'] = $post['send_date'];
		}

		if (array_key_exists('receive_date', $post)) {
			$data['receive_date'] = $post['receive_date'];
		}

		$this->application_form = $data;
        return $data;
    }


	protected function _record_created($post)
	{
		$current_user = $this->session->userdata();

		$post['created_by'] = $current_user['user_id'];
		$post['created_on'] = time();

		return $post;
	}


	protected function _record_updated($post)
	{
		$current_user = $this->session->userdata();

		$post['updated_by'] = $current_user['user_id'];
		$post['updated_on'] = time();

		return $post;
	}



	public function generate_new_number() {
		$year = date('y');
		$number = 1;

		$last_application = $this->order_by('id', 'DESC')->get_by([
			'FROM_UNIXTIME(created_on, "%y") = '."$year"
		]);

		if ($last_application) {
			$last_number = $last_application->number;
			$removed_last_number_year = substr($last_number,2);
			$number = ltrim($removed_last_number_year, "0");
			$number++;
		}

		return $year.leading_zero($number, 5);
	}


	public function get_like($key) {
		$this->db->select('a.id as application_id, a.number, a.client_name');
		$this->db->from('application_form a');
		$this->db->like('a.number', $key);
		$this->db->or_like('a.client_name', $key);
		$data = $this->db->get();
		return $data->result();
	}


}
