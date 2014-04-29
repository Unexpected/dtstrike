<p>
	Le HAL Challenge est un jeu dans lequel vous devez créer une Intelligence Artificielle (IA) qui va lutter contre celle des autres joueurs dans d'épiques batailles spatiales. 
</p>
<h3>Principe et But du jeu</h3>
<p>
	Vous commencez la partie en contrôlant une base militaire et 2 planètes de ravitaillement. Le jeu se déroule en tour par tour.
	<div style="text-align: center;" class="">
		<img src="<?php echo base_url('static/images/rules_start.png') ?>" />
	</div>
</p>
<p></p>
<p>
	Les cercles et carrés colorés représentent les planètes de ravitaillement et bases militaires contrôlées par les joueur. Les carrés et cercles gris sont des planètes "neutres". Elles se défendent, mais ne produisent ni n'envoient aucune flotte militaire sur d'autres planètes.
</p>
<div class="row-container">
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_red_military.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Une base militaire appartenant au joueur rouge accompagnée d'une planète de ravitaillement neutre</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_economic.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Deux planètes de ravitaillement appartenant au joueur jaune</div>
	</div>
</div>
<p>
	Les planètes de ravitaillement que vous contrôlez vous fournissent des troupes fraîches capables uniquement de se défendre. Vous devez les envoyer sur vos bases militaires pour les transformer en soldats endurcis prêts à conquérir d'autres planètes. Une fois que vous avez amassés assez de vaisseaux, vous pouvez envoyer des flottes militaires depuis les bases militaires capturer les planètes neutres et ennemies. 
</p>
<hr/>
<h3>Conditions de victoire</h3>
<p>
	A tout moment, si vous ne possédez plus de flottes militaires en vol ni aucune base militaire, vous êtes éliminé. Pour gagner, il vous suffit d'être le dernier joueur en vie dans la partie. 
</p>
<hr/>
<h3>Ravitaillement et flottes économiques</h3>
<p>
	A chaque tour, les planètes économiques non neutres fournissent au joueur qui les contrôle autant de nouveaux vaisseaux que leur valeur de ravitaillement.
</p>
<div class="row-container">
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_1.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Le joueur jaune contrôle une planète économique disposant de 51 vaisseaux</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_2.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Il décide d'envoyer une flotte de ravitaillement de 50 vaisseaux depuis la planète vers une base militaire à proximité</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_3.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">A chaque tour, la flotte s'éloigne. La planète ayant une valeur de ravitaillement de 2, elle continue de générer 2 vaisseaux à chaque tour</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_4.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Les flottes économiques ont été envoyées vers une base militaire qui ne possède actuellement aucun vaisseau</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_5.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">On peut voir que la planète économique à proximité produit des vaisseaux, mais pas la base militaire</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_6.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Lorsque les vaisseaux arrivent sur la base militaire, la flotte disparait, le nombre de vaisseaux sur la base militaire augmente d'autant</div>
	</div>
</div>
<hr/>
<h3>Conquête et Flottes militaires</h3>
<p>
	Vous pouvez à chaque tour envoyer des flottes militaires depuis vos bases militaires vers d'autres planètes.
</p>
<div class="row-container">
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_military_fleet_1.png') ?>" class="pull-right rule-row-img rule-row-img" /></div>
		<div class="span7">Le joueur jaune envoie une flotte militaire depuis la planète qu'il a réapprovisionné précédemment. Les flottes militaires sont représentées par des petits triangles. </div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_military_fleet_2.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Les flottes militaires sont envoyées sur une planète économique neutre, celle-ci ne produit pas de renfort.</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_military_fleet_3.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Les vaisseaux militaires attaquent la planète et sont en surnombre (50 contre 8), le joueur jaune capture la planète.</div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_military_fleet_4.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Les vaisseaux de combat sont démantelés lorsqu'ils arrivent sur les planètes économiques (selon la convention intergalactique des règles de la beta) et ne peuvent réattaquer. La planète de ravitaillement se met dès le tour suivant à produire des renforts. </div>
	</div>
	<div class="row rule-row">
		<div class="span2"><img src="<?php echo base_url('static/images/rules_yellow_military_fleet_5.png') ?>" class="pull-right rule-row-img" /></div>
		<div class="span7">Lorsqu'une autre flotte arrive sur la planète économique, elle appartient déjà au joueur jaune. Les vaisseaux sont également démantelés pour redevenir des troupes de défense uniquement</div>
	</div>
</div>
<hr/>
<h3>Règles de résolution des combats</h3>
<p>Les règles suivantes s'appliquent pour les flottes militaires : </p>
<ul>
	<li>Seules les bases militaires permettent d'envoyer des flottes militaires</li>
	<li>Lorsqu'une flotte militaire du joueur 1 avec F1 vaisseaux arrive sur une planète du joueur 1 contenant P1 vaisseaux, la flotte disparait et les vaisseaux de la flotte intègrent la planète de destination. On a donc F1 + P1 vaisseaux sur la planète appartenant au joueur 1. </li>
	<li>Une flotte militaire du joueur 1 avec F1 vaisseaux arrive sur une planète du joueur 2 qui contient P2 vaisseaux. Si F1 <= P2, alors la flotte disparait et il reste (P2 - F1) vaisseaux sur la planète. Si F1 > P2, alors la planète passe sous le contrôle du joueur 1 avec (F1 - P2) vaisseaux</li>
	<li>Si des flottes militaires appartenant aux joueurs 1, 2 et 3 avec respectivement F1, F2 et F3 vaisseaux arrivent au même tour sur une planète appartenant au joueur 3 (P3 vaisseaux). 
		<ul>
			<li>Si F1 > F2 et F1 > F3 + P3, alors la planète revient au joueur 1 qui se retrouve avec (F1 - max(F2, F3 + P3)) vaisseaux. </li>
			<li>Si F3 + P3 > F1 et F3 + P3 > F2, alors la planète reste sous contrôle du joueur 3 avec (F3 + P3) - max(F1, F2) vaisseaux. </li>
			<li>Si F1 == F2 et F1 > F3 + P3, la planète reste sous contrôle du joueur 3 avec 0 vaisseau. Il y a destruction mutuelle de l'ensemble des ennemis.</li>
		</ul>
	</li>
</ul>
<p>Les règles suivantes s'appliquent pour les flottes militaires : </p>
<ul>
	<li>Lorsqu'une flotte de ravitaillement du joueur 1 avec F1 vaisseaux arrive sur une planète du joueur 1, la flotte disparait et les vaisseaux de la flotte intègrent la planète de destination. On a donc F1 + P1 vaisseaux sur la planète appartenant au joueur 1. </li>
	<li>Lorsqu'une flotte de ravitaillement du joueur 1 arrive sur une planète appartenant au joueur 2, la flotte est détruite. Les vaisseaux de ravitaillement sont perdus et n'infligent aucune perte au défenseur. La planète reste sous contrôle du joueur 2. </li>
	<li>Lorsque des flottes de ravitaillement arrivent au même tour que des flottes militaires sur une planète, on résoud d'abord l'ensemble des batailles sans tenir compte des flottes de ravitaillement, puis, après avoir défini le nouveau propriétaire de la planète de destination, on fait arriver la flotte de ravitaillement selon les règles précédentes. </li>
</ul>
