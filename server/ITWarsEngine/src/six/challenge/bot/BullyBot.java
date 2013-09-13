package six.challenge.bot;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

public class BullyBot extends Bot {
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
		new BullyBot().readSystemInput();
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

		// Find the weakest enemy planet
		List<Planet> targets = new ArrayList<Planet>(game.getNotMyPlanets());
		Planet target = null;
		if (targets.size() > 0) {
			Collections.sort(targets, new Comparator<Planet>() {
				@Override
				public int compare(Planet p1, Planet p2) {
					return p1.numShips - p2.numShips;
				}

			});
			target = targets.get(0);
		}

		if (target == null) {
			return;
		}

		/* Sort sources by strength */
		List<MilitaryPlanet> sources = new ArrayList<MilitaryPlanet>(
				game.getMyMilitaryPlanets());
		MilitaryPlanet source = null;
		if (sources.size() > 0) {
			Collections.sort(sources, new Comparator<Planet>() {
				@Override
				public int compare(Planet p1, Planet p2) {
					return p2.numShips - p1.numShips;
				}
			});
			if (sources.get(0).numShips > target.numShips) {
				source = sources.get(0);
			}
		}

		// Hit hard !
		if (source != null) {
			game.issueOrder(source.id, target.id, source.numShips);
		}
	}
}
