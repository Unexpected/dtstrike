#include "EconomicPlanet.h"

EconomicPlanet::EconomicPlanet(int id, int owner, int numShips, int revenue, double x, double y) :
	Planet(id, owner, numShips, x, y),
	revenue(revenue)
{
}
