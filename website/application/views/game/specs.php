<h3>Fonctionnement général</h3>
<p>
	<ul>
		<li>Le bot est instancié une seule fois par le serveur</li>
		<li>Le bot et le serveur communiquent sur l'entrée et la sortie standard</li>
		<li>Les communications sont au format texte (voir détail plus bas)</li>
		<li>Tous les messages (serveur -> bot et bot -> serveur) doivent finir par un saut de ligne de type unix : '\n'. Ainsi, si les règles stipulent que le serveur envoie le message "go",  le serveur enverra "go\n" au bot. </li>
	</ul>
</p>
<h3>Tour de chauffe</h3>
<p>Après instanciations des bots, le serveur envoie à chacun d'eux les options de jeu. Le format est le suivant :<br/>
<div class="code">*option0:valeur<br/>
*option1:valeur<br/>
*option2:valeur<br/>
...<br/>
*optionN:valeur<br/>
ready<br/>
<br/></div>
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
<div class="code">go<br/></div>
<br/>
Tout autre message envoyé par le bot à ce stade du jeu sera ignoré. 
</p>
<h3>Tours de jeu</h3>
<p>
Le format des messages reçus à chaque tour de jeu est le suivant : <br/>
<div class="code">P0 x y owner num_ships<br/>
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
<br/></div>
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
<p>
A chaque tour de jeu, les bots envoient leurs ordres au serveur dans le format suivant : <br/> 
<div class="code">source_planet destination_planet num_ships<br/>
source_planet destination_planet num_ships<br/>
...
source_planet destination_planet num_ships<br/>
go<br/></div>
<br/>
Les règles suivantes s'appliquent aux <b>ordres des joueurs</b> : <br/>
<ul>
	<li>Les joueurs envoient autant d'ordre qu'ils le désirent à chaque tour</li>
	<li>Une fois tous les ordres envoyés, le message "go" doit être envoyé au serveur</li>
	<li>source_planet : identifiant de la planète source, entier. exemple : 3</li>
	<li>destination_planet : identifiant de la planète de destination, entier. exemple : 8</li>
	<li>num_ships : nombre de vaisseaux à envoyer de la planète source vers la planète de destination, entier strictement positif. exemple : 50</li>
	<li>L'ID de planète source doit correspondre à une planète militaire du joueur courant</li>
	<li>Le nombre de vaisseaux de la planète doit être supérieur ou égal à la somme des vaisseaux qui vont être envoyés depuis cette planète sur l'ensemble des ordres du tour</li>
</ul>
</p>
<h3>Tour de fin</h3>
<p>
A son dernier tour de jeu, le bot reçoit un message du format suivant :<br/>
<div class="code">end<br/>
players num_players<br/>
score score_player_1 score_player_2 ... score_player_N<br/>
status status_player_1  status_player_2 ... status_player_N<br/>
playerturns turns_player_1 turns_player_2 ... turns_player_N<br/>
go<br/></div>
<br/>
Les règles suivantes s'appliquent au tour de fin :<br/>
<ul>
	<li>Le message de fin commence par end. Il signifie la fin de partie, le bot peut terminer ses opérations en cours et s'arrêter proprement. (S'il ne le fait pas, il sera tué sauvagement par le système). </li>
	<li>A la suite du message de fin, le serveur envoie des statistiques qui peuvent être utilisées par le bot lors de la phase de développement. Une fois uploadé sur le serveur, les bots ne sont pas autorisées à renvoyer / stocker des informations. Il n'est donc pas utile de traiter les informations postérieures au message end dans les versions de bots uploadées sur le site. </li>
	<li>players : nombre de joueurs ayant participé au match. Exemple : players 4</li>
	<li>score : liste des scores des joueurs. Exemple score 4 0 1 0</li>
	<li>status : liste des statuts des joueurs en fin de partie. Exemple : survived eliminated survived crashed</li>
	<li>playerturns : liste des nombre de tours ou les joueurs ont participé. Exemple playerturns 1000 259 1000 1</li>
</ul>
</p>