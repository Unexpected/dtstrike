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


function nice_rank($rank, $rank_change) {
	return $rank.' ('.$rank_change.')';
}

function nice_user($user_id, $username) {
	return '<a href="'.site_url("user/view/$user_id").'">'.$username.'</a>';
}

function nice_country($country_code, $country_name, $flag) {
	return '<img src="'.base_url("static/flags/".$flag).'" title="'.$country_name.'" alt="'.$country_code.'" />';
}

function nice_organization($org_id, $org_name) {
	return $org_name;
}

function nice_language($lang_id, $lang_name) {
	return $lang_name;
}

function nice_ago($timestamp) {
	return $timestamp;
}

function nice_skill($skill, $mu, $sigma, $skill_change, $mu_change, $sigma_change) {
	return $skill;
}

function nice_status($status) {
	$statusLabel = getStatusLabelDescription($status);
	return '<span title="'.$statusLabel[1].'">'.$statusLabel[0].'</span>';
}
