import collection.mutable

/**
 * A small set of options for the game. See the specifications for more info. Immutable.
 *  @param loadTime : Timeout for initializing and setting up the bot on turn 0
 *  @param turnTime : Timeout for a single game turn, starting with turn 1
 *  @param turns : maximum number of turns the game will be played
 *  @param other : any other unspecified options
 */
case class GameOptions(
  val loadTime: Int,
  val turnTime: Int,
  val turns: Int,
  val other: Map[String, String])

