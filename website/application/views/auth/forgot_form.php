<?php
	echo validation_errors('<p class="form_error">', '</p>');
	if (isset($error_msg)) echo '<p class="form_error">'.$error_msg.'</p>';
	
	echo form_open('auth/forgot', array('id' => 'forgotform', 'class' => 'form-horizontal'));

	form_hidden('action', 'forgot');
	$this->bootstrap->input('E-mail :', array('name' => 'email', 'id' => 'email', 'class' => 'formfield', 'placeholder' => 'Addresse e-mail du compte'), $email);
			
	$this->bootstrap->submit('forgot', "Réinitialiser");
	echo form_close();
?>
