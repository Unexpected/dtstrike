<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| CONTEST SETTINGS
| -------------------------------------------------------------------
*/

/* Submission are open */
$config['submissions_open'] = TRUE;

/* Repo (GiT) dir */
$config['repo_dir'] = '';

/* Result upload dir */
$config['upload_dir'] = $config['repo_dir'].'uploads/';

/* Map dir */
$config['map_dir'] = $config['repo_dir'].'maps/';

/* Replay dir */
$config['replay_dir'] =  $config['repo_dir'].'replays/';

/* Key for auto-register of remote workers */
$config['api_create_key'] = 'dtsixchallenge';

/* API log file */
$config['api_log'] = $config['repo_dir'].'logs/api.log';

/* Game result errors file */
$config['game_result_errors'] = $config['repo_dir'].'logs/game_result.log';

