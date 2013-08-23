<h3>Fonctionnement général</h3>
<p>
	<ul>
		<li>Le bot est instancié une seule fois par le serveur</li>
		<li>Le bot et le serveur communiquent sur l'entrée et la sortie standard</li>
		<li>Les communications sont au format texte (voir détail plus bas)</li>
	</ul>
</p>
<h3>Tour de chauffe</h3>
<p>Après instanciations des bots, le serveur envoie à chacun d'eux les options de jeu. Le format est le suivant :<br/>
*option0:valeur<br/>
*option1:valeur<br/>
*option2:valeur<br/>
...<br/>
*optionN:valeur<br/>
ready<br/>
<br/>
La liste des options est susceptible de varier avec le temps (de nouvelles options peuvent être ajoutées). 
Les options sont envoyées à titre informatif et définissent les règles appliquées par le serveur. <br/>
Les options actuellement envoyées sont : 
<ul>
	<li>loadtime : temps maximum alloué au bot pour le tour de chauffe en millisecondes</li>
	<li>turntime : temps maximum alloué au bot par tour de jeu (hors tour de chauffe) en millisecondes</li>
	<li>turns : nombre de tours maximum avant la fin de partie</li>
</ul>
</p>
<h3>Tour de chauffe - Réponse du bot</h3>
<p>
Après la réception du message "ready", le bot peut "démarrer", créer les structures de données nécessaires à son bon fonctionnement, etc. <br/> 
Il doit notifier le serveur lorsqu'il est prêt en envoyant le message suivant : <br/>
go<br/>
<br/>
Tout autre message envoyé par le bot à ce stade du jeu sera ignoré. 
</p>
<h3>Tours de jeu</h3>
<p>
Le format des messages reçus à chaque tour de jeu est le suivant : <br/>
P0 x y owner num_ships<br/>
P1 x y owner num_ships growth_rate<br/>
P2 x y owner num_ships growth_rate<br/>
...<br/>
PN x y owner num_ships<br/>
F owner num_ships source_planet destination_planet total_trip_length turns_remaining<br/>
R owner num_ships source_planet destination_planet total_trip_length turns_remaining<br/>
...<br/>
F owner num_ships source_planet destination_planet total_trip_length turns_remaining<br/>
R owner num_ships source_planet destination_planet total_trip_length turns_remaining<br/>
R owner num_ships source_planet destination_planet total_trip_length turns_remaining<br/>
<br/>
Les règles suivantes s'appliquent aux <b>joueurs</b> : <br/>
<ul>
	<li>Le joueur courant à l'ID 1</li>
	<li>Le joueur neutre a l'ID 0</li>
	<li>Les ID des autres joueurs vont de 2 à N</li>
	<li>Quand un bot perd, ses flottes sont retirées du jeu et ses planètes passent sous contrôle du joueur neutre</li>
</ul>
Les règles suivantes s'appliquent aux <b>planètes</b> : <br/>
<ul>
	<li>P : type de planète, chaine de caractères. E pour planète économique, M pour planète Militaire</li>
	<li>x : coordonnée X de la planète, nombre flottant. ex : 11.014548</li>
	<li>y : coordonnée Y de la planète, nombre flottant. ex : 4.083429</li>
	<li>owner : propriétaire de la planète, entier. ex : 3</li>
	<li>num_ships : nombre de vaisseaux sur la planète, entier. ex : 25 </li>
	<li>growth_rate : nombre de renforcements envoyés par cette planète à chaque tour, entier. ex : 2</li>
	<li>L'ordre des planètes est immuable durant une partie. L'ID d'une planète est définie par son numéro d'ordre dans la liste des planètes envoyées.</li>
	<li>L'ID de la première planète est 0.</li>
</ul>
Les règles suivantes s'appliquent aux <b>flottes</b> : <br/>
<ul>
	<li>F / R : type de flotte, chaine de caractères. F pour flotte militaire, R pour une flotte de renforts</li>
	<li>owner : propriétaire de la flotte, entier. ex : 3</li>
	<li>num_ships : nombre de vaisseaux sur de la flotte, entier. ex : 25 </li>
	<li>source_planet : ID de la planète d'origine de la flotte, entier. ex : 2</li>
	<li>destination_planet : ID de la planète de destination de la flotte, entier. ex : 2</li>
	<li>total_trip_length : nombre de tours totaux pour effectuer le trajet de la flotte entre les planètes source et destination, entier. ex : 2</li>
	<li>turns_remaining : nombre de tours restant avant arrivée sur la planète de destination, entier. ex : 2</li>
</ul>
</p>
<h3>Tours de jeu - Réponse des bots</h3>
TODO : format des ordres

