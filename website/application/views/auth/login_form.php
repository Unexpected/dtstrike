<p>
Pour vous identifier, vous pouvez utiliser :
<ul>
	<li>soit votre compte <code>Groupinfra</code>, si vous en possédez un.</li>
	<li>soit le compte CGI Challenge que vous avez créé.</li>
</ul>
</p>

<?php echo form_open('auth/login', array('id' => 'loginform', 'class' => 'form-horizontal')); ?>
<?php echo form_fieldset('Entrez vos identifiants'); ?>
<?php
	echo validation_errors('<p class="form_error">', '</p>');
	if (isset($login_fail_msg)) echo '<p class="form_error">'.$login_fail_msg.'</p>';
?>
<?php $this->bootstrap->input('Utilisateur :', array('name' => 'username', 'id' => 'username', 'class' => 'formfield', 'placeholder' => 'Utilisateur')) ?>
<?php $this->bootstrap->password('Mot de passe :', array('name' => 'password', 'id' => 'password', 'class' => 'formfield', 'placeholder' => 'Mot de passe')) ?>
<?php $this->bootstrap->submit('login', 'Se connecter'); ?>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>

<p>
Si vous n'avez pas de compte, vous pouvez en <a href="<?php echo site_url('auth/register') ?>">créer un</a>.
</p>
<p>
Si vous avez oublié votre login ou mot de passe, vous pouvez <a href="<?php echo site_url('auth/forgot') ?>">les réinitialiser</a>.
</p>