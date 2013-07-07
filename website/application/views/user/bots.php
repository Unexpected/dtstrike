<p>
<?php
	if (is_array($bots) && count($bots) > 0) {
		echo '<table class="table table-striped table-hover table-condensed">';
		echo '<thead><tr>';
		foreach ($heading as $head) {
			echo '<th>'.$head.'</th>';
		}
		echo '</tr></thead>';
		echo '<tbody>';
		echo '</tbody>';
		foreach ($bots as $bot) {
			$statusLabel = getStatusLabelDescription($bot->status);
			
			echo '<tr>';
			echo '<td>'.$bot->submission_id.'</td>';
			echo '<td>'.$bot->version.'</td>';
			echo '<td><a title="'.$statusLabel[1].'">'.$statusLabel[0].'</a></td>';
			echo '<td>'.$bot->language_name.'</td>';
			echo '<td>'.$bot->rank.'</td>';
			echo '</tr>';
		}
		echo '</table>';
	} else {
		echo '<span class="comment">Aucun</span>';
	}
?>
</p>
