
  <div id="container">
    <header>
	  <div id="players">Loading</div>
    </header>
    
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
    </div>
  </div> <!-- end of #container -->

  <link rel="stylesheet" href="<?php echo base_url("visualizer/inc/style.css?v=1") ?>">
  <script type="text/javascript" src="<?php echo base_url("visualizer/inc/visualizer.js?v=1") ?>"></script>
  <script type="text/javascript">
  <?php
 	echo "	var dataUrl = '".base_url('replays/'.$replay_file)."';\n";
	echo "	Visualizer.parseDataFromUrl(dataUrl);\n";
  ?>
  </script>