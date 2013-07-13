package com.cgi.itwar;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashSet;
import java.util.Hashtable;

/**
 * Map Generator
 * 
 * <pre>
 * The class generates Colonies in a Map
 * 
 * Initial parameters:
 * 	The number of players
 * 	The size of the Map (width and height, in pixels)
 * 	The displayed radius of each Colony
 * 	The number of starting Base and non-Base(normal) Colonies
 * 	The number of neutral Base and non-Base(normal) Colonies 
 * 
 * It splits the map into individual quadrants, two per player.
 * 
 *  |---------------------------------|---------------------------------|
 *  |  primary quadrant of player #0  | secondary quadrant of player #0 |
 *  |---------------------------------|---------------------------------|
 *  | secondary quadrant of player #1 |  primary quadrant of player #1  |
 *  |---------------------------------|---------------------------------|
 *  |  primary quadrant of player #2  | secondary quadrant of player #2 |
 *  |---------------------------------|---------------------------------|
 *  | secondary quadrant of player #3 |  primary quadrant of player #3  |
 *  |---------------------------------|---------------------------------|
 *  |      ... etc ...                |      ... etc ...                |
 *  |---------------------------------|---------------------------------|
 *  
 *  Bases and Colonies are disposed according to the rules below:
 *  	First Base Colony of each player is placed in the player's primary quadrant
 *  	First non-Base Colony of each player is placed in the player's primary quadrant 
 *  	Other player Colonies are placed randomly (see rules on random placement) on one of the player's quadrants
 *  	Neutral Colonies are placed randomly (see rules on random placement) on any quadrant
 *  
 *  Random placement follows the rules below:
 *  	A quadrant is chosen randomly among the quadrants that have the minimum of colonies of all the candidate quadrants
 *  	The colony is placed in a quadrant at least at a 2*radius distance from the quadrant's edges
 *  	The colony is placed in a quadrant at least at a 3*radius distance from any other colony in the same quadrant
 * 
 * </pre>
 * 
 * @author vergosd
 * 
 */
public class MapGenerator {
	private final int nbGamers;
	private final int mapHeight;
	private final int mapWidth;
	final int minDistanceFromColony;
	final int minDistanceFromEdge;

	public final boolean debug;
	final static int TIMEOUT = 1000;

	private ArrayList<Quadrant> quadrants = new ArrayList<Quadrant>();

	private class Quadrant {
		HashSet<Colony> colonies = new HashSet<Colony>();
		final int index;
		final int offsetX;
		final int offsetY;

		Quadrant(int index, int offsetX, int offsetY) {
			this.index = index;
			this.offsetX = offsetX;
			this.offsetY = offsetY;
		}

		private void tryPutColony(boolean isBase, int player) {
			int x, y;
			boolean invalid = true;
			long t = System.currentTimeMillis();
			do {
				x = (int) (Math.random() * (getQuadrantWidth() - (2 * minDistanceFromEdge)));
				y = (int) (Math.random() * (getQuadrantHeight() - (2 * minDistanceFromEdge)));
				x += minDistanceFromEdge;
				y += minDistanceFromEdge;
				if (debug) System.out.println("Trying " + x + "," + y + " into " + this);
				if (colonies.size() == 0) {
					invalid = false;
				} else {
					for (Colony colony : colonies) {
						if (Math.abs(colony.y - y) >= minDistanceFromColony && Math.abs(colony.x - x) >= minDistanceFromColony) {
							invalid = false;
							break;
						}
					}
				}
				if (invalid && (System.currentTimeMillis() - t > TIMEOUT)) {
					throw new RuntimeException("Can't place colony in " + this);
				}
			} while (invalid);
			Colony colony = new Colony(x, y, player, isBase);
			if (debug) System.out.println(colony + " placed in quadrant #" + this.index);
			colonies.add(colony);
		}

		int getNbOfColonies() {
			return colonies.size();
		}

