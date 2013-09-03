import collection.mutable

/** Class to parse input and produce a GameOptions object. */
class GameOptionsBuilder {
  private val optionLoadTimeRegex = """loadtime:(\d*)""".r
  private val optionTurnTimeRegex = """turntime:(\d*)""".r
  private val optionTurnsRegex = """turns:(\d*)""".r
  private val optionOtherRegex = """(\w*):(\w*)""".r
  private val readyRegex = "ready".r

  private var loadTime: Option[Int] = None
  private var turnTime: Option[Int] = None
  private var turns: Option[Int] = None
  private val otherOptions = mutable.Map[String, String]()

  def parse(str: String) = {
    //System.err.println("Parsing " + str)
    var goOn = true
    str match {
      case optionLoadTimeRegex(lt) => loadTime = Some(lt.toInt)
      case optionTurnTimeRegex(tt) => turnTime = Some(tt.toInt)
      case optionTurnsRegex(t) => turns = Some(t.toInt)
      case optionOtherRegex(otherKey, otherValue) => otherOptions(otherKey) = otherValue
      case readyRegex() => goOn = false
     // case _ => System.err.println("Could not parse : [" + str + "]")
    }

    new {
      def continue = goOn
    }
  }

  def toGameOptions = GameOptions(loadTime.get, turnTime.get, turns.get, otherOptions.toMap)
}