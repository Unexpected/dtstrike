<?php
	$news = array();
	$news[] = array(
		"17/04/2014",
		"TODO",
		"Penser à écrire une première news utile."
	);
	
	if (defined("NEWS_INCLUDED")) {
?>
<div class="news-div nav-hide-on-mobile">
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