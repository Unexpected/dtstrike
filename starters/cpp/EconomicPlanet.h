#ifndef ECONOMICPLANET_H
#define ECONOMICPLANET_H

#include "Planet.h"

struct EconomicPlanet: public Planet
{
	EconomicPlanet(int id, int owner, int numShips, int revenue, double x, double y);

	int revenue;
};

using EconomicPlanetPtr = std::shared_ptr<EconomicPlanet>;

#endif
