<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends WebimpController
{
    protected $asides = [
        'header'        => 'asides/header',
        'alert'         => 'asides/alert',
        'footer'        => 'asides/footer',
    ];

    protected $models = ['user', 'login_attempt', 'auth_log', 'password_history', 'auth_otp'];
    protected $layout = 'layouts/public';


	public function __construct()
	{
		parent::__construct();

		$this->config->load('email');
		$this->config->load('account', true);
		$this->load->library(['session', 'form_validation', 'email']);

	}




	/**
	 * Login form.
	 *
	 * @access public
	 * @return void
	 */
	public function login()
	{


		//validate form input
		$this->form_validation->set_rules($this->_validation_rules_login());
		$this->form_validation->set_message('required', 'Please fill out this field');
		$this->form_validation->set_message('valid_email', 'Please enter valid email');
		$this->form_validation->set_message('validate_active', 'Please contact Admin for the Login Details');

		if ($this->form_validation->run()) {
            if ($this->user_model->login($this->input->post('email'), $this->input->post('password'))) {
				$user_id = $this->session->userdata('user_id');
				$ip = get_ip();
				$this->auth_log_model->insert([
					'user_id' => $user_id,
					'login_ip' => $ip,
					'login_time' => time()
				]);


                if ($referred_from = $this->session->userdata('referred_from')) {
                    redirect($referred_from, 'refresh');
                }

                $this->set_alert('Welcome, ' . $this->session->userdata('name') . '!', 'success', true);
				redirect('dashboard', 'refresh');
            } else {
				$email_exists = $this->user_model->get_by('email', $this->input->post('email'));
				if (!$email_exists) {
					$this->data['invalid_email'] = 'Please enter valid email';
				}
				$this->data['invalid_password'] = 'Please enter valid password';
            }
		}
		$this->user_model->logout();
	}




	/**
	 * Logout the user, clear session.
	 *
	 * @access public
	 * @return void
	 */
	public function logout()
	{
		// log the user out
		$user_id = $this->session->userdata('user_id');
		$user_login_data = $this->auth_log_model->get_many_by(['user_id' => $user_id, 'login_time !=' => '']);
		if ($user_login_data) {
			$ip = get_ip();
			$this->auth_log_model->update_by([
				'user_id' => $user_id,
				'logout_time IS NULL'
			], [
				'logout_ip' => $ip,
				'logout_time' => time()
			]);
		}

		$this->user_model->logout();

		// redirect them to the login page
		$this->set_alert('Logged out successfully!', 'success', true);
		redirect('login', 'refresh');
	}




	/**
     * Forgot password form.
     *
     * @access public
     * @return void
     *
     */
    public function forgot()
    {
       $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|min_length[5]|max_length[100]');

        if ($this->form_validation->run()) {
            $user = $this->user_model->get_by('email', $this->input->post('email'));

			if ($user) {
				// set forgotten code
				if ($this->user_model->forgotten_password($user->id)) {

					// retrieve updated user object
					$message['user'] = $user = $this->user_model->with('group')->with_details()->get($user->id);
                    $email_template = $this->config->item('email_templates', 'account') . 'auth/' . $this->config->item('email_forgot_password', 'account');


					// init email
					$this->email->initialize([
						'protocol'  => $this->config->item('protocol'),
						'smtp_host' => $this->config->item('smtp_host'),
						'smtp_user' => $this->config->item('smtp_user'),
						'smtp_pass' => $this->config->item('smtp_pass'),
						'smtp_port' => $this->config->item('smtp_port'),
						'crlf'      => $this->config->item('crlf'),
						'newline'   => $this->config->item('newline'),
					]);

					// email forgot token to user
					$this->email->set_newline("\r\n");
            		$this->email->from('noreply@app.asasg.com', 'Advanced System Assurance Pte Ltd');
            		$this->email->to($user->email);
                    $this->email->subject("Forgotten Password Reset");
                    $this->email->message($this->load->view($email_template, $message, TRUE));
            		//$this->email->message($this->load->view($email_template, $message, TRUE));

                    if ($this->email->send()) {
                        $this->set_alert("Reset link will be sent to the email address. Please check your inbox.", 'success', TRUE);
                        redirect("forgot", 'refresh');
                    } else {
						$errors = $this->email->print_debugger();
    					print_r($errors); die;
                        // $this->set_alert("Opsss, something went wrong. Please contact system administrator.", 'error', TRUE);
                        // redirect("login", 'refresh');
                    }
				} else {
					// failed to generate forgot password token
					$this->set_alert("Can't send out email. Please contact system administrator.", 'error', TRUE);
					redirect("login", 'refresh');
				}
			} else {
                // if user not found
				$this->set_alert("Account not found, unable to reset password!", 'error', TRUE);
				redirect("forgot", 'refresh');
			}
        } else {
            $this->set_alert(validation_errors(), 'error');
        }
    }




    /**
     * Reset password form.
     *
     * @access public
     * @return void
    **/
	public function reset_password($code)
    {
        $user = $this->user_model->forgotten_password_check($code);
        $this->data['form']['submit'] = 'auth/reset/' . $code;

        // if the code is valid then display the password reset form
        if ($user) {
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|matches[confirm_password]|callback_not_reuse_password['.$user->id.' ]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|min_length[8]');
			$this->form_validation->set_message('is_unique', "Sorry, the %s is already being used.");
			$this->form_validation->set_message('not_reuse_password', "Cannot reuse old password");


            if ($this->form_validation->run()) {
                $update_password = $this->user_model->update($user->id, [
                    'password' => $this->input->post('password')
                ]);
                if ($update_password) {

					$this->password_history_model->insert([
						'user_id' => $user->id,
						'password' => $this->input->post('password')
					]);

                    $message['user'] = $user = $this->user_model->with('group')->with_details()->get($user->id);
                    $email_template  = $this->config->item('email_templates', 'account') . 'auth/' . $this->config->item('email_forgot_password_complete', 'account');

					// email notify user's password successfully changed
					$this->email->from('noreply@asa.com.sg', 'Advanced System Assurance Pte Ltd');
            		$this->email->to($user->email);
                    $this->email->subject("Password Successfully Reset");
            		$this->email->message($this->load->view($email_template, $message, TRUE));

                    if ($this->email->send()) {
                        //clear the token anyway
                        $this->user_model->clear_forgotten_password_code($code);

                        $this->set_alert("Password successfully changed.", 'success', TRUE);
                        redirect("login", 'refresh');
                    } else {
                        $this->set_alert("Opsss, something went wrong. Unable to reset password. Please try again.", 'error', TRUE);
                        redirect("login", 'refresh');
                    }
                }
            } else {
                $this->set_alert(validation_errors(), 'error');
            }
        } else {
            $this->user_model->clear_forgotten_password_code($code);
            $this->set_alert('Password reset token is unknown or has been expired.', 'error', TRUE);
            redirect('login', 'redirect');
        }
    }


	function not_reuse_password($password, $user_id) {
		$password_history = $this->password_history_model->get_many_by('user_id', $user_id);
		if(!$password_history) {
			return true;
		}

		$password_reused = false;
		foreach ($password_history as $old_password) {
			if(password_verify($password, $old_password->password)) {
				$password_reused = true;
			}
		}

		if($password_reused) {
			return false;
		}

		return true;
	}




    /**
     * Validation rules for login form.
     *
     * @access protected
     * @return void
     */
    protected function _validation_rules_login()
    {
        $rules = [
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|valid_email|required',
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|callback_validate_active',
            ],
        ];

        return $rules;
    }

	public function validate_active() {
		$email = $this->input->post('email');
		$user = $this->user_model->get_by('email', $email);
		if (!$user) {
			return true;
		}
		$password = $this->user_model->hash_password_db($user->id, $this->input->post('password'));
		if ($user && $password && $user->status == 'Non-Active') {
			return false;
		}
		return true;
	}

	public function confirm_otp() {
		if ($this->form_validation->run()) {
			$otp = $this->input->post('otp');
			$user = $this->session->userdata('user');
			$valid_otp = $this->session->userdata('otp');
			if (!$valid_otp) {
				$this->user_model->logout();
				$this->set_alert('Session time out. Try to login again!', 'success', true);
				redirect('login', 'refresh');
			}
			if ($this->bcrypt->verify($otp, $valid_otp)) {
				$user = $this->session->userdata('user');
				$ip = get_ip();
				$this->auth_log_model->insert([
					'user_id' => $user->id,
					'login_ip' => $ip,
					'login_time' => time()
				]);

				$this->user_model->set_session($user);

                $this->user_model->update_last_login($user->id);

                $this->user_model->clear_login_attempts($user->email);

				$this->session->unset_userdata('user');
				$this->session->unset_userdata('otp');
				$this->session->unset_userdata('ip');

				$this->set_alert('Welcome, ' . $this->session->userdata('name') . '!', 'success', true);
				redirect('dashboard', 'refresh');
			} else {
				$this->set_alert('OTP not valid', 'error');
			}
		}
	}
}
