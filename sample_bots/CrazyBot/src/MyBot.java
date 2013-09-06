import java.io.IOException;
import java.util.List;
import java.util.Random;

public class MyBot extends Bot {

	private Random random = new Random();

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
		
		List<Planet> planets = game.getPlanets();

		for (Planet p : game.getMyMilitaryPlanets()) {
			int sending = random.nextInt(p.numShips);
			if (sending == 0)
				continue;
			
			int target = random.nextInt(planets.size());
			if (target == p.id)
				continue;
			
			game.issueOrder(p.id, target, sending);
		}

	}
}
