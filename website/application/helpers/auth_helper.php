<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_logged_in'))
{
	function is_logged_in()
	{
		return $this->authldap->is_authenticated();
	}
}
