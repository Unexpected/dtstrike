import java.io.IOException;

public class MyBot extends Bot {
	/**
	 * Main method executed by the game engine for starting the bot.
	 * 
	 * @param args
	 *            command line arguments
	 * 
	 * @throws IOException
	 *             if an I/O error occurs
	 */
	public static void main(String[] args) throws IOException {
		new MyBot().readSystemInput();
	}

	/**
	 * The DoTurn function is where your code goes.<br/>
	 * The Game object contains the state of the game, including information
	 * about all planets and fleets that currently exist.<br/>
	 * Inside this function, you issue orders using the {@link Game#issueOrder}
	 * functions.<br/>
	 * For example, to send 10 ships from planet 3 to planet 8, you would say
	 * <code>game.issueOrder(3, 8, 10).</code>
	 * 
	 * <p>
	 * There is already a basic strategy in place here.<br/>
	 * You can use it as a starting point, or you can throw it out entirely and
	 * replace it with your own.
	 * </p>
	 * 
	 * @param game
	 *            the Game instance
	 */
	@Override
	public void doTurn() {
		Game game = getGame();

		// If we currently have a fleet in flight, just do nothing.
		for (Fleet f : game.getMyMilitaryFleets()) {
			if (game.getPlanet(f.sourcePlanet) instanceof MilitaryPlanet) {
				return;
			}
		}

		if (game.getMyEconomicPlanets().isEmpty()
				|| game.getMyMilitaryPlanets().isEmpty()) {
			return;
		}

		EconomicPlanet eco = game.getMyEconomicPlanets().get(0);
		MilitaryPlanet mil = game.getMyMilitaryPlanets().get(0);

		/* Balance troops between both planets */
		int diff = (mil.numShips - eco.numShips) / 2;
		if (diff > 0) {
			game.issueOrder(mil.id, eco.id, diff);
		}
	}
}
