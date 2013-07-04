<?php
	// Errors
	echo validation_errors('<p class="error">', '</p>');
	if (isset($errors) && count($errors) > 0) {
		echo '<p class="error">';
		foreach ($errors as $error) {
			echo $error.'<br/>';
		}
		echo '</p>';
	}
	
	echo form_open('user/bot_upload', array('id' => 'botform', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal'));
	echo form_fieldset('Choisissez le fichier ZIP contenant votre bot :');
	
	echo form_hidden('MAX_FILE_SIZE', '2097152');
	$this->bootstrap->upload('Zip :', array('name' => 'uploadedfile', 'id' => 'uploadedfile', 'class' => 'formfield', 'placeholder' => 'Choisir un fichier'));
	
	$this->bootstrap->submit('send', 'Envoyer');
	
	echo form_fieldset_close();
	echo form_close();
?>
