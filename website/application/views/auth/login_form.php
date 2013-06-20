<?php echo form_fieldset('Utilisez votre login groupinfra'); ?>
<?php echo validation_errors('<p class="error">', '</p>');?>

<?php echo form_open('auth/login', array('id' => 'loginform')); ?>
<?php
	$table = array(
		array('', ''),
	    array(
	    	form_label('Utilisateur :', 'username'),
	    	form_input(array('name' => 'username', 'id' => 'username', 'class' => 'formfield'))
	    ),
	    array(
	    	form_label('Mot de passe :', 'password'),
	    	form_password(array('name' => 'password', 'id' => 'password','class' => 'formfield'))
		)
	);
	echo $this->table->generate($table);
?>
<br />
<?php echo form_submit('login', 'Login'); ?>
<?php echo form_close(); ?>
<?php echo form_fieldset_close(); ?>
