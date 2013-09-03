/** Orbs of life lost in the immensity of space... */
sealed abstract class Planet {
  val id: Int
  val owner: Int
  val shipsNumber: Int
  val coordinates: (Double, Double)
}

/** ... with factories */
case class EconomicPlanet(
  val id: Int,
  val owner: Int,
  val shipsNumber: Int,
  val coordinates: (Double, Double),
  val revenue : Int
) extends Planet

/** ... whith big guns ! */
case class MilitaryPlanet(
  val id: Int,
  val owner: Int,
  val shipsNumber: Int,
  val coordinates: (Double, Double)
) extends Planet
