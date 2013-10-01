<?php echo validation_errors('<p class="error">', '</p>');?>

<?php echo form_open('user/save', array('id' => 'userform', 'class' => 'form-horizontal')); ?>
<?php
	echo form_fieldset('Informations générales');
	$this->bootstrap->input('Username :', array('id' => 'username', 'name' => 'username', 'class' => 'formfield', 'disabled' => 'disabled'), $user->username);
	$this->bootstrap->input('E-mail :', array('id' => 'email', 'name' => 'email', 'class' => 'formfield'), $user->email);
	$this->bootstrap->select('Pays :', array('id' => 'country_code', 'name' => 'country_code', 'class' => 'formfield'), 'country_code', $countries, $user->country_code);

	echo form_fieldset('Choix de l\'organisation');
	$this->bootstrap->select('Existante :', array('id' => 'org_id', 'name' => 'org_id', 'class' => 'formfield'), 'org_id', $orgas, $user->org_id);
	echo $this->bootstrap->start('ou') . $this->bootstrap->end();
	$this->bootstrap->input('Création :', array('id' => 'org_name', 'name' => 'org_name', 'class' => 'formfield'), '');
	?>

<?php $this->bootstrap->submit('save', 'Sauvegarder'); ?>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>
