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
		Planet source = null; 
		Planet dest = null; 
		// (1) If an economic planet have more than 50 ships, send 50 ships to the closest military planet.
		for (Planet p : game.getMyEconomicPlanets()) {
			int score = p.numShips;
			if (score > 50) {
				source = p;
				dest = game.findClosestMilitaryPlanet(source);
				if (dest != null) {
					game.issueOrder(source, dest, 50);
				}
			}
		}

		// (2) If we currently have more than 2 fleet in flight, just do nothing.
		if (game.getMyMilitaryFleets().size() > 2) {
			return;
		}

		// (3) Find my strongest military planet.
		source = null;
		int sourceShips = Integer.MIN_VALUE;
		for (Planet p : game.getMyMilitaryPlanets()) {
			int score = p.numShips;
			if (score > sourceShips) {
				sourceShips = score;
				source = p;
			}
		}
		
		// No military planet found, just stop here.
		if (source == null) {
			return; 
		}

		// (4) Find the closest enemy or neutral planet.
		dest = null;
		int distMin = Integer.MAX_VALUE;
		for (Planet p : game.getNotMyPlanets()) {
			int distance = game.distance(source.id, p.id);
			if (distance < distMin) {
				distMin = distance;
				dest = p;
			}
		}

		// (5) Send all the ships from my strongest planet to the weakest
		// planet that I do not own.
		if (source != null && dest != null) {
			int numShips = source.numShips;
			if (numShips > 0) {
				game.issueOrder(source, dest, numShips);
			}
		}
	}

	/**
	 * Method called at the init phase of the Game
	 * (ie before first turn)
	 * !! No orders could be given here !!
	 */
	@Override
	public void doReadyTurn() {
		
	}
}
