<p>
Vous devez uploader ici un fichier zip (ou équivalent) contenant :<br/>
<ul>
	<li>Tous les fichiers nécessaires à votre IA</li>
	<li>Le fichier principal de votre IA nommé "MyBot" (attention à la casse)</li>
</ul>
</p>
<table>
<tr>
	<td>Voici un exemple de contenu pour une IA écrite en <code>Java</code> :</td>
	<td><img src="<?php echo base_url('static/images/bot_java.png') ?>" class="pull-right" /></td>
</tr>
<tr>
	<td>Et un autre exemple d'une IA écrite en <code>JavaScript</code> :</td>
	<td><img src="<?php echo base_url('static/images/bot_js.png') ?>" class="pull-right" /></td>
</tr>
</table>
<p>&nbsp;</p>
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
