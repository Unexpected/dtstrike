Content of the test folder: 

*** maps/N/
This folder contains a bunch of test maps. Maps are located in 3 separate folder depending on how many players they need. 
Currently, there are maps for 2, 3 or 4 players. 


*** visu/
This is the visualizer folder. 

*** visu/inc/ 
Visualizer sources. 

*** visu/replay.js
Replay data. This file contains data that will be displayed by index.html. 
This file is overwritten when you launch run.bat to play a game. 

*** visu/index.html
Open this file to watch the last match replay. This uses javascript canvas. You need an HTML5 compliant browser. 
Visualizer has been tested with Chrome, Firefox, Safari and IE9+. 
 
*** engine.jar
Java implementation of the engine. 

*** run.bat
Launch a match based on run.conf configuration. 