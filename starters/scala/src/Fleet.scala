case class Fleet(
  var owner: Int,
  val shipsNumber: Int,
  val sourcePlanetId: Int,
  val destinationPlanetId: Int,
  val totalTripLength: Int,
  var turnsRemaining: Int) {

  def doTimeStep() = {
    turnsRemaining -= 1;
    if (turnsRemaining < 0) {
      turnsRemaining = 0;
    }
  }
}

//
//	public Fleet(int owner, int numEngineers) {
//		this.owner = owner;
//		this.numShips = numEngineers;
//		this.sourcePlanet = -1;
//		this.destinationPlanet = -1;
//		this.totalTripLength = -1;
//		this.turnsRemaining = -1;
//	}
//
//	public void destroy() {
//		owner = 0;
//		numShips = 0;
//		turnsRemaining = 0;
//	}
//