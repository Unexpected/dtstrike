var Visualizer = {
    canvas: null,
    ctx: null,
    frame: 0,
    feedline: 0,
    playing: false,
    haveDrawnBackground: false,
    frameDrawStarted: null,
    frameDrawEnded: null,
	game_id: -1,
	game_time: -1,
	map_id: '',
    players: [],
    planets: [],
    moves: [],
    dirtyRegions: [],
    config : {
      planet_font: 'bold 15px Arial,Helvetica',
      fleet_font: 'normal 12px Arial,Helvetica',
      showFleetText: true,
      display_margin: 50,
      turnsPerSecond: 8,
      teamColor: ['#455','#E31937','#FF6A00','#F2A200','#A1C4D0'],
	  E_planet_size: 20,
	  M_planet_size: 40
    },
    
    setup: function() {
        // Setup Context
        this.canvas = document.getElementById('display');
        this.ctx = this.canvas.getContext('2d');
        this.ctx.textAlign = 'center';
        
        // Calculated configs
        this.config.unit_to_pixel = (this.canvas.height - this.config.display_margin * 2) / 24;
    },
    
    init: function() {
    	if (this.game_id > -1) {
            // Draw first frame
            this.drawFrame(0);
            
            hookButtons();
            bindActionsAndEvents();
            initStaticData();
    	    
            // Start playing
    	    this.start();
    	    this.drawChart();
    	}
    },
    
    unitToPixel: function(unit) {
        return this.config.unit_to_pixel * unit;
    },
    
    drawBackground: function(){
      var ctx = this.ctx;
      
      // Draw background
      ctx.fillStyle = '#000';
      if(this.haveDrawnBackground==false){  
        ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
        this.haveDrawnBackground = true;
      }
      for(var i = 0; i < this.dirtyRegions.length; i++) {
        var region = this.dirtyRegions[i];
        ctx.fillRect(
          parseInt(region[0]),
          parseInt(region[1]),
          parseInt(region[2]),
          parseInt(region[3])
        );
      }
      this.dirtyRegions = [];
      
    },
    
    drawFrame: function(frame) { 
        var disp_x = 0, disp_y = 0;
        var ctx = this.ctx;
        var frameNumber = Math.floor(frame);
        
        var planetStats = this.moves[frameNumber].planets;
        var fleets = this.moves[frameNumber].moving;
        
        this.drawBackground();
        
        // Draw Planets
        ctx.font = this.config.planet_font;
        ctx.textAlign = 'center';
        for(var i = 0; i < this.planets.length; i++) {
            var planet = this.planets[i];
            planet.owner = planetStats[i].owner;
            planet.numShips = planetStats[i].numShips;

            disp_x = this.unitToPixel(planet.x) + this.config.display_margin;
            disp_y = this.unitToPixel(planet.y) + this.config.display_margin;
						
			if (planet.type == 'E') {
				var planetSize = this.config.E_planet_size;
				
				// Add shadow
				ctx.beginPath();
				ctx.arc(disp_x + 0.5, this.canvas.height - disp_y + 0.5, planetSize + 1, 0, Math.PI*2, true);
				ctx.closePath();
				ctx.fillStyle = "#000";
				ctx.fill();
				
				// Draw circle
				ctx.beginPath();
				ctx.arc(disp_x, this.canvas.height - disp_y, planetSize, 0, Math.PI*2, true);
				ctx.closePath();
				ctx.fillStyle = this.config.teamColor[planet.owner];
				// TODO: hightlight planet when a fleet has reached them
				ctx.fill();
			} else if (planet.type == 'M') {
				var planetSize = this.config.M_planet_size;
				var halfSize = parseInt(planetSize / 2);
				
				// Add shadow
				ctx.beginPath();
				ctx.rect(disp_x - halfSize - 2, this.canvas.height - disp_y - halfSize - 2, planetSize + 4, planetSize + 4);
				ctx.closePath();
				ctx.fillStyle = "#000";
				ctx.fill();
				
				// Draw square
				ctx.beginPath();
				ctx.rect(disp_x - halfSize, this.canvas.height - disp_y - halfSize, planetSize, planetSize);
				ctx.closePath();
				ctx.fillStyle = this.config.teamColor[planet.owner];
				// TODO: hightlight planet when a fleet has reached them
				ctx.fill();
			}

            ctx.fillStyle = "#fff";
            ctx.fillText(planet.numShips, disp_x, this.canvas.height - disp_y + 5);
        }
        
        // Draw Fleets
        this.ctx.font = this.config.fleet_font;
        for(var i = 0; i < fleets.length; i++) {
          var fleet = fleets[i];
          
          var progress = (fleet.progress + 1 + (frame - frameNumber)) / (fleet.tripLength + 2);
          fleet.x = fleet.source.x + (fleet.destination.x - fleet.source.x) * progress;
          fleet.y = fleet.source.y + (fleet.destination.y - fleet.source.y) * progress;
          disp_x = this.unitToPixel(fleet.x) + this.config.display_margin;
          disp_y = this.unitToPixel(fleet.y) + this.config.display_margin;
          
          if (fleet.source.type == 'M') {
	          // Draw ship
	          ctx.fillStyle = this.config.teamColor[fleet.owner];
	          ctx.beginPath();
	          ctx.save();
	          ctx.translate(disp_x, this.canvas.height - disp_y);
	          
	          var scale = Math.log(Math.max(fleet.numShips,4)) * 0.03;
	          ctx.scale(scale, scale);
	          
	          var angle = Math.PI/2 - Math.atan(
	              (fleet.source.y - fleet.destination.y) /
	              (fleet.source.x - fleet.destination.x)
	          );
	          if(fleet.source.x - fleet.destination.x < 0) {
	              angle = angle - Math.PI;
	          }
	          ctx.rotate(angle);
	          
	          ctx.moveTo(0, -10);
	          ctx.lineTo(40,-30);
	          ctx.lineTo(0, 100);
	          ctx.lineTo(-40, -30);
	          ctx.closePath();
	          ctx.fill();
	          ctx.strokeStyle = "#fff";
	          ctx.stroke();
	          ctx.restore();
	
	          // Draw text
	          if(this.config.showFleetText==true){
	            angle = -1 * (angle + Math.PI/2); // switch the axis around a little
	            disp_x += -11 * Math.cos(angle);
	            disp_y += -11 * Math.sin(angle) - 5;
	            ctx.fillText(fleet.numShips, disp_x, this.canvas.height - disp_y);
	          }
          } else if (fleet.source.type == 'E') {
	          // Draw dot
	          ctx.fillStyle = this.config.teamColor[fleet.owner];

	          ctx.beginPath();
	          ctx.arc(disp_x, this.canvas.height - disp_y, 2, 0, Math.PI*2, true);
	          ctx.closePath();
	          ctx.fill();
          }
          
          this.dirtyRegions.push([disp_x - 25 , this.canvas.height - disp_y - 35, 50, 50]);
        }
		
		this.drawFeedline(frame);
        
        $(this.canvas).trigger('drawn');
    },
	
    drawFeedline: function(frame){
        var canvas = document.getElementById('feedline');
		if (!canvas) return;
        var ctx = canvas.getContext('2d');
		
        var widthFactor = canvas.width / Math.max(200, this.moves.length);
		
		// Clear
        //canvas.width = canvas.width;
		ctx.clearRect((this.feedline - 1)*widthFactor, 0, (this.feedline + 1)*widthFactor, canvas.height);
		//ctx.clearRect(0, 0, canvas.width, canvas.height);
		
		// Feed Line
		ctx.strokeStyle = '#000';
		ctx.fillStyle = '#000';
		ctx.beginPath();
		ctx.moveTo(frame*widthFactor, 0);
		ctx.lineTo(frame*widthFactor, canvas.height);
		ctx.stroke();
		ctx.closePath();
		
		this.feedline = frame;
	},
    
    drawChart: function(){
        var canvas = document.getElementById('chart');
		if (!canvas) return;
        var ctx = canvas.getContext('2d');
        ctx.scale(1,-1);
        ctx.translate(0,-canvas.height);
        
        // Total the ship counts
        var mostShips = 100;
        for(var i=0; i < this.moves.length; i++ ){
            var turn = this.moves[i];
            turn.shipCount = [];
			for(var j = 0; j <= this.players.length; j++ ){
				turn.shipCount[j] = 0;
			}
            for(var j=0; j < turn.moving.length; j++ ){
                var fleet = turn.moving[j];
                turn.shipCount[fleet.owner]+=fleet.numShips;
            }
            for(var j=0; j < turn.planets.length; j++ ){
                var planet = turn.planets[j];
                turn.shipCount[planet.owner]+=planet.numShips;
            }
                        
            for(var j=0; j < turn.shipCount.length; j++ ){
                mostShips = Math.max(mostShips, turn.shipCount[j] );
            }
        }

        var heightFactor = canvas.height / mostShips / 1.05;
        var widthFactor = canvas.width / Math.max(200, this.moves.length);
        for(var i = 1; i <= this.players.length; i++ ){
            ctx.strokeStyle = this.config.teamColor[i];
            ctx.fillStyle = this.config.teamColor[i];
            ctx.beginPath();
            ctx.moveTo(0,this.moves[0].shipCount[i] * heightFactor);
            var shipCount = 0;
            for(var j=1; j < this.moves.length; j++ ){
                shipCount = this.moves[j].shipCount[i];
                ctx.lineTo(j*widthFactor, shipCount*heightFactor);
            }
            ctx.stroke();
            
            ctx.beginPath();
            ctx.arc((j-1)*widthFactor, shipCount*heightFactor, 2, 0, Math.PI*2, true);
            ctx.fill();
        }
    },
    
    start: function() {
        this.playing = true;
        setTimeout(function() { Visualizer.run.apply(Visualizer); }, 1);
        $("#play-button").html("&#9553;");
    },
    
    stop: function() {
        this.playing = false;
        $('#play-button').html("&#9654;");
    },
    
    run: function() {
      if(!this.playing) return;
      this.frameDrawStarted = new Date().getTime();
      
      if(this.frame >= Visualizer.moves.length ){
        this.stop();
        return;
      }
      this.drawFrame(this.frame);
      
      var frameAdvance = (this.frameDrawStarted - this.frameDrawEnded) / (1000 / this.config.turnsPerSecond );
      if(isNaN(frameAdvance)){
        frameAdvance = 0.3;
      }
      
      this.frame += Math.min(1,Math.max(0.0166, frameAdvance ));
      this.frameDrawEnded = new Date().getTime();
      
      
      // Todo: If frameAdvance is the miniumum size (on a super fast system), then 
      // we need to delay drawing the next frame.
      var timeToNextDraw = 1;
      setTimeout(function() { Visualizer.run.apply(Visualizer); }, timeToNextDraw);
    },
    
    setFrame: function(targetFrame, wholeNumber){
      if(wholeNumber===true){
        targetFrame = Math.floor(targetFrame);
      }
      this.frame = Math.max(0,Math.min(this.moves.length-1, targetFrame));
    },
    
    parseDataFromFile: function(input) {
    	var gameResult = $.parseJSON(input);
		if (gameResult == null && input != '') {
			// Maybe already JSON ?
			gameResult = input;
		}

    	this.parseData(gameResult);
    },
    
    parseDataFromUrl: function(url) {
    	var that = this;
    	$.ajax({
    		url: url,
    	    dataType: 'json',
    		cache: false,
    		beforeSend: function(xhr) {
    			$("#players").html("Fetching remote replay ...");
    		},
    		success: function(data) {
    			try {
        			$("#players").html("Loading");
        			that.parseData(data);
    			} catch (e) {
        			$("#players").html("Error parsing result");
        			if (window.console && console.error) console.error(e);
    			}
        	},
    		error: function(jqXHR, textStatus, errorThrown) {
    			$("#players").html("Error loading remote replay :(");
    			if (window.console && console.error) {
    				console.error(jqXHR);
    			}
    		}
    	});
    },
    
    parseData: function(gameResult) {
    	this.game_id = gameResult.game_id;
    	//this.map_id = gameResult.;
    	this.game_time = gameResult.date;

    	var status = gameResult.status;
    	var playernames = gameResult.playernames;
    	var submission_ids = gameResult.submission_ids;
    	var user_ids = gameResult.user_ids;
    	
    	var playersNbr = gameResult.replaydata.players;
    	this.players = new Array();
    	for (var i=0; i<playersNbr; i++) {
    		this.players[i] = {
	            id: parseInt(user_ids[i]),
	            submission_id: parseInt(submission_ids[i]),
	            name: playernames[i],
	            status: status[i]
	        };
    	}
    	
        this.parsePlaybackData(gameResult.replaydata);
        
        this.init();
    },
    
    parsePlaybackData: function(replaydata) {
        // planets: [(x,y,owner,numShips,growthRate)]
        this.planets = replaydata.map.data.map(ParserUtils.parsePlanet);
        
        // insert planets as first move
        this.moves.push({
           'planets': this.planets.map(function(a) { return {
                owner: parseInt(a.owner),
                numShips: parseInt(a.numShips)
            }; }),
           'moving': []
        });

        // turns: [(owner,numShips)] 
        // ++ [(owner,numShips,sourcePlanet,destinationPlanet,totalTripLength,turnsRemaining)]
        if (replaydata.turns < 2) { 
          return; // No turns.
        }
        for(var i=0; i<replaydata.map.history.length; i++) {
            var turn = replaydata.map.history[i].split(',');
            var move = {};
            
            move.planets = turn.slice(0, this.planets.length).map(ParserUtils.parsePlanetState);
            var fleet_strings = turn.slice(this.planets.length);
            if( fleet_strings.length == 1 && fleet_strings[0] == '' ){
                fleet_strings = [];
            }
            move.moving = fleet_strings.map(ParserUtils.parseFleet);
            
            this.moves.push(move);
        }
    },
    
    _eof: true
};

