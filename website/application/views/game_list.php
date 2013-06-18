
<p>
	<h2>Liste des <?= $limit ?> dernières parties</h2>
<?php
	if (is_array($games) && count($games) > 0) {
    	echo $this->table->generate($games);
	} else {
		echo '<span class="comment">Aucune</span>';
	}
?>
</p>
