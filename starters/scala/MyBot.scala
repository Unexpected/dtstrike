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
  override def doTurn(game: GameState): Iterable[Order] = {
    
    /* if i have no more military planet, i can't do anything */
    if (game.myMilitaryPlanets.isEmpty) {
      return Nil
    }

    /* for each economic planet having more than 50 ships, send 50 ships to the nearest military planet */
    val ecoOrders = game.myEconomicPlanets.filter { _.shipsNumber > 50 }.map { eco =>
      val nearest = game.myMilitaryPlanets.min(new OrderingByDistanceTo(game, eco))
      Order(eco, nearest, 50)
    }

    val milOrder = if (game.myMilitaryFleets.size > 2 || game.myMilitaryPlanets.isEmpty || game.notMyPlanets.isEmpty) {
      /* If more than two military fleets, or i have no more military planet, or there is no more victim */
      None
    } else {
      /* Else, send all ships from my strongest planet to its closest neighbour. */
      val source = game.myMilitaryPlanets.max(OrderingByStrength)
      val nearest = game.notMyPlanets.min(new OrderingByDistanceTo(game, source))
      Some(Order(source, nearest, source.shipsNumber))
    }

    ecoOrders ++ milOrder
  }

}

object OrderingByStrength extends Ordering[Planet] {
  override def compare(x: Planet, y: Planet) = x.shipsNumber - y.shipsNumber
}

class OrderingByDistanceTo(val state: GameState, val target: Planet) extends Ordering[Planet] {
  override def compare(x: Planet, y: Planet) =
    state.distance(target.id, x.id) - state.distance(target.id, y.id)
}