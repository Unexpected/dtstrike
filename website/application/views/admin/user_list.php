<p>
<?php
	if (is_array($users) && count($users) > 0) {
		echo '<table class="table table-striped table-hover table-condensed">';
		echo '<thead><tr>';
		foreach ($heading as $head) {
			echo '<th>'.$head.'</th>';
		}
		echo '</tr></thead>';
		echo '<tbody>';
		echo '</tbody>';
		foreach ($users as $user) {
			echo '<tr>';
			echo '<td><a href="'.site_url("admin/user/$user->user_id").'">'.$user->user_id.'</a></td>';
			echo '<td><a href="'.site_url("admin/user/$user->user_id").'">'.$user->username.'</a></td>';
			echo '<td><a href="'.site_url("admin/user/$user->user_id").'">'.$user->email.'</a></td>';
			echo '<td><a href="'.site_url("admin/user/$user->user_id").'">'.$user->org_name.'</a></td>';
			echo '<td><a href="'.site_url("admin/user/$user->user_id").'">'.$user->country_name.'</a></td>';
			echo '<td><a href="'.site_url("admin/user/$user->user_id").'">'.$user->created.'</a></td>';
			echo '</tr>';
		}
		echo '</table>';
	} else {
		echo '<span class="comment">Aucun</span>';
	}
?>
</p>
