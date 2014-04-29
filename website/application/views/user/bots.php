<p>
<?php
	if (is_array($bots) && count($bots) > 0) {
		echo '<table class="table table-striped table-hover table-condensed">';
		echo '<thead><tr>';
		foreach ($heading as $head) {
			echo '<th>'.$head.'</th>';
		}
		echo '<th>Actions</th>';
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
			echo '<td>'.($bot->status == 70 ? '<a href="#" onclick="toggleBlock(\'errors'.$bot->submission_id.'\'); return false;">Voir les erreurs</a>' : '').'</td>';
			echo '</tr>';
			if ($bot->status == 70) {
				echo '<tr id="errors'.$bot->submission_id.'" style="display: none;">';
				echo '<td colspan="6"><div class="code">'.$bot->errors.'</div></td>';
				echo '</tr>';
			}
		}
		echo '</table>';
	} else {
		echo '<span class="comment">Aucune</span>';
	}
?>
</p>
<p>
Vous pouvez également en <input type="button" value="uploader une nouvelle" onclick="window.location='<?php echo site_url('user/bot_upload'); ?>'">.
</p>