import scala.collection.mutable.ListBuffer

class Game private (val myId: Int) {

  private val economicPlanetRegex = """"E (\d*\.\d*) (\d*\.\d*) (\d+) (\d+) (\d+)""".r
  private val militaryPlanetRegex = """"M (\d*\.\d*) (\d*\.\d*) (\d+) (\d+)""".r
  private val fleetRegex = """"F  (\d+) (\d+) (\d+) (\d+) (\d+) (\d+)""".r

  val planets: ListBuffer[Planet] = ListBuffer()
  val fleets: ListBuffer[Fleet] = ListBuffer()

  def this(gameStateAsString: String, id: Int) = {
    this(id)
    gameStateAsString.lines foreach { parseLine(_) }
  }

  def parseLine(str: String) = str match {
    case economicPlanetRegex(x, y, owner, numShips, revenue) =>
      planets += new EconomicPlanet(
        planets.length,
        owner.toInt,
        numShips.toInt,
        (x.toDouble, y.toDouble),
        revenue.toInt)
    case militaryPlanetRegex(x, y, owner, numShips) =>
      planets += new MilitaryPlanet(
        planets.length,
        owner.toInt,
        numShips.toInt,
        (x.toDouble, y.toDouble))
    case fleetRegex(owner, numShips, src, dest, totalTrip, remaining) =>
      fleets += new Fleet(
        owner.toInt,
        numShips.toInt,
        src.toInt,
        dest.toInt,
        totalTrip.toInt,
        remaining.toInt)
    case _ => throw new IllegalArgumentException(str)
  }

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

  def enemyFleets = {
    fleets.filter { _.owner != myId }
  }

  def distance(sourcePlanetId: Int, destinationPlanetId: Int) = {
    val (x1, y1) = planets(sourcePlanetId).coordinates
    val (x2, y2) = planets(destinationPlanetId).coordinates

    val dx = x1 - x2
    val dy = y1 - y2
    math.ceil(math.sqrt(dx * dx + dy * dy))

  }

  def issueOrder(src: Int, dest: Int, numShips: Int) = {
    print("" + src + " " + dest + " " + numShips + "\n")
  }

  def finishTurn = {
    print("go\n")
  }

  def numShips(player: Int) = {
    planets.filter { _.owner == player }.foldLeft(0) { _ + _.shipsNumber }
    +fleets.filter { _.owner == player }.foldLeft(0) { _ + _.shipsNumber }
  }

}