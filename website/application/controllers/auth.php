<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->library('Form_validation');
        $this->load->library('email');

		$this->load->model('Usermodel');
		$this->load->model('User_rolesmodel');
        $this->load->model('Organizationmodel');
        $this->load->model('Countrymodel');
        $this->load->model('Login_attemptmodel');

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

		// Données soumises
		$data['email'] = isset($_POST['email']) ? $_POST['email'] : "";
		$data['username'] = isset($_POST['username']) ? $_POST['username'] : "";
		$data['password'] = isset($_POST['password']) ? $_POST['password'] : "";
		$data['password2'] = isset($_POST['password2']) ? $_POST['password2'] : "";
		$data['country_code'] = isset($_POST['country_code']) ? $_POST['country_code'] : "FR";
		$data['org_id'] = isset($_POST['org_id']) ? $_POST['org_id'] : "";
		$data['org_name'] = isset($_POST['org_name']) ? $_POST['org_name'] : "";
		$data['bio'] = isset($_POST['bio']) ? $_POST['bio'] : "";

		$register = false;
		$error = '';
		if ($rules->run()) {
			// Valid form, try register
			// Check that the two passwords given match.
			if ($data['password'] != $data['password2']) {
				$data['register_fail_msg'] = 'Les mots de passe ne correspondent pas.';
			} else if ($data['org_id'] == "" && $data['org_name'] == "") {
				$data['register_fail_msg'] = 'Veuillez choisir ou créer une organisation.';
			} else {
				// Check if the username already exists.
				$query = array(array('username', $data['username']));
				if ($this->Usermodel->count('username', $query) != 0) {
					$data['register_fail_msg'] = 'Cet utilisateur existe déjà.';
				} else {
					// Check if the email is already in use.
					$query = array(array('email', $data['email']));
					if ($this->Usermodel->count('user_id', $query) != 0) {
						$data['register_fail_msg'] = 'Cet email est déjà utilisé.';
					} else {
						// Add the user to the database, with user access.
						$confirmation_code = md5(salt(64));
						$data['confirmation_code'] = $confirmation_code;
						
						if ($data['org_name'] != "") {
							// Create new organisation
							$this->Organizationmodel->name = htmlentities($data['org_name']);
							$ret = $this->Organizationmodel->insert();
			
							$org = $this->Organizationmodel->getOne('name', $data['org_name']);
							$data['org_id'] = $org->org_id;
						}
						
						// Create user
						$this->Usermodel->email = $data['email'];
						$this->Usermodel->username = $data['username'];
						$this->Usermodel->password = crypt_password($data['password']);
						$this->Usermodel->country_code = $data['country_code'];
						$this->Usermodel->org_id = $data['org_id'];
						$this->Usermodel->bio = htmlentities($data['bio']);
						$this->Usermodel->created = new DateTime();
						$this->Usermodel->activation_code = $confirmation_code;
						$this->Usermodel->activated = 0;
						
						$this->Usermodel->insert();
						
						
						// Send confirmation mail to user.
						if ($this->sendmail($data['email'], $confirmation_code, true)) {
							// And create role
							$user_id_arr = $this->Usermodel->search('user_id', array(array("username", $data['username'])));
							if (is_array($user_id_arr) && count($user_id_arr) == 1) {
								$user_id = $user_id_arr[0]->user_id;
								$this->User_rolesmodel->user_id = $user_id;
								$this->User_rolesmodel->role_name = 'USER';
								$this->User_rolesmodel->insert();
								
								$register = true;
							} else {
								log_message('error', 'Error during user role creation');
								$data['register_fail_msg'] = 'Erreur lors de la création de l\'utilisateur en base.';
								$this->Usermodel->delete('username', $data['username']);
							}
						} else {
							// Send mail error
							$data['register_fail_msg'] = 'Erreur lors de l\'envoi du mail de confirmation, merci de vous réinscrire ultérieurement.';
							$this->Usermodel->delete('username', $data['username']);
						}
					}
				}
			}
		} else if (isset($_POST['action'])) {
			// Register FAIL
			$data['register_fail_msg'] = 'Erreur avec les données fournies.';
		}
		
		if (!$register) {
			// Préparation des données
			$data['orgas'] = $this->Organizationmodel->getAllForCombo('org_id', 'name', true);
			$data['countries'] = $this->Countrymodel->getAllForCombo('country_code', 'name');
			
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
	
	/**
	 * Envoi d'un email
	 * 
	 * @param string $to destinataire
	 * @param boolean $register TRUE si c'est la confirmation, FALSE si c'est l'oubli de mail
	 * @return boolean
	 */
	private function sendmail($to, $confirmation_code, $register) {
		$BASE_URL = site_url();
		$BR = "<br/>\n\n";
		
		$this->email->from('cgichallenge@logica.com', 'CGI Challenge');
		$this->email->to($to);
		
		if ($register) {
			// Confirmation register
			$this->email->subject('CGI Challenge : inscription');
			$activation_url = $BASE_URL . "auth/register_validate?confirmation_code=" . $confirmation_code;
			$peer_message = "";
			$this->email->message("<html><body>" . 
					"Bienvenu au CGI Challenge !" . $BR .
					$BR .
					"Cliquez sur le lien ci-dessous pour activer votre compte :" . $BR .
					"<a href=\"" . $activation_url . "\">".$activation_url ."</a>" . $BR .
					$BR .
					"Après cette activation, vous pourrez vous authentifier avec votre compte " .
					"et participer à la compétition." . $BR .
					$peer_message . $BR . 
					"Merci pour votre participation," . $BR . 
					"Good Luck & Have Fun !" . $BR .
					"Les organisateurs." . $BR .
					"</body></html>");
		} else {
			// Password Lost
			$this->email->subject('CGI Challenge : Reset du compte');
			$reset_url = $BASE_URL . "auth/reset?confirmation_code=" . $confirmation_code;
			$this->email->message("<html><body>" . 
					"Réinitialissation de votre compte CGI Challenge !" . $BR .
					$BR .
					"Cliquez sur le lien ci-dessous pour réinitialiser votre compte :" . $BR .
					"<a href=\"" . $reset_url . "\">".$reset_url ."</a>" . $BR .
					$BR .
					"Vous pourrez ainsi modifier votre mot de passe." . $BR .
					$BR . 
					"Merci pour votre confiance," . $BR . 
					"Les organisateurs." . $BR .
					"</body></html>");
		}
		
		if (!$this->email->send()) {
			// Send mail error
			log_message('error', $this->email->print_debugger());
			return false;
		} else {
			return true;
		}
	}

	function register_validate() {
		if (is_logged_in($this)) {
			redirect('welcome');
		}

		$confirmation_code = isset($_GET['confirmation_code']) ? $_GET['confirmation_code'] : "";
		$msg = "Activation réussie";
		if ($confirmation_code == NULL || strlen($confirmation_code) <= 0) {
			$this->session->set_flashdata('error', "Echec de l'activation (101)");
		} else {
			$user_id = $this->Usermodel->get_userid_from_confirmation_code($confirmation_code);
			if (!$user_id) {
				$this->session->set_flashdata('error', "Echec de l'activation (102)");
			} else {
				$result = $this->Usermodel->activate_user($user_id);
				if (!$result) {
					$this->session->set_flashdata('error', "Echec de l'activation (103)");
				} else {
					$this->session->set_flashdata('message', "Activation réussie");
				}
			}
		}

		redirect('welcome');
	}

	function forgot() {
		if (is_logged_in($this)) {
			redirect('welcome');
		}

		// Set up rules for form validation
		$rules = $this->form_validation;
		$rules->set_rules('email', 'E-mail', 'required|valid_email');

		// Données soumises
		$data['email'] = isset($_POST['email']) ? $_POST['email'] : "";
		
		if ($rules->run()) {
			// Update user
			$confirmation_code = md5(salt(64));
			$userdata['activated'] = 0;
			$userdata['activation_code'] = $confirmation_code;
			$ret = $this->usermodel->update('email', $data['email'], $userdata);
			
			if (!$ret) {
				// Email non reconnu
				$data['error_msg'] = 'Email non reconnu !';
				$data['page_title'] = 'Réinitialisation d\'un compte';
				$data['page_icon'] = 'user';
				render($this, 'auth/forgot_form', $data);
			} else {
				// Send confirmation mail to user.
				$this->sendmail($data['email'], $confirmation_code, FALSE);
				
				$data['page_title'] = 'Réinitialisation en cours !';
				$data['page_icon'] = 'user';
				render($this, 'auth/forgot_ok', $data);
			}
		} else {
			// First display or error
			$data['page_title'] = 'Réinitialisation d\'un compte';
			$data['page_icon'] = 'user';
			render($this, 'auth/forgot_form', $data);
		}
	}

	function reset() {
		if (is_logged_in($this)) {
			redirect('welcome');
		}

		$confirmation_code = isset($_GET['confirmation_code']) ? $_GET['confirmation_code'] : "";
		if ($confirmation_code == NULL || strlen($confirmation_code) <= 0) {
			$this->session->set_flashdata('error', "Echec de la réinitialisation (101)");
			redirect('welcome');
		} else {
			$user_id = $this->Usermodel->get_userid_from_confirmation_code($confirmation_code);
			if (!$user_id) {
				$this->session->set_flashdata('error', "Echec de la réinitialisation (102)");
				redirect('welcome');
			} else {
				$data['confirmation_code'] = $confirmation_code;
				$data['page_title'] = 'Réinitialisation du mot de passe';
				$data['page_icon'] = 'user';
				render($this, 'auth/reset_form', $data);
			}
		}
	}

	function reset_validate() {
		if (is_logged_in($this)) {
			redirect('welcome');
		}
		
		$rules = $this->form_validation;
		$rules->set_rules('password', 'Password', 'required|min_length[6]');
		$rules->set_rules('password2', 'Confirmation', 'required');

		// Données soumises
		$data['confirmation_code'] = isset($_POST['confirmation_code']) ? $_POST['confirmation_code'] : "-";
		$data['password'] = isset($_POST['password']) ? $_POST['password'] : "";
		$data['password2'] = isset($_POST['password2']) ? $_POST['password2'] : "";

		if ($rules->run()) {
			// Check that the two passwords given match.
			if ($data['password'] != $data['password2']) {
				$data['error_msg'] = 'Les mots de passe ne correspondent pas.';
			} else {
				// Change password 
				$user_id = $this->Usermodel->get_userid_from_confirmation_code($data['confirmation_code']);
				
				if (!$user_id) {
					$this->session->set_flashdata('error', "Echec de la réinitialisation (103)");
				} else {
					// Update user
					$userdata['activated'] = 1;
					$userdata['password'] = crypt_password($data['password']);
					$userdata['activation_code'] = NULL;
					$ret = $this->usermodel->update('user_id', $user_id, $userdata);
					
					if ($ret) {
						$this->session->set_flashdata('message', "Mot de passe modifié, vous pouvez vous connecter.");
					} else {
						$this->session->set_flashdata('error', "Echec de la réinitialisation (104)");
					}
				}
				redirect('welcome');
			}
		}
		
		// Error, re-display form
		$data['page_title'] = 'Réinitialisation du mot de passe';
		$data['page_icon'] = 'user';
		render($this, 'auth/reset_form', $data);
	}

	function login($errorMsg = NULL){
		$this->session->keep_flashdata('tried_to');
		if (!$this->is_authenticated()) {
			// Set up rules for form validation
			$rules = $this->form_validation;
			$rules->set_rules('username', 'Username', 'required|alpha_numeric');
			$rules->set_rules('password', 'Password', 'required');

			// Do the login...
			if ($rules->run()) {
				$username = $rules->set_value('username');
				$password = $rules->set_value('password');
				
				// Log attempt
				$naive_ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
				$real_ip = mysql_real_escape_string($this->getRealIpAddr());
				$this->Login_attemptmodel->logAttempt($username, $naive_ip, $real_ip);
				
				if ($this->Usermodel->check_credentials($username, $password)) {
					// Login WIN!
					$user_id = $this->session->userdata('user_id');
						
					// Load roles from DB
					$roles_db = $this->User_rolesmodel->search('role_name', array(array("user_id", $user_id)));
					$roles = array();
					for ($i=0; $i<count($roles_db); $i++) {
						$roles[$i] = $roles_db[$i]->role_name;
					}
					$this->session->set_userdata('roles', $roles);
	
					if ($this->session->flashdata('tried_to')) {
						redirect($this->session->flashdata('tried_to'));
					} else {
						redirect('welcome');
					}
				} else {
					// Login FAIL
					$data['page_title'] = 'Identification';
					$data['page_icon'] = 'user';
					$data['login_fail_msg'] = 'Utilisateur/Mot de passe invalides ou utilisateur inactif.';
					render($this, 'auth/login_form', $data);
				}
			}else {
				// No Login
				$data['page_title'] = 'Identification';
				$data['page_icon'] = 'user';
				render($this, 'auth/login_form', $data);
			}
		} else {
			// Already logged in...
			redirect('welcome');
		}
	}

	function logout() {
		if ($this->session->userdata('logged_in')) {
	        $this->session->set_userdata(array('logged_in' => FALSE));
	        $this->session->sess_destroy();
		}
		redirect('welcome');
	}
	
    private function is_authenticated() {
        if($this->session->userdata('logged_in')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	private function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

?>
