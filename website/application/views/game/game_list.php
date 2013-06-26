
<p>
	<h2>Liste des <?= $limit ?> dernières parties</h2>
<?php
	if (is_array($games) && count($games) > 0) {
		$this->table->set_template(array ('table_open' => '<table class="table table-striped table-hover table-condensed">'));
    	echo $this->table->generate($games);
	} else {
		echo '<span class="comment">Aucune</span>';
	}
?>
</p>