		@Override
		public String toString() {
			return "Quadrant[#" + index + " at{" + offsetX + "," + offsetY + "} colonies:{" + colonies + "}]";
		}
	}
	private class Colony {
		public final int x;
		public final int y;
		public final int gamer;
		public final boolean isBase;
		public static final int NEUTRAL_PLAYER = -1;
		public final int numShip;
		public final int growthRate = 2;
		public final int maxX;
		public final int maxY;

		public Colony(int x, int y, int player, boolean isBase) {
			this(x, y, player, isBase, 24, 24);
		}
		public Colony(int x, int y, int player, boolean isBase, int maxX, int maxY) {
			this.x = x;
			this.y = y;
			this.gamer = player;
			this.isBase = isBase;
			this.numShip = (int)Math.round(Math.random()*60);
			this.maxX = maxX;
			this.maxY = maxY;
		}
		
		/**
		 * Return the colony with the correct map format.
		 * <ul>
		 * <li>M|E : the colony type</li>
		 * <li>X : on 24 base double</li>
		 * <li>Y : on 24 base double</li>
		 * <li>owner</li>
		 * <li>NumShip (0 < random < 60)</li>
		 * <li>GrowthRate (= 2) only if E type colony</li>
		 * </ul>
		 * 
		 * @return colony representation
		 */
		public String toMap() {
			return (isBase ? "M" : "E") 					// Type
					+ " " + (24d * x / maxX)				// X
					+ " " + (24d * y / maxY)				// Y
					+ " " + (gamer + 1)						// Owner
					+ " " + numShip							// NumShip
					+ (isBase ? "" : " "+growthRate) 		// GrowthRate
					;
		}

		public String toReplay() {
			return (isBase ? "M" : "E") 					// Type
					+ "," + (24d * x / maxX)				// X
					+ "," + (24d * y / maxY)				// Y
					+ "," + (gamer + 1)						// Owner
					+ "," + numShip							// NumShip
					+ (isBase ? "" : ","+growthRate) 		// GrowthRate
					;
		}

		@Override
		public String toString() {
			return "Colony["
					+ x
					+ ","
					+ y
					+ " "
					+ ((isBase) ? "BASE" : "COLONY")
					+ " belongs to "
					+ ((gamer == Colony.NEUTRAL_PLAYER) ? "NEUTRAL" : "#"
							+ (gamer + 1)) + "]";
		}
	}

	public MapGenerator(boolean debug, int nbGamers, int mapHeight, int mapWidth, int colonyRadius, int basesPerGamer, int coloniesPerGamer, int neutralBases,
			int neutralColonies) {
		long debut = System.currentTimeMillis();
		this.debug = debug;
		if (debug) System.out.println("Building MapGenerator");
		/* Initialize variables */
		this.nbGamers = nbGamers;
		this.mapHeight = mapHeight;
		this.mapWidth = mapWidth;

		minDistanceFromColony = 3 * colonyRadius;
		minDistanceFromEdge = 2 * colonyRadius;

		/* Check validity */
		if (nbGamers < 2) throw new RuntimeException("Number of gamers must be at least 2.");
		if (basesPerGamer < 1) throw new RuntimeException("Each participant must have at least one base.");
		if (coloniesPerGamer < 1) throw new RuntimeException("Each participant must have at least one colony.");
		if (colonyRadius < 1) throw new RuntimeException("Base colonyRadius must be at least 1 pixel");
		int minHeight = minDistanceFromEdge * 2 * nbGamers;
		if (mapHeight < minHeight) throw new RuntimeException("Map height should be at least " + minHeight + ".");
		int minWidth = minDistanceFromEdge * 2 * 2;
		if (mapWidth < minWidth) throw new RuntimeException("Map width should be at least " + minWidth + ".");

		/* Initialize Quadrants */
		for (int i = 0; i < nbGamers; i++) {
			int offsetX = 0;
			int offsetY = i * getQuadrantHeight();
			quadrants.add(new Quadrant(i * 2, offsetX, offsetY));
			offsetX += getQuadrantWidth();
			quadrants.add(new Quadrant((i * 2) + 1, offsetX, offsetY));
		}

		/* Populate Quadrants with gamer colonies */
		for (int gamer = 0; gamer < nbGamers; gamer++) {
			boolean isPrimary = true;
			for (int i = 0; i < basesPerGamer; i++) {
				getLeastPopulatedQuadrant(gamer, isPrimary).tryPutColony(true, gamer);
				isPrimary = false;
			}
			isPrimary = true;
			for (int i = 0; i < coloniesPerGamer; i++) {
				getLeastPopulatedQuadrant(gamer, isPrimary).tryPutColony(false, gamer);
				isPrimary = false;
			}
		}
		/* Populate Quadrants with neutral colonies */
		for (int i = 0; i < neutralBases; i++) {
			getLeastPopulatedQuadrant().tryPutColony(true, Colony.NEUTRAL_PLAYER);
		}
		for (int i = 0; i < neutralColonies; i++) {
			getLeastPopulatedQuadrant().tryPutColony(false, Colony.NEUTRAL_PLAYER);
		}
		if (debug) System.out.println("Map built in " + (System.currentTimeMillis() - debut) + "ms.");
	}

