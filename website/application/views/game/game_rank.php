<p>
<?php
// echo '<pre>';
// print_r($rankings);
// echo '</pre>';
	if (is_array($rankings) && count($rankings) > 0) {
		echo '<table class="table table-striped table-hover table-condensed">';
		echo '<thead><tr>';
		echo '	<th>Rank</th>
	<th>Username</th>
	<th>Country</th>
	<th>Organization</th>
	<th>Language</th>
	<th>Version</th>
	<th><span title="mu - 3 * sigma">Skill</span></th>
	<th><span title="total games for current submission">Games</span></th>
	<th><span title="number of games in past 24 hours">Recent</span></th>';
		echo '</tr></thead>';
		echo '<tbody>';
		echo '</tbody>';

		$oddity = 'even';
		foreach ($rankings as $row) {
			$oddity = $oddity == 'odd' ? 'even' : 'odd';
	
			echo "<tr class=\"$oddity\">";
			$rank = $row["rank"];
			if ($row["rank"]) {
			    echo "<td class=\"number\">".nice_rank($row["rank"], $row["rank_change"])."</td>";
			} else {
			    echo "<td class=\"number\"><span title=\"best submission's last rank\">(&gt;\")&gt;</span></td>";
			}
			
			echo "<td class=\"username\">".nice_user($row["user_id"], $row["username"])."</td>";
			echo "<td class=\"country\">".nice_country($row["country_code"], $row["country"], $row["flag_filename"])."</td>";
			echo "<td class=\"org\">".nice_organization($row["org_id"], $row["org_name"])."</td>";
			
			$programming_language = htmlentities($row["programming_language"], ENT_COMPAT, 'UTF-8');
			$programming_language_link = urlencode($row["programming_language"]);
			echo "<td>".nice_language($row["language_id"], $row["programming_language"])."</td>";
			
			$version = $row["version"];
			$age = nice_ago($row["timestamp"]);
			echo "<td class=\"number\"><span title=\"$age\">$version</span></td>";
			
			$skill = nice_skill($row['skill'],$row['mu'],$row['sigma'],
			    $row['skill_change'],$row['mu_change'],$row['sigma_change']);
			echo "<td class=\"number\">$skill</td>";
			
			echo "<td class=\"number\">".$row["game_count"]."</td>";
			echo "<td class=\"number\">".$row["game_rate"]."</td>";
			echo '</tr>';
		}
		echo '</table>';
		echo $this->pagination->create_links();
	} else {
		echo '<span class="comment">Il n\'y a aucun classement actuellement.</span>';
	}
?>
</p>
