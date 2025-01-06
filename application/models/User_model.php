<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends WebimpModel
{
    protected $_table = 'user';
    protected $soft_delete = true;

    protected $errors = [];

    protected $error_start_delimiter;
    protected $error_end_delimiter;

    protected $belongs_to = [
        'group' => [
            'primary_key' => 'group_id',
            'model'       => 'group_model',
        ],
    ];

	protected $has_many = [
		'client_history' => [
            'primary_key' => 'created_by',
            'model'       => 'client_history_model',
        ],
		'contact_history' => [
            'primary_key' => 'created_by',
            'model'       => 'contact_history_model',
        ],
    ];
    protected $after_relate  = ['_get_details'];
    protected $before_create = ['_format_submission'];
    protected $before_update = ['_format_submission'];
    protected $before_delete = ['_cache_user_id'];
    // protected $after_delete  = ['_mark_email_phone_deleted', '_delete_attachment_file'];

    protected $_with_details = false;
    protected $group_id;
    protected $password;
    protected $del_user_id;


    public function __construct()
    {
        parent::__construct();

        $this->config->load('form_validation', true);
        $this->error_start_delimiter = $this->config->item('error_prefix', 'form_validation');
        $this->error_end_delimiter   = $this->config->item('error_suffix', 'form_validation');

        $this->config->load('account', true);
        $this->salt_length = $this->config->item('salt_length', 'account');
        $this->store_salt  = $this->config->item('store_salt', 'account');
        $this->hash_method = $this->config->item('hash_method', 'account');

        $this->load->library(['session', 'bcrypt', 'upload', 'GsClient']);
        $this->load->helper('cookie');
        $this->load->model('login_attempt_model');
        $this->load->model('group_model');
		$this->load->model('group_permission_model');
		$this->load->model('permission_model');
		$this->load->model('auth_otp_model');
		$this->load->model('authorized_ip_model');
    }




    /**
     * Check if user is logged in.
     *
     * @access public
     * @return bool
     */
    public function logged_in()
    {
        return (bool) $this->session->userdata('user_id');
    }




    /**
     * Login, creating session.
     *
     * @access public
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
			$this->session->set_flashdata('error', 'Incorrect login!');
            return false;
        }

        if ($this->is_time_locked_out($email)) {
            // Hash something anyway, just to take up time
            $this->hash_password($password);

			$this->session->set_flashdata('error', 'Temporarily locked out. Try again later.');

            return false;
        }

        $user = $this->with_details()->get_by('email', $email);

        if ($user) {

			if ($user->status == 'Non-Active') {
				$this->session->set_flashdata('error', 'Cant login with non active user');
	            return false;
			}

            $password = $this->hash_password_db($user->id, $password);

            if ($password === true) {

				$ip = get_ip();
				// if (!$this->_ip_allowed($ip)) {
				// 	$this->session->set_userdata([
				// 		'user' => $user,
				// 		'ip' => $ip
				// 	]);
				// 	$this->_send_otp();
				// 	redirect('auth/confirm_otp');
				// }

                $this->set_session($user);

                $this->update_last_login($user->id);

                $this->clear_login_attempts($email);

                return true;
            }
        }

        // Hash something anyway, just to take up time
        $this->hash_password($password);

        $this->increase_login_attempts($email);

        $this->set_error('Incorrect login!');
		$this->session->set_flashdata('error', 'Incorrect login!');

        return false;
    }




    /**
     * Logout, clearing session.
     *
     * @access public
     * @return void
     */
    public function logout()
    {
        $this->session->unset_userdata(['user_id','group_id','email','details','name','last_login','group']);
        // delete the remember me cookies if they exist
        if (get_cookie('identity'))
            delete_cookie('identity');

        if (get_cookie('remember_code'))
            delete_cookie('remember_code');

        // Destroy the session
        $this->session->sess_destroy();

        //Recreate the session
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->session->sess_create();
        } else {
            if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
                session_start();
            }
            $this->session->sess_regenerate(true);
        }

        return true;
    }




    /**
     * Hashes the password to be stored in the database.
     *
     * @access public
     * @param string $password
     * @param bool $salt (default: false)
     * @param bool $use_sha1_override (default: false)
     * @return string
     */
    public function hash_password($password, $salt = false, $use_sha1_override = false)
    {
        if (empty($password))
            return false;

        // bcrypt
        if ($use_sha1_override === false && $this->hash_method == 'bcrypt') {
            return $this->bcrypt->hash($password);
        }


        if ($this->store_salt && $salt) {
            return  sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }




    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @access public
     * @param int $id
     * @param string $password
     * @param bool $use_sha1_override (default: false)
     * @return bool
     */
    public function hash_password_db($id, $password, $use_sha1_override = false)
    {
        if (empty($id) || empty($password))
            return false;

        $hash_password_db = $this->limit(1)->get_by('id', $id);

        // bcrypt
        if ($use_sha1_override === false && $this->hash_method == 'bcrypt') {
            if ($this->bcrypt->verify($password, $hash_password_db->password))
                return true;

            return false;
        }

        // sha1
        if ($this->store_salt) {
            $db_password = sha1($password . $hash_password_db->salt);
        } else {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);

            $db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }

        if ($db_password == $hash_password_db->password)
            return true;
        else
            return false;
    }




    /**
     * Update the user's password.
     *
     * @access public
     * @return void
     */
    public function update_password($password, $user_id)
    {
        $password = $this->hash_password($password);

        $user = $this->get((int) $user_id);

        $updated = $this->update($user->id, [
            'password' => $password,
        ]);

        if ($updated)
            return true;

        return false;
    }




    /**
     * Check if user's in the group.
     *
     * @access public
     * @param string|array  $name     A single group name or an array of group names.
     * @param int           $user_id  Default null.
     *
     * @return bool                   True if user id exists in the group.
     */
    public function grouped_in($name, $user_id = null)
    {
        if ($user_id) {
            $user         = $this->with('group')->get((int) $user_id);
            $current_name = $user->group->name;
        } else {
            if ($this->logged_in()) {
                $group = $this->session->userdata('group');
            } else {
                return false;
            }

            $current_name = $group['name'];
        }

        if (is_array($name)) {
            return (bool) in_array($current_name, $name);
        } elseif (is_string($name)) {
            return (bool) ($current_name === $name);
        }
    }




    /**
     * Set flag to return with details.
     *
     * @access public
     * @return void
     */
    public function with_details()
    {
        $this->_with_details = true;

        return $this;
    }




    /**
     * Set user data into session.
     *
     * @access public
     * @param object $user
     * @return bool
     */
    public function set_session($user)
    {
        $session_data = [
            'user_id'        => $user->id,
            'group_id'       => $user->group_id,
            'email'          => $user->email,
            'details'        => (array) $user->details,
            'name'     		 => $user->first_name. ' ' . $user->last_name,
            'last_login'     => $user->last_login,
            'group'          => [
                'name'        => $user->group->name,
                'description' => $user->group->description,
            ],
			'permission'	 => $user->permissions
        ];

        $this->session->set_userdata($session_data);

        return true;
    }



    /**
     * Clear login_attempt for email.
     *
     * @access public
     * @param string $email
     * @param int $expire_period (default: 86400)
     * @return void
     */
    public function clear_login_attempts($email, $expire_period = 86400)
    {
        $ip_address = $this->input->ip_address();

        // Purge obsolete login attempts
        $this->login_attempt_model->_database->or_where('time <', time() - $expire_period, false);

        return $this->login_attempt_model->delete_by([
            'ip_address' => $ip_address,
            'login'      => $email,
        ]);

        return false;
    }




    /**
     * Update user's last_login.
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function update_last_login($id)
    {
        return $this->update((int) $id, [
            'last_login' => mysql_datetime(),
        ]);

        return false;
    }




    /**
     * Get a boolean to determine if an account should be locked out due to
     * exceeded login attempts within a given period.
     *
     * @access public
     * @param string $email
     * @return bool
     */
    public function is_time_locked_out($email)
    {
        $email = strtolower($email);

        if ($this->is_max_login_attempts_exceeded($email)
            && $this->get_last_attempt_time($email) > time() - $this->config->item('lockout_time', 'account')
        ) {
            return true;
        }

        return false;
    }




    /**
	 * Get the time of the last time a login attempt occured from given IP-address or identity
	 *
     * @access public
	 * @param	string $identity
	 * @return	int
	 */
	public function get_last_attempt_time($identity)
	{
		if ($this->config->item('track_login_attempts', 'account')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->select_max('time');
            if ($this->config->item('track_login_ip_address', 'account')) $this->db->where('ip_address', $ip_address);
			elseif (strlen($identity) > 0) $this->db->or_where('login', $identity);
			$qres = $this->db->get('login_attempt', 1);

			if ($qres->num_rows() > 0) {
				return $qres->row()->time;
			}
		}

		return 0;
	}




    /**
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth).
     *
     * @access public
     * @param string $email
     * @return bool
     */
    public function is_max_login_attempts_exceeded($email)
    {
        if ($this->config->item('track_login_attempts', 'account')) {
            $max_attempts = $this->config->item('maximum_login_attempts', 'account');

            if ($max_attempts > 0) {
                $attempts = $this->get_attempts_num($email);

                return $attempts >= $max_attempts;
            }
        }

        return false;
    }




    /**
     * Get number of attempts to login occured from given IP-address or identity
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth).
     *
     * @access public
     * @param string $email
     * @return int
     */
    public function get_attempts_num($email)
    {
        $ip_address = $this->input->ip_address();

        return $this->login_attempt_model->count_by([
            'ip_address' => $ip_address,
            'login'      => $email,
        ]);

        return 0;
    }




    /**
     * Record login attempts.
     * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth).
     *
     * @access public
     * @param string $email
     * @return bool
     */
    public function increase_login_attempts($email)
    {
        $ip_address = $this->input->ip_address();

        return $this->login_attempt_model->insert([
            'ip_address' => $ip_address,
            'login'      => $email,
            'time'       => time(),
        ]);

        return false;
    }




    /**
     * Set an error message.
     *
     * @access public
     * @param string $error
     * @return void
     */
    public function set_error($error)
    {
        $this->errors[] = $error;
        return $error;
    }




    /**
     * Get the error message.
     *
     * @access public
     * @return void
     */
    public function errors()
    {
        for ($i = 0; $i < count($this->errors); $i++) {
            $new_errors[] = $this->error_start_delimiter . $this->errors[$i] . $this->error_end_delimiter;
        }

        return implode('', $new_errors);
    }




    /**
     * Generate salt.
     *
     * @access public
     * @return void
     */
    public function salt()
    {
        $raw_salt_len = 16;

        $buffer = '';
        $buffer_valid = false;

        if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
            $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);

            if ($buffer)
                $buffer_valid = true;
        }

        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($raw_salt_len);

            if ($buffer)
                $buffer_valid = true;
        }

        if (!$buffer_valid && @is_readable('/dev/urandom')) {
            $f    = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);

            while ($read < $raw_salt_len) {
                $buffer .= fread($f, $raw_salt_len - $read);
                $read = strlen($buffer);
            }

            fclose($f);

            if ($read >= $raw_salt_len)
                $buffer_valid = true;
        }


        if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
            $bl = strlen($buffer);

            for ($i = 0; $i < $raw_salt_len; $i++) {
                if ($i < $bl)
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                else
                    $buffer .= chr(mt_rand(0, 255));
            }
        }

        $salt = $buffer;

        // encode string with the Base64 variant used by crypt
        $base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $base64_string   = base64_encode($salt);

        $salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);
        $salt = substr($salt, 0, $this->salt_length);

        return $salt;
    }




    /**
     * Check if email exists.
     *
     * @access public
     * @param string $email
     * @param int $user_id (default: null)
     * @return bool
     */
    public function email_allowed($email, $user_id = null)
    {
        $email = strtoupper($email);

        if ($user_id) {
            if ($this->count_by(['email' => $email, 'id !=' => $user_id]) > 0)
                return false;
        } else {
            if ($this->count_by(['email' => $email]) > 0)
                return false;
        }

        return true;
    }




    /**
     * Include user details, according to type.
     *
     * @access protected
     * @param mixed $row
     * @return void
     */
    protected function _get_details($row)
    {
        if (is_object($row)) {
            // get group details if not in relate
            if (!empty($row->group_id) && !isset($row->group)) {
                $row->group = $this->group_model->get($row->group_id);
            }

            // get file details if not in relate
            if (!empty($row->file_id) && !isset($row->file)) {
                $row->file = $this->file_model->get($row->file_id);
            }

            if ($this->_with_details) {
                // more group details
                if (isset($row->group) && !empty($row->group)) {
                    switch ($row->group->name) {
                        case 'superadmin':
                            $row->details = new stdClass();
                            $row->details->display_name = 'Superadmin';
                            break;
                        case 'crew':
                            $row->details = new stdClass();
                            $row->details->display_name = 'Crew';
                            break;
                        default:
                            $row->details = new stdClass();
                            $row->details->display_name = 'Unknown';
                            break;
                    }
                }
				// get permission with detail by group id
				$permissions = $this->group_model->roleWisePermissionDetails($row->group_id);
				$row->permissions = new stdClass();
				$row->permissions = $permissions;
            }
        } elseif (is_array($row)) {
            // get group details if not in relate
            if (!empty($row['group_id']) && !isset($row['group'])) {
                $row['group'] = $this->group_model->get($row['group_id']);
            }

            if ($this->_with_details) {
                // more group details
                if (isset($row['group']) && !empty($row['group'])) {

                }
            }
        }

        return $row;
    }




    protected function _prepare_ip($ip_address)
    {
		// just return the string IP address now for better compatibility
		return $ip_address;
	}




	/**
     * Format the post submission.
     *
     * @access protected
     * @param array $post
     * @return array
     */
    protected function _format_submission($post)
    {
        // email
        if (isset($post['email'])) {
            $post['email'] = strtolower($post['email']);
        }

        // email
        if (isset($post['email'])) {
            $post['email'] = strtolower($post['email']);
        }

        // password
        if (isset($post['password']) && !empty($post['password'])) {
            $post['password'] = $this->hash_password($post['password']);
        } else {
            unset($post['password']);
        }

        // confirm password
        unset($post['confirm_password']);

        // user ID
        if (isset($post['user_id'])) {
            unset($post['user_id']);
        }

        // ID Number
        if (!isset($post['reference_id']) || ($post['reference_id']=='')) {
            $post['reference_id'] = null;
        }

		if (!isset($post['status'])) {
            $post['status'] = 'Active';
        }

        return $post;
    }




    /**
     * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
     *
     * @return void
     * @author Mathew
     **/
    public function hash_code($password)
    {
        return $this->hash_password($password, false, true);
    }




    /**
     * Insert a forgotten password key.
     *
     * @access public
     * @param int $user_id
     * @return void
     */
    public function forgotten_password($user_id)
    {
        $user = $this->get((int) $user_id);

        if (empty($user->email))
            return false;

        // All some more randomness
        $activation_code_part = "";

        if(function_exists("openssl_random_pseudo_bytes"))
            $activation_code_part = openssl_random_pseudo_bytes(128);

        for ($i = 0; $i < 1024; $i++) {
            $activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
        }

        $key = $this->hash_code($activation_code_part . $user->email);

        // If enable query strings is set, then we need to replace any unsafe characters so that the code can still work
        if ($key != ''
            && $this->config->item('permitted_uri_chars') != ''
            && $this->config->item('enable_query_strings') == false
        ) {
            // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
            // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
            if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $key))
            {
                $key = preg_replace("/[^" . $this->config->item('permitted_uri_chars') . "]+/i", "-", $key);
            }
        }

        $this->forgotten_password_code = $key;

        $update = array(
            'forgotten_password_code' => $key,
        );

        if ($this->update_by(array('id' => $user->id), $update))
            return true;

        return false;
    }




    /**
	 * Clear the forgotten password code from table.
	 *
	 * @access public
	 * @param str $code
	 * @return bool
	 */
	public function clear_forgotten_password_code($code)
	{
		if (empty($code))
			return false;

		$this->db->where('forgotten_password_code', $code);

		if ($this->count_by('forgotten_password_code', $code) > 0) {
			$data = array(
			    'forgotten_password_code' => null,
			    'forgotten_password_time' => null
			);

			if ($this->update_by(array('forgotten_password_code' => $code), $data))
    			return true;
		}

		return false;
	}




    /**
	 * Check if forgotten password code is valid.
	 *
	 * @access public
	 * @param string $code
	 * @return mixed
	 */
	public function forgotten_password_check($code)
	{
        $user = $this->get_by('forgotten_password_code', $code);

		if (!is_object($user)) {
			return false;
		} else {
			if ($this->config->item('forgot_password_expiration', 'account') > 0) {
				// make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'account');

				if (time() - $user->forgotten_password_time > $expiration) {
					// it has expired
					$this->clear_forgotten_password_code($code);
					return false;
				}
			}
			return $user;
		}
    }




    /**
     * Generate a dropdown of Group if any filter
     *
     * @access public
     * @param array $filter (default: null)
     * @return array
     */
    public function dropdown_filter($filter = null, $technician = null, $insert_first_row = true)
    {
        $options = [];

        if ($technician['id']) {
            $users = $this->get_many_by($technician);
        } else {
            if ($filter) {
                $users = $this->get_many_by($filter);
            } else {
                $users = $this->get_all();
            }
        }


        foreach($users as $user) {
            $options[$user->id] = $user->name;
        }

        return ($insert_first_row) ? ( array('' => '-- Please select a technician --') + $options ) : $options;
    }




    /**
     * Cache user ID before delete
     *
     * @access protected
     * @param int $user_id
     * @return void
     */
    protected function _cache_user_id($user_id)
    {
        $this->del_user_id = $user_id;

        return $user_id;
    }




    /**
     * Append delete remarks to deleted user's email & email
     *
     * @access protected
     * @param object $data
     * @return void
     */
    protected function _mark_email_phone_deleted($data)
    {
        if (!empty($this->del_user_id)) {
            // get deleted user
            $deleted_user = $this->user_model->with_deleted()->get($this->del_user_id);

            // append deleted remarks to the email & email
            $remark_email = $deleted_user->email . '-deleted';
            $remark_email    = $deleted_user->email . '-deleted';

            $this->user_model->update($this->del_user_id, [
                'email' => $remark_email,
                'email'    => $remark_email
            ]);
        }

        return $data;
    }




    /**
     * Delete user's attached files
     *
     * @access protected
     * @param object $data
     * @return void
     */
    protected function _delete_attachment_file($data)
    {
        if (!empty($this->del_user_id)) {
            // get user's attached files
            $user_files = $this->user_file_model->get_many_by('user_id', $this->del_user_id);

            if (!empty($user_files)) {
                foreach ($user_files as $user_file) {
                    // delete file
                    $this->file_model->delete($user_file->file_id);
                }

                // delete user files
                $this->user_file_model->delete_by('user_id', $this->del_user_id);
            }
        }

        return $data;
    }


	public function addRoles($user_id, $roles)
    {
        $data["user_id"] = $user_id;
        if (is_array($roles)) {
            foreach ($roles as $role) {
                $data["role_id"] = $role;
                $this->addRole($data);
            }
        }
        else {
            $data["role_id"] = $roles;
            $this->addRole($data);
        }

        return 1;
    }

    /**
     * Insert role.
     *
     * @param $data
     * @return mixed
     */
    public function addRole($data)
    {
        return $this->db->insert('roles_users', $data);
    }

    /**
     * Edit roles.
     *
     * @param $user_id
     * @param $roles
     * @return int
     */
    public function editRoles($user_id, $roles)
    {
        if($this->deleteRoles($user_id, $roles))
            $this->addRoles($user_id, $roles);

        return 1;
    }

    /**
     * Delete roles.
     *
     * @param $user_id
     * @param $roles
     * @return mixed
     */
    public function deleteRoles($user_id, $roles)
    {

        return $this->db->delete('roles_users', array('user_id' => $user_id));
    }

    /**
     * Delete role.
     *
     * @param $user_id
     * @param $role_id
     * @return mixed
     */
    public function deleteRole($user_id, $role_id)
    {

        return $this->db->delete('roles_users', array('user_id' => $user_id, 'role_id' => $role_id));
    }

    /**
     * Find roles associated with user.
     *
     * @param $id
     * @return array
     */
    public function userWiseRoles($id)
    {
        return array_map(function($item){
            return $item["role_id"];
        }, $this->db->get_where("roles_users", array("user_id" => $id))->result_array());
    }

    /**
     * Find role details associated with user.
     *
     * @param $id
     * @return array
     */
    public function userWiseRoleDetails($id)
    {
        return array_map(function($item){
            $user = new User();
            return $user->findRole($item);
        }, $this->userWiseRoles($id));
    }

    /**
     * Find role.
     *
     * @param $id
     * @return mixed
     */
    public function findRole($id)
    {
        return $this->db->get_where("roles", array("id" => $id, "deleted_at" => null))->row(0);
    }

	private function _ip_allowed($ip) {
		$ip_authorized = $this->authorized_ip_model->get_by('ip', $ip);
		if ($ip_authorized) {
			return true;
		}
		return false;
	}

	private function _send_otp() {
		$otp = rand(100000, 999999);

		$email_template = 'emails/auth/otp.tpl.php';
		$message['otp'] = $otp;
		$message['user'] = $this->session->userdata('user');
		$this->email->initialize([
			'protocol'  => $this->config->item('protocol'),
			'smtp_host' => $this->config->item('smtp_host'),
			'smtp_user' => $this->config->item('smtp_user'),
			'smtp_pass' => $this->config->item('smtp_pass'),
			'smtp_port' => $this->config->item('smtp_port'),
			'crlf'      => $this->config->item('crlf'),
			'newline'   => $this->config->item('newline'),
		]);

		$this->email->set_newline("\r\n");
		$this->email->from('noreply@app.asasg.com', 'Advanced System Assurance Pte Ltd');
		$this->email->to('viji@asasg.com');
		$this->email->cc('chidam@asasg.com');
		$this->email->cc('developers@webimp.com.sg');
		$this->email->subject("ASA Login Attempt");
		$this->email->message($this->load->view($email_template, $message, TRUE));

		if ($this->email->send()) {
			$session = $this->session->userdata();
			$data = [
				'user_id' => $session['user']->id,
				'ip' => $session['ip'],
				'otp' => $otp,
				'created_on' => time()
			];
			$this->auth_otp_model->insert($data);

			return $this->session->set_userdata([
				'otp' => $this->hash_password($otp)
			]);
		} else {
			$errors = $this->email->print_debugger();
			dd($errors); die;
		}


	}
}
