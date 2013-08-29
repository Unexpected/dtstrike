<?php
	if($this->config->item('submissions_open')) {
		echo validation_errors('<p class="form_error">', '</p>');
		if (isset($register_fail_msg)) echo '<p class="form_error">'.$register_fail_msg.'</p>';
		
		echo form_open('auth/register', array('id' => 'registerform', 'class' => 'form-horizontal'));
		echo form_fieldset('Complétez tous les champs');
		$this->bootstrap->input('E-mail :', array('name' => 'email', 'id' => 'email', 'class' => 'formfield', 'placeholder' => 'Addresse e-mail valide'), $email);
		$this->bootstrap->input('Utilisateur :', array('name' => 'username', 'id' => 'username', 'class' => 'formfield', 'placeholder' => "> 6 caract [a-z, A-Z, 0-9, '-', '_' et '.']"), $username);
		$this->bootstrap->password('Mot de passe :', array('name' => 'password', 'id' => 'password', 'class' => 'formfield', 'placeholder' => 'Mot de passe'), $password);
		$this->bootstrap->password('Confirmation :', array('name' => 'password2', 'id' => 'password2', 'class' => 'formfield', 'placeholder' => 'Mot de passe'), $password2);
		$this->bootstrap->select('Pays :', array('name' => 'country_code', 'id' => 'country_code', 'class' => 'formfield'), 'country_code', $countries, $country_code);
		$this->bootstrap->submit('register', "Créer son compte");
		echo form_fieldset_close();
		echo form_close();
	} else {
		echo "Concours terminé !";
	}
?>
