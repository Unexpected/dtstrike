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
			x = getRandom(map.quadrantMinX, map.quadrantMaxX);
			y = getRandom(map.quadrantMinY, map.quadrantMaxY);
			double alpha = Math.atan(y / x);
			if (alpha < 0) alpha += Math.PI/2;
			if (x < 0) alpha += Math.PI/2;
			if (y < 0) alpha += Math.PI/2;
			if (map.debug) System.out.println("Trying " + x + "," + y + ", " + alpha + " into " + this);
			
			// Check rapport au bord
			if (Math.abs(map.quadrantMaxX - x) < map.minDistanceFromEdge || Math.abs(map.quadrantMaxY - y) < map.minDistanceFromEdge) {
				// Trop proche du bord
				if (map.debug) {
					System.out.println("  >> Map edge reject with threshold of " + map.minDistanceFromEdge);
					System.out.println("      x : "+map.quadrantMaxX+" - "+x+" = " + Math.abs(map.quadrantMaxX - x));
					System.out.println("      y : "+map.quadrantMaxY+" - "+y+" = " + Math.abs(map.quadrantMaxY - y));
				}
				continue;
			}
			
			// Check si le point est dans la cadrant
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
							System.out.println("  >> Colony was too close of "+colony + " with threshold of " + map.minDistanceFromColony);
							System.out.println("      x : "+colony.x+" - "+x+" = " + Math.abs(colony.x - x));
							System.out.println("      y : "+colony.y+" - "+y+" = " + Math.abs(colony.y - y));
						}
						posValid = false;
						break;
					}
				}
				if (posValid) {
					invalid = false;
				}
			}
			if (invalid && (System.currentTimeMillis() - t > map.timeout)) {
				throw new RuntimeException("Can't place colony in " + this
						+ " in less than " + map.timeout + "ms.");
			}
		} while (invalid);
		
		Colony colony = null;
		if (isBase && player == 0) {
			// Force numShips on Military planets of player
			int numShips = 100 / map.basesPerGamer;
			colony = new Colony(x, y, player, isBase, numShips, map.mapWidth, map.mapHeight);
		} else {
			// Otherwise, use random numShips
			colony = new Colony(x, y, player, isBase, map.mapWidth, map.mapHeight);
		}
		if (map.debug) System.out.println("  "+colony + " placed in quadrant #" + this.index);
		colonies.add(colony);
	}
	
	private double getRandom(double min, double max) {
		return min + (Math.random() * (max - min));
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
