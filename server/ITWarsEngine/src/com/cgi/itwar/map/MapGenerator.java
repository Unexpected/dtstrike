package com.cgi.itwar.map;

import java.awt.geom.Point2D;
import java.io.File;
import java.io.FileWriter;
import java.util.ArrayList;
import java.util.Arrays;
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
	public final int nbGamers;
	public final double mapHeight = 640;
	public final double mapWidth = 640;
	public final double colonyRadius = 20;
	public final double minDistanceFromColony;
	public final double minDistanceFromEdge;
	public final boolean debug;
	public final static int TIMEOUT = 2000;

	public final double quadrantAngle;
	private ArrayList<Quadrant> quadrants = new ArrayList<Quadrant>();


	public MapGenerator(boolean debug, int pNbGamers, int pBasesPerGamer, int pColoniesPerGamer, int pNeutralBases,	int pNeutralColonies) {
		long debut = System.currentTimeMillis();
		this.debug = debug;
		if (debug) {
			System.out.println("Building MapGenerator");
			System.out.println("  pNbGamers="+pNbGamers);
			System.out.println("  pBasesPerGamer="+pBasesPerGamer);
			System.out.println("  pColoniesPerGamer="+pColoniesPerGamer);
			System.out.println("  pNeutralBases="+pNbGamers);
			System.out.println("  pNeutralColonies="+pNeutralColonies);
			System.out.println("");
		}
		/* Initialize variables */
		this.nbGamers = pNbGamers;
		minDistanceFromColony = 1.5 * colonyRadius;
		minDistanceFromEdge = 1 * colonyRadius;

		/* Check validity */
		if (nbGamers < 2) throw new RuntimeException("Number of gamers must be at least 2.");
		if (pBasesPerGamer < 1) throw new RuntimeException("Each participant must have at least one base at start.");
		if (pColoniesPerGamer < 1) throw new RuntimeException("Each participant must have at least one colony at start.");
//		int minHeight = minDistanceFromEdge * 2 * nbGamers;
//		if (mapHeight < minHeight) throw new RuntimeException("Map height should be at least " + minHeight + ".");
//		int minWidth = minDistanceFromEdge * 2 * 2;
//		if (mapWidth < minWidth) throw new RuntimeException("Map width should be at least " + minWidth + ".");

		/* Initialize First quadrant */
		quadrantAngle = 360d/nbGamers;
		if (debug) System.out.println("Angle calculé pour les quadrants : "+quadrantAngle);
		Quadrant quadrant = new Quadrant(0, this);

		if (debug) System.out.println("");
		if (debug) System.out.println("Génération du premier quadrant aléatoire :");
		/* Populate Quadrant with gamer colonies */
		for (int i = 0; i < pBasesPerGamer; i++) {
			quadrant.tryPutColony(true, 0);
		}
		for (int i = 0; i < pColoniesPerGamer; i++) {
			quadrant.tryPutColony(false, 0);
		}
		/* Populate Quadrant with neutral colonies */
		for (int i = 0; i < pNeutralBases; i++) {
			quadrant.tryPutColony(true, Colony.NEUTRAL_PLAYER);
		}
		for (int i = 0; i < pNeutralColonies; i++) {
			quadrant.tryPutColony(false, Colony.NEUTRAL_PLAYER);
		}
		quadrants.add(quadrant);
		
		/* Création des autres quadrants par rotation */
		if (debug) System.out.println("");
		if (debug) System.out.println("Génération des autres quadrants par rotation :");
		Quadrant nextQuadrant;
		for (int gamer = 1; gamer < nbGamers; gamer++) {
			nextQuadrant = new Quadrant(gamer, this);

			for (Colony colony : quadrant.getColonies()) {
				Point2D newPoint = rotationPoint(new Point2D.Double(colony.x,  colony.y), (-1 * gamer * quadrantAngle));
				int newGamer = colony.gamer == Colony.NEUTRAL_PLAYER ? colony.gamer : gamer;
				
				nextQuadrant.addColony(new Colony(newPoint.getX(), newPoint.getY(), newGamer, colony.isBase, this.mapWidth, this.mapHeight));
			}
			
			quadrants.add(nextQuadrant);
		}
		
		if (debug) System.out.println("");
		if (debug) System.out.println("Map built in " + (System.currentTimeMillis() - debut) + "ms.");
	}

	public double getQuadrantWidth() {
		return mapWidth / 2;
	}

	public double getQuadrantHeight() {
		return mapHeight / 2;
	}

	public ArrayList<Colony> getColonies() {
		ArrayList<Colony> colonies = new ArrayList<Colony>();
		for (int i = 0; i < quadrants.size(); i++) {
			Quadrant quadrant = quadrants.get(i);
			for (Colony colony : quadrant.getColonies()) {
				colonies.add(new Colony(colony.x + getQuadrantWidth(), colony.y + getQuadrantHeight(), colony.gamer, colony.isBase, this.mapWidth, this.mapHeight));
			}
		}
		return colonies;
	}
	
	private Point2D rotationPoint(Point2D ptDepart, double angleRotation) {
		double angleRadian = Math.PI*angleRotation/180d;
		double sina = Math.sin(angleRadian);
		double cosa = Math.cos(angleRadian);
		if (angleRadian == -1*Math.PI) sina = 0; // Fix round error (with 180° rotation)
		double x1 = ptDepart.getX() * cosa - ptDepart.getY() * sina;
		double y1 = ptDepart.getX() * sina + ptDepart.getY() * cosa;
		
		return new Point2D.Double(x1, y1);
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
			// Replay ouput
			StringBuilder sb = new StringBuilder();
			sb.append("var data=\"game_id=4\\nwinner=1\\nmap_id=map1.txt\\ndraw=0\\ntimestamp=1371808248769\\nplayers=");
			for (int i=1; i<(map.nbGamers+1); i++) {
				if (i > 1) sb.append("|");
				sb.append(i+":player"+i);
			}
			sb.append("\\nplayback_string=");
			boolean first = true;
			for (Colony colony : colonies) {
				if (!first) sb.append(":");
				sb.append(colony.toReplay());
				first = false;
			}
			sb.append("\"");
			
			File f = new File("D:/dev/workspace/dtstrike/visualizer/game.js");
			if (f.exists()) {
				try {
					FileWriter fw = new FileWriter(f);
					fw.write(sb.toString());
					fw.close();
					System.out.println("Game file written");
				} catch (Exception e) {
					System.out.println(sb.toString());
				}
			} else {
				System.out.println(sb.toString());
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
		if (!ret.containsKey("gamerBases")) {
			if (nbPlayers == 2) {
				ret.put("gamerBases", 1);
			} else {
				ret.put("gamerBases", 2);
			}
		}
		if (!ret.containsKey("gamerColonies")) {
			if (nbPlayers == 2) {
				ret.put("gamerColonies", 1);
			} else {
				ret.put("gamerColonies", 3);
			}
		}
		if (!ret.containsKey("neutralBases")) {
			if (nbPlayers == 2) {
				ret.put("neutralBases", 1);
			} else {
				ret.put("neutralBases", nbPlayers);
			}
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
