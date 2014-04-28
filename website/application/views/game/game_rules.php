<p>
	Le HAL Challenge est un jeu dans lequel vous devez créer une Intelligence Artificielle (IA) qui va lutter contre celle des autres joueurs dans d'épiques batailles spatiales. 
</p>
<h3>Principe et But du jeu</h3>
<p>
	Vous commencez la partie en contrôlant une base militaire et 2 planètes de ravitaillement. Le jeu se déroule en tour par tour. 
	<img src="<?php echo base_url('static/images/rules_start.png') ?>" class="pull-right" />
</p>
<p>
	Les cercles et carrés colorés représentent les planètes de ravitaillement et bases militaires contrôlées par les joueur. Les carrés et cercles gris sont des planètes "neutres". Elles se défendent, mais ne produisent ni n'envoient aucune flotte militaire sur d'autres planètes. 
	<ul>
		<li>
			Une base militaire appartenant au joueur rouge accompagnée d'une planète de ravitaillement neutre : <img src="<?php echo base_url('static/images/rules_red_military.png') ?>" class="pull-right" />
		</li>
		<li>
			Deux planètes de ravitaillement appartenant au joueur jaune : <img src="<?php echo base_url('static/images/rules_yellow_economic.png') ?>" class="pull-right" />
		</li>
	</li>
</p>
<p>
	Les planètes de ravitaillement que vous contrôlez vous fournissent des troupes fraîches capables uniquement de se défendre. Vous devez les envoyer sur vos bases militaires pour les transformer en soldats endurcis prêts à conquérir d'autres planètes. Une fois que vous avez amassés assez de vaisseaux, vous pouvez envoyer des flottes militaires depuis les bases militaires capturer les planètes neutres et ennemies. 
</p>
<p>
	A tout moment, si vous ne possédez plus de flottes militaires en vol ni aucune base militaire, vous êtes éliminé. Pour gagner, il vous suffit d'être le dernier joueur en vie dans la partie. 
</p>
<h3>Ravitaillement et flottes économiques</h3>
<p>
	A chaque tour, les planètes économiques non neutres fournissent au joueur qui les contrôle autant de nouveaux vaisseaux que leur valeur de ravitaillement. 
	<ul>
		<li>
			Le joueur jaune contrôle une planète économique disposant de 51 vaisseaux : 
			<img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_1.png') ?>" class="pull-right" />
		</li>
		<li>
			Il décide d'envoyer une flotte de ravitaillement de 50 vaisseaux depuis la planète vers une base militaire à proximité : 
			<img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_2.png') ?>" class="pull-right" />
		</li>
		<li>
			A chaque tour, la flotte s'éloigne. La planète ayant une valeur de ravitaillement de 2, elle continue de générer 2 vaisseaux à chaque tour : 
			<img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_3.png') ?>" class="pull-right" />
		</li>
		<li>
			Les flottes économiques ont été envoyées vers une base militaire qui ne possède actuellement aucun vaisseau : 
			<img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_4.png') ?>" class="pull-right" />
		</li>
		<li>
			On peut voir que la planète économique à proximité produit des vaisseaux, mais pas la base militaire : 
			<img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_5.png') ?>" class="pull-right" />
		</li>
		<li>
			Lorsque les vaisseaux arrivent sur la base militaire, la flotte disparait, le nombre de vaisseaux sur la base militaire augmente d'autant : 
			<img src="<?php echo base_url('static/images/rules_yellow_eco_fleet_6.png') ?>" class="pull-right" />
		</li>
	</ul>
</p>
<h3>Conquête et Flottes militaires</h3>
<p>

</p>
<h3>Règles de résolution des combats</h3>