var ParserUtils = {
    parseFleet: function(data) {
        data = data.split(' ');
        // (type owner numShips sourcePlanet destinationPlanet totalTripLength turnsRemaining)
        return {
			type: data[0],
            owner: parseInt(data[1]),
            numShips: parseInt(data[2]),
            source: Visualizer.planets[data[3]],
            destination: Visualizer.planets[data[4]],
            tripLength: parseInt(data[5]),
            progress: parseInt(data[5] - data[6])
        };
    },
    
    parsePlanet: function(data) {
        data = data.split(' ');
		type = data[0];
		
        if (type == "M") {
			return {
				type: type,
				x: parseFloat(data[1]),
				y: parseFloat(data[2]),
				owner: parseInt(data[3]),
				numShips: parseInt(data[4])
			};
        } else if (type == 'E') {
			return {
				type: type,
				x: parseFloat(data[1]),
				y: parseFloat(data[2]),
				owner: parseInt(data[3]),
				numShips: parseInt(data[4]),
				growthRate: parseInt(data[5])
			};
        }
    },
    
    parsePlanetState: function(data) {
        data = data.split(' ');
        // (owner,numShips)
        return {
            owner: parseInt(data[0]),
            numShips: parseInt(data[1])
        };
    },
    
    _eof: true
};

