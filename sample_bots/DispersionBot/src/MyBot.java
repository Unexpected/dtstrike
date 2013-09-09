import java.io.IOException;
import java.util.Collections;
import java.util.Comparator;
import java.util.Iterator;
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
	 * 
	 * @param game
	 *            the Game instance
	 */
	@Override
	public void doTurn() {
		Game game = getGame();

		// Count my production
		int production = 0;
		for (EconomicPlanet p : game.getMyEconomicPlanets()) {
			production += p.revenue;
		}

		/* Sort targets by weakness */
		List<Planet> targets = game.getNotMyPlanets();
		Collections.sort(targets, new Comparator<Planet>() {
			@Override
			public int compare(Planet p1, Planet p2) {
				return p1.numShips - p2.numShips;
			}

		});

		/* Sort sources by strength */
		List<MilitaryPlanet> sources = game.getMyMilitaryPlanets();
		Collections.sort(sources, new Comparator<Planet>() {
			@Override
			public int compare(Planet p1, Planet p2) {
				return p2.numShips - p1.numShips;
			}
		});

		/* Send one */
		Iterator<Planet> itTargets = targets.iterator();
		while (itTargets.hasNext() && production > 0) {
			for (Planet src : sources) {
				if (src.numShips > 0) {
					game.issueOrder(src.id, itTargets.next().id, 1);
					production--;
					break;
				}
			}
		}
	}
}
