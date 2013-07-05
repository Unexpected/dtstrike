sealed abstract class Planet {

  val id: Int
  val owner: Int
  val shipsNumber: Int
  val coordinates: (Double, Double)
}

case class EconomicPlanet(
  val id: Int,
  val owner: Int,
  val shipsNumber: Int,
  val coordinates: (Double, Double),
  val revenue : Int
) extends Planet

case class MilitaryPlanet(
  val id: Int,
  val owner: Int,
  val shipsNumber: Int,
  val coordinates: (Double, Double)
) extends Planet
