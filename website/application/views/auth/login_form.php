<?php echo validation_errors('<p class="error">', '</p>');?>

<?php echo form_open('auth/login', array('id' => 'loginform', 'class' => 'form-horizontal')); ?>
<?php echo form_fieldset('Utilisez votre login groupinfra'); ?>
<?php $this->bootstrap->input('Utilisateur :', array('name' => 'username', 'id' => 'username', 'class' => 'formfield', 'placeholder' => 'Utilisateur')) ?>
<?php $this->bootstrap->password('Mot de passe :', array('name' => 'password', 'id' => 'password', 'class' => 'formfield', 'placeholder' => 'Mot de passe')) ?>
<?php $this->bootstrap->submit('login', 'Login'); ?>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>
