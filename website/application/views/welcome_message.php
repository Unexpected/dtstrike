
<p>
Le Six Challenge est un jeu dans lequel vous devez créer une Intelligence Artificielle (IA) qui va lutter contre celle des autres joueurs dans d'épiques batailles spatiales. <br/><br/>

Grâce aux Kits de démarrage "clé en main", que vous soyez débutant ou développeur experimenté, vous pouvez en moins de <code>5 minutes</code>, choisir votre langage de programmation préféré, soumettre le kit de démarrage correspondant et regarder vos vaisseaux 
affronter d'autres joueurs de CGI pour conquérir la galaxie. Pour embarquer, <a href="<?php echo site_url("game/start") ?>">suivez le guide !</a></p><br/>

<p>Une partie jouée récemment : </p>
<div id="container" style="text-align: center;>
	<div id="players">Loading</div>
    <div id="main">
        <canvas id="display" width="480" height="480"></canvas>
        <p id="controls">
        	| 
            <a href="#" id="slow-button">-</a> | 
            <a href="#" id="start-button">&laquo;</a> | 
            <a href="#" id="prev-frame-button">&laquo;</a> | 
            <a href="#" id="play-button">&#9654;</a> | 
            <a href="#" id="next-frame-button">&raquo;</a> | 
            <a href="#" id="end-button">&raquo;</a> | 
            <a href="#" id="fast-button">+</a> |
        </p>
    </div>
</div> <!-- end of #container -->

<link rel="stylesheet" href="<?php echo base_url("visualizer/inc/style.css?v=1") ?>">
<script type="text/javascript" src="<?php echo base_url("visualizer/inc/visualizer.js?v=1") ?>"></script>
<script type="text/javascript">
<?php
	echo "	var dataUrl = '".base_url($replay_file)."';\n";
	echo "	Visualizer.parseDataFromUrl(dataUrl);\n";
?>
</script>
<br/><br/>
Ce jeu est très fortement inspiré des concours organisés par Google et l'Université de Waterloo et nommés <code>Google AI Challenge</code>.<br/>
Il y a eux diverses versions au fil des années :
</p>
<ul>
	<li>Tron (Winter 2009)</li>
	<li><a href="http://planetwars.aichallenge.org">Planet Wars (Automne 2010)</a></li>
	<li><a href="http://aichallenge.org">Ants (Automne 2011)</a></li>
</ul><br/>

<br/>
<br/>
<code>Stay Tuned</code>
<p style="text-align: right;"><br/>May the DT be with you.</p>
