package six.challenge.bot;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;

public class Game {
	// Init values
	private Map<String, String> options;

	private long turnStartTime;

	private final Set<Order> orders = new HashSet<Order>();
	private final List<Planet> planets = new ArrayList<Planet>();
	private final List<Fleet> fleets = new ArrayList<Fleet>();

	public Game(Map<String, String> options) {
		this.options = options;
	}

	/**
	 * Returns timeout for initializing and setting up the bot on turn 0.
	 * 
	 * @return timeout for initializing and setting up the bot on turn 0
	 */
	public int getLoadTime() {
		return Integer.parseInt(options.get("loadtime"));
	}

	/**
	 * Returns timeout for a single game turn, starting with turn 1.
	 * 
	 * @return timeout for a single game turn, starting with turn 1
	 */
	public int getTurnTime() {
		return Integer.parseInt(options.get("turntime"));
	}

	/**
	 * Returns maximum number of turns the game will be played.
	 * 
	 * @return maximum number of turns the game will be played
	 */
	public int getTurns() {
		return Integer.parseInt(options.get("turns"));
	}

	/**
	 * Sets turn start time.
	 * 
	 * @param turnStartTime
	 *            turn start time
	 */
	public void setTurnStartTime(long turnStartTime) {
		this.turnStartTime = turnStartTime;
	}

	/**
	 * Returns how much time the bot has still has to take its turn before
	 * timing out.
	 * 
	 * @return how much time the bot has still has to take its turn before
	 *         timing out
	 */
	public int getTimeRemaining() {
		return getTurnTime()
				- (int) (System.currentTimeMillis() - turnStartTime);
	}

	/**
	 * Clears game state information about my fleets locations.
	 */
	public void clearFleets() {
		fleets.clear();
	}

	/**
	 * Clears game state information about my planets locations.
	 */
	public void clearPlanets() {
		planets.clear();
	}

	/**
	 * Add a new planet
	 * 
	 * @param p
	 *            the planet
	 */
	public void addPlanet(Planet p) {
		planets.add(p);
	}

	/**
	 * Add a new fleet
	 * 
	 * @param f
	 *            the fleet
	 */
	public void addFleet(Fleet f) {
		fleets.add(f);
	}

	/**
	 * Returns all orders sent so far.
	 * 
	 * @return all orders sent so far
	 */
	public Set<Order> getOrders() {
		return orders;
	}

	/**
	 * Returns the number of planets. Planets are numbered starting with 0.
	 * 
	 * @return the planet count
	 */
	public int numPlanets() {
		return planets.size();
	}

	/**
	 * Returns the planet with the given planet_id They are numbered starting at
	 * 0. There are NumPlanets() planets. <b>planet_id's ARE consistent from one
	 * turn to the next.</b>
	 * 
	 * @param planetID
	 *            the planet ID
	 * @return the Planet instance
	 */
	public Planet getPlanet(int planetID) {
		return planets.get(planetID);
	}

	/**
	 * Returns the number of fleets.
	 * 
	 * @return the fleet count
	 */
	public int numFleets() {
		return fleets.size();
	}

	/**
	 * Returns the fleet with the given fleet_id. Fleets are numbered starting
	 * with 0. There are numFleets() fleets. <b>fleet_id's are not consistent
	 * from one turn to the next.</b>
	 * 
	 * @param fleetID
	 *            the fleet ID
	 * @return the Fleet instance
	 */
	public Fleet getFleet(int fleetID) {
		return fleets.get(fleetID);
	}

	/**
	 * Returns a list of all the planets.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getPlanets() {
		return planets;
	}


	/**
	 * Return a list of all the economic planets owned by the current player. By
	 * convention, the current player is always player number 1.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getMyEconomicPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == 1 && p instanceof EconomicPlanet) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the military planets owned by the current player. By
	 * convention, the current player is always player number 1.
	 * 
	 * @return the planet list
	 */
	public List<MilitaryPlanet> getMyMilitaryPlanets() {
		List<MilitaryPlanet> r = new ArrayList<MilitaryPlanet>();
		for (Planet p : planets) {
			if (p.owner == 1 && p instanceof MilitaryPlanet) {
				r.add((MilitaryPlanet)p);
			}
		}
		return r;
	}
	
