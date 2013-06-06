public class EconomicPlanet extends Planet {

	public int revenue;

	public EconomicPlanet(int id, int owner, int numShips, int growthRate,
			double x, double y) {
		super(id, owner, numShips, x, y);
		this.revenue = growthRate;
	}
}
