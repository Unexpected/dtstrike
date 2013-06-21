<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('render'))
{
	function render($context, $view, $data = NULL)
	{
		$context->load->view('all_header', $data);
		$context->load->view($view, $data);
		$context->load->view('all_footer');
	}
}
