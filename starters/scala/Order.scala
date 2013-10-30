/**
 * <p>
 * Order to move from a military planet to some target. An order sent to the server is composed of
 * a source planet number, a destination planet number, and a number of ships. A few things to keep
 * in mind:
 * </p>
 * <ul>
 * <li>you can issue many orders per turn if you like.</li>
 * <li>the planets are numbered starting at zero, not one.</li>
 * <li>you must own the source planet.<br/>
 * <b>If you break this rule, the game engine kicks your bot out of the game
 * instantly.</b></li>
 * <li>you can't move more ships than are currently on the source planet.</li>
 * <li>the ships will take a few turns to reach their destination.<br/>
 * <b>Travel is not instant.</b><br/>
 * See the distance() function for more info.</li>
 * </ul>
 */
case class Order(
  val source: Planet,
  val destination: Planet,
  val shipsNumber: Int) {

  override def toString() = source.id + " " + destination.id + " " + shipsNumber
}