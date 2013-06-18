      <?php echo form_fieldset('Login'); ?>
      <?php echo validation_errors();?>

      <?php echo form_open('auth/login', array('id' => 'loginform')); ?>
      <?php
      
      $table = array(array('', ''),
          array(form_label('Username:', 'username'),
                form_input(array('name' => 'username', 'id' => 'username',
                     'class' => 'formfield'))),
          array(form_label('Password', 'password'),
                form_password(array('name' => 'password', 'id' => 'password',
                     'class' => 'formfield'))));
          echo $this->table->generate($table);
      ?>

      <?php echo form_label('Remember me', 'remember'); ?>
      <?php echo form_checkbox(array('name' => 'remember', 'id' => 'remember',
         'value' => 1,  'checked' => FALSE, 'disabled' => TRUE)); ?>
      <br />
      <?php echo form_submit('login', 'Login'); ?>
      <?php echo form_close(); ?>
      <?php echo form_fieldset_close(); ?>
