package six.challenge.bot;

import java.io.IOException;

public class LooterBot extends Bot {
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
		new LooterBot().readSystemInput();
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

		for (MilitaryPlanet military : game.getMyMilitaryPlanets()) {
			boolean fleetSent = false;
			// One fleet for each military planet
			for (Fleet f : game.getMyMilitaryFleets()) {
				if (f.sourcePlanet == military.id) {
					fleetSent = true;
					break;
				}
			}
			if (!fleetSent) {
				// Find the closest planet and send everything if there not
				// enough ships on the planet
				int targetDistance = Integer.MAX_VALUE;
				Planet target = null;
				for (Planet p : game.getNotMyPlanets()) {
					int pDistance = game.distance(military.id, p.id);
					if (pDistance < targetDistance && p.numShips < military.numShips) {
						target = p;
						targetDistance = pDistance;
					}
				}
				if (target != null) {
					game.issueOrder(military.id, target.id, military.numShips);
				}
			}
		}
	}
}
