#ifndef FLEET_H
#define FLEET_H

#include <memory>

struct Fleet: public std::enable_shared_from_this<Fleet>
{
	Fleet(int owner, int numShips, int sourcePlanet, int destPlanet,
	      int totalTripLength, int turnsRemaining);

	virtual ~Fleet();

	void destroy();

	void doTimeStep();

	int owner;
	int numShips;
	int sourcePlanet;
	int destPlanet;
	int totalTripLength;
	int turnsRemaining;
};

using FleetPtr = std::shared_ptr<Fleet>;

#endif
