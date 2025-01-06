<?php defined('BASEPATH') OR exit('No direct script access allowed');

class GsClient
{
    /**
     * Handle upload/download file in Google Storage in your CodeIgniter applications.
     *
     * @link https://github.com/googleapis/google-cloud-php-storage
     */
	public function __construct()
	{
        include('gstorage-client/autoload.php');
	}
}