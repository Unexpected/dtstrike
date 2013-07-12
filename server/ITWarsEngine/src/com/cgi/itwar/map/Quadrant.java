package com.cgi.itwar.map;

import java.util.HashSet;

/**
 * Un Quadrant représente une "part" de la zone de jeu.
 * Cette zone a été découpée en part égale en fonction du nombre de joueur.
 * 
 * 
 * 
 * @author schmittse
 */
public class Quadrant {
	private final int index;
	private final MapGenerator map;
	private final HashSet<Colony> colonies = new HashSet<Colony>();

	public Quadrant(int index, MapGenerator map) {
		this.index = index;
		this.map = map;
	}

	public void tryPutColony(boolean isBase, int player) {
		double x, y;
		boolean invalid = true;
		long t = System.currentTimeMillis();
		do {
			x = getRandom(-1*map.getQuadrantWidth(), map.getQuadrantWidth());
			y = getRandom(-1*map.getQuadrantHeight(), map.getQuadrantHeight());
			if (map.debug) System.out.println("Trying " + x + "," + y + " into " + this);
			
			// Check rapport au bord
			if ((map.getQuadrantWidth() - x) < map.minDistanceFromEdge || (map.getQuadrantHeight() - y) < map.minDistanceFromEdge) {
				// Trop proche du bord
				if (map.debug) System.out.println("  >> Map edge reject !");
				continue;
			}
			
			// Check si le point est dans la cadrant
			double alpha = Math.atan(x / y);
			if (alpha > map.quadrantAngle) {
				// Hors du quandrant
				if (map.debug) System.out.println("  >> Out of Quandrant with "+alpha+"° > "+map.quadrantAngle+"° !");
				continue;
			}
			
			if (colonies.size() == 0) {
				invalid = false;
			} else {
				boolean posValid = true;
				for (Colony colony : colonies) {
					// Check si on est pas trop pret de la colony
					if (Math.abs(colony.y - y) < map.minDistanceFromColony || Math.abs(colony.x - x) < map.minDistanceFromColony) {
						if (map.debug) {
							System.out.println("  Colony was too close of "+colony);
							System.out.println("    x : "+colony.x+" / "+x);
							System.out.println("    y : "+colony.y+" / "+y);
						}
						posValid = false;
						break;
					}
				}
				if (posValid) {
					invalid = false;
				}
			}
			if (invalid && (System.currentTimeMillis() - t > MapGenerator.TIMEOUT)) {
				throw new RuntimeException("Can't place colony in " + this);
			}
		} while (invalid);
		
		Colony colony = new Colony(x, y, player, isBase, map.mapWidth, map.mapHeight);
		if (map.debug) System.out.println("  "+colony + " placed in quadrant #" + this.index);
		colonies.add(colony);
	}
	
	private double getRandom(double min, double max) {
		return min + (Math.random() * ((max - min) + 1));
	}

	public int getNbOfColonies() {
		return colonies.size();
	}

	public HashSet<Colony> getColonies() {
		return colonies;
	}

	public void addColony(Colony colony) {
		if (map.debug) System.out.println("  "+colony + " placed in quadrant #" + this.index);
		colonies.add(colony);
	}

	@Override
	public String toString() {
		return "Quadrant[#" + index + "]";
	}
}
