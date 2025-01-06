<?php

if(! function_exists("check")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function check()
    {
		$CI =& get_instance();
		$CI->load->library(['auth']);
        $auth = new Auth();
        return $auth->loginStatus();
    }
}

if(! function_exists("can")) {

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @return bool
     */
    function can($permissions)
    {

		$CI =& get_instance();
		$CI->load->library(['session', 'auth']);
		$CI->load->model(['group_model']);
		$user = $CI->session->userdata();
		$hasFullAccess = $CI->group_model->hasFullAccess($user['group_id']);

		if ($hasFullAccess) {
			return true;
		} else {
			$auth = new Auth();
			return $auth->can($permissions);
		}
    }
}

if(! function_exists("hasRole")) {

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @return bool
     */
    function hasRole($roles)
    {
		$CI =& get_instance();
		$CI->load->library(['auth']);
        $auth = new Auth();
        return $auth->hasRole($roles);
    }
}
