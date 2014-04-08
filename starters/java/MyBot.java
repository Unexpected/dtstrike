import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

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
	 */
	@Override
	public void doTurn() {

		Game game = getGame();

		/******************************************
		 * Economic planets and fleets management *
		 ******************************************/

		// Send reinforcements to the closest military planet
		final List<Planet> myMilitaryPlanets = game.getMyMilitaryPlanets();
		for (Planet origin : game.getMyEconomicPlanets()) {
			Planet target = game.getClosestPlanet(origin, myMilitaryPlanets);
			game.issueOrder(origin, target, origin.numShips);
		}

		/******************************************
		 * Military planets and fleets management *
		 ******************************************/

		// (1) If we currently have a fleet in flight, just do nothing.
		for (Fleet f : game.getMyMilitaryFleets()) {
			if (game.getPlanet(f.sourcePlanet) instanceof MilitaryPlanet) {
				return;
			}
		}

		// (2) Find my strongest military planet.
		Planet source = null;
		int sourceShips = Integer.MIN_VALUE;
		for (Planet p : myMilitaryPlanets) {
			int score = p.numShips;
			if (score > sourceShips) {
				sourceShips = score;
				source = p;
			}
		}

		// (3) Find the weakest enemy or neutral planet.
		Planet dest = null;
		int destScore = Integer.MAX_VALUE;
		for (Planet p : game.getNotMyPlanets()) {
			int score = p.numShips;
			if (score < destScore) {
				destScore = score;
				dest = p;
			}
		}

		// (4) Send half the ships from my strongest planet to the weakest
		// planet that I do not own.
		if (source != null && dest != null) {
			int numShips = source.numShips / 2;
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
