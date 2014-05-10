#ifndef MILITARYFLEET_H
#define MILITARYFLEET_H

#include "Fleet.h"

struct MilitaryFleet: public Fleet
{
	MilitaryFleet(int owner, int numShips, int sourcePlanet, int destPlanet,
		      int totalTripLength, int turnsRemaining);

};

using MilitaryFleetPtr = std::shared_ptr<MilitaryFleet>;

#endif
