<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bootstrap {

	public function __construct()
	{
		log_message('debug', "Bootstrap Class Initialized");
	}

	public function input($label_text = '', $attributes = array(), $value = '')
	{// A revoir, c'est aussi illisible que les renderers JSF...
		echo $this->start($label_text, $attributes) . "\t\t" . form_input($attributes, $value) . "\n" . $this->end();
	}

	public function password($label_text = '', $attributes = array(), $value = '')
	{
		echo $this->start($label_text, $attributes) . "\t\t" . form_password($attributes, $value) . "\n" . $this->end();
	}

	public function submit($name, $value = '')
	{
		echo $this->start() . "\t\t" . form_submit(array('name' => $name, 'class'=> "btn"), $value) . "\n" . $this->end();
	}

	public function multiselect($label_text = '', $attributes = array(), $name = '', $options = array(), $selected = array())
	{
		echo $this->start($label_text, $attributes) . "\t\t" . 
			form_multiselect($name, $options, $selected) . "\n" . 
			$this->end();
	}

	public function select($label_text = '', $attributes = array(), $name = '', $options = array(), $selected = array())
	{
		echo $this->start($label_text, $attributes) . "\t\t" . 
			form_dropdown($name, $options, $selected) . "\n" . 
			$this->end();
	}

	public function text($label_text = '', $value = '')
	{
		echo $this->start($label_text) . "\t\t" . 
			'<div>' . $value . "</div>\n" . 
			$this->end();
	}

	public function upload($label_text = '', $attributes = array(), $value = '')
	{
		echo $this->start($label_text, $attributes) . "\t\t" . 
			form_upload($attributes, $value) . "\n" . 
			$this->end();
	}

	function start($label_text = '', $attributes = array())
	{
		// FIXME Gérer classe CSS erreur + message
		$result = '<div class="control-group">' . "\n";
		if ($label_text != '')
		{
			$id = isset($attributes['id']) ? $attributes['id'] : '';
			$result .= "\t" . form_label($label_text, $id, array('class' => 'control-label')) . "\n";
		}
		$result .= "\t" . '<div class="controls">' . "\n";
		return $result;
	}

	function end()
	{
		return "\t</div>\n</div>\n";
	}
	
	function nice_rank($rank, $rank_change) {
		return $rank.' ('.$rank_change.')';
	}
	
	function nice_user($user_id, $username) {
		return '<a href="'.site_url("user/view/$user_id").'">'.$username.'</a>';
	}
	
	function nice_country($country_code, $country_name, $flag) {
		return $country_name;
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

}

?>
