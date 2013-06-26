<?php echo validation_errors('<p class="error">', '</p>');?>

<?php echo form_open('admin/user_save', array('id' => 'userform', 'class' => 'form-horizontal')); ?>
<?php echo form_fieldset('Modification'); ?>

<?php 
	echo form_hidden('user_id', $user->user_id);
	$this->bootstrap->input('Username :', array('id' => 'username', 'class' => 'formfield', 'disabled' => 'disabled'), $user->username);
	$this->bootstrap->input('E-mail :', array('id' => 'email', 'class' => 'formfield'), $user->email);
	$this->bootstrap->select('Organisation :', array('id' => 'org_id', 'class' => 'formfield'), 'org_id', $orgas, $user->org_id);
	$this->bootstrap->select('Pays :', array('id' => 'country_code', 'class' => 'formfield'), 'country_code', $countries, $user->country_code);
	$this->bootstrap->multiselect('Rôles :', array('id' => 'roles', 'class' => 'formfield'), 'roles', $roles, $user_roles);
	
?>

<?php $this->bootstrap->submit('save', 'Sauvegarder'); ?>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>
