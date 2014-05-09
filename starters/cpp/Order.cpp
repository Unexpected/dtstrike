#include "Order.h"

Order::Order(int sourcePlanet, int destPlanet, int numShips) :
	sourcePlanet(sourcePlanet),
	destPlanet(destPlanet),
	numShips(numShips)
{
}


void Order::print(std::ostream& out) const
{
	out << sourcePlanet << " " << destPlanet << " " << numShips;
}
