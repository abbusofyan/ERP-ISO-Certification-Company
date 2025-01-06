<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('dd')) {
	function dd($var) {
		echo "<pre>";
		print_r($var);
		echo "</pre>"; die;
	}
}
