<?php
	$menu = array(
		array('HAL Challenge', '', 'globe', '')
		, array("Accueil", site_url("welcome"), '', '')
		, array("Classement actuel", site_url("game/rank"), '', '')
		, array("Dernières parties", site_url("game"), '', 'nav-hide-on-mobile')
		, array("Les règles du concours", site_url("game/rules"), '', '')
		, array('Communauté', '', 'group', 'nav-hide-on-mobile')
		, array("Le forum", base_url("forum"), '', 'nav-hide-on-mobile')
		, array("IRC", 'irc://irc.freenode.net/halchallenge', '', 'nav-hide-on-mobile')
		, array("IRC webclient", 'http://webchat.freenode.net?channels=%23halchallenge', '', 'nav-hide-on-mobile')
		, array('Le concours', '', 'gamepad', '')
		, array("Démarrage rapide", site_url("game/start"), '', 'nav-hide-on-mobile')
		, array("Les kits de démarrage", site_url("game/kits"), '', 'nav-hide-on-mobile')
		, array("Tutoriels et Stratégies", site_url("game/tuto"), '', 'nav-hide-on-mobile')
		, array("Spécifications", site_url("game/specs"), '', 'nav-hide-on-mobile')
		, array("Règle du jeu", site_url("game/game_rules"), '', '')
		//, array("Les cartes officielles", site_url("game/maps"), '', 'nav-hide-on-mobile')
	);
	if (verify_user_role($this, "league", TRUE)) {
		$menu = array_merge($menu, array(array("Ligues", site_url("league"), '', 'nav-hide-on-mobile')));
	}
	if (verify_user_role($this, "tournament", TRUE)) {
		$menu = array_merge($menu, array(array("Tournois", site_url("tournament"), '', 'nav-hide-on-mobile')));
	}
	$menu = array_merge($menu, array(array('Mon compte', '', 'user', '')));
	
	if (!is_logged_in($this)) {
		$menu = array_merge($menu, array(array("S'enregistrer", site_url("auth/register"), '', 'nav-hide-on-mobile')));
		$menu = array_merge($menu, array(array("Se connecter", site_url("auth/login"), '', '')));
	} else {
		$menu = array_merge($menu, array(array("Mon compte", site_url("user"), '', 'nav-hide-on-mobile')
		, array("Mes IAs", site_url("user/bots"), '', 'nav-hide-on-mobile')
		, array("Mes parties", site_url("game/mine"), '', '')));

		if (verify_user_role($this, "league", TRUE)) {
			$menu = array_merge($menu, array(array("Mes ligues", site_url("league/mine"), '', 'nav-hide-on-mobile')));
		}

		if (verify_user_role($this, "tournament", TRUE)) {
			$menu = array_merge($menu, array(array("Mes tournois", site_url("tournament/mine"), '', 'nav-hide-on-mobile')));
		}
		
		$menu = array_merge($menu, array(array("Se déconnecter", site_url("auth/logout"), '', '')));

		if (verify_user_role($this, "admin", TRUE)) {
			$menu = array_merge($menu, array(array('Administration', '', 'cogs', 'nav-hide-on-mobile')
			, array("Administration", site_url("admin"), '', 'nav-hide-on-mobile')));
		}
	}
?>
<div id="menu" class="span3">
	<ul class="nav nav-list">
<?php
	foreach ($menu as $menuEntry) {
		if ($menuEntry[1] == '') {
?>
		<li class="nav-header <?php echo $menuEntry[3]; ?>"><i class="icon-<?php echo $menuEntry[2]; ?> icon-2x"></i><?php echo $menuEntry[0]; ?></li>
<?php
		} else {
?>
		<li class="<?php echo $menuEntry[3]; ?>"><a href="<?php echo $menuEntry[1]; ?>"><?php echo $menuEntry[0]; ?></a></li>
<?php
		}
	}
?>
	</ul>
</div>
