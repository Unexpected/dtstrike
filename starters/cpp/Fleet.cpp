#include "Fleet.h"

Fleet::Fleet(int owner, int numShips, int sourcePlanet, int destPlanet,
	     int totalTripLength, int turnsRemaining) :
	owner(owner),
	numShips(numShips),
	sourcePlanet(sourcePlanet),
	destPlanet(destPlanet),
	totalTripLength(totalTripLength),
	turnsRemaining(turnsRemaining)
{
}

Fleet::~Fleet() = default;


void Fleet::destroy()
{
	owner = 0;
	numShips = 0;
	turnsRemaining = 0;
}

void Fleet::doTimeStep()
{
	turnsRemaining -= 1;
	if (turnsRemaining < 0) {
		turnsRemaining = 0;
	}
}
