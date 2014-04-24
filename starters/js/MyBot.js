/** @type {Game} */
var game = require('./Game').game;

var bot = {
    'onReady': function() {
    	game.finishTurn();
    },
    'onTurn': function() {
		// (0) Send reinforcement from eco to military
		var planets = game.myEconomicPlanets();
		for ( var i = 0, len = planets.length; i < len; ++i) {
			var ecoPlanet = planets[i];
			if (ecoPlanet.numShips > 50) {
				var target = game.findNearestMilitaryPlanet(ecoPlanet);
				if (target != null) {
					game.issueOrder(ecoPlanet.id, target.id, 50);
				}
			}
		}
		
		// (1) If we currently have 2 fleets in flight, just do nothing.
		if (game.myMilitaryFleets().length >= 2) {
			game.finishTurn();
			return;
		}
		
		// (2) Find my strongest military planet.
		var source = null;
		var sourceShips = Number.MIN_VALUE;
		var planets = game.myMilitaryPlanets();
		for ( var i = 0, len = planets.length; i < len; ++i) {
			var p = planets[i];
			var score = p.numShips;
			if (score > sourceShips) {
				sourceShips = score;
				source = p;
			}
		}

		// (3) Find the nearest enemy or neutral planet.
		var dest = null;
		var destScore = Number.MAX_VALUE;
		var planets = game.notMyPlanets();
		for ( var i = 0, len = planets.length; i < len; ++i) {
			var p = planets[i];
			var score = game.distance(source, p);
			if (score < destScore) {
				destScore = score;
				dest = p;
			}
		}

		// (4) Send all the ships to the target planet.
		if (source != null && dest != null) {
			game.issueOrder(source.id, dest.id, source.numShips);
		}
		game.finishTurn();
    },
    'onEnd': function() {
		game.finishTurn();
    }
};
game.start(bot);