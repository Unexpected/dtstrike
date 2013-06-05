public class MilitaryPlanet extends Planet implements Cloneable {

	public MilitaryPlanet(int owner, int numShips, double x, double y) {
		this.owner = owner;
		this.numShips = numShips;
		this.x = x;
		this.y = y;
	}

	private MilitaryPlanet(MilitaryPlanet pSP) {
		this.owner = pSP.owner;
		this.numShips = pSP.numShips;
		this.x = pSP.x;
		this.y = pSP.y;
	}

	public Object clone() {
		return new MilitaryPlanet(this);
	}

}
