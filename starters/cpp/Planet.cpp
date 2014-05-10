#include "Planet.h"

#include <iostream>

Planet::Planet(int id, int owner, int numShips, double x, double y) :
	id(id),
	owner(owner),
	numShips(numShips),
	x(x),
	y(y)
{
}

Planet::~Planet() = default;
