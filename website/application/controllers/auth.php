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
        $this->load->library('table');
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
                if($this->session->flashdata('tried_to')) {
                    redirect($this->session->flashdata('tried_to'));
                }else {
                $this->load->view('auth/success_view', array(
                    'username' => $this->session->userdata('username'), 
                    'role_name' => $this->session->userdata('role_name'),
                    'role_level' => $this->session->userdata('role_level'),
                    'logged_in' => $this->session->userdata('logged_in'),
                    'name' => $this->session->userdata('name'),
                    'id' => $this->session->userdata('session_id'),
                    'user_agent' => $this->session->userdata('user_agent')
                  )
                );
                }
            }else {
                // Login FAIL
                $this->load->view('auth/login_form', array('login_fail_msg'
                                        => 'Error with LDAP authentication.'));
            }
        }else {
                // Already logged in...
                $this->load->view('auth/success_view', array(
                    'username' => $this->session->userdata('username'), 
                    'role_name' => $this->session->userdata('role_name'),
                    'role_level' => $this->session->userdata('role_level'),
                    'logged_in' => $this->session->userdata('logged_in'),
                    'name' => $this->session->userdata('name'),
                    'id' => $this->session->userdata('session_id'),
                    'user_agent' => $this->session->userdata('user_agent')
                  )
                );
        }
    }

    function logout() {
        if($this->session->userdata('logged_in')) {
            $data['name'] = $this->session->userdata('cn');
            $data['username'] = $this->session->userdata('username');
            $data['logged_in'] = TRUE;
            $this->authldap->logout();
        } else {
            $data['logged_in'] = FALSE;
        }
            $this->load->view('auth/logout_view', $data);
    }
}

?>
