#include "Game.h"


#include <math.h>
#include <limits>

#include <iostream>

Game::Game() = default;

void Game::setOption(std::string key, std::string value)
{
	options[key] = value;
}

int Game::getLoadTime() const
{
	return std::stoi(options.at("loadtime"));
}

int Game::getTurnTime() const
{
	return std::stoi(options.at("turntime"));
}


int Game::getTurns() const
{
	return std::stoi(options.at("turns"));
}

void Game::setTurnStartTime(long turnStartTime)
{
	this->turnStartTime = turnStartTime;
}

int Game::getTimeRemaining() const
{
	return getTurnTime() - getSystemTimeInMillis() - turnStartTime;
}


void Game::clearFleets()
{
	fleets.clear();
}

void Game::clearPlanets()
{
	planets.clear();
}

void Game::addPlanet(PlanetPtr p)
{
	planets.push_back(p);
}

void Game::addFleet(FleetPtr f)
{
	fleets.push_back(f);
}

const std::set<OrderPtr>& Game::getOrders()
{
	return orders;
}

void Game::clearOrders()
{
	orders.clear();
}


int Game::numPlanets() const
{
	return planets.size();
}


PlanetPtr Game::getPlanet(int planetID)
{
	return planets.at(planetID);
}

int Game::numFleets() const
{
	return fleets.size();
}

FleetPtr Game::getFleet(int fleetID)
{
	return fleets.at(fleetID);
}

const std::vector<PlanetPtr>& Game::getPlanets()
{
	return planets;
}


/**
 * Iterate on container and add matching elements
 */
template<typename TargetType, typename Predicate, typename Container>
std::vector<std::shared_ptr<TargetType> > getMatching(const Container& container, Predicate predicate)
{
	typename std::vector<std::shared_ptr<TargetType> > result;
	for(auto c : container)
	{
		typename std::shared_ptr<TargetType> cast = std::dynamic_pointer_cast<TargetType>(c);
		if (cast and predicate(cast)) {
			result.push_back(cast);
		}
	}
	return result;
}

template<typename TargetType, typename Predicate>
std::vector<std::shared_ptr<TargetType> > Game::getFleetsMatching(Predicate predicate)
{
	return getMatching<TargetType>(fleets, predicate);
}

template<typename TargetType, typename Predicate>
std::vector<std::shared_ptr<TargetType> > Game::getPlanetsMatching(Predicate predicate)
{
	return getMatching<TargetType>(planets, predicate);
}


std::vector<EconomicPlanetPtr> Game::getMyEconomicPlanets()
{
	return getPlanetsMatching<EconomicPlanet>(
		[] (EconomicPlanetPtr p) {
			return p->owner == 1;
		});
}

std::vector<MilitaryPlanetPtr> Game::getMyMilitaryPlanets()
{
	return getPlanetsMatching<MilitaryPlanet>(
		[] (MilitaryPlanetPtr p) {
			return p->owner == 1;
		});
}

std::vector<PlanetPtr> Game::getMyPlanets()
{
	return getPlanetsMatching<Planet>(
		[] (PlanetPtr p) {
			return p->owner == 1;
		});
}


std::vector<PlanetPtr> Game::getNeutralPlanets()
{
	return getPlanetsMatching<Planet>(
		[] (PlanetPtr p) {
			return p->owner == 0;
		});
}


std::vector<PlanetPtr> Game::getEnemyPlanets()
{
	return getPlanetsMatching<Planet>(
		[] (PlanetPtr p) {
			return p->owner != 0 and p->owner != 1;
		});
}


std::vector<PlanetPtr> Game::getEnemyPlanets(int playerID)
{
	return getPlanetsMatching<Planet>(
		[playerID] (PlanetPtr p) {
			return p->owner == playerID;
		});
}


std::vector<MilitaryPlanetPtr> Game::getEnemyMilitaryPlanets()
{
	return getPlanetsMatching<MilitaryPlanet>(
		[] (MilitaryPlanetPtr p) {
			return p->owner != 0 and p->owner != 1;
		});
}


std::vector<MilitaryPlanetPtr> Game::getEnemyMilitaryPlanets(int playerID)
{
	return getPlanetsMatching<MilitaryPlanet>(
		[playerID] (MilitaryPlanetPtr p) {
			return p->owner == playerID;
		});
}


std::vector<PlanetPtr> Game::getNotMyPlanets()
{
	return getPlanetsMatching<Planet>(
		[] (PlanetPtr p) {
			return p->owner != 1;
		});
}


const std::vector<FleetPtr>& Game::getFleets()
{
	return fleets;
}


std::vector<FleetPtr> Game::getMyFleets()
{
	return getFleetsMatching<Fleet>(
		[] (FleetPtr f) {
			return f->owner == 1;
		});
}


std::vector<EconomicFleetPtr> Game::getMyEconomicFleets()
{
	return getFleetsMatching<EconomicFleet>(
		[] (EconomicFleetPtr f) {
			return f->owner == 1;
		});
}


std::vector<MilitaryFleetPtr> Game::getMyMilitaryFleets()
{
	return getFleetsMatching<MilitaryFleet>(
		[] (MilitaryFleetPtr f) {
			return f->owner == 1;
		});
}


std::vector<FleetPtr> Game::getEnemyFleets()
{
	return getFleetsMatching<Fleet>(
		[] (FleetPtr f) {
			return f->owner != 1;
		});
}


std::vector<MilitaryFleetPtr> Game::getEnemyMilitaryFleets()
{
	return getFleetsMatching<MilitaryFleet>(
		[] (MilitaryFleetPtr f) {
			return f->owner != 1;
		});
}


std::vector<FleetPtr> Game::getEnemyFleets(int playerID)
{
	return getFleetsMatching<Fleet>(
		[playerID] (FleetPtr f) {
			return f->owner == playerID;
		});
}


int Game::getNumShips(int playerID) const
{
	int numShips = 0;

	for (PlanetPtr p: planets) {
		if (p->owner == playerID) {
			numShips += p->numShips;
		}
	}

	for (FleetPtr f: fleets) {
		if (f->owner == playerID) {
			numShips += f->numShips;
		}
	}
	return numShips;
}


int Game::distance(int sourcePlanet, int destinationPlanet) const
{
	PlanetPtr source = planets.at(sourcePlanet);
	PlanetPtr destination = planets.at(destinationPlanet);

	double dx = source->x - destination->x;
	double dy = source->y - destination->y;

	return static_cast<int>(sqrt(dx*dx + dy*dy) + 0.5);
}


MilitaryPlanetPtr Game::findClosestMilitaryPlanet(PlanetPtr sourcePlanet)
{
	return findClosestMilitaryPlanet(sourcePlanet->id);
}


MilitaryPlanetPtr Game::findClosestMilitaryPlanet(int sourcePlanet)
{
	MilitaryPlanetPtr destination = nullptr;

	int min_distance = std::numeric_limits<int>::max();
	for (MilitaryPlanetPtr p: getMyMilitaryPlanets()) {
		int score = distance(sourcePlanet, p->id);
		if (score < min_distance) {
			min_distance = score;
			destination = p;
		}
	}
	return destination;
}


void Game::issueOrder(int sourcePlanet, int destinationPlanet, int numShips)
{
	OrderPtr order = std::make_shared<Order>(sourcePlanet, destinationPlanet, numShips);
	orders.insert(order);
	order->print(std::cout);
	std::cout << std::endl;
}


void Game::issueOrder(PlanetPtr source, PlanetPtr dest, int numShips)
{
	issueOrder(source->id, dest->id, numShips);
}
