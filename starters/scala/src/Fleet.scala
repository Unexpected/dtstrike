/** Fleets flying in the air */
sealed abstract class Fleet {  
  val owner: Int
  val shipsNumber: Int
  val source: Planet
  val destination: Planet
  val totalTripLength: Int
  val turnsRemaining: Int
}

/** Fleets coming from a military planet, and potentially threatening */
case class MilitaryFleet(val owner: Int,
  val shipsNumber: Int,
  val source: Planet,
  val destination: Planet,
  val totalTripLength: Int,
  val turnsRemaining: Int) extends Fleet

/** Fleets flying from an economic planet to a friendly military planet nearby */
case class EconomicFleet(val owner: Int,
  val shipsNumber: Int,
  val source: Planet,
  val destination: Planet,
  val totalTripLength: Int,
  val turnsRemaining: Int) extends Fleet