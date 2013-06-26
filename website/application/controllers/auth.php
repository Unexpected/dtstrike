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
        $this->load->library('Bootstrap');

        $this->load->model('Usermodel');
        $this->load->model('User_rolesmodel');
    }

    function index() {
        $this->session->keep_flashdata('tried_to');
        $this->login();
    }

    function login($errorMsg = NULL){
        $this->session->keep_flashdata('tried_to');
        if(!$this->authldap->is_authenticated()) {
            // Set up rules for form validation
            $rules = $this->form_validation;
            $rules->set_rules('username', 'Username', 'required|alpha_dash');
            $rules->set_rules('password', 'Password', 'required');

            // Do the login...
            if($rules->run() && $this->authldap->login(
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
					$this->Usermodel->email = $this->session->userdata('mail');;
					$this->Usermodel->org_id = 1; // 1 = CGI
					$this->Usermodel->country_code = 'FR';
					$this->Usermodel->created = new DateTime();
					$this->Usermodel->shutdown_date = null;
					$this->Usermodel->max_game_id = null;
					$this->Usermodel->insert() or die('Error during user creation');

					// And create role
					$user_id_arr = $this->Usermodel->search('user_id', array(array("username", $username)));
	            	if (is_array($user_id_arr) && count($user_id_arr) == 1) {
	            		$user_id = $user_id_arr[0]->user_id;
	            	} else {
	            		// FIXME : Faire mieux
	            		die('Error during user creation');
	            	}
					$this->User_rolesmodel->user_id = $user_id;
					$this->User_rolesmodel->role_name = 'USER';
					$this->User_rolesmodel->insert();
            	}
            	
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
            }else {
                // Login FAIL
				$data['page_title'] = 'Identification sur Six Challenge';
				$data['page_icon'] = 'user';
				$data['login_fail_msg'] = 'Error with LDAP authentication.';
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
