<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Training_type_model extends WebimpModel
{
    protected $_table = 'training_type';

    public function __construct()
    {
        parent::__construct();
    }

	public function get_all() {
		return $this->certification_scheme_model->get_many_by('name LIKE "ISO%"');
	}

}
