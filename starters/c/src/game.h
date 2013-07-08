/*
 * game.h
 *
 *  Created on: 8 juil. 2013
 *      Author: louis
 */

#ifndef GAME_H_
#define GAME_H_

struct coordinates {
	double x;
	double y;
};

struct economic_planet {
	int id;
	int owner;
	int shipsNumber;
	struct coordinates coordinates;
	int revenue;
};

struct military_planet {
	int id;
	int owner;
	int shipsNumber;
	struct coordinates coordinates;
};

struct fleet {
	int owner;
	int shipsNumber;
	int sourcePlanetId;
	int destinationPlanetId;
	int totalTripLength;
	int turnsRemaining;
};

#endif /* GAME_H_ */
