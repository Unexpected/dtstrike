#ifndef PLANET_H
#define PLANET_H

#include <memory>

struct Planet: public std::enable_shared_from_this<Planet>
{
	Planet(int id, int owner, int numShips, double x, double y);
	virtual ~Planet();

	int id;
	int owner;
	int numShips;
	double x;
	double y;
};

using PlanetPtr = std::shared_ptr<Planet>;


#endif
