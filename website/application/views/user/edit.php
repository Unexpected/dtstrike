<?php echo validation_errors('<p class="error">', '</p>');?>

<?php echo form_open('user/save', array('id' => 'userform', 'class' => 'form-horizontal')); ?>
<?php
	echo form_fieldset('Informations générales');
	$this->bootstrap->input('Username :', array('id' => 'username', 'name' => 'username', 'class' => 'formfield', 'disabled' => 'disabled'), $user->username);
	$this->bootstrap->input('E-mail :', array('id' => 'email', 'name' => 'email', 'class' => 'formfield'), $user->email);
	$this->bootstrap->select('Pays :', array('id' => 'country_code', 'name' => 'country_code', 'class' => 'formfield'), 'country_code', $countries, $user->country_code);

	// Organisations
	echo $this->bootstrap->start('Organisation :', array('id' => 'org_id', 'name' => 'org_id', 'class' => 'formfield'))."\t\t";
	// Ajout lignes supplémentaires
	$orgas = array_merge($orgas, array('-' => '<b>== Créer votre organisation ==</b>'));
	echo form_dropdown('org_id', $orgas, '#'.$user->org_id, ' id="org_id" onchange="orgSelect();"') . "\n";
	echo form_input(array('id' => 'org_name', 'name' => 'org_name', 'class' => 'formfield', 'placeholder' => 'Organisation'), '', ' style="display: none;"');
	echo $this->bootstrap->end();

	$this->bootstrap->textarea('Bio :', array('name' => 'bio', 'id' => 'bio', 'class' => 'formtexarea'), html_entity_decode($user->bio));
?>
<?php $this->bootstrap->submit('save', 'Sauvegarder'); ?>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>
