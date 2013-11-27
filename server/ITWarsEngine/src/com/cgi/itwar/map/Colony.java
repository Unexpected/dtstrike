package com.cgi.itwar.map;

public class Colony {

	public final double x;
	public final double y;
	public final int gamer;
	public final boolean isBase;
	public static final int NEUTRAL_PLAYER = -1;
	public final int numShip;
	public final int growthRate = 2;
	public final double maxX;
	public final double maxY;

	public Colony(double x, double y, int player, boolean isBase, double maxX,
			double maxY) {
		this.x = x;
		this.y = y;
		this.gamer = player;
		this.isBase = isBase;
		this.numShip = (int) Math.round(Math.random() * 60);
		this.maxX = maxX;
		this.maxY = maxY;
	}

	public Colony(double x, double y, int player, boolean isBase, int numShip,
			double maxX, double maxY) {
		this.x = x;
		this.y = y;
		this.gamer = player;
		this.isBase = isBase;
		this.numShip = numShip;
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
		return (isBase ? "M" : "E") // Type
				+ " " + (24d * (MapGenerator.nbGamers / 2) * x / maxX) // X
				+ " " + (24d * (MapGenerator.nbGamers / 2) * y / maxY) // Y
				+ " " + (gamer + 1) // Owner
				+ " " + numShip // NumShip
				+ (isBase ? "" : " " + growthRate) // GrowthRate
		;
	}

	public String toReplay() {
		return (isBase ? "M" : "E") // Type
				+ "," + (24d * x / maxX) // X
				+ "," + (24d * y / maxY) // Y
				+ "," + (gamer + 1) // Owner
				+ "," + numShip // NumShip
				+ (isBase ? "" : "," + growthRate) // GrowthRate
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
