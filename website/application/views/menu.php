<?php
	// FIXME : Voir si on peut mieux faire ...
	$userRoles = array();
	if (is_logged_in($this)) {
		$userRoles = array('User'=>1, "League"=>2, "Tournament"=>3);
	}
	
	$menu = array(
		array("Accueil", site_url("welcome"))
		, array('', '')
		, array("Dernières parties", site_url("game"))
	);
	if (isset($userRoles["League"])) {
		$menu = array_merge($menu, array(array("Ligues", site_url("league"))));
	}
	if (isset($userRoles["Tournament"])) {
		$menu = array_merge($menu, array(array("Tournois", site_url("tournament"))));
	}
	$menu = array_merge($menu, array(array('', '')));
	
	if (!isset($userRoles["User"])) {
		//$menu = array_merge($menu, array(array("S'enregistrer", site_url("user/register"))));
		$menu = array_merge($menu, array(array("S'identifier", site_url("auth/login"))));
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
		
		$menu = array_merge($menu, array(array('', '')
		, array("Se délogger", site_url("auth/logout"))));
	}
?>
<div id="menu">
	<ul>
<?php
	foreach ($menu as $menuEntry) {
		if ($menuEntry[1] == '') {
?>
		<li><hr/></li>
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