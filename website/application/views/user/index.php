<p>
<?php 
	if (isset($place)) {
?>
Vous êtes actuellement classé <code><?php echo $place; ?></code> du classement général.
<?php
	} else {
?>
Vous n'êtes pas encore classé, pensez à uploader votre première IA !
<?php
	}
?>
</p>

<p>Les actions possibles sont les suivantes :</p>
<ul>
	<li><a href="<?php echo site_url('user/bot_upload') ?>">Upload d'un bot</a></li>
	<li><a href="<?php echo site_url('user/bots') ?>">Mes bots</a></li>
	<li><a href="<?php echo site_url('game/mine') ?>">Mes parties</a></li>
<?php if (verify_user_role($this, "league", TRUE)) { ?>
	<li><a href="<?php echo site_url('league/mine') ?>">Mes ligues</a></li>
<?php } ?>
</ul>