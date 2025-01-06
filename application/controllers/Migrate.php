<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends WebimpController
{
    protected $asides = [
        'header' => 'asides/header',
        'alert' => 'asides/alert',
    ];
    
    protected $layout = 'layouts/public';

    public function __construct()
    {
        parent::__construct();

        $this->load->library('migration');
    }




	/**
	 * Load the version defined in config.
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
        $this->data['result'] = $result = $this->migration->current();

        if ($result === false)
            show_error($this->migration->error_string());
	}




	/**
	 * Change migration version.
	 * 
	 * @access public
	 * @param str $version
	 * @return void
	 */
	public function v($version)
	{
        if (ENVIRONMENT == 'production') {
            $this->view = false;
            show_404();
        } else {
        	$this->view = 'controllers/migrate/index.php';
    
            $this->data['result'] = $result = $this->migration->version($version);
    
            if ($result === false)
                show_error($this->migration->error_string());
        }
	}




    /**
     * Reset the migration.
     * 
     * @access public
     * @return void
     */
    public function reset()
    {
        if (ENVIRONMENT == "production") {
            $this->view = false;
            show_404();
        } else {
            // only left session table
            $this->v(20221227184000);
        }
    }
}
