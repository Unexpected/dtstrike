using System;
using System.Collections.Generic;
using System.Linq;
using System.Globalization;
using System.Text;
using System.Collections;
using System.IO;

namespace DTStrike.MyBot
{
    public class Game
    {
        public List<Planet> planets;
	    public List<Fleet> fleets;
        public const int MY_ID = 1;

	    public Game(List<String> data) {
		    planets = new List<Planet>();
		    fleets = new List<Fleet>();
		    if (parse(data) > 0) {
                System.Console.Error.WriteLine("erreur parse...");
		    }
	    }

	    /**
	     * A player is alive if he owns at least one military planet or one fleet.
	     * 
	     * @param id
	     *            player id to check
	     * @return true if the player is alive
	     */
	    public bool isAlive(int id) {
		    foreach (Planet p in planets) {
			    if (p is MilitaryPlanet && p.owner == id)
				    return true;
		    }
		    foreach (Fleet f in fleets) {
			    if (f.owner == id && f.militaryFleet) {
				    return true;
			    }
		    }
		    return false;
	    }

	// Returns the number of planets. Planets are numbered starting with 0.
	public int numPlanets() {
		return planets.Count();
	}

	// Returns the planet with the given planet_id. There are NumPlanets()
	// planets. They are numbered starting at 0.
	public Planet getPlanet(int planetID) {
		return planets.Find(x=>x.id == planetID);
	}

	// Returns the number of fleets.
	public int numFleets() {
		return fleets.Count();
	}

	// Returns the fleet with the given fleet_id. Fleets are numbered starting
	// with 0. There are NumFleets() fleets. fleet_id's are not consistent from
	// one turn to the next.
	public Fleet getFleet(int fleetID) {
		return fleets.Find(x=>x.owner == fleetID);
	}

	// Returns a list of all the planets.
	public List<Planet> getPlanets() {
		return planets;
	}

	// Return a list of all the planets owned by the current player. By
	// convention, the current player is always player number 1.
	public List<Planet> getMyPlanets() {
		List<Planet> r = new List<Planet>();
		foreach (Planet p in planets) {
			if (p.owner == MY_ID) {
				r.Add(p);
			}
		}
		return r;
	}

	// Return a list of all the planets owned by the current player. By
	// convention, the current player is always player number 1.
	public List<Planet> getMyMilitaryPlanets() {
		List<Planet> r = new List<Planet>();
		foreach (Planet p in planets) {
			if (p.owner == MY_ID && p is MilitaryPlanet) {
				r.Add(p);
			}
		}
		return r;
	}

	// Return a list of all neutral planets.
	public List<Planet> getNeutralPlanets() {
		List<Planet> r = new List<Planet>();
		foreach (Planet p in planets) {
			if (p.owner == 0) {
				r.Add(p);
			}
		}
		return r;
	}

	// Return a list of all the planets owned by rival players. This excludes
	// planets owned by the current player, as well as neutral planets.
	public List<Planet> getEnemyPlanets() {
		List<Planet> r = new List<Planet>();
		foreach (Planet p in planets) {
			if (p.owner != 0 && p.owner != MY_ID) {
				r.Add(p);
			}
		}
		return r;
	}

	// Return a list of all the planets that are not owned by the current
	// player. This includes all enemy planets and neutral planets.
	public List<Planet> getNotMyPlanets() {
		List<Planet> r = new List<Planet>();
		foreach (Planet p in planets) {
			if (p.owner != MY_ID) {
				r.Add(p);
			}
		}
		return r;
	}

	// Return a list of all the fleets.
	public List<Fleet> getFleets() {
		List<Fleet> r = new List<Fleet>();
		foreach (Fleet f in fleets) {
			r.Add(f);
		}
		return r;
	}

	// Return a list of all the fleets owned by the current player.
	public List<Fleet> getMyFleets() {
		List<Fleet> r = new List<Fleet>();
		foreach (Fleet f in fleets) {
			if (f.owner == MY_ID) {
				r.Add(f);
			}
		}
		return r;
	}

