package six.challenge.game.galaxsix;

public class EconomicPlanet extends Planet {

	public int revenue;

	public EconomicPlanet(int id, int owner, int numShips, int revenue,
			double x, double y) {
		super(id, owner, numShips, x, y);
		this.revenue = revenue;
	}

	@Override
	public String toString() {
		return String
				.format("E %f %f %d %d %d", x, y, owner, numShips, revenue);
	}
}
