<p>
<?php 
	if (isset($place) && $place != '') {
?>
Vous êtes actuellement classé <code><?php echo $place; ?></code> du classement général.
<?php
	} else {
?>
Vous n'êtes pas encore classé, pensez à uploader votre première IA !
<?php
	}
?>
</p>

<p>Les actions possibles sont les suivantes :</p>
<ul>
	<li><a href="<?php echo site_url('user/bot_upload') ?>">Upload d'un bot</a></li>
	<li><a href="<?php echo site_url('user/bots') ?>">Mes IAs</a></li>
	<li><a href="<?php echo site_url('game/mine') ?>">Mes parties</a></li>
<?php if (verify_user_role($this, "league", TRUE)) { ?>
	<li><a href="<?php echo site_url('league/mine') ?>">Mes ligues</a></li>
<?php } ?>
<?php if (verify_user_role($this, "tournament", TRUE)) { ?>
	<li><a href="<?php echo site_url('tournament/mine') ?>">Mes tournois</a></li>
<?php } ?>
</ul>
<?php
/*
 * Submission activation / deactivation section
 */
 /*
    if ($server_info["submissions_open"]
            && logged_in_with_valid_credentials()
            && (logged_in_as_admin() || current_user_id() == $user_id)) {
        $status_result = contest_query("select_submission_status", $user_id);
        if ($status_row = mysql_fetch_assoc($status_result)) {
            if ($status_row['status'] == 100 || $status_row['status'] == 40) {
                echo "<div class=\"activate\">";
                echo "<form method=\"post\" action=\"update_submission.php\">";
                if ($status_row['status'] == 100) {
                    echo "<p>Your current submission was deactivated on ".$status_row['shutdown_date']." (".
                         nice_ago($status_row['shutdown_date']).")</p>";
                }                
                echo "<input type=\"hidden\" name=\"update_key\" value=\"$update_key\" />
                      <input type=\"submit\" name=\"activate\" value=\"Activate\" />";

                if ($status_row['status'] == 40) {
                    echo "<input type=\"submit\" name=\"deactivate\" value=\"Deactivate\" />";
                    echo "<p>Your current submission will be deactivated on ".$status_row['shutdown_date']." (".
                         nice_ago($status_row['shutdown_date']).")</p>";
                }
                echo "<p><em>Inactive submissions will not be chosen as a seed player for a new matchup, but may still be chosen as an opponent in a game.</em><p>";
                echo "</form>";
                echo "</div>";
            }
        }
    }
*/
?>