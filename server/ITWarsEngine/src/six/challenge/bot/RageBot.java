package six.challenge.bot;

import java.io.IOException;

public class RageBot extends Bot {
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
		new RageBot().readSystemInput();
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

		// send bot to nearest military planetes
		Planet dest = null;
		for (Planet economic : game.getMyEconomicPlanets()) {
			int score = economic.numShips;
			if (score > 30) {
				dest = game.findClosestMilitaryPlanet(economic);
				if (dest != null) {
					game.issueOrder(economic, dest, score - 20);
				}
			}
		}

		// Find the weakest enemy economic planet.
		dest = null;
		int destScore = Integer.MAX_VALUE;
		for (Planet p : game.getEnemyEconomicPlanets()) {
			int score = p.numShips;
			if (score < destScore) {
				destScore = score;
				dest = p;
			}
		}

		// If none, find the weakest enemy military planet.
		if (dest == null) {
			destScore = Integer.MAX_VALUE;
			for (Planet p : game.getEnemyMilitaryPlanets()) {
				int score = p.numShips;
				if (score < destScore) {
					destScore = score;
					dest = p;
				}
			}
		}

		if (dest == null) {
			return;
		}

		int destId = dest.id;

		// From each of my military planets, send max fleet
		for (Planet p : game.getMyMilitaryPlanets()) {
			if (p.numShips > 0)
				game.issueOrder(p.id, destId, p.numShips);
		}
	}
}
