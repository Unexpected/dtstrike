
<p>
<?php
	if (is_array($users) && count($users) > 0) {
		$this->table->set_template(array ('table_open' => '<table class="table table-striped table-hover table-condensed">'));
		$this->table->set_heading($heading);
    	echo $this->table->generate($users);
	} else {
		echo '<span class="comment">Aucun</span>';
	}
?>
</p>
