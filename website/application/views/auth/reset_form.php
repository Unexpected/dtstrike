<?php
	echo validation_errors('<p class="form_error">', '</p>');
	if (isset($error_msg)) echo '<p class="form_error">'.$error_msg.'</p>';
	
	echo form_open('auth/reset_validate', array('id' => 'resetform', 'class' => 'form-horizontal'));
	echo '<h3>Complétez tous les champs</h3>';

	echo form_hidden('confirmation_code', $confirmation_code);
	$this->bootstrap->password('Mot de passe :', array('name' => 'password', 'id' => 'password', 'class' => 'formfield', 'placeholder' => 'Mot de passe'), "");
	$this->bootstrap->password('Confirmation :', array('name' => 'password2', 'id' => 'password2', 'class' => 'formfield', 'placeholder' => 'Mot de passe'), "");
	
	$this->bootstrap->submit('reinit', "Sauvegarder");
	echo form_close();
?>