	private int getQuadrantWidth() {
		return mapWidth / 2;
	}

	private int getQuadrantHeight() {
		return mapHeight / nbGamers;
	}

	private Quadrant getLeastPopulatedQuadrant() {
		/* calc min colonies of any quadrant */
		int min = Integer.MAX_VALUE;
		for (Quadrant quadrant : quadrants) {
			int nb = quadrant.getNbOfColonies();
			if (nb < min) min = nb;
		}
		long t = System.currentTimeMillis();
		do {
			int i = (int) (Math.random() * quadrants.size());
			if (quadrants.get(i).getNbOfColonies() == min) return quadrants.get(i);
			if (System.currentTimeMillis() - t > TIMEOUT) throw new RuntimeException("Cannot find least populated for min=" + min);
		} while (true);
	}

	private Quadrant getLeastPopulatedQuadrant(int gamer, boolean isPrimary) {
		int primary = (gamer * 2) + (gamer % 2);
		if (isPrimary) {
			return quadrants.get(primary);
		} else {
			int secondary = (gamer * 2) + (-1 * (gamer % 2)) + 1;
			if (quadrants.get(primary).getNbOfColonies() > quadrants.get(secondary).getNbOfColonies()) return quadrants.get(secondary);
			else return quadrants.get(primary);
		}

	}

	public ArrayList<Colony> getColonies() {
		ArrayList<Colony> colonies = new ArrayList<Colony>();
		for (int i = 0; i < quadrants.size(); i++) {
			Quadrant quadrant = quadrants.get(i);
			for (Colony colony : quadrant.colonies) {
				colonies.add(new Colony(colony.x + quadrant.offsetX, colony.y + quadrant.offsetY, colony.gamer, colony.isBase, this.mapWidth, this.mapHeight));
			}
		}
		return colonies;
	}

	private final static int MIN_PLAYER = 2;
	private final static int MAX_PLAYER = 5;

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		Hashtable<String, Integer> options = null;
		try {
			options = parseOptions(args);
		} catch (Exception e) {
			System.err.println("Error parsing parameters:");
			System.err.println(Arrays.toString(args));
		}
		
		if (options.containsKey("help")) {
			usage();
			System.exit(0);
		}
		if (!options.containsKey("nbPlayers") || options.get("nbPlayers") < MIN_PLAYER || options.get("nbPlayers") > MAX_PLAYER) {
			System.err.println("Wrong nbPlayers : "+options.get("nbPlayers"));
			usage();
			System.exit(1);
		}
		
