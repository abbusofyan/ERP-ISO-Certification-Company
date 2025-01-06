<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('full_address')) {
	function full_address($data) {
		if ($data) {
			return ($data->address ? $data->address . ', ' :  '') . $data->address_2 . ' ' . ($data->country ? $data->country . ', ' : '') . $data->postal_code;
		}
	}
}
