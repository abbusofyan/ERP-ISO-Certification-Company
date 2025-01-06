<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

class Referrer extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('referrer_model');
    }



	/**
     * get referrer by like post value.
     *
     * @access public
     * @return json
     */
    public function get_like_post()
    {
        $name = $this->post("name");

		$referrers = $this->db->like('name', $name)->get('referrer')->result();

        if ($referrers) {
			$referrers_name = array_column($referrers, 'name');
			$this->response($referrers_name, REST_Controller::HTTP_OK);
        }

        $this->response([], REST_Controller::HTTP_OK);
    }


}
