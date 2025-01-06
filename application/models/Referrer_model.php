<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Referrer_model extends WebimpModel
{
    protected $_table = 'referrer';

    public function __construct()
    {
        parent::__construct();
    }

	public function check_referrer_exist($referrer_name) {
		$referrer = $this->get_by('name', $referrer_name);
		if ($referrer) {
			return true;
		}
		return false;
	}

}
