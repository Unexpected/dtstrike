<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->library('Form_validation');
		$this->load->library('AuthLDAP');
        $this->load->library('Bootstrap');

		$this->load->model('Usermodel');
		$this->load->model('Submissionmodel');

		$this->load->helper('submission');
		$this->load->helper('file_system');
		$this->load->helper('rank');
	}

	public function index()
	{
		verify_user_logged($this, 'user');
		
		// Récupérer le classement du joueur
		$user_id = current_user_id();
		$query = get_my_rank_query($user_id);

		$result_id = $this->Submissionmodel->db->simple_query($query);
		
		if ($result_id === FALSE) {
			$data['place'] = '';
		} else {
			$row = mysql_fetch_assoc($result_id);
			if ($row) {
				$rank = $row['rank'];
				$data['place'] = $rank . ($rank == 1 ? 'er' : 'ème');
			}
		}

		$data['page_title'] = 'Mon compte';
		$data['page_icon'] = 'cogs';
		render($this, 'user/index', $data);
	}

	public function bots()
	{
		verify_user_logged($this, 'user/bots');

		$user_id = current_user_id();
		$this->Submissionmodel->db->select('submission_id, version, status, language.language_id, language.name as "language_name", rank', false);
		$this->Submissionmodel->db->from('submission');
		$this->Submissionmodel->db->join('language', 'language.language_id = submission.language_id');
		$this->Submissionmodel->db->order_by("submission_id", "desc");
		$this->Submissionmodel->db->where('user_id', $user_id);

		$query = $this->Submissionmodel->db->get();
		if ($query->num_rows())  {
			$bots = $query->result();
		} else {
			$bots = array();
		}
		$heading = array(
			'ID',
			'Version',
			'Statut',
			'Language',
			'Rang'
		);
		$data['heading'] = $heading;
		$data['bots'] = $bots;

		$data['page_title'] = "Mes IAs";
		$data['page_icon'] = 'fighter-jet';
		render($this, 'user/bots', $data);
	}

	public function view($user_id)
	{
		if (!isset($user_id)) {
			redirect('user');
		}

		$user =  $this->Usermodel->getUserData($user_id);
		if ($user == NULL) {
			redirect('user');
		}
		$data['user'] = $user;

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
			$errors = array();
			
			// Sauvegarde
			log_message('debug', print_r( $_FILES['uploadedfile'], true));

			if(!$this->config->item('submissions_open'))
				$errors[] = "Nuh-uh. The contest is over. No more submissions.";

			if (count($errors) == 0) {
				if (has_recent_submission()) {
					$errors[] = "Sorry your last submission was too recent.";
				} else {
					$errors = $this->upload_errors($errors);
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
				$submission_id = current_submission_id();
				$destination_folder = submission_directory($submission_id);
				$filename = basename($_FILES['uploadedfile']['name']);
				if (ends_with($filename, ".zip")) {
					$filename = "entry.zip";
				}
				if (ends_with($filename, ".tar.gz")) {
					$filename = "entry.tar.gz";
				}
				if (ends_with($filename, ".tgz")) {
					$filename = "entry.tgz";
				}
				if (ends_with($filename, ".tar.xz")) {
					$filename = "entry.tar.xz";
				}
				if (ends_with($filename, ".txz")) {
					$filename = "entry.txz";
				}
				if (ends_with($filename, ".tar.bz2")) {
					$filename = "entry.tar.bz2";
				}
				if (ends_with($filename, ".tbz")) {
					$filename = "entry.tbz";
				}
				$target_path = $destination_folder . '/' . $filename;
				delete_directory($destination_folder);
				if (!mkdir($destination_folder, 0775, true)) {
					update_current_submission_status(90);
					$errors[] = "Problem while creating submission directory.";
				} else {
					if (!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
						update_current_submission_status(90);
						$errors[] = "Failed to move file from temporary to permanent " .
								"location.";
						update_current_submission_status(30);
					} else {
						chmod($destination_folder, 0775);
						chmod($target_path, 0664);
						if (!update_current_submission_status(20)) {
							$errors[] = "Failed to update the submission status in the " .
									"database.";
						}
					}
				}
			}
			
			// TODO : Ajouter la désactivation des anciens matchup
			//SELECT * FROM matchup m, matchup_player mp WHERE m.matchup_id = mp.matchup_id AND EXISTS (SELECT * FROM submission s WHERE s.submission_id = mp.submission_id AND s.latest = 0)
			// DELETE FROM matchup_player mp WHERE EXISTS (SELECT * FROM submission s WHERE s.submission_id = mp.submission_id AND s.latest = 0)
			// DELETE FROM matchup m WHERE NOT EXISTS (SELECT 1 FROM matchup_player mp WHERE m.matchup_id = mp.matchup_id)

			if (count($errors) == 0) {
				redirect('user');
			} else {
				$data['page_title'] = "Upload d'une IA";
				$data['page_icon'] = 'fighter-jet';
				$data['errors'] = $errors;
				render($this, 'user/bot_upload', $data);
			}
		} else {
			$data['page_title'] = "Upload d'une IA";
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
