import scala.collection.mutable

class GameStateBuilder {

  private val economicPlanetRegex = ("""E\d* (\-?\d+\.?\d*) (\-?\d+\.?\d*) (\d+) (\d+) (\d+)""").r
  private val militaryPlanetRegex = ("""M\d* (\-?\d+\.?\d*) (\-?\d+\.?\d*) (\d+) (\d+)""").r
  private val economicFleetRegex = """R (\d+) (\d+) (\d+) (\d+) (\d+) (\d+)""".r
  private val militaryFleetRegex = """F (\d+) (\d+) (\d+) (\d+) (\d+) (\d+)""".r

  private val turnRegex = """turn (\d+)""".r
  private val goRegex = "go".r
  private val endRegex = "end".r

  private var turnNumber: Option[Int] = Option(0)
  private var indexedPlanets: Option[IndexedSeq[Planet]] = None
  private var planets = mutable.ListBuffer[Planet]()
  private var fleets = mutable.ListBuffer[Fleet]()
  private var startTime: Option[Long] = None

  private def getIndexedPlanets = {
    if (indexedPlanets == None) {
      indexedPlanets = Some(planets.toIndexedSeq)
    }
    indexedPlanets.get
  }

  private def planet(id: Int) = {
    getIndexedPlanets(id)
  }

  def parse(str: String): GameStateBuilderStatus.Value = {
    str match {
      case economicPlanetRegex(x, y, owner, numShips, revenue) =>
        planets += EconomicPlanet(planets.length, owner.toInt, numShips.toInt, (x.toDouble, y.toDouble), revenue.toInt)
      case militaryPlanetRegex(x, y, owner, numShips) =>
        planets += MilitaryPlanet(planets.length, owner.toInt, numShips.toInt, (x.toDouble, y.toDouble))
      case economicFleetRegex(owner, numShips, src, dest, totalTrip, remaining) =>
        fleets += EconomicFleet(owner.toInt, numShips.toInt, planet(src.toInt), planet(dest.toInt), totalTrip.toInt, remaining.toInt)
      case militaryFleetRegex(owner, numShips, src, dest, totalTrip, remaining) =>
        fleets += MilitaryFleet(owner.toInt, numShips.toInt, planet(src.toInt), planet(dest.toInt), totalTrip.toInt, remaining.toInt)
      case turnRegex(turnNum) =>
        turnNumber = Some(turnNum.toInt)
      case goRegex() =>
        startTime = Some(System.currentTimeMillis())
        return GameStateBuilderStatus.GO
      case endRegex() =>
        return GameStateBuilderStatus.END
    }

    GameStateBuilderStatus.CONTINUE
  }

  def toGameState = GameState(turnNumber.get, getIndexedPlanets, fleets.toIndexedSeq, startTime.get)

}

object GameStateBuilderStatus extends Enumeration {
  val CONTINUE, GO, END = Value
}