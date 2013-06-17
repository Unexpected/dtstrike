<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
      <title>Great Success!</title>
  </head>
  <body>
    <?php if($logged_in): ?>
      <p><?= $username ?> has been logged in with a role <?= $role_name ?>(<?= $role_level ?>).</p>
      <p>Session ID: <?= $id ?></p>
      <p>User Agent: <?= $user_agent ?></p>
      <p><?php echo anchor('/auth/logout', 'Logout', ''); ?></p>
    <?php else: ?>
      <p>You need to <?php echo anchor('/auth/login', 'login', ''); ?> before you log out...</p>
    <?php endif;?>
  </body>
</html>