<p>
Le tutoriel suivant part du principe que vous allez utiliser un des starters kits fourni 
afin de pouvoir vous concentrer sur le jeu plus rapidement. Si vous préférez écrire l'intégralité de votre bot, 
y compris les couches techniques de communication entre bot et serveur, pensez à prendre en compte 
<a href="<?php echo site_url('game/specs'); ?>">les spécifications</a>. 
</p>
<h1>Contenu du starter Kit</h1>
Les starters kits contiennent tous à peu près la même chose : 
<ul>
	<li>Un fichier <b>MyBot.xxx</b> dans lequel vous allez implémenter votre IA</li>
	<li>Les sources de toute la tuyauterie technique nécessaire au fonctionnement du bot, que vous n'avez pas besoin de modifier</li>
	<li>Un dossier <b>test</b> permettant de tester votre IA avant de l'uploader</li>
</ul>
Le contenu des starters se présente sous cette forme : <img src="<?php echo base_url('static/images/starters.png') ?>" class="pull-right" />
<p>
Si vous avez suivi le guide de <a href="<?php echo site_url('game/start'); ?>">démarrage rapide</a>, vous avez téléchargé et uploadé 
votre premier bot, et vous apparaissez probablement déjà dans le bas du <a href="<?php echo site_url('game/rank'); ?>">classement</a> 
(les bots fournis dans les starter kits sont assez faibles). <br/>
Pour améliorer l'IA du bot fourni, vous devez modifier le fichier MyBot.xxx. Vous pouvez bien sur utiliser votre IDE préféré et/ou modifier la structure 
des sources à condition de toujours respecter les 2 conditions suivantes : 
<ul>
	<li>Le fichier contenant la méthode "main" doit s'appeller MyBot.xxx</li>
	<li>Les sources doivent être fournies "à plat", à la racine du zip lors de l'upload</li>
</ul>
</p>
<h1>Améliorer l'IA</h1>
<p>
<i>L'exemple suivant se base sur le starter kit Java. Si vous avez besoins d'aide / de conseils sur un starter kit différent pour démarrer, 
n'hésitez pas à consulter le forum. </i><br/><br/>
Jetons un oeil au code du starter kit Java dans la classe MyBot.java, dans la méthode doTurn(). Cette méthode est appellée à chaque tour pour que notre bot puisse 
donner ses ordres en fonction de la situation. <br/>
<div class="code">
		Game game = getGame();
</div>
La classe Game contient les API qui vont nous faciliter la vie. Elle nous permet de récupérer les planètes et les flottes en cours de trajet. 
<div class="code">
// (1) If we currently have a fleet in flight, just do nothing.
for (Fleet f : game.getMyMilitaryFleets()) {
	if (game.getPlanet(f.sourcePlanet) instanceof MilitaryPlanet) {
		return;
	}
}
</div>
Ce morceau de code limite notre bot à avoir une seule flotte militaire en vol à tout moment. Nous allons simplement supprimer ces lignes pour être un peu plus agressif. 
</p>
<h1>Tester son bot</h1>
<p>
Avant d'uploader le bot sur le serveur, il serait judicieux de le tester. On va pour cela utiliser le petit outil de fourni avec le starter kit dans le 
dossier <i>test</i>. Il faut pour cela : 
<ul>
	<li>Compiler notre bot (à la main ou avec un IDE quelconque)</li>
	<li>Modifier le fichier run.conf pour (mettre la commande java -cp "C:/monBot/bin/" MyBot)</li>
	<li>Lancer l'exécutable run.bat</li>
	<li>Regarder le replay dans test/visu/index.html</li>
</ul>
L'utilitaire de test permet de lancer des parties contre des bots intégrés dans le starter kit ou bien contre d'autres bots que vous avez fait vous même. <br/>
Un outil de test plus graphique est disponible sur la page des <a href="<?php echo site_url('game/kits'); ?>">starter kits</a>. 
</p>
<h1>Continuer à progresser</h1>
<p>
Une fois toutes les modifications effectuées, vous pouvez rezipper vos sources et les uploader de nouveau pour mettre en ligne votre nouveau bot. Il suffit alors de 
répéter les étapes précédentes jusqu'à devenir le meilleur du classement !
</p>
