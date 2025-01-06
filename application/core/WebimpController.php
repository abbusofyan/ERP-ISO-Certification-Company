<?php
/**
 * A base controller for CodeIgniter with view autoloading, layout support,
 * model loading, helper loading, asides/partials and per-controller 404
 *
 * Modified by KhairulA <khairul@webimp.com.sg>
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-controller
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class WebimpController extends CI_Controller
{

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * Show profiler
     *
     * (default value: false)
     *
     * @var bool
     * @access public
     */
    public $profiler_option = false;

    /**
     * Show notifications for sidebar
     *
     * (default value: true)
     *
     * @var bool
     * @access public
     */
    //public $show_notifications = true;

    /**
     * An array of style handles to enqueue.
     *
     * @var array
     */
    //public $styles = ['zipsecurity'];

    /**
     * An array of script handles to enqueue.
     *
     * @var array
     */
    //public $scripts = ['zipsecurity'];

    /**
     * The current request's view. Automatically guessed
     * from the name of the controller and action
     */
    protected $view = '';

    /**
     * An array of variables to be passed through to the
     * view, layout and any asides
     */
    protected $data = array();

    /**
     * The name of the layout to wrap around the view.
     */
    protected $layout;

    /**
     * An arbitrary list of asides/partials to be loaded into
     * the layout. The key is the declared name, the value the file
     */
    protected $asides = array();

    /**
     * A list of models to be autoloaded
     */
    protected $models = array();

    /**
     * A formatting string for the model autoloading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = '%_model';

    /**
     * A list of helpers to be autoloaded
     */
    protected $helpers = array();

    /**
     * Array of messages to set in alert box
     */
    protected $alert = array();

    /**
     * Array of breadcrumbs, auto-loaded into view
     */
    protected $breadcrumbs = array();

    /**
     * Array of scripts, auto-loaded into view
     */
    protected $scripts = array();


    /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the controller, tie into the CodeIgniter superobject
     * and try to autoload the models and helpers
     */
    public function __construct()
    {
        parent::__construct();

        $this->load_models();
        $this->load_helpers();

        $this->breadcrumbs[] = anchor(site_url(), 'Home');

        if (ENVIRONMENT == 'production') {
            $this->load->driver('cache', ['adapter' => 'memcached']);
        } else {
            $this->load->driver('cache', ['adapter' => 'file']);
        }

        // load csrf
        $this->data['csrf'] = [
            'name'  => $this->security->get_csrf_token_name(),
            'value' => $this->security->get_csrf_hash(),
        ];
    }




    /**
     * Set the alert.
     *
     * @access public
     * @param string $description
     * @param string $status (default: 'warning')
     * @param bool $flash (default: false)
     * @return void
     */
    public function set_alert($description = null, $status = 'warning', $flash = false)
    {
        $this->load->library('session');

        $description = (string) trim($description);

        if ($description) {
            $alert = [
                'status'      => $status,
                'description' => $description,
            ];

            if ($flash) {
                $this->session->set_flashdata('alert', $alert);
            } else {
                $this->data['alert'] = $alert;
            }
        }
    }




    /**
     * Get and set the alert variable in controller.
     *
     * @access private
     * @return array
     */
    private function get_alert()
    {
        if (isset($this->data['alert']['description'])
            && !empty($this->data['alert']['description'])
        ) {
            $this->data['alert'] = $this->data['alert'];
        } else {
            $this->data['alert'] = $this->session->flashdata('alert');
        }
    }




    /**
     * Add links to breachcrumb.
     *
     * @access public
     * @param string $anchor
     * @return void
     */
    public function add_breadcrumb($anchor)
    {
        if (is_array($anchor)) {
            foreach ($anchor as $link) {
                $this->breadcrumbs[] = $link;
            }
        } elseif (is_string($anchor)) {
            $this->breadcrumbs[] = $anchor;
        }
    }




    /**
     * Set array of breadcrumbs for controller.
     *
     * @access private
     * @return void
     */
    private function get_breadcrumbs()
    {
        $this->data['page']['breadcrumbs'] = $this->breadcrumbs;
    }




    /**
     * Add script handles to the controller before loading view.
     *
     * @param array|string  $handle  The script handle string or an
     *                               array of script handles.
     */
    public function add_script($script)
    {
        if (is_array($script)) {
            foreach ($script as $link) {
                $this->scripts[] = $link;
            }
        } elseif (is_string($script)) {
            $this->scripts[] = $script;
        }
    }




    /**
     * Set array of scripts for controller.
     *
     * @access private
     * @return void
     */
    private function get_scripts()
    {
        $this->data['page']['scripts'] = $this->scripts;
    }
    
    
    
    
    /* --------------------------------------------------------------
     * VIEW RENDERING
     * ------------------------------------------------------------ */

    /**
     * Override CodeIgniter's despatch mechanism and route the request
     * through to the appropriate action. Support custom 404 methods and
     * autoload the view into the layout.
     */
    public function _remap($method)
    {
        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
        } else {
            if (method_exists($this, '_404')) {
                call_user_func_array(array($this, '_404'), array($method));
            } else {
                show_404(strtolower(get_class($this)).'/'.$method);
            }
        }

        $this->_load_view();
    }




     /**
     * Automatically load the view, allowing the developer to override if
     * he or she wishes, otherwise being conventional.
     */
    protected function _load_view()
    {
        // If $this->view == false, we don't want to load anything
        if ($this->view !== false) {
            //$this->register_assets();
            //$this->enqueue_assets();

            $this->load->library('session'); // comment out this line if no session table in DB

            $this->get_alert();

            $this->get_breadcrumbs();
            
            $this->get_scripts();

            $this->output->enable_profiler($this->profiler_option);

            // If $this->view isn't empty, load it. If it isn't, try and guess based on the controller and action name
            $view = (!empty($this->view)) ? $this->view : $this->router->directory . 'controllers/' . strtolower($this->router->class) . '/' . strtolower($this->router->method);

            // Load the view into $yield
            $data['yield'] = $this->load->view($view, $this->data, true);

            // Do we have any asides? Load them.
            if (!empty($this->asides)) {
                foreach ($this->asides as $name => $file) {
                    $data['yield_'.$name] = $this->load->view($file, $this->data, true);
                }
            }

            // Load in our existing data with the asides and view
            $data = array_merge($this->data, $data);
            $layout = false;

            // If we didn't specify the layout, try to guess it
            if (!isset($this->layout)) {
                if (file_exists(APPPATH . 'views/layouts/' . strtolower($this->router->class) . '.php')) {
                    $layout = 'layouts/' . strtolower($this->router->class);
                } elseif ($this->user_model->logged_in()
                          && file_exists(APPPATH . 'views/layouts/private.php')
                ) {
                    $layout = 'layouts/private';
                } else {
                    $layout = 'layouts/public';
                }
            } elseif ($this->layout !== false) { // If we did, use it
                $layout = $this->layout;
            }

            // If $layout is false, we're not interested in loading a layout, so output the view directly
            if ($layout == false) {
                $this->output->set_output($data['yield']);
            } else { // Otherwise? Load away :)
                $this->load->view($layout, $data);
            }
        }
    }




    /**
     * Register scripts and styles for the application. These assets are managed by Grunt.
     
    private function register_assets()
    {
        $version = get_app_version();
    }*/




    /**
     * Enqueue scripts and styles for the application.
     
    private function enqueue_assets()
    {
        foreach ((array) $this->scripts as $script) {
            enqueue_script($script);
        }

        foreach ((array) $this->styles as $style) {
            enqueue_style($style);
        }
    }*/




    /* --------------------------------------------------------------
     * MODEL LOADING
     * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function load_models()
    {
        foreach ($this->models as $model) {
            $this->load->model($this->_model_name($model));
        }
    }




    /**
     * Returns the loadable model name based on
     * the model formatting string
     */
    protected function _model_name($model)
    {
        return str_replace('%', $model, $this->model_string);
    }




    /* --------------------------------------------------------------
     * HELPER LOADING
     * ------------------------------------------------------------ */

    /**
     * Load helpers based on the $this->helpers array
     */
    private function load_helpers()
    {
        foreach ($this->helpers as $helper) {
            $this->load->helper($helper);
        }
    }
}
