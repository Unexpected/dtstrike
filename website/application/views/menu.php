<?php
	// FIXME : Voir si on peut mieux faire ...
	$userRoles = array();
	if (is_logged_in($this)) {
		$userRoles = array('User'=>1, "League"=>2, "Tournament"=>3, "ADMIN"=>4);
	}
	
	$menu = array(
		array('Six Challenge', '')
		, array("Accueil", site_url("welcome"))
		, array('Synthèse du concours', '')
		, array("Classement actuel", site_url("game/rank"))
		, array("Dernières parties", site_url("game"))
		, array("Les cartes officielles", site_url("game/maps"))
	);
	if (isset($userRoles["League"])) {
		$menu = array_merge($menu, array(array("Ligues", site_url("league"))));
	}
	if (isset($userRoles["Tournament"])) {
		$menu = array_merge($menu, array(array("Tournois", site_url("tournament"))));
	}
	$menu = array_merge($menu, array(array('Mon compte', '')));
	
	if (!isset($userRoles["User"])) {
		//$menu = array_merge($menu, array(array("S'enregistrer", site_url("user/register"))));
		$menu = array_merge($menu, array(array("Se connecter", site_url("auth/login"))));
	} else {
		$menu = array_merge($menu, array(array("Mon compte", site_url("user"))
		, array("Mes bots", site_url("user/bots"))
		, array("Mes parties", site_url("game/mine"))));
		
		if (isset($userRoles["League"])) {
			$menu = array_merge($menu, array(array("Mes ligues", site_url("league/mine"))));
		}
		
		if (isset($userRoles["Tournament"])) {
			$menu = array_merge($menu, array(array("Mes tournois", site_url("tournament/mine"))));
		}
		
		$menu = array_merge($menu, array(array("Se déconnecter", site_url("auth/logout"))));
		
		if (isset($userRoles["ADMIN"])) {
			$menu = array_merge($menu, array(array('Administration', '')
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
		<li class="nav-header"><i class="icon-xx icon-2x"></i><?php echo $menuEntry[0]; ?></li>
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