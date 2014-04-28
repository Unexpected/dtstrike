<!doctype html>
<html lang="en" class="no-js">
<head>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="inc/style.css?v=1">

</head>
<body>
  <div id="container">
    <header>
	  <div id="players">Loading</div>
    </header>

    <div id="main">
        <canvas id="display" width="640" height="640"></canvas>
        <p id="controls">
        	|
            <a href="#" id="slow-button" class="fa fa-minus" title="Slowdown playback"></a> |
            <a href="#" id="start-button" class="fa fa-fast-backward" title="Go to start"></a> |
            <a href="#" id="prev-frame-button" class="fa fa-step-backward" title="Go back one turn"></a> |
            <a href="#" id="play-button" class="fa fa-play" title="Play / Pause"></a> |
            <a href="#" id="next-frame-button" class="fa fa-step-forward" title="Advance one turn"></a> |
            <a href="#" id="end-button" class="fa fa-fast-forward" title="Go to end"></a> |
            <a href="#" id="fast-button" class="fa fa-plus" title="Speedup playback"></a> |
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

  <script type="text/javascript" src="inc/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="inc/visualizer.js?v=1"></script>
  <script>
  <?php
  /*
	$gameFile = 'input';
	if (isset($_GET['game_file'])) {
		$gameFile = $_GET['game_file'];
	}
	$input = file_get_contents($gameFile);
	echo '	var data = \'' . str_replace("\n", "\\n", $input) . '\'\n';
	echo '	Visualizer.parseDataFromFile(data);\n';
  */
 	echo "	var dataUrl = 'replays/1.replay';\n";
	echo "	Visualizer.parseDataFromUrl(dataUrl);\n";
  ?>
  </script>
</body>
</html>
