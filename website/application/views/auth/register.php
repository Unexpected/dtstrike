<?php
	if($this->config->item('submissions_open')) {
		echo validation_errors('<p class="form_error">', '</p>');
		if (isset($register_fail_msg)) echo '<p class="form_error">'.$register_fail_msg.'</p>';
		
		echo form_open('auth/register', array('id' => 'registerform', 'class' => 'form-horizontal'));

		echo form_fieldset('Complétez tous les champs');
		form_hidden('action', 'register');
		$this->bootstrap->input('E-mail :', array('name' => 'email', 'id' => 'email', 'class' => 'formfield', 'placeholder' => 'Addresse e-mail valide @logica.com'), $email);
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
		
		if (false) {
		echo form_fieldset('Ou connectez vous avec :');
?>
    <button class="g-signin"
        data-scope="https://www.googleapis.com/auth/plus.login"
        data-requestvisibleactions="http://schemas.google.com/AddActivity"
        data-clientId="1037751703998.apps.googleusercontent.com}"
        data-accesstype="offline"
        data-callback="onSignInCallback"
        data-theme="dark"
        data-cookiepolicy="single_host_origin">
    </button>
	<script type="text/javascript" src="https://plus.google.com/js/client:plusone.js"></script>
<script type="text/javascript">
function onSignInCallback(authResult) {
    /*$('#authResult').html('Auth Result:<br/>');
    for (var field in authResult) {
      $('#authResult').append(' ' + field + ': ' + authResult[field] + '<br/>');
    }
    if (authResult['access_token']) {
      // The user is signed in
      this.authResult = authResult;
      helper.connectServer();
      // After we load the Google+ API, render the profile data from Google+.
      gapi.client.load('plus','v1',this.renderProfile);
    } else if (authResult['error']) {
      // There was an error, which means the user is not signed in.
      // As an example, you can troubleshoot by writing to the console:
      console.log('There was an error: ' + authResult['error']);
      $('#authResult').append('Logged out');
      $('#authOps').hide('slow');
      $('#gConnect').show();
    }
    */
    console.log('authResult', authResult);
}
</script>
<?php
		echo form_fieldset_close();
		}
		
		echo form_close();
	} else {
		echo "Concours terminé !";
	}
?>
