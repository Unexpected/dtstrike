<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->load->model('Usermodel');
        $this->load->library('table');
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

		$query = $this->Usermodel->db->get();
		if ($query->num_rows())  {
			$users = $query->result_array();
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

	public function user_update()
	{
		verify_user_logged($this, 'admin/user_update');
		verify_user_role($this, 'admin');

		$data['page_title'] = "MaJ d'un utilisateur";
		$data['page_icon'] = 'save';

		render($this, 'todo', $data);
	}

	public function user_save()
	{
		verify_user_logged($this, 'admin');
		verify_user_role($this, 'admin');
		
		// TODO : Save user

		redirect('admin');
	}
}
