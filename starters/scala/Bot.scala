import scala.collection.mutable

/** Provides basic functions for the bot. */
trait Bot {
  
  val linesIterator = io.Source.stdin.getLines

  val options = {
    val optionsBuilder = new GameOptionsBuilder
    linesIterator.takeWhile { optionsBuilder.parse(_).continue }.foreach { x => }
    optionsBuilder.toGameOptions
  }

  setup()
  print("go\n")

  linesIterator.foldLeft(new GameStateBuilder) { (builder, str) =>
    builder.parse(str) match {
      case GameStateBuilderStatus.CONTINUE => builder
      case GameStateBuilderStatus.END => internalEnd(builder)
      case GameStateBuilderStatus.GO => internalGo(builder)
    }
  }

  private def internalEnd(builder: GameStateBuilder) = {
    //System.err.println("End game !")
    System.exit(0)
    new GameStateBuilder
  }

  private def internalGo(builder: GameStateBuilder) = {
    doTurn(builder.toGameState).foreach { order =>
      print(order.toString + "\n")
    }
    print("go\n")
    new GameStateBuilder
  }

  /* Methods for the specific bot to implement */
  
  /** Called once the options have been set during the preparation round */
  def setup() = {}

  /** Main method. Called for each turn. Returns order to be rendered. */
  def doTurn(game: GameState): TraversableOnce[Order]
}