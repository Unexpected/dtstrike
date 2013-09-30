<?php
	$menu = array(
		array('CGI Challenge', '', 'globe')
		, array("Accueil", site_url("welcome"))
		, array("Classement actuel", site_url("game/rank"))
		, array("Dernières parties", site_url("game"))
		, array("Les règles du concours", site_url("game/rules"))
		, array('Le concours', '', 'gamepad')
		, array("Démarrage rapide", site_url("game/start"))
		, array("Les kits de démarrage", site_url("game/kits"))
		, array("Tutoriels et Stratégies", site_url("game/tuto"))
		, array("Spécifications", site_url("game/specs"))
		//, array("Les cartes officielles", site_url("game/maps"))
	);
	if (verify_user_role($this, "league", TRUE)) {
		$menu = array_merge($menu, array(array("Ligues", site_url("league"))));
	}
	if (verify_user_role($this, "tournament", TRUE)) {
		$menu = array_merge($menu, array(array("Tournois", site_url("tournament"))));
	}
	$menu = array_merge($menu, array(array('Mon compte', '', 'user')));
	
	if (!is_logged_in($this)) {
		//$menu = array_merge($menu, array(array("S'enregistrer", site_url("user/register"))));
		$menu = array_merge($menu, array(array("Se connecter", site_url("auth/login"))));
	} else {
		$menu = array_merge($menu, array(array("Mon compte", site_url("user"))
		, array("Mes IAs", site_url("user/bots"))
		, array("Mes parties", site_url("game/mine"))));

		if (verify_user_role($this, "league", TRUE)) {
			$menu = array_merge($menu, array(array("Mes ligues", site_url("league/mine"))));
		}

		if (verify_user_role($this, "tournament", TRUE)) {
			$menu = array_merge($menu, array(array("Mes tournois", site_url("tournament/mine"))));
		}
		
		$menu = array_merge($menu, array(array("Se déconnecter", site_url("auth/logout"))));

		if (verify_user_role($this, "admin", TRUE)) {
			$menu = array_merge($menu, array(array('Administration', '', 'cogs')
			, array("Administration", site_url("admin"))));
		}
	}
?>
<div id="menu" class="span3">
	<ul class="nav nav-list">
<?php
	foreach ($menu as $menuEntry) {
		if ($menuEntry[1] == '') {
?>
		<li class="nav-header"><i class="icon-<?php echo $menuEntry[2]; ?> icon-2x"></i><?php echo $menuEntry[0]; ?></li>
<?php
		} else {
?>
		<li><a href="<?php echo $menuEntry[1]; ?>"><?php echo $menuEntry[0]; ?></a></li>
<?php
		}
	}
?>
	</ul>
</div>