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


struct military_planets {
	int size;
	int _array_size;
	struct military_planet * list;
};

struct economic_planets {
	int size;
	int _array_size;
	struct economic_planet * list;
};

struct fleets {
	int size;
	int _array_size;
	struct fleet * list;
};

struct military_planets my_military_planets();
struct military_planets enemy_military_planets();
struct military_planets neutral_military_planets();

struct economic_planets my_economic_planets();
struct economic_planets enemy_economic_planets();
struct economic_planets neutral_economic_planets();

struct fleets my_fleets();
struct fleets enemy_fleets();
struct fleets neutral_fleets();

int distance (struct coordinates src, struct coordinates dest);

void issueOrder(int src, int dest, int numShips);

void finishTurn();

void initTurn();

void freeData();

void parseLine(char *p);

char **str_split (char *, const char *);

#endif /* GAME_H_ */
