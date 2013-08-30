<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * This file is part of AuthLDAP.

AuthLDAP is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

AuthLDAP is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with AuthLDAP.  If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @author      Greg Wojtak <gwojtak@techrockdo.com>
 * @copyright   Copyright © 2010-2013 by Greg Wojtak <gwojtak@techrockdo.com>
 * @package     AuthLDAP
 * @subpackage  auth demo
 * @license     GNU Lesser General Public License
*/
class Auth extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->library('Form_validation');
		$this->load->library('AuthLDAP');

		$this->load->model('Usermodel');
		$this->load->model('User_rolesmodel');
        $this->load->model('Organizationmodel');
        $this->load->model('Countrymodel');

        $this->load->helper('login');
	}

	function index() {
		$this->session->keep_flashdata('tried_to');
		$this->login();
	}

	function register() {
		if (is_logged_in($this)) {
			redirect('welcome');
		}

		// Set up rules for form validation
		$rules = $this->form_validation;
		$rules->set_rules('email', 'E-mail', 'required|valid_email');
		$rules->set_rules('username', 'Username', 'required|alpha_numeric|min_length[6]');
		$rules->set_rules('password', 'Password', 'required|min_length[6]');
		$rules->set_rules('password2', 'Confirmation', 'required');
		$rules->set_rules('country_code', 'Confirmation', 'required');

		$register = false;
		$error = '';
		if($rules->run()) {
			// Valid form, register
			$data['email'] = isset($_POST['email']) ? $_POST['email'] : "";
			$data['username'] = isset($_POST['username']) ? $_POST['username'] : "";
			$data['password'] = isset($_POST['password']) ? $_POST['password'] : "";
			$data['password2'] = isset($_POST['password2']) ? $_POST['password2'] : "";
			$data['country_code'] = isset($_POST['country_code']) ? $_POST['country_code'] : "FR";
			
			// Check if the username already exists.
			$query = array(array('username', $data['username']));
			if ($this->Usermodel->count('user_id', $query) != 0) {
				$data['register_fail_msg'] = 'Cet utilisateur existe déjà.';
			} else {
				// Check if the email is already in use (except by an admin account or a donotsend account).
				$query = array(array('email', $data['email']));
				if ($this->Usermodel->count('user_id', $query) != 0) {
					$data['register_fail_msg'] = 'Cet email est déjà utilisé.';
				} else {
					// Check that the two passwords given match.
					if ($data['password'] != $data['password2']) {
						$data['register_fail_msg'] = 'Les mots de passe ne correspondent pas.';
					} else {
						// Add the user to the database, with user access.
						$confirmation_code = md5(salt(64));
						$data['confirmation_code'] = $confirmation_code;
						
						// Send confirmation mail to user.
						
						$register = true;
					}
				}
			}
		}else {
			// Register FAIL
			$data['register_fail_msg'] = 'Erreur avec les données fournies.';
		}
		
		if (!$register) {
			// Préparation des données
			$data['countries'] = $this->Countrymodel->getAllForCombo('country_code', 'name');
			// Données soumises
			$data['email'] = isset($_POST['email']) ? $_POST['email'] : "";
			$data['username'] = isset($_POST['username']) ? $_POST['username'] : "";
			$data['password'] = isset($_POST['password']) ? $_POST['password'] : "";
			$data['password2'] = isset($_POST['password2']) ? $_POST['password2'] : "";
			$data['country_code'] = isset($_POST['country_code']) ? $_POST['country_code'] : "FR";
			
			$data['page_title'] = 'Création de compte';
			$data['page_icon'] = 'user';
			render($this, 'auth/register', $data);
		} else {
			// Register win, redirect
			$data['page_title'] = 'Inscription réussie !';
			$data['page_icon'] = 'user';
			render($this, 'auth/register_ok', $data);
		}

	}
	
	function register_validate($activationcode) {
		if (is_logged_in($this)) {
			redirect('welcome');
		}
	}

	function login($errorMsg = NULL){
		$this->session->keep_flashdata('tried_to');
		if(!$this->authldap->is_authenticated()) {
			// Set up rules for form validation
			$rules = $this->form_validation;
			$rules->set_rules('username', 'Username', 'required|alpha_numeric');
			$rules->set_rules('password', 'Password', 'required');

			// Do the login...
			if ($rules->run()) {
				if ($this->authldap->login(
					$rules->set_value('username'),
					$rules->set_value('password'))) {
					// Login WIN!
					$username = $this->session->userdata('username');
					log_message('debug', 'Logged with username='.$username);
	
					// Check if user exists
					$user_id = -1;
					$user_id_arr = $this->Usermodel->search('user_id', array(array("username", $username)));
					if (is_array($user_id_arr) && count($user_id_arr) == 1) {
						$user_id = $user_id_arr[0]->user_id;
					}
					log_message('debug', 'User_id='.($user_id));
					if ($user_id == -1) {
						// No, create user
						$this->Usermodel->user_id = null;
						$this->Usermodel->username = $username;
						$this->Usermodel->email = $this->session->userdata('mail');
						$this->Usermodel->org_id = 1; // 1 = CGI global
						$this->Usermodel->country_code = 'FR';
						$this->Usermodel->created = new DateTime();
						$this->Usermodel->shutdown_date = null;
						$this->Usermodel->max_game_id = null;
						$ret = $this->Usermodel->insert();
						log_message('error', 'Error creation result = $ret');
						if (!$ret) {
							log_message('error', 'Error during user creation');
							$this->authldap->logout();
							$data['login_fail_msg'] = 'Erreur lors de la création de l\'utilisateur en base.';
							render($this, 'auth/login_form', $data);
							return;
						}
	
						// And create role
						$user_id_arr = $this->Usermodel->search('user_id', array(array("username", $username)));
						if (is_array($user_id_arr) && count($user_id_arr) == 1) {
							$user_id = $user_id_arr[0]->user_id;
						} else {
							log_message('error', 'Error during user creation');
							$this->authldap->logout();
							$data['login_fail_msg'] = 'Erreur lors de la création de l\'utilisateur en base.';
							render($this, 'auth/login_form', $data);
							return;
						}
						$this->User_rolesmodel->user_id = $user_id;
						$this->User_rolesmodel->role_name = 'USER';
						$this->User_rolesmodel->insert();
					}
					$this->session->set_userdata('user_id', $user_id);
						
					// Load roles from DB
					$roles_db = $this->User_rolesmodel->search('role_name', array(array("user_id", $user_id)));
					$roles = array();
					for ($i=0; $i<count($roles_db); $i++) {
						$roles[$i] = $roles_db[$i]->role_name;
					}
					log_message('debug', 'Roles: '.print_r($roles, true));
					$this->session->set_userdata('roles', $roles);
	
					if($this->session->flashdata('tried_to')) {
						redirect($this->session->flashdata('tried_to'));
					}else {
						redirect('welcome');
					}
				} else {
					// Login FAIL
					$data['page_title'] = 'Identification';
					$data['page_icon'] = 'user';
					$data['login_fail_msg'] = 'Error with LDAP authentication.';
					render($this, 'auth/login_form', $data);
				}
			}else {
				// No Login
				$data['page_title'] = 'Identification';
				$data['page_icon'] = 'user';
				//$data['login_fail_msg'] = 'Error with LDAP authentication.';
				render($this, 'auth/login_form', $data);
			}
		} else {
			// Already logged in...
			redirect('welcome');
		}
	}

	function logout() {
		if($this->session->userdata('logged_in')) {
			$this->authldap->logout();
		}
		redirect('welcome');
	}
}

?>