		MapGenerator map=new MapGenerator(
				options.containsKey("debug"),
				options.get("nbPlayers"), 
				options.get("mapHeight"), 
				options.get("mapWidth"), 
				options.get("colonyRadius"), 
				options.get("gamerBases"),
				options.get("gamerColonies"),
				options.get("neutralBases"),
				options.get("neutralColonies")
				);
		ArrayList<Colony> colonies=map.getColonies();
		if (map.debug) {
			System.out.println("Got "+colonies.size()+" colonies:");
			for (Colony colony : colonies) {
				System.out.println(colony);
			}
		} else if (options.containsKey("replay")) {
			// Replayer ouput
			boolean first = true;
			for (Colony colony : colonies) {
				if (!first) System.out.print(":");
				System.out.print(colony.toReplay());
				first = false;
			}
		} else {
			// Live ouput
			for (Colony colony : colonies) {
				System.out.println(colony.toMap());
			}
		}
	}
	
	public static void usage() {
		System.out.println("Usage : java Test <option>");
		System.out.println("  Available options (all nb are in Integer format) :");
		System.out.println("    -help : Display this help screen");
		//System.out.println("    -debug : Print debug informations");
		//System.out.println("    -replay : To get output in 'replay' mode");
		System.out.println("    -nbPlayers <nb> [4] : Define number of players on the map");
		System.out.println("    -mapHeight <nb> [800] : Height of the map");
		System.out.println("    -mapWidth <nb> [800] : Width of the map");
		System.out.println("    -colonyRadius <nb> [3] : The displayed radius of each Colony");
		System.out.println("    -gamerBases <nb> [2] : The number of starting Base and non-Base(normal) Colonies");
		System.out.println("    -gamerColonies <nb> [3] : The number of starting non-Base(normal) Colonies");
		System.out.println("    -neutralBases <nb> [4] : The number of neutral Base and non-Base(normal) Colonies");
		System.out.println("    -neutralColonies <nb> [16] : The number of neutral non-Base(normal) Colonies");
		System.out.println("");
		System.out.println("nbPlayers should be > "+MIN_PLAYER+" and < "+MAX_PLAYER);
	}

	public static Hashtable<String, Integer> parseOptions(String[] args) {
		Hashtable<String, Integer> ret = new Hashtable<String, Integer>();
		if (args != null && args.length != 0) {
			// parse args
			for (int i=0; i<args.length; i++) {
				String key = args[i];
				if (key.startsWith("-")) key = key.substring(1);
				
				if ("help".equals(key)) {
					ret.put(key, Integer.valueOf(1));
					return ret;
				} else if ("replay".equals(key)
						|| "debug".equals(key)) {
					ret.put(key, Integer.valueOf(1));
				} else if ("nbPlayers".equals(key)
						|| "mapHeight".equals(key)
						|| "mapWidth".equals(key)
						|| "colonyRadius".equals(key)
						|| "gamerBases".equals(key)
						|| "gamerColonies".equals(key)
						|| "neutralBases".equals(key)
						|| "neutralColonies".equals(key)) {
					// Get next arg
					i++;
					Integer val = Integer.valueOf(args[i]);
					ret.put(key, val);
				} else {
					System.err.println("Parameter '"+key+"' not used.");
				}
			}
		}
		
		// Add default values
		int nbPlayers = 4;
		if (!ret.containsKey("nbPlayers")) {
			ret.put("nbPlayers", nbPlayers);
		} else {
			nbPlayers = ret.get("nbPlayers");
		}
		if (!ret.containsKey("mapHeight")) {
			if (nbPlayers == 2) {
				ret.put("mapHeight", 400);
			} else {
				ret.put("mapHeight", 800);
			}
		}
		if (!ret.containsKey("mapWidth")) {
			if (nbPlayers == 2) {
				ret.put("mapWidth", 400);
			} else {
				ret.put("mapWidth", 800);
			}
		}
		if (!ret.containsKey("colonyRadius")) {
			ret.put("colonyRadius", 3);
		}
		if (!ret.containsKey("gamerBases")) {
			if (nbPlayers == 2) {
				ret.put("gamerBases", 1);
			} else {
				ret.put("gamerBases", 2);
			}
		}
		if (!ret.containsKey("gamerColonies")) {
			if (nbPlayers == 2) {
				ret.put("gamerColonies", 2);
			} else {
				ret.put("gamerColonies", 3);
			}
		}
		if (!ret.containsKey("neutralBases")) {
			ret.put("neutralBases", nbPlayers);
		}
		if (!ret.containsKey("neutralColonies")) {
			if (nbPlayers == 2) {
				ret.put("neutralColonies", nbPlayers*3);
			} else {
				ret.put("neutralColonies", nbPlayers*4);
			}
		}
		
		return ret;
	}
}
