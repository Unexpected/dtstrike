#include "MyBot.h"
#include <limits>

MyBot::MyBot() = default;

void MyBot::doTurn()
{
	PlanetPtr source, dest;

	// (1) If an economic planet have more than 50 ships, send 50 ships to the closest military planet.
	for (PlanetPtr p : game.getMyEconomicPlanets()) {
		int score = p->numShips;
		if (score > 50) {
			source = p;
			dest = game.findClosestMilitaryPlanet(source);
			if (dest) {
				game.issueOrder(source, dest, 50);
			}
		}
	}

	// (2) If we currently have more than 2 fleet in flight, just do nothing.
	if (game.getMyMilitaryFleets().size() > 2) {
		return;
	}

	// (3) Find my strongest military planet.
	source = nullptr;
	int sourceShips = std::numeric_limits<int>().min();
	for (PlanetPtr p : game.getMyMilitaryPlanets()) {
		int score = p->numShips;
		if (score > sourceShips) {
			sourceShips = score;
			source = p;
		}
	}

	// No military planet found, just stop here.
	if (source == nullptr) {
		return;
	}


	// (4) Find the closest enemy or neutral planet.
	dest = nullptr;
	int distMin = std::numeric_limits<int>().max();
	for (PlanetPtr p : game.getNotMyPlanets()) {
		int distance = game.distance(source->id, p->id);
		if (distance < distMin) {
			distMin = distance;
			dest = p;
		}
	}

	// (5) Send all the ships from my strongest planet to the weakest
	// planet that I do not own.
	if (source and dest) {
		int numShips = source->numShips;
		if (numShips > 0) {
			game.issueOrder(source, dest, numShips);
		}
	}
}

void MyBot::doReadyTurn()
{
}

int main(int , const char **)
{
	MyBot bot;

	bot.readSystemInput();
	return 0;
}
