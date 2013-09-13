package six.challenge.bot;
/**
 * 
 * @param sourcePlanet
 *            the ID of the source planet
 * @param destinationPlanet
 *            the ID of the destination planet
 * @param numShips
 *            the number of ships to send
 */

public class Order {
	private final int sourcePlanet;

	private final int destPlanet;

	private final int numShips;

	/**
	 * Creates new {@link Order} object.
	 * 
	 * @param sourcePlanet
	 *            the source planet ID
	 * @param destPlanet
	 *            the destination planet ID
	 * @param numShips
	 *            the number of ships to send
	 */
	public Order(int sourcePlanet, int destPlanet, int numShips) {
		this.sourcePlanet = sourcePlanet;
		this.destPlanet = destPlanet;
		this.numShips = numShips;
	}

	/**
	 * {@inheritDoc}
	 */
	@Override
	public String toString() {
		return "" + sourcePlanet + " " + destPlanet + " " + numShips;
	}
}