function initStaticData() {
	var playersHtml = '';
    for (var i = 0; i < Visualizer.players.length; i++) {
		playersHtml += '<a style="color: '+ Visualizer.config.teamColor[i+1] +'"';
		if ('survived' == Visualizer.players[i].status) {
			playersHtml += ' class="winner"';
		} else {
			playersHtml += ' class="looser"';
		}
		if (userUrl) {
			playersHtml += ' href="' + userUrl + Visualizer.players[i].id + '"';
		}
		playersHtml += '>';
		playersHtml += (i+1) + '. ' + Visualizer.players[i].name;
		playersHtml += '</a>&nbsp;&nbsp;';
	}
	$('#players').html(playersHtml);
    //$('title').text('CGI - Planet Wars - Match '+Visualizer.game_id);
    $('#macthId').text(Visualizer.game_id);
}

function hookButtons() {
    // Hook buttons
    $('#play-button').click(function() {
        if(!Visualizer.playing){
            if(Visualizer.frame > Visualizer.moves.length - 2){
              Visualizer.setFrame(0);
            }
            Visualizer.start();
          } else {
            Visualizer.stop();
          }
          return false;
      });
    
    $('#start-button').click(function() {
        Visualizer.setFrame(0);
        Visualizer.drawFrame(Visualizer.frame);
        Visualizer.stop();
        return false;
    });
    
    $('#end-button').click(function() {
        Visualizer.setFrame(Visualizer.moves.length - 1, true);
        Visualizer.drawFrame(Visualizer.frame);
        Visualizer.stop();
        return false;
    });

    $('#prev-frame-button').click(function() {
        Visualizer.setFrame(Visualizer.frame - 1, true);
        Visualizer.drawFrame(Visualizer.frame);
        Visualizer.stop();
        return false;
    });
    
    $('#next-frame-button').click(function() {
        Visualizer.setFrame(Visualizer.frame + 1);
        Visualizer.drawFrame(Visualizer.frame);
        Visualizer.stop();
        return false;
    });
    
    $('#fast-button').click(function() {
        Visualizer.config.turnsPerSecond += 2;
        return false;
    });
	
    $('#slow-button').click(function() {
        Visualizer.config.turnsPerSecond -= 2;
        return false;
    });
}

function bindActionsAndEvents() {
    $(document.documentElement).keydown(function(evt){
        if(evt.keyCode == '37'){ // Left Arrow
        	$('#prev-frame-button').click();
            return false;
        }else if(evt.keyCode == '39'){ // Right Arrow
        	$('#next-frame-button').click();
            return false;
        }else if(evt.keyCode == '32'){ // Spacebar
        	$('#play-button').click();
            return false;
        }
    });
    
    // Update turn counter after redraw
    $('#display').bind('drawn', function(){
    	$('#turnCounter').text(Math.floor(Visualizer.frame+1)+' of '+Visualizer.moves.length);
    });
		
	// Add onclick event on timeline
	$('#feedline').click(function(event) {
		var canvas = $('#feedline');
	    var widthFactor = canvas.width() / Math.max(200, Visualizer.moves.length);
		
		var x = event.pageX - canvas.offset().left;
		
		Visualizer.stop();
		Visualizer.setFrame(parseInt((x / widthFactor) + 1));
		Visualizer.drawFrame(Visualizer.frame);
		
		return false;
	});
}

(function($) {
	Visualizer.setup();
})(window.jQuery);
