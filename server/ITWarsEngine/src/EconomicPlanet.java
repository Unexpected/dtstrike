public class EconomicPlanet extends Planet implements Cloneable {

	public int revenue;

	public EconomicPlanet(int owner, int numShips, int growthRate, double x,
			double y) {
		this.owner = owner;
		this.numShips = numShips;
		this.revenue = growthRate;
		this.x = x;
		this.y = y;
	}

	private EconomicPlanet(EconomicPlanet pSP) {
		this.owner = pSP.owner;
		this.numShips = pSP.numShips;
		this.revenue = pSP.revenue;
		this.x = pSP.x;
		this.y = pSP.y;
	}

	public Object clone() {
		return new EconomicPlanet(this);
	}

}
