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
			if (ecoPlanet.numShips > 50) {
				var target = game.findNearestMilitaryPlanet(ecoPlanet);
				if (target != null) {
					var numShips = (int) (ecoPlanet.numShips / 2);
					game.issueOrder(ecoPlanet.id, target.id, numShips);
				}
			}
		}
		
		// (1) If we currently have a fleet in flight, just do nothing.
		if (game.myMilitaryFleets().length >= 1) {
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

		// (3) Find the weakest enemy or neutral planet.
		var dest = null;
		var destScore = Number.MAX_VALUE;
		var planets = game.notMyPlanets();
		for ( var i = 0, len = planets.length; i < len; ++i) {
			var p = planets[i];
			var score = p.numShips;
			if (score < destScore) {
				destScore = score;
				dest = p;
			}
		}

		// (4) Send half the ships from my strongest planet to the weakest
		// planet that I do not own.
		if (source != null && dest != null) {
			var numShips = source.numShips / 2;
			game.issueOrder(source.id, dest.id, numShips);
		}
		game.finishTurn();
    },
    'onEnd': function() {
		game.finishTurn();
    }
};
game.start(bot);