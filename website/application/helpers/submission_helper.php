<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function current_user_id() {
	$ci =& get_instance();

	if ( isset($ci->session->userdata('user_id')) ) {
		return $ci->session->userdata('user_id');
	} else {
		return -1;
	}
}

function has_recent_submission() {
	// TODO Check if user has recent submission
	return FALSE;
}

function create_new_submission_for_current_user() {
	// TODO Save new submission
	return TRUE;
}

function current_submission_id() {
	// TODO Return last submission ID
}

function submission_directory($submission_id) {
	// TODO Return directory for submission ID
}

function update_current_submission_status($status) {
	// TODO Update current submission status
}
