#ifndef GAME_H
#define GAME_H

#include <string>
#include <map>
#include <vector>
#include <set>

#include "Planet.h"
#include "MilitaryPlanet.h"
#include "EconomicPlanet.h"

#include "Fleet.h"
#include "MilitaryFleet.h"
#include "EconomicFleet.h"

#include "Order.h"


class Game
{
public:
	using options_map = std::map<std::string, std::string>;

	Game();

	/**
	 * Returns timeout for initializing and setting up the bot on turn 0.
	 *
	 * @return timeout for initializing and setting up the bot on turn 0
	 */
	int getLoadTime() const;

	/**
	 * Returns timeout for a single game turn, starting with turn 1.
	 *
	 * @return timeout for a single game turn, starting with turn 1
	 */
	int getTurnTime() const;


	/**
	 * Returns maximum number of turns the game will be played.
	 *
	 * @return maximum number of turns the game will be played
	 */
	int getTurns() const;

	/**
	 * Sets turn start time.
	 *
	 * @param turnStartTime
	 *            turn start time
	 */
	void setTurnStartTime(long turnStartTime);


	/**
	 * Returns how much time the bot has still has to take its turn before
	 * timing out.
	 *
	 * @return how much time the bot has still has to take its turn before
	 *         timing out
	 */
	int getTimeRemaining() const;


	/**
	 * Clears game state information about my fleets locations.
	 */
	void clearFleets();


	/**
	 * Clears game state information about my planets locations.
	 */
	void clearPlanets();


	/**
	 * Add a new planet
	 *
	 * @param p
	 *            the planet
	 */
	void addPlanet(PlanetPtr p);


	/**
	 * Add a new fleet
	 *
	 * @param f
	 *            the fleet
	 */
	void addFleet(FleetPtr f);


	/**
	 * Returns all orders sent so far.
	 *
	 * @return all orders sent so far
	 */
	const std::set<OrderPtr>& getOrders();

	/**
	 * Remove all orders.
	 */
	void clearOrders();


	/**
	 * Returns the number of planets. Planets are numbered starting with 0.
	 *
	 * @return the planet count
	 */
	int numPlanets() const;


	/**
	 * Returns the planet with the given planet_id They are numbered starting at
	 * 0. There are NumPlanets() planets. <b>planet_id's ARE consistent from one
	 * turn to the next.</b>
	 *
	 * @param planetID
	 *            the planet ID
	 * @return the Planet instance
	 */
	PlanetPtr getPlanet(int planetID);


	/**
	 * Returns the number of fleets.
	 *
	 * @return the fleet count
	 */
	int numFleets() const;


	/**
	 * Returns the fleet with the given fleet_id. Fleets are numbered starting
	 * with 0. There are numFleets() fleets. <b>fleet_id's are not consistent
	 * from one turn to the next.</b>
	 *
	 * @param fleetID
	 *            the fleet ID
	 * @return the Fleet instance
	 */
	FleetPtr getFleet(int fleetID);


	/**
	 * Returns a list of all the planets.
	 *
	 * @return the planet list
	 */
	const std::vector<PlanetPtr>& getPlanets();


	/**
	 * Return a list of all the economic planets owned by the current player. By
	 * convention, the current player is always player number 1.
	 *
	 * @return the planet list
	 */
	std::vector<EconomicPlanetPtr> getMyEconomicPlanets();


	/**
	 * Return a list of all the military planets owned by the current player. By
	 * convention, the current player is always player number 1.
	 *
	 * @return the planet list
	 */
	std::vector<MilitaryPlanetPtr> getMyMilitaryPlanets();


	/**
	 * Return a list of all the planets owned by the current player. By
	 * convention, the current player is always player number 1.
	 *
	 * @return the planet list
	 */
	std::vector<PlanetPtr> getMyPlanets();


	/**
	 * Return a list of all neutral planets.
	 *
	 * @return the planet list
	 */
	std::vector<PlanetPtr> getNeutralPlanets();


	/**
	 * Return a list of all the planets owned by rival players. This excludes
	 * planets owned by the current player, as well as neutral planets.
	 *
	 * @return the planet list
	 */
	std::vector<PlanetPtr> getEnemyPlanets();


	/**
	 * Return a list of all the planets owned by the targeted rival player.
	 *
	 * @param playerID
	 *            player id (> 1)
	 * @return the planet list
	 */
	std::vector<PlanetPtr> getEnemyPlanets(int playerID);


	/**
	 * Return a list of all the military planets owned by rival players.
	 *
	 * @return the planet list
	 */
	std::vector<MilitaryPlanetPtr> getEnemyMilitaryPlanets();


	/**
	 * Return a list of all the military planets owned by the targeted rival
	 * player.
	 *
	 * @param playerID
	 *            player id (> 1)
	 * @return the planet list
	 */
	std::vector<MilitaryPlanetPtr> getEnemyMilitaryPlanets(int playerID);


