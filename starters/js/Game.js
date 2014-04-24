var fs = require('fs');


exports.game = {
	'bot' : null,
	/** @type {number} */
	'currentTurn' : 0,
	/** @type {boolean} */
	'turnEnded': true,
	/** @type {Order[]} */
	'orders' : [],
	/** @type {Planet[]} */
	'planets' : [],
	/** @type {Fleet[]} */
	'fleets' : [],

	/**
	 * Get total number of planets
	 * 
	 * @returns {number}
	 */
	'numPlanets' : function() {
		return this.planets.length;
	},
	/**
	 * Get a planet by id
	 * 
	 * @param {number} planetID
	 * @returns {Planet}
	 */
	'getPlanet' : function(planetID) {
		return this.planets[planetID];
	},

	/**
	 * Get total number of fleets
	 * 
	 * @returns {number}
	 */
	'numFleets' : function() {
		return this.fleets.length;
	},
	/**
	 * Get a fleet by id
	 * 
	 * @param {number} fleetID
	 * @returns {Fleet}
	 */
	'getFleet' : function(fleetID) {
		return this.fleets[fleetID];
	},

	/**
	 * Get all my planets
	 * 
	 * @this {Game}
	 * @returns {Planet[]}
	 */
	'myPlanets' : function() {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner == 1) {
				result.push(p);
			}
		}
		return result;
	},

	/**
	 * Get my economic planets
	 * 
	 * @this {Game}
	 * @returns {Planet[]}
	 */
	'myEconomicPlanets' : function() {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner == 1 && p.type == 'E') {
				result.push(p);
			}
		}
		return result;
	},

	/**
	 * Get my military planets
	 * 
	 * @this {Game}
	 * @returns {Planet[]}
	 */
	'myMilitaryPlanets' : function() {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner == 1 && p.type == 'M') {
				result.push(p);
			}
		}
		return result;
	},

	'neutralPlanets' : function() {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner == 0) {
				result.push(p);
			}
		}
		return result;
	},

	'ennemyPlanets' : function() {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner != 0 && p.owner != 1) {
				result.push(p);
			}
		}
		return result;
	},

	'ennemyPlanets' : function(playerID) {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner != 0 && p.owner == playerID) {
				result.push(p);
			}
		}
		return result;
	},

	'notMyPlanets' : function() {
		var result = [];
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner != 1) {
				result.push(p);
			}
		}
		return result;
	},

	'myFleets' : function() {
		var result = [];
		for ( var i = 0, len = this.fleets.length; i < len; ++i) {
			var fleet = this.fleets[i];
			if (fleet.owner == 1) {
				result.push(fleet);
			}
		}
		return result;
	},
	'myMilitaryFleets' : function() {
		var result = [];
		for ( var i = 0, len = this.fleets.length; i < len; ++i) {
			var fleet = this.fleets[i];
			if (fleet.owner == 1 && fleet.type == 'M') {
				result.push(fleet);
			}
		}
		return result;
	},
	'enemyFleets' : function() {
		var result = [];
		for ( var i = 0, len = this.fleets.length; i < len; ++i) {
			var fleet = this.fleets[i];
			if (fleet.owner != 1) {
				result.push(fleet);
			}
		}
		return result;
	},
	'enemyMilitaryFleets' : function() {
		var result = [];
		for ( var i = 0, len = this.fleets.length; i < len; ++i) {
			var fleet = this.fleets[i];
			if (fleet.owner != 1 && fleet.type == 'M') {
				result.push(fleet);
			}
		}
		return result;
	},
	'enemyFleets' : function(playerID) {
		var result = [];
		for ( var i = 0, len = this.fleets.length; i < len; ++i) {
			var fleet = this.fleets[i];
			if (fleet.owner != playerID) {
				result.push(fleet);
			}
		}
		return result;
	},

	/**
	 * Get distance between 2 objects
	 * 
	 * @this {Game}
	 * @param {Planet|number} sourcePlanet, either planet objet or planet id
	 * @param {Planet|number} destinationPlanet, either planet objet or planet id
	 * @returns {number}
	 */
	'distance' : function(sourcePlanet, destinationPlanet) {
		var source = null;
		var destination = null;
		if (sourcePlanet instanceof Object) {
			source = sourcePlanet;
		} else {
			source = this.planets[sourcePlanet];
		}
		if (destinationPlanet instanceof Object) {
			destination = destinationPlanet;
		} else {
			destination = this.planets[destinationPlanet];
		}
		if (source == null || destination == null) return Number.MAX_VALUE;
		
		var dx = source.x - destination.x;
		var dy = source.y - destination.y;
		return Math.ceil(Math.sqrt(dx * dx + dy * dy));
	},

	/**
	 * Get total number of ships of a player
	 * @param {Integer} playerID, the id of the player
	 * @returns {number} the number of ships
	 */
	'numShips' : function(playerID) {
		var numShips = 0;
		for ( var i = 0, len = this.planets.length; i < len; ++i) {
			var p = this.planets[i];
			if (p.owner == playerID) {
				numShips += p.numShips;
			}
		}
		for ( var i = 0, len = this.fleets.length; i < len; ++i) {
			var f = this.fleets[i];
			if (f.owner == playerID) {
				numShips += f.numShips;
			}
		}
		return numShips;
	},
    
	/**
	 * Get the nearest military planet
	 * 
	 * @this {Game}
	 * @param {Planet} ecoPlanet, the Planet objet
	 * @returns {Planet} the nearest military planet
	 */
    'findNearestMilitaryPlanet' : function(ecoPlanet) {
    	var min_dist = Number.MAX_VALUE;
    	var target = null;
		var planets = this.myMilitaryPlanets();
		for ( var i = 0, len = planets.length; i < len; ++i) {
			var p = planets[i];
			var dist = this.distance(ecoPlanet, p);
			if (dist < min_dist) {
				min_dist = dist;
				target = p;
			}
		}
		return target;
    },
	
	/**
	 *  Do NOT touch the following methods
	 */
	'start' : function(botInput) {
		this.bot = botInput;

		var partialline = "";
		process.stdin.resume();
		process.stdin.setEncoding('utf8');
		var thisoutside = this;
		process.stdin.on('data', function(chunk) {
			if (thisoutside.turnEnded) {
				// Reset gama data on turn start
				thisoutside.currentTurn++;
				thisoutside.planets = [];
				thisoutside.fleets = [];
				thisoutside.turnEnded = false;
			}
			
			var lines = chunk.split("\n");
			lines[0] = partialline + lines[0];
			partialline = "";
			// Complete lines will leave an empty
			// string at the end, if that is not the case
			// buffer this line until the next chunk
			if (lines[lines.length - 1] !== "") {
				partialline = lines[lines.length - 1];
				lines.splice(lines.length - 1, 1);
			}
			for ( var i = 0, len = lines.length; i < len; ++i) {
				thisoutside.processLine(lines[i]);
			}
		});
	},
	'processLine' : function(line) {
		line = line.trim().split(' ');
		
		if (line[0] === 'ready') {
			this.bot.onReady();
			if (!this.turnEnded) {
				// Failsafe if bot forgot to send 'go'
				this.finishTurn();
			}
			return;
		} else if (line[0] === 'go') {
			this.bot.onTurn();
			if (!this.turnEnded) {
				// Failsafe if bot forgot to send 'go'
				this.finishTurn();
			}
			return;
		} else if (line[0] === 'end') {
			this.bot.onEnd();
			if (!this.turnEnded) {
				// Failsafe if bot forgot to send 'go'
				this.finishTurn();
			}
			return;
		}
		
		if (line.length != 0) {
			if (line[0] == "M") {
				if (line.length != 5) {
					return 1;
				}
				this.planets.push({
					'type' : 'M',
					'id' : this.planets.length,
					'x' : parseFloat(line[1]),
					'y' : parseFloat(line[2]),
					'owner' : parseInt(line[3]),
					'numShips' : parseInt(line[4])
				});
			} else if (line[0] == "E") {
				if (line.length != 6) {
					return 1;
				}
				this.planets.push({
					'type' : 'E',
					'id' : this.planets.length,
					'x' : parseFloat(line[1]),
					'y' : parseFloat(line[2]),
					'owner' : parseInt(line[3]),
					'numShips' : parseInt(line[4]),
					'revenue' : parseInt(line[5])
				});
			} else if (line[0] == "F") {
				if (line.length != 7) {
					return 1;
				}
				this.fleets.push({
					'type' : 'M',
					'owner' : parseInt(line[1]),
					'numShips' : parseInt(line[2]),
					'sourcePlanet' : parseInt(line[3]),
					'destinationPlanet' : parseInt(line[4]),
					'totalTripLength' : parseInt(line[5]),
					'turnsRemaining' : parseInt(line[6])
				});
			} else if (line[0] == "R") {
				if (line.length != 7) {
					return 1;
				}
				this.fleets.push({
					'type' : 'E',
					'owner' : parseInt(line[1]),
					'numShips' : parseInt(line[2]),
					'sourcePlanet' : parseInt(line[3]),
					'destinationPlanet' : parseInt(line[4]),
					'totalTripLength' : parseInt(line[5]),
					'turnsRemaining' : parseInt(line[6])
				});
			} else {
				return 1;
			}
		}
	},
	'issueOrder' : function(src, dest, numShip) {
		this.orders.push({
			'src' : parseInt(src),
			'dest' : parseInt(dest),
			'numShip' : parseInt(numShip)
		});
	},
	'finishTurn' : function() {
		for ( var i = 0, len = this.orders.length; i < len; ++i) {
			var order = this.orders[i];
			fs.writeSync(process.stdout.fd, '' + order.src + ' ' + order.dest
					+ ' ' + order.numShip + '\n');
		}
		this.orders = [];
		fs.writeSync(process.stdout.fd, 'go\n');
		this.turnEnded = true;
	},
	'log' : function(msg) {
		fs.writeSync(process.stderr.fd, msg + '\n');
	}
};