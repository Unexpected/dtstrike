#ifndef MILITARYPLANET_H
#define MILITARYPLANET_H

#include "Planet.h"

struct MilitaryPlanet: public Planet
{
	MilitaryPlanet(int id, int owner, int numShips, double x, double y);
};

using MilitaryPlanetPtr = std::shared_ptr<MilitaryPlanet>;

#endif
