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
	// Check if user has recent submission
	$ci =& get_instance();

	$user_id = current_user_id();
	if ($user_id == -1) {
		return FALSE;
	}

	$query = "SELECT COUNT(*) FROM submission WHERE user_id = '".$user_id."' AND
			(status < 30 OR (status in (40, 100) AND timestamp >= (NOW() - INTERVAL 1 MINUTE)))";
	$result_id = $ci->Submissionmodel->db->simple_query($query);

	if ($result_id === FALSE) {
		return FALSE;
	}
	if (!$row = mysql_fetch_row($result_id)) {
		return FALSE;
	}
	if ($row[0] == 0) {
		return FALSE;
	}
	return TRUE;
}

function create_new_submission_for_current_user() {
	// Save new submission
	$ci =& get_instance();

	$user_id = current_user_id();
	if ($user_id == -1) {
		return FALSE;
	}
    $query = "insert into submission (user_id, version, status, timestamp, language_id)
                               select user_row.user_id,
                                      coalesce(max(s.version), 0) + 1 as next_version,
                                      user_row.status,
                                      user_row.timestamp,
                                      user_row.language_id
                               from (select $user_id as user_id,
                                     20 as status,
                                     current_timestamp() as timestamp,
                                     0 as language_id) user_row
                               left outer join submission s
                                   on s.user_id = user_row.user_id
                               group by user_row.user_id,
                                        user_row.status,
                                        user_row.timestamp,
                                        user_row.language_id;";
	return $ci->Submissionmodel->db->simple_query($query);
}

function current_submission_id() {
	$ci =& get_instance();

	$user_id = current_user_id();
	if ($user_id == -1) {
		return -1;
	}
	$query = "SELECT submission_id, timestamp FROM submission " .
			"WHERE user_id = " . $user_id . " ORDER BY timestamp DESC LIMIT 1";
	$result_id = $ci->Submissionmodel->db->simple_query($query);
	if (!$result) {
		log_message("error", $query, mysql_error());
		return -1;
	}
	if ($row = mysql_fetch_assoc($result)) {
		return $row['submission_id'];
	} else {
		return -1;
	}
}

function submission_directory($submission_id) {
	$ci =& get_instance();

	$submission_id = intval($submission_id);
	return $ci->config->item('uploads_path')."/".strval((int)($submission_id/1000))."/".strval($submission_id);
}

function update_current_submission_status($status) {
	// Update current submission status
	$submission_id = current_submission_id();
	if ($submission_id < 0) {
		log_message("error",  "submission_id = " . $submission_id . "");
		return FALSE;
	}
	$user_id = current_user_id();
	if ($user_id < 0) {
		log_message("error",  "user_id = " . $user_id . "");
		return FALSE;
	}
	$query = "UPDATE submission SET status = " . $new_status .
			" WHERE submission_id = " . $submission_id . " AND user_id = " . $user_id;
	return $ci->Submissionmodel->db->simple_query($query);
}
