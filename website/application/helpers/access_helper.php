<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_logged_in'))
{
	function is_logged_in($context)
	{
		return $context->session->userdata('logged_in');
	}
}

if ( ! function_exists('get_user_roles'))
{
	function get_user_roles($context)
	{
		return $context->session->userdata('roles');
	}
}

if ( ! function_exists('verify_user_logged'))
{
	function verify_user_logged($context, $origin)
	{
		if (!is_logged_in($context)) {
			$context->session->set_flashdata('tried_to', $origin);
			redirect('auth/login');
		}
	}
}

if ( ! function_exists('verify_user_role'))
{
	function verify_user_role($context, $role_to_check, $returnValue = FALSE)
	{
		$roles = get_user_roles($context);
		
		if (!is_array($roles) || count($roles) == 0) {
			if ($returnValue) return false;
			redirect("welcome");
		}
		
		// Admin
		if ($roles[0] == 'ADMIN') {
			if ($returnValue) return true;
			return;
		}
		
		if (array_search($role_to_check, $roles) === FALSE) {
			if ($returnValue) return false;
			redirect("welcome");
		}
		
		if ($returnValue) return true;
	}
}