	/**
	 * Return a list of all the planets that are not owned by the current
	 * player. This includes all enemy planets and neutral planets.
	 *
	 * @return the planet list
	 */
	std::vector<PlanetPtr> getNotMyPlanets();


	/**
	 * Return a list of all the fleets.
	 *
	 * @return the fleet list
	 */
	const std::vector<FleetPtr>& getFleets();


	/**
	 * Return a list of all the fleets owned by the current player.
	 *
	 * @return the fleet list
	 */
	std::vector<FleetPtr> getMyFleets();


	/**
	 * Return a list of all the <b>economic</b> fleets owned by the current
	 * player.
	 *
	 * @return the fleet list
	 */
	std::vector<EconomicFleetPtr> getMyEconomicFleets();


	/**
	 * Return a list of all the <b>military</b> fleets owned by the current
	 * player.
	 *
	 * @return the fleet list
	 */
	std::vector<MilitaryFleetPtr> getMyMilitaryFleets();


	/**
	 * Return a list of all the fleets owned by enemy players.
	 *
	 * @return the fleet list
	 */
	std::vector<FleetPtr> getEnemyFleets();


	/**
	 * Return a list of all the <b>military</b> fleets owned by enemy players.
	 *
	 * @return the fleet list
	 */
	std::vector<MilitaryFleetPtr> getEnemyMilitaryFleets();


	/**
	 * Return a list of all the fleets owned by the targeted enemy player.
	 *
	 * @param playerID
	 *            the player ID
	 * @return the fleet list
	 */
	std::vector<FleetPtr> getEnemyFleets(int playerID);


	/**
	 * Returns the number of ships that the targeted player has, either located
	 * on planets or in flight.
	 *
	 * @param playerID
	 *            the playerID
	 * @return the fleet count
	 */
	int getNumShips(int playerID) const;


	/**
	 * Returns the distance between two planets, rounded up to the next highest
	 * integer. This is the number of discrete time steps it takes to get
	 * between the two planets.
	 *
	 * @param sourcePlanet
	 *            the ID of the source planet
	 * @param destinationPlanet
	 *            the ID of the destination planet
	 * @return the distance
	 */
	int distance(int sourcePlanet, int destinationPlanet) const;


	MilitaryPlanetPtr findClosestMilitaryPlanet(PlanetPtr sourcePlanet);


	MilitaryPlanetPtr findClosestMilitaryPlanet(int sourcePlanet);


	/**
	 * Sends an order to the game engine.<br/>
	 * An order is composed of a source planet number, a destination planet
	 * number, and a number of ships. A few things to keep in mind:
	 * <ul>
	 * <li>you can issue many orders per turn if you like.</li>
	 * <li>the planets are numbered starting at zero, not one.</li>
	 * <li>you must own the source planet.<br/>
	 * <b>If you break this rule, the game engine kicks your bot out of the game
	 * instantly.</b></li>
	 * <li>you can't move more ships than are currently on the source planet.</li>
	 * <li>the ships will take a few turns to reach their destination.<br/>
	 * <b>Travel is not instant.</b><br/>
	 * See the distance() function for more info.</li>
	 * </ul>
	 *
	 * @param sourcePlanet
	 *            the ID of the source planet
	 * @param destinationPlanet
	 *            the ID of the destination planet
	 * @param numShips
	 *            the number of ships to send
	 */
	void issueOrder(int sourcePlanet, int destinationPlanet, int numShips);


	/**
	 * Sends an order to the game engine
	 *
	 * @see Game#issueOrder(int, int, int)
	 * @param source
	 *            the instance of the source planet
	 * @param dest
	 *            the instance of the destination planet
	 * @param numShips
	 *            the number of ships to send
	 */
	void issueOrder(PlanetPtr source, PlanetPtr dest, int numShips);

	/**
	 * Sets an option if the options map.
	 */
	void setOption(std::string key, std::string value);

private:
	/**
	 * Prevent copy constructors and such.
	 */
	Game(const Game&) = delete;
	Game(Game&&) = delete;
	Game& operator=(const Game&) = delete;
	Game& operator=(Game&&) = delete;


	std::map<std::string, std::string> options;

	template<typename TargetType, typename Predicate>
	std::vector<std::shared_ptr<TargetType> > getPlanetsMatching(Predicate p);

	template<typename TargetType, typename Predicate>
	std::vector<std::shared_ptr<TargetType> > getFleetsMatching(Predicate p);

	long turnStartTime;
	std::set<OrderPtr> orders;
	std::vector<PlanetPtr> planets;
	std::vector<FleetPtr> fleets;
};

inline long getSystemTimeInMillis()
{
	timespec ts;
	clock_gettime(CLOCK_MONOTONIC, &ts);

	return ts.tv_nsec / 1000;
}

#endif
