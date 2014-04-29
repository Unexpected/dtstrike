<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->load->model('Usermodel');
        $this->load->model('User_rolesmodel');
        $this->load->model('Rolesmodel');
        $this->load->model('Organizationmodel');
        $this->load->model('Countrymodel');
        
        $this->load->library('table');
        $this->load->library('Bootstrap');
    }

	public function index()
	{
		verify_user_logged($this, 'admin');
		verify_user_role($this, 'admin');

		$data['page_title'] = 'Administration';
		$data['page_icon'] = 'cogs';

		render($this, 'admin/index', $data);
	}

	public function users()
	{
		verify_user_logged($this, 'admin/users');
		verify_user_role($this, 'admin');

		$this->Usermodel->db->select('user_id, username, email, organization.name as "org_name", country.name as "country_name", created', false);
		$this->Usermodel->db->from('user');
		$this->Usermodel->db->join('organization', 'organization.org_id = user.org_id');
		$this->Usermodel->db->join('country', 'country.country_code = user.country_code');
		$this->Usermodel->db->order_by("user_id", "desc");

		$query = $this->Usermodel->db->get();
		if ($query->num_rows())  {
			$users = $query->result();
		} else {
			$users = array();
		}
		$heading = array(
			'#',
			'Login',
			'Email',
			'Organisation',
			'Pays',
			'Date de création'
		);
		$data['heading'] = $heading;
		$data['users'] = $users;

		$data['page_title'] = "Liste des utilisateurs";
		$data['page_icon'] = 'user';

		render($this, 'admin/user_list', $data);
	}

	public function user_update($user_id)
	{
		verify_user_logged($this, 'admin/user_update');
		verify_user_role($this, 'admin');

		// Lecture du user
		$user = $this->Usermodel->search('', array(array('user_id', $user_id)));
		log_message('debug', 'User '.count($user).': '.print_r($user, TRUE));
		if (count($user) < 1) {
			show_error("Utilisateur avec l'ID $user_id non disponible.");
		}
		$data['user'] = $user[0];
		// Lecture de ses rôles
		$user_roles = $this->User_rolesmodel->search('role_name', array(array('user_id', $user_id)));
		$user_rolesArray = array();
		for ($i=0; $i<count($user_roles); $i++) {
			$user_rolesArray[$i] = $user_roles[$i]->role_name;
		}
		$data['user_roles'] = $user_rolesArray;
		
		// Lecture des référentiels
		$data['orgas'] = $this->Organizationmodel->getAllForCombo('org_id', 'name', false, '#');
		$data['countries'] = $this->Countrymodel->getAllForCombo('country_code', 'name');
		$data['roles'] = $this->Rolesmodel->getAllForCombo('name', 'descr');

		// Affichage
		$data['page_title'] = "MaJ d'un utilisateur";
		$data['page_icon'] = 'save';
		render($this, 'admin/user_edit', $data);
	}

	public function user_save()
	{
		verify_user_logged($this, 'admin');
		verify_user_role($this, 'admin');
		
		// Lecture des données
		$data = $this->input->post();
		
		$user_id = $data['user_id'];
		if ($user_id > 0) {
			$user = $this->Usermodel->search('', array(array('user_id', $user_id)));
			if (count($user) < 1) {
				show_error("Utilisateur avec l'ID $user_id non disponible.");
			}
			$user = $user[0];
			
			// MaJ du user
			$userdata['org_id'] = $data['org_id'];
			$userdata['country_code'] = $data['country_code'];
			$userdata['email'] = $data['email'];
			$this->Usermodel->update('user_id', $user_id, $userdata);

			// MaJ des roles
			$query = "DELETE FROM user_roles WHERE user_id=".$this->User_rolesmodel->db->escape($user_id);
			$this->User_rolesmodel->db->_execute($query);
			if (isset($data['roles'])) {
				if (!is_array($data['roles'])) {
					$data['roles'] = array($data['roles']);
				}
				$query = "INSERT INTO user_roles (user_id, role_name) VALUES ";
				for ($i=0; $i<count($data['roles']); $i++) {
					$role = $data['roles'][$i];
					
					if ($i > 0) {
						$query .= ', ';
					}
					$query .= '('.$this->User_rolesmodel->db->escape($user_id).', '.$this->User_rolesmodel->db->escape($role).')';
				}
			}
			$this->User_rolesmodel->db->_execute($query);
		}

		redirect('admin/users');
	}
}
