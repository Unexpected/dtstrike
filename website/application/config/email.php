<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Configs for email library
 * 
 */

// Protocol
$config['protocol'] = 'smtp';

// SMTP parameters
$config['smtp_host'] = 'smtp.uk.logica.com';
$config['smtp_port'] = 25;
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_timeout'] = 1;

// Type
$config['mailtype'] = 'html';

// Config
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
