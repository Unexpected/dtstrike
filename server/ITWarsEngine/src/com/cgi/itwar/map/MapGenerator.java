package com.cgi.itwar.map;

import java.awt.geom.Point2D;
import java.io.File;
import java.io.FileWriter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Hashtable;

/**
 * <h1>Générateur de map symétriques</h1>
 * <br>
 * Les paramètres sont les suivants :<br>
 * <ul>
 *   <li>nbPlayers [4] : Nombre de joueurs sur la carte</li>
 *   <li>gamerMilitary [1] : Nombre de planète militaire de base par joueur</li>
 *   <li>gamerEconomic [1] : Nombre de planète économique de base par joueur</li>
 *   <li>neutralMilitary [1] : Nombre de planète militaire neutre par joueur</li>
 *   <li>neutralEconomic [3] : Nombre de planète économique neutre par joueur</li>
 * </ul>
 * Les 4 dernièrs paramètres correspondent aux nombres de colonies générées dans le 1er cadrant !<br>
 * <br>
 * <pre>
 * Cette classe génère des maps pour de 2 à 4 joueurs de manière symétrique.
 * 
 * La génération est faite comme suit :
 *   - Découpage du plateau (640 x 640) en 1 "part"
 *     Cette part correspond à un angle de 360° / Nb de joueurs.
 *   - Génération aléatoire des bases pour 1 joueurs et les neutres dans ce cadrant.
 *   - Duplication du cadrant par rotation pour générer les autres joueurs.
 * </pre>
 * FIXME : La détection de proximité des "bords" pour une colonie ne prend pas en compte la bordure de fin de la "part".
 * 
 * @author Dimitri Vergos
 * @author Sébastien Schmitt
 * 
 */
public class MapGenerator {
	public final int nbGamers;
	public final int basesPerGamer;
	public final double mapHeight = 640;
	public final double mapWidth = 640;
	public final double colonyRadius = 20;
	public final double minDistanceFromColony;
	public final double minDistanceFromEdge;
	public final boolean debug;
	public int timeout = 1000;

	public final double quadrantAngle;
	public final double quadrantMinX;
	public final double quadrantMaxX;
	public final double quadrantMinY;
	public final double quadrantMaxY;
	private ArrayList<Quadrant> quadrants = new ArrayList<Quadrant>();


	private MapGenerator(boolean debug, int pNbGamers, int pBasesPerGamer, int pColoniesPerGamer, int pneutralMilitary,	int pneutralEconomic) {
		long debut = System.currentTimeMillis();
		this.debug = debug;
		if (debug) {
			this.timeout = 5000;
			System.out.println("Building MapGenerator");
			System.out.println("  pNbGamers="+pNbGamers);
			System.out.println("  pBasesPerGamer="+pBasesPerGamer);
			System.out.println("  pColoniesPerGamer="+pColoniesPerGamer);
			System.out.println("  pneutralMilitary="+pNbGamers);
			System.out.println("  pneutralEconomic="+pneutralEconomic);
			System.out.println("");
		}
		/* Initialize variables */
		this.nbGamers = pNbGamers;
		this.basesPerGamer = pBasesPerGamer;
		minDistanceFromColony = 1.8 * colonyRadius;
		minDistanceFromEdge = 1 * colonyRadius;

		/* Check validity */
		if (nbGamers < 2) throw new RuntimeException("Number of gamers must be at least 2.");
		if (pBasesPerGamer < 1) throw new RuntimeException("Each participant must have at least one base at start.");
		if (pColoniesPerGamer < 1) throw new RuntimeException("Each participant must have at least one colony at start.");

		/* Initialize First quadrant */
		quadrantAngle = 2*Math.PI/nbGamers;
		quadrantMaxX = mapWidth / 2;
		double minX = (mapWidth / 2);
		final double halfPi = (Math.PI / 2);
		double angle = quadrantAngle;
		while (angle >= halfPi) {
			angle -= halfPi;
			minX -= (mapWidth / 2);
		}
		quadrantMinX = -1 * Math.tan(angle) * (mapWidth / 2) + minX;
		quadrantMinY = 0;
		quadrantMaxY = mapHeight / 2;
		if (debug) {
			System.out.println("Angle calculé pour les quadrants : "+quadrantAngle);
			System.out.println("Dim X : "+quadrantMinX+" < X < "+quadrantMaxX);
			System.out.println("Dim Y : "+quadrantMinY+" < Y < "+quadrantMaxY);
		}
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
		for (int i = 0; i < pneutralMilitary; i++) {
			quadrant.tryPutColony(true, Colony.NEUTRAL_PLAYER);
		}
		for (int i = 0; i < pneutralEconomic; i++) {
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
				
				nextQuadrant.addColony(new Colony(newPoint.getX(), newPoint.getY(), newGamer, colony.isBase, colony.numShip, this.mapWidth, this.mapHeight));
			}
			
			quadrants.add(nextQuadrant);
		}
		
