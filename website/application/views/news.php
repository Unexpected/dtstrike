<?php
	$news = array();
	$news[] = array(
		"03/12/2013",
		"Les starters packages ont été mis à jour ... encore",
		"Il y avait un soucis avec l'engine local qui interprettait mal le code de sortie."
	);
	$news[] = array(
		"02/12/2013",
		"Les skils ont été resettés!",
		"Cela signifie que vos bots actuels recommencent tous avec le même classement et les matchs vont redéterminer l'ordre."
	);
	$news[] = array(
		"02/12/2013",
		"Les starters packages ont été mis à jour",
		"Avec notamment les dernières versions des bots d'exemples mais également le support des maps à 5 et 6 joueurs."
	);
	
	if (defined("NEWS_INCLUDED")) {
?>
<div style="padding-bottom: 20px; width: 400px; margin: auto; overflow: hidden;">
<table class="table table-striped table-hover table-condensed">
<thead>
	<tr>
		<th style="text-align: center; font-weight: bold;"><i class="icon-exclamation"></i>&nbsp;&nbsp;<a href="<?php echo site_url('welcome/news'); ?>">Les dernières news</a>&nbsp;&nbsp;<i class="icon-exclamation"></i></th>
	</tr>
</thead>
<tbody>
<?php
		$cnt = 0;
		foreach ($news as $newsElem) {
			$css = $cnt%2 == 0 ? 'odd' : 'even';
			
			$date = $newsElem[0];
			$titre = $newsElem[1];
			echo "<tr class=\"$css\"><td style=\"white-space: nowrap;\">$date : $titre</td></tr>";
			$cnt++;
			if ($cnt >= 3) break;
		}
?>
</tbody>
</table>
</div>
<?php
	} else {
		$cnt = 0;
		foreach ($news as $newsElem) {
			$css = $cnt%2 == 0 ? 'odd' : 'even';
			$date = $newsElem[0];
			$titre = $newsElem[1];
			
			echo "<div class=\"news\">";
			echo "<span class=\"news_date\">$date</span>";
			echo "<span class=\"news_title\">$titre</span>";
			echo "<div class=\"news_content\">".nl2br($newsElem[2])."</div>";
			echo "</div>";
			
			$cnt++;
			if ($cnt >= 10) break;
		}
	}