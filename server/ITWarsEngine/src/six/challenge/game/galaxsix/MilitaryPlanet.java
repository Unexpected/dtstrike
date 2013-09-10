package six.challenge.game.galaxsix;

public class MilitaryPlanet extends Planet {

	public MilitaryPlanet(int id, int owner, int numShips, double x, double y) {
		super(id, owner, numShips, x, y);
	}

	@Override
	public String toString() {
		return String.format("M %f %f %d %d", x, y, owner, numShips);
	}
}