	/**
	 * Return a list of all the planets owned by the current player. By
	 * convention, the current player is always player number 1.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getMyPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == 1) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all neutral planets.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getNeutralPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == 0) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the planets owned by rival players. This excludes
	 * planets owned by the current player, as well as neutral planets.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getEnemyPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner != 0 && p.owner != 1) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the planets owned by the targeted rival player.
	 * 
	 * @param playerID
	 *            player id (> 1)
	 * @return the planet list
	 */
	public List<Planet> getEnemyPlanets(int playerID) {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == playerID) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the military planets owned by rival players.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getEnemyMilitaryPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner != 0 && p.owner != 1 && p instanceof MilitaryPlanet) {
				r.add(p);
			}
		}
		return r;
	}
	
	/**
	 * Return a list of all the military planets owned by rival players.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getEnemyEconomicPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner != 0 && p.owner != 1 && p instanceof EconomicPlanet) {
				r.add(p);
			}
		}
		return r;
	}


	/**
	 * Return a list of all the military planets owned by the targeted rival
	 * player.
	 * 
	 * @param playerID
	 *            player id (> 1)
	 * @return the planet list
	 */
	public List<Planet> getEnemyMilitaryPlanets(int playerID) {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == playerID && p instanceof MilitaryPlanet) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the planets that are not owned by the current
	 * player. This includes all enemy planets and neutral planets.
	 * 
	 * @return the planet list
	 */
	public List<Planet> getNotMyPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner != 1) {
				r.add(p);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the fleets.
	 * 
	 * @return the fleet list
	 */
	public List<Fleet> getFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			r.add(f);
		}
		return r;
	}

	/**
	 * Return a list of all the fleets owned by the current player.
	 * 
	 * @return the fleet list
	 */
	public List<Fleet> getMyFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner == 1) {
				r.add(f);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the <b>economic</b> fleets owned by the current
	 * player.
	 * 
	 * @return the fleet list
	 */
	public List<Fleet> getMyEconomicFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner == 1 && f instanceof EconomicFleet) {
				r.add(f);
			}
		}
		return r;
	}
	
	/**
	 * Return a list of all the <b>military</b> fleets owned by the current
	 * player.
	 * 
	 * @return the fleet list
	 */
	public List<Fleet> getMyMilitaryFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner == 1 && f instanceof MilitaryFleet) {
				r.add(f);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the fleets owned by enemy players.
	 * 
	 * @return the fleet list
	 */
	public List<Fleet> getEnemyFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner != 1) {
				r.add(f);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the <b>military</b> fleets owned by enemy players.
	 * 
	 * @return the fleet list
	 */
	public List<Fleet> getEnemyMilitaryFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner != 1 && f instanceof MilitaryFleet) {
				r.add(f);
			}
		}
		return r;
	}

	/**
	 * Return a list of all the fleets owned by the targeted enemy player.
	 * 
	 * @param playerID
	 *            the player ID
	 * @return the fleet list
	 */
	public List<Fleet> getEnemyFleets(int playerID) {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner == playerID) {
				r.add(f);
			}
		}
		return r;
	}

	/**
	 * Returns the number of ships that the targeted player has, either located
	 * on planets or in flight.
	 * 
	 * @param playerID
	 *            the playerID
	 * @return the fleet count
	 */
	public int getNumShips(int playerID) {
		int numShips = 0;
		for (Planet p : planets) {
			if (p.owner == playerID) {
				numShips += p.numShips;
			}
		}
		for (Fleet f : fleets) {
			if (f.owner == playerID) {
				numShips += f.numShips;
			}
		}
		return numShips;
	}

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
	public int distance(int sourcePlanet, int destinationPlanet) {
		Planet source = planets.get(sourcePlanet);
		Planet destination = planets.get(destinationPlanet);
		double dx = source.x - destination.x;
		double dy = source.y - destination.y;
		return (int) Math.ceil(Math.sqrt(dx * dx + dy * dy));
	}
	
	public MilitaryPlanet findClosestMilitaryPlanet(Planet sourcePlanet) {
		return findClosestMilitaryPlanet(sourcePlanet.id);
	}
	
	public MilitaryPlanet findClosestMilitaryPlanet(int sourcePlanet) {
		MilitaryPlanet destination = null;
		int distance = Integer.MAX_VALUE;
		for (MilitaryPlanet p : getMyMilitaryPlanets()) {
			int score = distance(sourcePlanet, p.id);
			if (score < distance) {
				distance = score;
				destination = p;
			}
		}
		return destination;
	}

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
	public void issueOrder(int sourcePlanet, int destinationPlanet, int numShips) {
		Order order = new Order(sourcePlanet, destinationPlanet, numShips);
		orders.add(order);
		System.out.println(order);
	}

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
	public void issueOrder(Planet source, Planet dest, int numShips) {
		issueOrder(source.id, dest.id, numShips);
	}

}