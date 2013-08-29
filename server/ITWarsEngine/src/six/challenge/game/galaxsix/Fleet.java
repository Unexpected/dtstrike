package six.challenge.game.galaxsix;

public class Fleet {
	public int owner;
	public int numShips;
	public int sourcePlanet;
	public int destinationPlanet;
	public int totalTripLength;
	public int turnsRemaining;
	public boolean military;

	public Fleet(int owner, int numEngineers, int sourceDept, int destDept,
			int tripLength, int turnsRemaining, boolean military) {
		this.owner = owner;
		this.numShips = numEngineers;
		this.sourcePlanet = sourceDept;
		this.destinationPlanet = destDept;
		this.totalTripLength = tripLength;
		this.turnsRemaining = turnsRemaining;
		this.military = military;
	}

	public Fleet(int owner, int numEngineers) {
		this.owner = owner;
		this.numShips = numEngineers;
		this.sourcePlanet = -1;
		this.destinationPlanet = -1;
		this.totalTripLength = -1;
		this.turnsRemaining = -1;
	}

	public void destroy() {
		owner = 0;
		numShips = 0;
		turnsRemaining = 0;
	}

	public void doTimeStep() {
		turnsRemaining -= 1;
		if (turnsRemaining < 0) {
			turnsRemaining = 0;
		}
	}
}
