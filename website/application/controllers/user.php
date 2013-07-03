<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->library('Form_validation');
		$this->load->library('AuthLDAP');

		$this->load->model('Usermodel');
		$this->load->model('Submissionmodel');
		
		$this->load->helper('submission');
	}

	public function index()
	{
		verify_user_logged($this, 'user');

		$data['page_title'] = 'Mon compte';
		$data['page_icon'] = 'cogs';
		render($this, 'user/index', $data);
	}

	public function bots()
	{
		verify_user_logged($this, 'user/bots');

		$data['page_title'] = "Mes bots";
		$data['page_icon'] = 'fighter-jet';
		render($this, 'todo', $data);
	}

	public function view($user_id)
	{

		$this->Usermodel->db->select('username, email, organization.name as "org_name", country.name as "country_name", created', false);
		$this->Usermodel->db->from('user');
		$this->Usermodel->db->join('organization', 'organization.org_id = user.org_id');
		$this->Usermodel->db->join('country', 'country.country_code = user.country_code');
		$this->Usermodel->db->order_by("username", "asc");
		$this->Usermodel->db->order_by('user_id', $user_id);

		$query = $this->Usermodel->db->get();
		if ($query->num_rows())  {
			$users = $query->result();
		} else {
			$users = array();
		}
		if (count($users) < 1) {
			show_error("Utilisateur avec l'ID $user_id non disponible.");
		}
		$data['user'] = $users[0];

		$data['page_title'] = "Fiche du joueur";
		$data['page_icon'] = 'user';
		render($this, 'user/view', $data);
	}

	public function bot_upload()
	{
		verify_user_logged($this, 'user/bot_upload');

		$data = $this->input->post();
		log_message('debug', print_r($data, true));
		// Check form
		if (isset($data['send'])) {
			// Lecture du fichier

			// Sauvegarde
			log_message('debug', print_r( $_FILES['uploadedfile'], true));

			if(!$this->config->item('submissions_open'))
				$errors[] = "Nuh-uh. The contest is over. No more submissions.";

			if (count($errors) == 0) {
				if (has_recent_submission()) {
					$errors[] = "Sorry your last submission was too recent.";
				} else {
					$errors = upload_errors($errors);
				}
			}

			if (count($errors) == 0) {
				log_message('debug', 'CREATE SUBMISSION');
				if (!create_new_submission_for_current_user()) {
					$errors[] = "Problem while creating submission entry in database. ".mysql_error();
				}
			}

			if (count($errors) == 0) {
				log_message('debug', 'COPY SUBMISSION FILE');
				// 				$submission_id = current_submission_id();
				// 				$destination_folder = submission_directory($submission_id);
				// 				$filename = basename($_FILES['uploadedfile']['name']);
				// 				if (ends_with($filename, ".zip")) {
				// 					$filename = "entry.zip";
				// 				}
				// 				if (ends_with($filename, ".tar.gz")) {
				// 					$filename = "entry.tar.gz";
				// 				}
				// 				if (ends_with($filename, ".tgz")) {
				// 					$filename = "entry.tgz";
				// 				}
				// 				if (ends_with($filename, ".tar.xz")) {
				// 					$filename = "entry.tar.xz";
				// 				}
				// 				if (ends_with($filename, ".txz")) {
				// 					$filename = "entry.txz";
				// 				}
				// 				if (ends_with($filename, ".tar.bz2")) {
				// 					$filename = "entry.tar.bz2";
				// 				}
				// 				if (ends_with($filename, ".tbz")) {
				// 					$filename = "entry.tbz";
				// 				}
				// 				$target_path = $destination_folder . '/' . $filename;
				// 				delete_directory($destination_folder);
				// 				if (!mkdir($destination_folder, 0775, true)) {
				// 					update_current_submission_status(90);
				// 					$errors[] = "Problem while creating submission directory.";
				// 				} else {
				// 					if (!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				// 						update_current_submission_status(90);
				// 						$errors[] = "Failed to move file from temporary to permanent " .
				// 								"location.";
				// 						update_current_submission_status(30);
				// 					} else {
				// 						chmod($destination_folder, 0775);
				// 						chmod($target_path, 0664);
				// 						if (!update_current_submission_status(20)) {
				// 							$errors[] = "Failed to update the submission status in the " .
				// 									"database.";
				// 						}
				// 					}
				// 				}
			}

			if (count($errors) == 0) {
				redirect('user');
			} else {
				$data['page_title'] = "Upload d'un bot";
				$data['page_icon'] = 'fighter-jet';
				$data['errors'] = $errors;
				render($this, 'user/bot_upload', $data);
			}
		} else {
			$data['page_title'] = "Upload d'un bot";
			$data['page_icon'] = 'fighter-jet';
			render($this, 'user/bot_upload', $data);
		}
	}
	
	private function upload_errors($errors) {
		$post_max_size = intval(str_replace('M', '', ini_get('post_max_size'))) * 1024 * 1024;
		$content_length = intval($_SERVER['CONTENT_LENGTH']);
		if ($content_length > $post_max_size) {
			$errors[] = "Your zip file may be larger than the maximum allowed " .
					"size, ".ini_get('upload_max_filesize').". " .
					"You probably have some executables or other larger " .
					"files in your zip file. Re-zip your submission, being sure to " .
					"include only the source code.";
		} elseif (count($_FILES) == 0 || count($_FILES['uploadedfile']) == 0
				|| strlen($_FILES['uploadedfile']['name']) == 0
				|| $_FILES['uploadedfile']['error'] == UPLOAD_ERR_NO_FILE) {
			$errors[] = "Somehow you forgot to upload a file!";
		} elseif ($_FILES['uploadedfile']['error'] > 0) {
			if ($_FILES['uploadedfile']['error'] == UPLOAD_ERR_FORM_SIZE or
			$_FILES['uploadedfile']['error'] == UPLOAD_ERR_INI_SIZE) {
				$errors[] = "Your zip file may be larger than the maximum allowed " .
						"size, ".ini_get('upload_max_filesize').". " .
						"You probably have some executables or other larger " .
						"files in your zip file. Re-zip your submission, being sure to " .
						"include only the source code.";
			} else {
				$errors[] = "General upload error: " . $_FILES['uploadedfile']['error'];
			}
		} else {
			$filename = basename($_FILES['uploadedfile']['name']);
			if (!ends_with($filename, ".zip") &&
			!ends_with($filename, ".tar.xz") &&
			!ends_with($filename, ".tar.bz2") &&
			!ends_with($filename, ".txz") &&
			!ends_with($filename, ".tbz") &&
			!ends_with($filename, ".tgz") &&
			!ends_with($filename, ".tar.gz")) {
				$errors[] = "Invalid file type. Must be zip, tgz, tar.gz, tbz, tar.bz2, txz, or tar.xz";
			}
		}
		return $errors;
	}
}
