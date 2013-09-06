object MyBot extends App with Bot {

  /**
   * The DoTurn function is where your code goes.<br/>
   * The GameState object contains the state of the game, including information
   * about all planets and fleets that currently exist.<br/>
   * This function must return orders that will be sent to the server<br/>   *
   * <p>
   * There is already a basic strategy in place here.<br/>
   * You can use it as a starting point, or you can throw it out entirely and
   * replace it with your own.
   * </p>
   */
  override def doTurn(game: GameState): TraversableOnce[Order] = {

    // (1) If we currently have a fleet in flight, just do nothing.
    if (!game.myMilitaryFleets.isEmpty) {
      return None
    }

    // (2) Find my strongest military planet
    val myMilitaryPlanets = game.myMilitaryPlanets
    val source = if (myMilitaryPlanets.isEmpty) None else Some(myMilitaryPlanets.max(OrderingByStrength))

    // (3) Find the weakest enemy or neutral planet
    val notMyPlanets = game.notMyPlanets
    val dest = if (notMyPlanets.isEmpty) None else Some(notMyPlanets.min(OrderingByStrength))

    // (4) Send half the ships from my strongest planet to the weakest planet that I do not own.
    if (source.isDefined && dest.isDefined) {
      val ships = source.get.shipsNumber / 2
      Some(Order(source.get, dest.get, ships))
    } else {
      None
    }
  }

}

object OrderingByStrength extends Ordering[Planet] {
  override def compare(x: Planet, y: Planet) = x.shipsNumber - y.shipsNumber
}