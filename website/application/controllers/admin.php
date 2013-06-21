<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

	public function index()
	{
		verify_user_logged($this, 'admin');
		verify_user_role($this, 'admin');

		$data['page_title'] = 'Administration de DTstrike';
		$data['page_icon'] = 'cogs';

		render($this, 'todo', $data);
	}

	public function user()
	{
		verify_user_logged($this, 'admin/user');
		verify_user_role($this, 'admin');

		$data['page_title'] = "Liste des utilisateurs";
		$data['page_icon'] = 'user';

		render($this, 'todo', $data);
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
