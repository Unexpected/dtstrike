#ifndef ECONOMICFLEET_H
#define ECONOMICFLEET_H

#include "Fleet.h"

struct EconomicFleet: public Fleet
{
	EconomicFleet(int owner, int numShips, int sourcePlanet, int destPlanet,
		      int totalTripLength, int turnsRemaining);
};

using EconomicFleetPtr = std::shared_ptr<EconomicFleet>;

#endif
