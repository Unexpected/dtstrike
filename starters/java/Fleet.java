public class Fleet {
	public int owner;
	public int numShips;
	public int sourcePlanet;
	public int destinationPlanet;
	public int totalTripLength;
	public int turnsRemaining;

	protected Fleet(int owner, int numEngineers, int sourceDept, int destDept,
			int tripLength, int turnsRemaining) {
		this.owner = owner;
		this.numShips = numEngineers;
		this.sourcePlanet = sourceDept;
		this.destinationPlanet = destDept;
		this.totalTripLength = tripLength;
		this.turnsRemaining = turnsRemaining;
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
