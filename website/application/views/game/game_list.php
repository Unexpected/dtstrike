<p>
<?php
	if (isset($sub_title)) {
		echo "$sub_title";
		echo "<hr/>";
	}

	if (is_array($games) && count($games) > 0) {
		echo '<table id="game_table" class="table table-striped table-hover table-condensed">';
		echo '<thead><tr>';
		echo '<th>Date</th>';
		echo '<th>Joueurs</th>';
		echo '<th>Map</th>';
		echo '<th>Visualiser</th>';
		echo '</tr></thead>';
		echo '<tbody>';
		echo '</tbody>';
		foreach ($games as $game) {
			echo '<tr id="game_'.$game['game_id'].'">';
			echo '<td>'.nice_datetime_span($game['timestamp']).'</td>';
			echo '<td>';
			for ($i=0; $i<$game['players']; $i++) {
				echo nice_opponent($game['user_id'][$i], $game['username'][$i], $game['game_rank'][$i] + 1, $game['rank_before'][$i]);
// 				echo '<span class="comment">#'.$game['rank_before'][$i].'</span>-';
// 				echo ''.nice_rank($game['game_rank'][$i] + 1, null).'-';
// 				echo nice_user($game['user_id'][$i], $game['username'][$i]);
				echo '<br/>';
			}
			echo '</td>';
			echo '<td><a href="'.base_url("maps/map".$game['map_id']).'.txt">'.$game['map_name'].'</a></td>';
			echo '<td>'.nice_viewer($game['game_id'], $game['game_length'], $game['cutoff'], $game['winning_turn']).'</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo $this->pagination->create_links();
	} else {
		echo '<span class="comment">Aucunes</span>';
	}
?>
</p>