		if (debug) System.out.println("");
		if (debug) System.out.println("Map built in " + (System.currentTimeMillis() - debut) + "ms.");
	}

	public ArrayList<Colony> getColonies() {
		ArrayList<Colony> colonies = new ArrayList<Colony>();
		for (int i = 0; i < quadrants.size(); i++) {
			Quadrant quadrant = quadrants.get(i);
			for (Colony colony : quadrant.getColonies()) {
				colonies.add(new Colony(colony.x + quadrantMaxX, colony.y + quadrantMaxY, colony.gamer, colony.isBase, this.mapWidth, this.mapHeight));
			}
		}
		return colonies;
	}
	
	private Point2D rotationPoint(Point2D ptDepart, double angleRadian) {
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
				options.get("gamerMilitary"),
				options.get("gamerEconomic"),
				options.get("neutralMilitary"),
				options.get("neutralEconomic")
				);
		ArrayList<Colony> colonies=map.getColonies();
		
		if (options.containsKey("replay")) {
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
		}
		
		if (map.debug) {
			System.out.println("Got "+colonies.size()+" colonies:");
			for (Colony colony : colonies) {
				System.out.println(colony);
			}
		} else {
			// Live ouput
			for (Colony colony : colonies) {
				System.out.print(colony.toMap());
				System.out.print("\n");
			}
		}
	}
	
	private static void usage() {
		System.out.println("Usage : java Test <option>");
		System.out.println("  Available options (all nb are in Integer format) :");
		System.out.println("    -help : Display this help screen");
		//System.out.println("    -debug : Print debug informations");
		//System.out.println("    -replay : To get output in 'replay' mode");
		System.out.println("    -nbPlayers <nb> [4] : Define number of players on the map");
		System.out.println("    -gamerMilitary <nb> [1] : The number of starting Base and non-Base(normal) Colonies");
		System.out.println("    -gamerEconomic <nb> [1] : The number of starting non-Base(normal) Colonies");
		System.out.println("    -neutralMilitary <nb> [1] : The number of neutral Base and non-Base(normal) Colonies");
		System.out.println("    -neutralEconomic <nb> [3] : The number of neutral non-Base(normal) Colonies");
		System.out.println("");
		System.out.println("nbPlayers should be > "+MIN_PLAYER+" and < "+MAX_PLAYER);
	}

	private static Hashtable<String, Integer> parseOptions(String[] args) {
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
						|| "gamerMilitary".equals(key)
						|| "gamerEconomic".equals(key)
						|| "neutralMilitary".equals(key)
						|| "neutralEconomic".equals(key)) {
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
		if (!ret.containsKey("gamerMilitary")) {
			ret.put("gamerMilitary", 1);
		}
		if (!ret.containsKey("gamerEconomic")) {
			ret.put("gamerEconomic", 1);
		}
		if (!ret.containsKey("neutralMilitary")) {
			ret.put("neutralMilitary", 1);
		}
		if (!ret.containsKey("neutralEconomic")) {
			if (nbPlayers == 2) {
				ret.put("neutralEconomic", 4);
			} else if (nbPlayers == 3) {
				ret.put("neutralEconomic", 4);
			} else {
				ret.put("neutralEconomic", 3);
			}
		}
		
		return ret;
	}
}