    // Return a list of all the fleets owned by the current player.
    public List<Fleet> getMyMilitaryFleets()
    {
        List<Fleet> r = new List<Fleet>();
        foreach (Fleet f in fleets)
        {
            if (f.owner == MY_ID && f.militaryFleet)
            {
                r.Add(f);
            }
        }
        return r;
    }

	// Return a list of all the fleets owned by enemy players.
	public List<Fleet> getEnemyFleets() {
		List<Fleet> r = new List<Fleet>();
		foreach (Fleet f in fleets) {
			if (f.owner != MY_ID) {
				r.Add(f);
			}
		}
		return r;
	}

	// Returns the distance between two planets, rounded up to the next highest
	// integer. This is the number of discrete time steps it takes to get
	// between the two planets.
	public int distance(int sourcePlanet, int destinationPlanet) {
		Planet source = planets.Find(x=>x.id == sourcePlanet);
		Planet destination = planets.Find(x=>x.id == destinationPlanet);
		double dx = source.x - destination.x;
		double dy = source.y - destination.y;
		return (int) Math.Ceiling(Math.Sqrt(dx * dx + dy * dy));
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
		System.Console.Out.Write("" + sourcePlanet + " " + destinationPlanet + " "
				+ numShips + "\n");
        System.Console.Out.Flush();
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
		System.Console.Out.Write("" + source.id + " " + dest.id + " " + numShips + "\n");
        System.Console.Out.Flush();
	}

	// Sends the game engine a message to let it know that we're done sending
	// orders. This signifies the end of our turn.
	public void finishTurn() {
        System.Console.Out.Write("go\n");
        System.Console.Out.Flush();
	}

	// Returns the number of ships that the current player has, either located
	// on planets or in flight.
	public int numShips(int playerID) {
		int numShips = 0;
		foreach (Planet p in planets) {
			if (p.owner == playerID) {
				numShips += p.numShips;
			}
		}
		foreach (Fleet f in fleets) {
			if (f.owner == playerID) {
				numShips += f.numShips;
			}
		}
		return numShips;
	}

	private int parse(List<String> data) {
		planets = new List<Planet>();
        fleets = new List<Fleet>();

        CultureInfo en = CultureInfo.GetCultureInfo("en-US"); // Doubles are written with a . separator

		foreach (String str in data) {
			if (str.Trim().Length != 0) {
				String[] line = str.Split(' ');
				if (line.Length != 0) {
					if (line[0].Equals("M")) {
						if (line.Length != 5) {
							System.Console.Error.WriteLine("error line 0: " + str);
							return 1;
						}
						double x = Double.Parse(line[1], en);
                        double y = Double.Parse(line[2], en);
                        int owner = int.Parse(line[3]);
                        int numShips = int.Parse(line[4]);
                        
						MilitaryPlanet p = new MilitaryPlanet(planets.Count(),
								owner, numShips, x, y);
						planets.Add(p);
					} else if (line[0].Equals("E")) {
						if (line.Length != 6) {
							System.Console.Error.WriteLine("error line 1: " + str);
							return 1;
						}
                        double x = Double.Parse(line[1], en);
                        double y = Double.Parse(line[2], en);
                        int owner = int.Parse(line[3]);
                        int numShips = int.Parse(line[4]);
						int economicValue = int.Parse(line[5]);

						EconomicPlanet p = new EconomicPlanet(planets.Count(),
								owner, numShips, economicValue, x, y);
						planets.Add(p);
					} else if (line[0].Equals("F") || line[0].Equals("R")) {
						if (line.Length != 7) {
							System.Console.Error.WriteLine("error line 2: " + str);
							return 1;
						}
                        int owner = int.Parse(line[1]);
                        int numShips = int.Parse(line[2]);
                        int sourceDept = int.Parse(line[3]);
                        int destDept = int.Parse(line[4]);
                        int tripLength = int.Parse(line[5]);
                        int turnsRemaining = int.Parse(line[6]);
						Fleet fleet = new Fleet(owner, numShips, sourceDept,
                                destDept, tripLength, turnsRemaining, line[0].Equals("F"));
						this.fleets.Add(fleet);
					} else if (line[0].Equals("turn")) {
						// New turn
					}
				}
			}
		}
		return 0;
	}
    }
}
