<?php 
	$is_included = defined("VISUALIZER_INCLUDED");
?>
<div id="container" class="nav-hide-on-mobile">
	<div id="players">Loading</div>
    
    <div id="main">
        <canvas id="display" width="640" height="640"></canvas>
        <p id="controls">
        	| 
            <a href="#" id="slow-button">-</a> | 
            <a href="#" id="start-button">&laquo;</a> | 
            <a href="#" id="prev-frame-button">&laquo;</a> | 
            <a href="#" id="play-button">&#9654;</a> | 
            <a href="#" id="next-frame-button">&raquo;</a> | 
            <a href="#" id="end-button">&raquo;</a> | 
            <a href="#" id="fast-button">+</a> |
        </p>
<?php if (!$is_included) { ?>
        <p>
		  <div style="height: 20px;">
			  <span style="float: left">Match: <span id="macthId">Loading</span></span>
			  <span style="float: right">Turn: <span id="turnCounter">Loading</span></span>
		  </div>
		  <div style="height: 100px;">
			  <canvas id="chart" width="640" height="100" style="position: absolute; z-index: 1" ></canvas>
			  <canvas id="feedline" width="640" height="100" style="position: absolute; background-color: transparent; z-index: 2" ></canvas>
		  </div>
        </p>
<?php } ?>
    </div>
</div> <!-- end of #container -->
  
  <!-- Errors display -->
<?php
	if (!$is_included) {
		foreach ($errors as $error) {
			$username = $error["username"];
			$status = $error["status"];
			echo "<ul>";
	        echo "<li><p>$username - $status</p><pre class=\"error\">";
	        echo str_replace('\n', "\n", $error["errors"])."\n";
	        echo "</pre></li>";
			echo "</ul>";
		}
	}
?>

  <link rel="stylesheet" href="<?php echo base_url("visualizer/inc/style.css?v=1") ?>">
  <script type="text/javascript" src="<?php echo base_url("visualizer/inc/visualizer.js?v=1") ?>"></script>
  <script type="text/javascript">
  <?php
 	echo "	var userUrl = '".site_url('user/view')."/';\n";
 	echo "	var dataUrl = '".base_url($replay_file)."';\n";
	echo "	Visualizer.parseDataFromUrl(dataUrl);\n";
  ?>
  </script>
  