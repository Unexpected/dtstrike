<?php
	if (isset($error) && $error != "") echo '<p class="form_error">'.$error.'</p>';
	if (isset($message) && $message != "") echo '<p class="form_message">'.$message.'</p>';
?>
<?php define("NEWS_INCLUDED", TRUE); include 'news.php'; ?>
<p>
Le CGI Challenge est un jeu dans lequel vous devez créer une Intelligence Artificielle (IA) qui va lutter contre celle des autres joueurs dans d'épiques batailles spatiales. <br/><br/>

Grâce aux Kits de démarrage "clé en main", que vous soyez débutant ou développeur experimenté, vous pouvez en moins de <code>5 minutes</code>, choisir votre langage de programmation préféré, soumettre le kit de démarrage correspondant et regarder vos vaisseaux 
affronter d'autres joueurs de CGI pour conquérir la galaxie. Pour embarquer, <a href="<?php echo site_url("game/start") ?>">suivez le guide !</a></p><br/>

<p>Une partie jouée récemment : </p>
<?php define("VISUALIZER_INCLUDED", TRUE); include 'game/game_view.php'; ?>
<br/><br/>
Ce jeu est très fortement inspiré des concours organisés par Google et l'Université de Waterloo et nommés <code>Google AI Challenge</code>.<br/>
Il y a eux diverses versions au fil des années :
</p>
<ul>
	<li>Tron (Hiver 2009)</li>
	<li><a href="http://planetwars.aichallenge.org">Planet Wars (Automne 2010)</a></li>
	<li><a href="http://aichallenge.org">Ants (Automne 2011)</a></li>
</ul><br/>

<br/>
<br/>
<code>Stay Tuned</code>
<p style="text-align: right;"><br/>May the DT be with you.</p>
