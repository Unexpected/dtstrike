import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

public class Game {

	public List<Planet> planets;
	public List<Fleet> fleets;
	public int myID;

	public Game(String gameState, int myID) throws Exception {
		planets = new ArrayList<Planet>();
		fleets = new ArrayList<Fleet>();
		this.myID = myID;
		if (parse(gameState) > 0) {
			throw new Exception("Error parsing game state");
		}
	}

	/**
	 * A player is alive if he owns at least one military planet or one fleet.
	 * 
	 * @param id
	 *            player id to check
	 * @return true if the player is alive
	 */
	public boolean isAlive(int id) {
		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet && p.owner == id)
				return true;
		}
		for (Fleet f : fleets) {
			if (f.owner == id) {
				return true;
			}
		}
		return false;
	}

	/**
	 * All player bases are belong to neutral player
	 * 
	 * @param id
	 *            player id to drop
	 */
	public void dropPlayer(int id) {
		for (Planet p : planets) {
			if (p.owner == id) {
				p.owner = 0;
			}
		}
		for (Fleet f : fleets) {
			if (f.owner == id) {
				f.destroy();
			}
		}
	}

	/**
	 * Returns a view of the current situation
	 * 
	 * @param paramInt
	 * @return
	 */
	public String playerView(int paramInt) {
		StringBuilder sb = new StringBuilder();
		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet) {
				sb.append(String.format("M %f %f %d %d\n", p.x, p.y, p.owner,
						p.numShips));
			} else if (p instanceof EconomicPlanet) {
				sb.append(String.format("E %f %f %d %d %d\n", p.x, p.y,
						p.owner, p.numShips, ((EconomicPlanet) p).revenue));
			}
		}
		for (Fleet f : fleets) {
			sb.append(String.format("F %d %d %d %d %d %d\n", f.owner,
					f.numShips, f.sourcePlanet, f.destinationPlanet,
					f.totalTripLength, f.turnsRemaining));
		}
		return sb.toString();
	}

	// Returns the number of planets. Planets are numbered starting with 0.
	public int numPlanets() {
		return planets.size();
	}

	// Returns the planet with the given planet_id. There are NumPlanets()
	// planets. They are numbered starting at 0.
	public Planet getPlanet(int planetID) {
		return planets.get(planetID);
	}

	// Returns the number of fleets.
	public int numFleets() {
		return fleets.size();
	}

	// Returns the fleet with the given fleet_id. Fleets are numbered starting
	// with 0. There are NumFleets() fleets. fleet_id's are not consistent from
	// one turn to the next.
	public Fleet getFleet(int fleetID) {
		return fleets.get(fleetID);
	}

	// Returns a list of all the planets.
	public List<Planet> getPlanets() {
		return planets;
	}

	// Return a list of all the planets owned by the current player. By
	// convention, the current player is always player number 1.
	public List<Planet> getMyPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == myID) {
				r.add(p);
			}
		}
		return r;
	}

	// Return a list of all the planets owned by the current player. By
	// convention, the current player is always player number 1.
	public List<Planet> getMyMilitaryPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == myID && p instanceof MilitaryPlanet) {
				r.add(p);
			}
		}
		return r;
	}

	// Return a list of all neutral planets.
	public List<Planet> getNeutralPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner == 0) {
				r.add(p);
			}
		}
		return r;
	}

	// Return a list of all the planets owned by rival players. This excludes
	// planets owned by the current player, as well as neutral planets.
	public List<Planet> getEnemyPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner != 0 && p.owner != myID) {
				r.add(p);
			}
		}
		return r;
	}

	// Return a list of all the planets that are not owned by the current
	// player. This includes all enemy planets and neutral planets.
	public List<Planet> getNotMyPlanets() {
		List<Planet> r = new ArrayList<Planet>();
		for (Planet p : planets) {
			if (p.owner != myID) {
				r.add(p);
			}
		}
		return r;
	}

	// Return a list of all the fleets.
	public List<Fleet> getFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			r.add(f);
		}
		return r;
	}

	// Return a list of all the fleets owned by the current player.
	public List<Fleet> getMyFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner == myID) {
				r.add(f);
			}
		}
		return r;
	}

	// Return a list of all the fleets owned by enemy players.
	public List<Fleet> getEnemyFleets() {
		List<Fleet> r = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.owner != myID) {
				r.add(f);
			}
		}
		return r;
	}

	// Returns the distance between two planets, rounded up to the next highest
	// integer. This is the number of discrete time steps it takes to get
	// between the two planets.
	public int distance(int sourcePlanet, int destinationPlanet) {
		Planet source = planets.get(sourcePlanet);
		Planet destination = planets.get(destinationPlanet);
		double dx = source.x - destination.x;
		double dy = source.y - destination.y;
		return (int) Math.ceil(Math.sqrt(dx * dx + dy * dy));
	}

	// Sends an order to the game engine. An order is composed of a source
	// planet number, a destination planet number, and a number of ships. A
	// few things to keep in mind:
	// * you can issue many orders per turn if you like.
	// * the planets are numbered starting at zero, not one.
	// * you must own the source planet. If you break this rule, the game
	// engine kicks your bot out of the game instantly.
	// * you can't move more ships than are currently on the source planet.
	// * the ships will take a few turns to reach their destination. Travel
	// is not instant. See the Distance() function for more info.
	public void issueOrder(int sourcePlanet, int destinationPlanet, int numShips) {
		System.out.print("" + sourcePlanet + " " + destinationPlanet + " "
				+ numShips + "\n");
		System.out.flush();
	}

	// Sends an order to the game engine. An order is composed of a source
	// planet number, a destination planet number, and a number of ships. A
	// few things to keep in mind:
	// * you can issue many orders per turn if you like.
	// * the planets are numbered starting at zero, not one.
	// * you must own the source planet. If you break this rule, the game
	// engine kicks your bot out of the game instantly.
	// * you can't move more ships than are currently on the source planet.
	// * the ships will take a few turns to reach their destination. Travel
	// is not instant. See the Distance() function for more info.
	public void issueOrder(Planet source, Planet dest, int numShips) {
		System.out
				.print("" + source.id + " " + dest.id + " " + numShips + "\n");
		System.out.flush();
	}

	// Sends the game engine a message to let it know that we're done sending
	// orders. This signifies the end of our turn.
	public void finishTurn() {
		System.out.print("go\n");
		System.out.flush();
	}

	// Returns the number of ships that the current player has, either located
	// on planets or in flight.
	public int numShips(int playerID) {
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

	private int parse(File mapFile) throws IOException {
		FileInputStream fis = new FileInputStream(mapFile);
		ByteArrayOutputStream bos = new ByteArrayOutputStream();
		int len;
		byte[] buf = new byte[2048];
		while ((len = fis.read(buf)) > 0) {
			bos.write(buf, 0, len);
		}
		fis.close();
		String map = bos.toString();
		return parse(map);
	}

	private int parse(String map) {
		planets = new ArrayList<Planet>();
		fleets = new ArrayList<Fleet>();

		String[] lines = map.split("\n");
		for (int i = 0; i < lines.length; i++) {
			String str = lines[i];

			int hash = str.indexOf(35);
			if (hash >= 0) {
				str = str.substring(0, hash);
			}
			if (str.trim().length() != 0) {
				String[] line = str.split(" ");
				if (line.length != 0) {
					if (line[0].equals("M")) {
						if (line.length != 5) {
							return 1;
						}
						double x = Double.parseDouble(line[1]);
						double y = Double.parseDouble(line[2]);
						int owner = Integer.parseInt(line[3]);
						int numShips = Integer.parseInt(line[4]);

						MilitaryPlanet p = new MilitaryPlanet(planets.size(),
								owner, numShips, x, y);
						planets.add(p);
					} else if (line[0].equals("E")) {
						if (line.length != 6) {
							return 1;
						}
						double x = Double.parseDouble(line[1]);
						double y = Double.parseDouble(line[2]);
						int owner = Integer.parseInt(line[3]);
						int numShips = Integer.parseInt(line[4]);
						int economicValue = Integer.parseInt(line[5]);

						EconomicPlanet p = new EconomicPlanet(planets.size(),
								owner, numShips, economicValue, x, y);
						planets.add(p);
					} else if (line[0].equals("F")) {
						if (line.length != 7) {
							return 1;
						}
						int owner = Integer.parseInt(line[1]);
						int numShips = Integer.parseInt(line[2]);
						int sourceDept = Integer.parseInt(line[3]);
						int destDept = Integer.parseInt(line[4]);
						int tripLength = Integer.parseInt(line[5]);
						int turnsRemaining = Integer.parseInt(line[6]);
						Fleet fleet = new Fleet(owner, numShips, sourceDept,
								destDept, tripLength, turnsRemaining);
						this.fleets.add(fleet);
					} else {
						return 1;
					}
				}
			}
		}
		return 0;
	}
}
