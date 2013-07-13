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
			echo '<tr>';
			echo '<td>'.$bot->submission_id.'</td>';
			echo '<td>'.$bot->version.'</td>';
			echo '<td>'.nice_status($bot->status).'</td>';
			echo '<td>'.nice_language($bot->language_id, $bot->language_name).'</td>';
			echo '<td>'.$bot->rank.'</td>';
			echo '</tr>';
		}
		echo '</table>';
	} else {
		echo '<span class="comment">Aucun</span>';
	}
?>
</p>
<p>
Vous pouvez également en <input type="button" value="uploader un nouveau" onclick="window.location='<?php echo site_url('user/bot_upload'); ?>'">.
</p>