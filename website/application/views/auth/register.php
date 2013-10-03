<?php
	if($this->config->item('submissions_open')) {
		echo validation_errors('<p class="form_error">', '</p>');
		if (isset($register_fail_msg)) echo '<p class="form_error">'.$register_fail_msg.'</p>';
		
		echo form_open('auth/register', array('id' => 'registerform', 'class' => 'form-horizontal'));

		echo form_fieldset('Complétez tous les champs');
		form_hidden('action', 'register');
		$this->bootstrap->input('E-mail :', array('name' => 'email', 'id' => 'email', 'class' => 'formfield', 'placeholder' => 'Addresse e-mail valide'), $email);
		$this->bootstrap->input('Utilisateur :', array('name' => 'username', 'id' => 'username', 'class' => 'formfield', 'placeholder' => "> 6 caract [a-z, A-Z, 0-9, '-', '_' et '.']"), $username);
		$this->bootstrap->password('Mot de passe :', array('name' => 'password', 'id' => 'password', 'class' => 'formfield', 'placeholder' => 'Mot de passe'), $password);
		$this->bootstrap->password('Confirmation :', array('name' => 'password2', 'id' => 'password2', 'class' => 'formfield', 'placeholder' => 'Mot de passe'), $password2);
		$this->bootstrap->select('Pays :', array('name' => 'country_code', 'id' => 'country_code', 'class' => 'formfield'), 'country_code', $countries, $country_code);
		
		// Organisations
		echo $this->bootstrap->start('Organisation :', array('id' => 'org_id', 'name' => 'org_id', 'class' => 'formfield'))."\t\t";
		// Ajout lignes supplémentaires
		$orgas = array_merge($orgas, array('-' => '<b>== Créer votre organisation ==</b>'));
		echo form_dropdown('org_id', $orgas, $org_id, ' id="org_id" onchange="orgSelect();"') . "\n";
		echo form_input(array('id' => 'org_name', 'name' => 'org_name', 'class' => 'formfield', 'placeholder' => 'Organisation'), $org_name, ' style="display: none;"');
		echo $this->bootstrap->end();
		echo form_fieldset_close();
		
		$this->bootstrap->submit('register', "Créer son compte");
		echo form_close();
	} else {
		echo "Concours terminé !";
	}
?>
