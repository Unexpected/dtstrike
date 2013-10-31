import collection.mutable

/**
 * Current state of the game.
 *  @param planets : Planets are numbered starting with 0.  <b>Planet ids ARE consistent from one turn to the next.</b>
 * @param fleets : Fleets are numbered starting with 0. <b>Fleet ids are not consistentfrom one turn to the next.</b>
 */
case class GameState(val turn: Int, val planets: IndexedSeq[Planet], val fleets: IndexedSeq[Fleet], val turnStart: Long) {

  /** By convention, the current player is always player number 1 */
  val myId = 1

  def isAlive(player: Int) = {
    planets.exists { _.owner == player } || fleets.exists(_.owner == player)
  }

  def myPlanets = {
    planets.filter { _.owner == myId }
  }

  def myMilitaryPlanets = {
    planets.filter { p => p.owner == myId && p.isInstanceOf[MilitaryPlanet] }
  }

  def myEconomicPlanets = {
    planets.filter { p => p.owner == myId && p.isInstanceOf[EconomicPlanet] }
  }

  def neutralPlanets = {
    planets.filter { _.owner == 0 }
  }

  def enemyPlanets = {
    planets.filter { p => p.owner != 0 && p.owner != myId }
  }

  def notMyPlanets = {
    planets.filter { _.owner != myId }
  }

  def myFleets = {
    fleets.filter { _.owner == myId }
  }

  def myMilitaryFleets = {
    fleets.filter { f => f.owner == myId && f.isInstanceOf[MilitaryFleet] }
  }

  def myEconomicFleets = {
    fleets.filter { f => f.owner == myId && f.isInstanceOf[EconomicFleet] }
  }

  def enemyFleets = {
    fleets.filter { _.owner != myId }
  }

  def shipsNumber(player: Int) = {
    planets.filter { _.owner == player }.foldLeft(0) { _ + _.shipsNumber } +
      fleets.filter { _.owner == player }.foldLeft(0) { _ + _.shipsNumber }
  }

  /**
   * Returns the distance between two planets, rounded up to the next highest
   * integer. This is the number of discrete time steps it takes to get
   * between the two planets.
   */
  def distance(p1: Planet, p2: Planet): Int = {
    val (x1, y1) = p1.coordinates
    val (x2, y2) = p2.coordinates
    val dx = x1 - x2
    val dy = y1 - y2
    math.ceil(math.sqrt(dx * dx + dy * dy)).toInt
  }

  def distance(p1Id: Int, p2Id: Int): Int = {
    distance(planets(p1Id), planets(p2Id))
  }

  /** Returns how much time the bot has still has to take its turn before timing out. */
  def timeSpent = System.currentTimeMillis() - turnStart
}

